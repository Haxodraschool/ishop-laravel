<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Property-based tests for product listing and detail.
 *
 * P4: Category filter returns only matching products
 * P5: Search returns only matching products
 * P6: Product detail page renders all fields
 */
class ProductPropertyTest extends TestCase
{
    use RefreshDatabase;

    // ---------------------------------------------------------------
    // P4: For any category, filtering by categories_id returns only
    //     products belonging to that category
    // ---------------------------------------------------------------

    public function test_p4_category_filter_returns_only_matching_products(): void
    {
        // Create 3 categories with products
        $categories = Category::factory()->count(3)->create();

        foreach ($categories as $cat) {
            Product::factory()->count(rand(2, 5))->create(['categories_id' => $cat->id]);
        }

        // Run property for each category (simulates generator iterations)
        foreach ($categories as $targetCategory) {
            $response = $this->get('/products?categories_id=' . $targetCategory->id);
            $response->assertStatus(200);

            // All products in DB for this category
            $expectedIds = Product::where('categories_id', $targetCategory->id)
                ->pluck('id')
                ->sort()
                ->values()
                ->toArray();

            // Products returned in view data
            $viewProducts = $response->viewData('products');
            $returnedIds  = collect($viewProducts->items())
                ->pluck('id')
                ->sort()
                ->values()
                ->toArray();

            // Every returned product must belong to the target category
            foreach ($viewProducts->items() as $product) {
                $this->assertEquals(
                    $targetCategory->id,
                    $product->categories_id,
                    "Product #{$product->id} should belong to category #{$targetCategory->id}"
                );
            }
        }
    }

    // ---------------------------------------------------------------
    // P5: Search by keyword returns only products whose name or desc
    //     contains the keyword (case-insensitive LIKE)
    // ---------------------------------------------------------------

    public function test_p5_search_returns_only_matching_products(): void
    {
        $category = Category::factory()->create();

        // Create products with known, unique keywords
        $keywords = ['laptop', 'phone', 'tablet', 'watch', 'camera'];

        foreach ($keywords as $kw) {
            Product::factory()->count(2)->create([
                'categories_id' => $category->id,
                'name'          => $kw . ' ' . uniqid(),
            ]);
        }

        // Also create some products with no matching keyword
        Product::factory()->count(3)->create([
            'categories_id' => $category->id,
            'name'          => 'zzz_nomatch_' . uniqid(),
            'desc'          => 'zzz_nomatch_' . uniqid(),
        ]);

        // Property: for each keyword, all returned products must match
        foreach ($keywords as $kw) {
            $response = $this->get('/products?search=' . urlencode($kw));
            $response->assertStatus(200);

            $viewProducts = $response->viewData('products');

            foreach ($viewProducts->items() as $product) {
                $nameMatches = stripos($product->name, $kw) !== false;
                $descMatches = stripos($product->desc, $kw) !== false;

                $this->assertTrue(
                    $nameMatches || $descMatches,
                    "Product '{$product->name}' should match search keyword '{$kw}'"
                );
            }
        }
    }

    // ---------------------------------------------------------------
    // P6: Product detail page renders all required fields
    // ---------------------------------------------------------------

    public function test_p6_product_detail_renders_all_fields(): void
    {
        $category = Category::factory()->create(['name' => 'Test Category']);

        // Run 10 iterations with different products
        for ($i = 0; $i < 10; $i++) {
            $product = Product::factory()->create([
                'categories_id' => $category->id,
                'name'          => 'Product Name ' . $i,
                'desc'          => 'Product description ' . $i,
                'price'         => rand(10000, 5000000),
                'img'           => 'https://example.com/img' . $i . '.jpg',
            ]);

            $response = $this->get('/products/' . $product->id);
            $response->assertStatus(200);

            // All fields must appear in the rendered HTML
            $response->assertSee($product->name);
            $response->assertSee($product->desc);
            $response->assertSee($category->name);
            // Price formatted — check the numeric part
            $response->assertSee(number_format($product->price, 0, ',', '.'));
        }
    }
}
