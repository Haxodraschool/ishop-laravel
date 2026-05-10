<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Property-based tests for wishlist functionality.
 *
 * P16: Wishlist add/remove round-trip — no duplicates, remove deletes correct record
 */
class WishlistPropertyTest extends TestCase
{
    use RefreshDatabase;

    // ---------------------------------------------------------------
    // P16a: Adding same product multiple times creates only one record
    // ---------------------------------------------------------------

    public function test_p16a_wishlist_add_no_duplicates(): void
    {
        $category = Category::factory()->create();

        for ($i = 0; $i < 20; $i++) {
            $user    = User::factory()->create(['role' => 0]);
            $product = Product::factory()->create(['categories_id' => $category->id]);

            $addCount = rand(2, 5);

            // Add the same product multiple times
            for ($j = 0; $j < $addCount; $j++) {
                $this->actingAs($user)->post('/wishlist/add', [
                    'product_id' => $product->id,
                ]);
            }

            // Property: exactly one wishlist record exists
            $count = Wishlist::where('users_id', $user->id)
                ->where('products_id', $product->id)
                ->count();

            $this->assertEquals(
                1,
                $count,
                "Adding product #{$product->id} {$addCount} times should result in exactly 1 wishlist record"
            );
        }
    }

    // ---------------------------------------------------------------
    // P16b: Remove deletes the correct record, leaves others intact
    // ---------------------------------------------------------------

    public function test_p16b_wishlist_remove_deletes_correct_record(): void
    {
        $category = Category::factory()->create();

        for ($i = 0; $i < 20; $i++) {
            $user     = User::factory()->create(['role' => 0]);
            $products = Product::factory()->count(rand(2, 5))->create(['categories_id' => $category->id]);

            // Add all products to wishlist
            foreach ($products as $product) {
                Wishlist::create([
                    'users_id'    => $user->id,
                    'products_id' => $product->id,
                ]);
            }

            $totalBefore = $products->count();

            // Remove one random product
            $toRemove = $products->random();

            $this->actingAs($user)->post('/wishlist/remove', [
                'product_id' => $toRemove->id,
            ]);

            // Property: removed product no longer in wishlist
            $removedExists = Wishlist::where('users_id', $user->id)
                ->where('products_id', $toRemove->id)
                ->exists();

            $this->assertFalse(
                $removedExists,
                "Product #{$toRemove->id} should be removed from wishlist"
            );

            // Property: other products still in wishlist
            $remaining = Wishlist::where('users_id', $user->id)->count();
            $this->assertEquals(
                $totalBefore - 1,
                $remaining,
                "Wishlist should have {$totalBefore}-1 items after removing one"
            );
        }
    }

    // ---------------------------------------------------------------
    // P16c: Add/remove round-trip — wishlist returns to empty state
    // ---------------------------------------------------------------

    public function test_p16c_wishlist_add_remove_round_trip(): void
    {
        $category = Category::factory()->create();

        for ($i = 0; $i < 20; $i++) {
            $user    = User::factory()->create(['role' => 0]);
            $product = Product::factory()->create(['categories_id' => $category->id]);

            // Add
            $this->actingAs($user)->post('/wishlist/add', ['product_id' => $product->id]);

            $this->assertEquals(1, Wishlist::where('users_id', $user->id)->count());

            // Remove
            $this->actingAs($user)->post('/wishlist/remove', ['product_id' => $product->id]);

            $this->assertEquals(
                0,
                Wishlist::where('users_id', $user->id)->count(),
                "Wishlist should be empty after add+remove round-trip"
            );
        }
    }
}
