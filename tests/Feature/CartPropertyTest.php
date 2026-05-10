<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Services\CartService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Property-based tests for cart functionality.
 *
 * P3: Cart badge reflects session state
 * P7: Add to cart round-trip — session contains product after POST /cart/add
 * P8: Cart quantity merge — adding same product twice merges, no duplicates
 * P9: Cart total is sum of price × quantity for all items
 */
class CartPropertyTest extends TestCase
{
    use RefreshDatabase;

    // ---------------------------------------------------------------
    // P3: Cart badge count equals total quantity in session
    // ---------------------------------------------------------------

    public function test_p3_cart_badge_reflects_session_state(): void
    {
        $category = Category::factory()->create();

        // Run 20 iterations with random cart sizes
        for ($i = 0; $i < 20; $i++) {
            $itemCount = rand(1, 6);
            $cart      = [];
            $totalQty  = 0;

            for ($j = 1; $j <= $itemCount; $j++) {
                $qty        = rand(1, 5);
                $totalQty  += $qty;
                $cart[$j]   = [
                    'id'       => $j,
                    'name'     => 'Product ' . $j,
                    'price'    => rand(10000, 500000),
                    'img'      => null,
                    'quantity' => $qty,
                ];
            }

            // Visit home page with cart in session
            $response = $this->withSession(['cart' => $cart])->get('/');
            $response->assertStatus(200);

            // Badge should show total quantity
            $response->assertSee((string) $totalQty);
        }
    }

    // ---------------------------------------------------------------
    // P7: POST /cart/add round-trip — product appears in session cart
    // ---------------------------------------------------------------

    public function test_p7_add_to_cart_round_trip(): void
    {
        $category = Category::factory()->create();

        for ($i = 0; $i < 20; $i++) {
            $product = Product::factory()->create(['categories_id' => $category->id]);
            $qty     = rand(1, 10);

            $response = $this->post('/cart/add', [
                'product_id' => $product->id,
                'quantity'   => $qty,
            ]);

            $response->assertRedirect();

            // Session must contain the product
            $cart = session('cart', []);
            $this->assertArrayHasKey(
                $product->id,
                $cart,
                "Cart should contain product #{$product->id} after adding"
            );
            $this->assertEquals($qty, $cart[$product->id]['quantity']);
            $this->assertEquals($product->name, $cart[$product->id]['name']);

            // Reset session for next iteration
            session()->forget('cart');
        }
    }

    // ---------------------------------------------------------------
    // P8: Adding same product twice merges quantity — no duplicates
    // ---------------------------------------------------------------

    public function test_p8_cart_quantity_merge_no_duplicates(): void
    {
        $service = new CartService();

        for ($i = 0; $i < 100; $i++) {
            $productId   = rand(1, 1000);
            $qty1        = rand(1, 10);
            $qty2        = rand(1, 10);
            $price       = rand(10000, 5000000);
            $productData = ['name' => 'Product', 'price' => $price, 'img' => null];

            $cart = [];
            $cart = $service->add($cart, $productId, $qty1, $productData);
            $cart = $service->add($cart, $productId, $qty2, $productData);

            // Property: exactly one entry for this product
            $this->assertCount(1, $cart, "Cart must have exactly 1 entry for product #{$productId}");

            // Property: quantity is sum of both additions
            $this->assertEquals(
                $qty1 + $qty2,
                $cart[$productId]['quantity'],
                "Merged quantity should be {$qty1} + {$qty2} = " . ($qty1 + $qty2)
            );
        }
    }

    // ---------------------------------------------------------------
    // P9: Cart total equals sum of price × quantity for all items
    // ---------------------------------------------------------------

    public function test_p9_cart_total_is_sum_of_price_times_quantity(): void
    {
        $service = new CartService();

        for ($i = 0; $i < 100; $i++) {
            $itemCount    = rand(1, 8);
            $cart         = [];
            $expectedTotal = 0.0;

            for ($j = 1; $j <= $itemCount; $j++) {
                $price    = (float) rand(10000, 5000000);
                $qty      = rand(1, 10);
                $expectedTotal += $price * $qty;

                $cart[$j] = [
                    'id'       => $j,
                    'name'     => 'Product ' . $j,
                    'price'    => $price,
                    'img'      => null,
                    'quantity' => $qty,
                ];
            }

            $actualTotal = $service->total($cart);

            $this->assertEqualsWithDelta(
                $expectedTotal,
                $actualTotal,
                0.01,
                "Cart total should equal sum of price×quantity for all {$itemCount} items"
            );
        }
    }
}
