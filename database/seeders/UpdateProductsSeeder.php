<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class UpdateProductsSeeder extends Seeder
{
    public function run()
    {
        // Disable foreign key checks to truncate products table
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Product::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Ensure categories exist
        $catIphone = Category::firstOrCreate(['name' => 'iPhone']);
        $catIpad = Category::firstOrCreate(['name' => 'iPad']);
        $catMacbook = Category::firstOrCreate(['name' => 'MacBook']);
        $catAirpods = Category::firstOrCreate(['name' => 'AirPods']);

        $products = [
            [
                'name' => 'iPhone 17',
                'desc' => 'Thiết kế mới mẻ, hiệu năng đỉnh cao.',
                'price' => 24990000,
                'img' => 'productsimg/Iphone17.jpg',
                'categories_id' => $catIphone->id,
            ],
            [
                'name' => 'iPhone 16 Pro Max',
                'desc' => 'Titanium, siêu nhẹ, sức mạnh vượt trội.',
                'price' => 31990000,
                'img' => 'productsimg/iphone16promax.jpg',
                'categories_id' => $catIphone->id,
            ],
            [
                'name' => 'iPhone 17 Pro Max',
                'desc' => 'Thiết kế titan đẳng cấp, sức mạnh vượt bậc từ chip A19 Pro.',
                'price' => 34990000,
                'img' => 'productsimg/iphone17promax.jpeg',
                'categories_id' => $catIphone->id,
            ],
            [
                'name' => 'iPad Gen 10',
                'desc' => 'Nhiều màu sắc, linh hoạt, phù hợp mọi nhu cầu.',
                'price' => 10990000,
                'img' => 'productsimg/ipadgen10.jpg',
                'categories_id' => $catIpad->id,
            ],
            [
                'name' => 'iPad Air 11-inch',
                'desc' => 'Chip M2, sức mạnh Pro trong thiết kế mỏng nhẹ.',
                'price' => 16990000,
                'img' => 'productsimg/ipadair11.jpeg',
                'categories_id' => $catIpad->id,
            ],
            [
                'name' => 'iPad Pro 11-inch',
                'desc' => 'Mỏng nhẹ nhất với màn hình OLED tuyệt hảo.',
                'price' => 28990000,
                'img' => 'productsimg/ipadpro11.jpg',
                'categories_id' => $catIpad->id,
            ],
            [
                'name' => 'MacBook Air M1',
                'desc' => 'Sức mạnh thay đổi cuộc chơi, pin siêu khoẻ.',
                'price' => 18990000,
                'img' => 'productsimg/macbookm1.jpg',
                'categories_id' => $catMacbook->id,
            ],
            [
                'name' => 'MacBook Air M4',
                'desc' => 'Đỉnh cao mỏng nhẹ, chip M4 siêu tốc độ.',
                'price' => 27990000,
                'img' => 'productsimg/macbookairm4.jpg',
                'categories_id' => $catMacbook->id,
            ],
            [
                'name' => 'MacBook Pro M5',
                'desc' => 'Hiệu năng vô song cho dân chuyên nghiệp.',
                'price' => 79990000,
                'img' => 'productsimg/macbookm5.jpg',
                'categories_id' => $catMacbook->id,
            ],
            [
                'name' => 'AirPods',
                'desc' => 'Âm thanh sống động, kết nối tức thì.',
                'price' => 3490000,
                'img' => 'productsimg/airpod1.jpg',
                'categories_id' => $catAirpods->id,
            ],
            [
                'name' => 'AirPods 2',
                'desc' => 'Nhiều giờ nghe nhạc, sạc nhanh chóng.',
                'price' => 2990000,
                'img' => 'productsimg/airpod2.jpg',
                'categories_id' => $catAirpods->id,
            ],
            [
                'name' => 'AirPods Pro 2',
                'desc' => 'Khử tiếng ồn tuyệt đỉnh, âm thanh không gian cá nhân hóa.',
                'price' => 6190000,
                'img' => 'productsimg/airpodpro.jpg',
                'categories_id' => $catAirpods->id,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
