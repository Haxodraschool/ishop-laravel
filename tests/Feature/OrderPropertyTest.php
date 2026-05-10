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
 * Property-based tests for order checkout and listing.
 *
 * P10: Checkout creates Order + OrderItems and clears cart session
 * P11: Order list shows only the authenticated user's orders
 * P12: Order status label mapping is exhaustive (all statuses 0-3 render correctly)
 */
class OrderPropertyTest extends TestCase
{
    use RefreshDatabase;

    // ---------------------------------------------------------------
    // P10: POST /orders creates Order + OrderItems, clears cart
    // ---------------------------------------------------------------

    public function test_p10_checkout_creates_order_and_clears_cart(): void
    {
        $category = Category::factory()->create();

        for ($i = 0; $i < 10; $i++) {
            $user     = User::factory()->create(['role' => 0]);
            $itemCount = rand(1, 5);
            $cart      = [];

            for ($j = 0; $j < $itemCount; $j++) {
                $product        = Product::factory()->create(['categories_id' => $category->id]);
                $qty            = rand(1, 5);
                $cart[$product->id] = [
                    'id'       => $product->id,
                    'name'     => $product->name,
                    'price'    => $product->price,
                    'img'      => $product->img,
                    'quantity' => $qty,
                ];
            }

            $response = $this->actingAs($user)
                ->withSession(['cart' => $cart])
                ->post('/orders', [
                    'receiver' => 'Người nhận ' . $i,
                    'desc'     => 'Địa chỉ giao hàng ' . $i,
                ]);

            $response->assertRedirect();

            // Property: Order exists in DB for this user
            $order = Order::where('users_id', $user->id)->latest()->first();
            $this->assertNotNull($order, "Order should be created for user #{$user->id}");
            $this->assertEquals(0, $order->status, 'New order should have status=0');

            // Property: OrderItems match cart contents
            $this->assertCount(
                $itemCount,
                $order->orderItems,
                "Order should have {$itemCount} items matching cart"
            );

            foreach ($cart as $productId => $item) {
                $orderItem = OrderItem::where('orders_id', $order->id)
                    ->where('products_id', $productId)
                    ->first();

                $this->assertNotNull($orderItem, "OrderItem for product #{$productId} should exist");
                $this->assertEquals($item['quantity'], $orderItem->amount);
            }

            // Property: Cart session is cleared after checkout
            $this->assertEquals([], session('cart', []), 'Cart should be empty after checkout');
        }
    }

    // ---------------------------------------------------------------
    // P11: GET /orders returns only the authenticated user's orders
    // ---------------------------------------------------------------

    public function test_p11_order_list_shows_only_own_orders(): void
    {
        for ($i = 0; $i < 10; $i++) {
            $userA = User::factory()->create(['role' => 0]);
            $userB = User::factory()->create(['role' => 0]);

            $countA = rand(1, 4);
            $countB = rand(1, 4);

            Order::factory()->count($countA)->create(['users_id' => $userA->id]);
            Order::factory()->count($countB)->create(['users_id' => $userB->id]);

            $response = $this->actingAs($userA)->get('/orders');
            $response->assertStatus(200);

            $orders = $response->viewData('orders');

            // Property: every returned order belongs to userA
            foreach ($orders as $order) {
                $this->assertEquals(
                    $userA->id,
                    $order->users_id,
                    "Order #{$order->id} should belong to user #{$userA->id}, not #{$userB->id}"
                );
            }

            // Property: count matches what was created for userA
            $this->assertCount($countA, $orders);
        }
    }

    // ---------------------------------------------------------------
    // P12: All status values 0-3 render correct Vietnamese labels
    // ---------------------------------------------------------------

    public function test_p12_order_status_label_mapping_is_exhaustive(): void
    {
        $expectedLabels = [
            0 => 'Chờ xử lý',
            1 => 'Đang xử lý',
            2 => 'Đã giao',
            3 => 'Đã hủy',
        ];

        $user     = User::factory()->create(['role' => 0]);
        $category = Category::factory()->create();

        foreach ($expectedLabels as $status => $label) {
            $order = Order::factory()->create([
                'users_id' => $user->id,
                'status'   => $status,
            ]);

            // Check on orders list page
            $listResponse = $this->actingAs($user)->get('/orders');
            $listResponse->assertStatus(200);
            $listResponse->assertSee($label);

            // Check on order detail page
            $showResponse = $this->actingAs($user)->get('/orders/' . $order->id);
            $showResponse->assertStatus(200);
            $showResponse->assertSee($label);
        }
    }
}
