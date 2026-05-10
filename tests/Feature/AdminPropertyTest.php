<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Property-based tests for admin panel.
 *
 * P17: Dashboard statistics match actual database counts/aggregates
 * P18: Admin CRUD operations persist correctly to database
 */
class AdminPropertyTest extends TestCase
{
    use RefreshDatabase;

    private function makeAdmin(): User
    {
        return User::factory()->create(['role' => 1]);
    }

    // ---------------------------------------------------------------
    // P17: Dashboard stats match DB aggregates
    // ---------------------------------------------------------------

    public function test_p17_dashboard_statistics_match_database(): void
    {
        $admin = $this->makeAdmin();

        for ($i = 0; $i < 10; $i++) {
            // Create random data
            $categoryCount = rand(1, 3);
            $productCount  = rand(1, 5);
            $userCount     = rand(1, 4);
            $orderCount    = rand(1, 3);

            $categories = Category::factory()->count($categoryCount)->create();

            foreach ($categories as $cat) {
                Product::factory()->count($productCount)->create(['categories_id' => $cat->id]);
            }

            $customers = User::factory()->count($userCount)->create(['role' => 0]);

            foreach ($customers as $customer) {
                for ($o = 0; $o < $orderCount; $o++) {
                    $order    = Order::factory()->create(['users_id' => $customer->id]);
                    $products = Product::inRandomOrder()->limit(rand(1, 3))->get();

                    foreach ($products as $product) {
                        OrderItem::create([
                            'orders_id'   => $order->id,
                            'products_id' => $product->id,
                            'amount'      => rand(1, 5),
                        ]);
                    }
                }
            }

            // Compute expected values from DB
            $expectedOrders   = Order::count();
            $expectedProducts = Product::count();
            $expectedUsers    = User::count();
            $expectedRevenue  = OrderItem::join('products', 'order_items.products_id', '=', 'products.id')
                ->selectRaw('SUM(products.price * order_items.amount) as total')
                ->value('total') ?? 0;

            $response = $this->actingAs($admin)->get('/admin/dashboard');
            $response->assertStatus(200);

            // Property: view data matches DB aggregates
            $this->assertEquals($expectedOrders,   $response->viewData('totalOrders'));
            $this->assertEquals($expectedProducts, $response->viewData('totalProducts'));
            $this->assertEquals($expectedUsers,    $response->viewData('totalUsers'));
            $this->assertEqualsWithDelta(
                (float) $expectedRevenue,
                (float) $response->viewData('totalRevenue'),
                0.01
            );
        }
    }

    // ---------------------------------------------------------------
    // P18a: Admin product create persists to DB
    // ---------------------------------------------------------------

    public function test_p18a_admin_product_create_persists(): void
    {
        $admin    = $this->makeAdmin();
        $category = Category::factory()->create();

        for ($i = 0; $i < 20; $i++) {
            $name  = 'Product ' . $i . ' ' . uniqid();
            $price = rand(10000, 5000000);
            $desc  = 'Description ' . $i;

            $this->actingAs($admin)->post('/admin/products', [
                'name'          => $name,
                'desc'          => $desc,
                'price'         => $price,
                'categories_id' => $category->id,
            ])->assertRedirect('/admin/products');

            // Property: product exists in DB with correct data
            $product = Product::where('name', $name)->first();
            $this->assertNotNull($product, "Product '{$name}' should exist in DB");
            $this->assertEquals($desc,        $product->desc);
            $this->assertEquals($price,       (int) $product->price);
            $this->assertEquals($category->id, $product->categories_id);
        }
    }

    // ---------------------------------------------------------------
    // P18b: Admin product update persists changes to DB
    // ---------------------------------------------------------------

    public function test_p18b_admin_product_update_persists(): void
    {
        $admin    = $this->makeAdmin();
        $category = Category::factory()->create();

        for ($i = 0; $i < 20; $i++) {
            $product  = Product::factory()->create(['categories_id' => $category->id]);
            $newName  = 'Updated Product ' . $i . ' ' . uniqid();
            $newPrice = rand(10000, 5000000);

            $this->actingAs($admin)->put('/admin/products/' . $product->id, [
                'name'          => $newName,
                'desc'          => $product->desc,
                'price'         => $newPrice,
                'categories_id' => $category->id,
            ])->assertRedirect('/admin/products');

            $product->refresh();

            // Property: updated fields match what was sent
            $this->assertEquals($newName,  $product->name);
            $this->assertEquals($newPrice, (int) $product->price);
        }
    }

    // ---------------------------------------------------------------
    // P18c: Admin product delete removes from DB
    // ---------------------------------------------------------------

    public function test_p18c_admin_product_delete_removes_from_db(): void
    {
        $admin    = $this->makeAdmin();
        $category = Category::factory()->create();

        for ($i = 0; $i < 20; $i++) {
            $product = Product::factory()->create(['categories_id' => $category->id]);
            $id      = $product->id;

            $this->actingAs($admin)
                ->delete('/admin/products/' . $id)
                ->assertRedirect('/admin/products');

            // Property: product no longer exists in DB
            $this->assertNull(
                Product::find($id),
                "Product #{$id} should be deleted from DB"
            );
        }
    }

    // ---------------------------------------------------------------
    // P18d: Admin order status update persists to DB
    // ---------------------------------------------------------------

    public function test_p18d_admin_order_status_update_persists(): void
    {
        $admin    = $this->makeAdmin();
        $customer = User::factory()->create(['role' => 0]);

        $validStatuses = [0, 1, 2, 3];

        for ($i = 0; $i < 20; $i++) {
            $order     = Order::factory()->create(['users_id' => $customer->id, 'status' => 0]);
            $newStatus = $validStatuses[array_rand($validStatuses)];

            $this->actingAs($admin)->put('/admin/orders/' . $order->id . '/status', [
                'status' => $newStatus,
            ])->assertRedirect();

            $order->refresh();

            // Property: status in DB matches what was set
            $this->assertEquals(
                $newStatus,
                $order->status,
                "Order #{$order->id} status should be {$newStatus} after update"
            );
        }
    }

    // ---------------------------------------------------------------
    // P18e: Admin category create/update/delete persists correctly
    // ---------------------------------------------------------------

    public function test_p18e_admin_category_crud_persists(): void
    {
        $admin = $this->makeAdmin();

        for ($i = 0; $i < 10; $i++) {
            $name = 'Category ' . $i . ' ' . uniqid();
            $desc = 'Desc ' . $i;

            // Create
            $this->actingAs($admin)->post('/admin/categories', [
                'name' => $name,
                'desc' => $desc,
            ])->assertRedirect();

            $category = Category::where('name', $name)->first();
            $this->assertNotNull($category);
            $this->assertEquals($desc, $category->desc);

            // Update
            $newName = 'Updated ' . $name;
            $this->actingAs($admin)->put('/admin/categories/' . $category->id, [
                'name' => $newName,
                'desc' => $desc,
            ])->assertRedirect();

            $category->refresh();
            $this->assertEquals($newName, $category->name);

            // Delete (no products attached)
            $this->actingAs($admin)->delete('/admin/categories/' . $category->id)
                ->assertRedirect();

            $this->assertNull(Category::find($category->id));
        }
    }
}
