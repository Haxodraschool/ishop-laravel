<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ProductRebuildSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('🚀 Bắt đầu tái cấu trúc sản phẩm và ảnh...');

        // 1. Clear existing data
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('media')->truncate();
        Product::truncate();
        Category::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 2. Clear storage/app/public/[1-9]* directories to clean Media Library folders
        $publicPath = storage_path('app/public');
        if (File::exists($publicPath)) {
            $directories = File::directories($publicPath);
            foreach ($directories as $dir) {
                if (is_numeric(basename($dir))) {
                    File::deleteDirectory($dir);
                }
            }
        }

        // 3. Create Categories
        $catIphone = Category::create(['name' => 'iPhone', 'slug' => 'iphone']);
        $catIpad = Category::create(['name' => 'iPad', 'slug' => 'ipad']);
        $catMacbook = Category::create(['name' => 'MacBook', 'slug' => 'macbook']);
        $catAirpods = Category::create(['name' => 'AirPods', 'slug' => 'airpods']);

        $products = [
            [
                'name' => 'iPhone 17',
                'desc' => 'Thiết kế mới mẻ, hiệu năng đỉnh cao.',
                'price' => 24990000,
                'img_source' => 'Iphone17.jpg',
                'categories_id' => $catIphone->id,
            ],
            [
                'name' => 'iPhone 16 Pro Max',
                'desc' => 'Titanium, siêu nhẹ, sức mạnh vượt trội.',
                'price' => 31990000,
                'img_source' => 'iphone16promax.jpg',
                'categories_id' => $catIphone->id,
            ],
            [
                'name' => 'iPhone 17 Pro Max',
                'desc' => 'Thiết kế titan đẳng cấp, sức mạnh vượt bậc từ chip A19 Pro.',
                'price' => 34990000,
                'img_source' => 'iphone17promax.jpeg',
                'categories_id' => $catIphone->id,
            ],
            [
                'name' => 'iPad Gen 10',
                'desc' => 'Nhiều màu sắc, linh hoạt, phù hợp mọi nhu cầu.',
                'price' => 10990000,
                'img_source' => 'ipadgen10.jpg',
                'categories_id' => $catIpad->id,
            ],
            [
                'name' => 'iPad Air 11-inch',
                'desc' => 'Chip M2, sức mạnh Pro trong thiết kế mỏng nhẹ.',
                'price' => 16990000,
                'img_source' => 'ipadair11.jpeg',
                'categories_id' => $catIpad->id,
            ],
            [
                'name' => 'iPad Pro 11-inch',
                'desc' => 'Mỏng nhẹ nhất với màn hình OLED tuyệt hảo.',
                'price' => 28990000,
                'img_source' => 'ipadpro11.jpg',
                'categories_id' => $catIpad->id,
            ],
            [
                'name' => 'MacBook Air M1',
                'desc' => 'Sức mạnh thay đổi cuộc chơi, pin siêu khoẻ.',
                'price' => 18990000,
                'img_source' => 'macbookm1.jpg',
                'categories_id' => $catMacbook->id,
            ],
            [
                'name' => 'MacBook Air M4',
                'desc' => 'Đỉnh cao mỏng nhẹ, chip M4 siêu tốc độ.',
                'price' => 27990000,
                'img_source' => 'macbookairm4.jpg',
                'categories_id' => $catMacbook->id,
            ],
            [
                'name' => 'MacBook Pro M5',
                'desc' => 'Hiệu năng vô song cho dân chuyên nghiệp.',
                'price' => 79990000,
                'img_source' => 'macbookm5.jpg',
                'categories_id' => $catMacbook->id,
            ],
            [
                'name' => 'AirPods',
                'desc' => 'Âm thanh sống động, kết nối tức thì.',
                'price' => 3490000,
                'img_source' => 'airpod1.jpg',
                'categories_id' => $catAirpods->id,
            ],
            [
                'name' => 'AirPods 2',
                'desc' => 'Nhiều giờ nghe nhạc, sạc nhanh chóng.',
                'price' => 2990000,
                'img_source' => 'airpod2.jpg',
                'categories_id' => $catAirpods->id,
            ],
            [
                'name' => 'AirPods Pro 2',
                'desc' => 'Khử tiếng ồn tuyệt đỉnh, âm thanh không gian cá nhân hóa.',
                'price' => 6190000,
                'img_source' => 'airpodpro.jpg',
                'categories_id' => $catAirpods->id,
            ],
        ];

        foreach ($products as $data) {
            $imgSource = $data['img_source'];
            unset($data['img_source']);
            
            $data['slug'] = \Illuminate\Support\Str::slug($data['name']);
            $product = Product::create($data);

            $sourcePath = database_path('seeders/images/' . $imgSource);
            if (File::exists($sourcePath)) {
                $product->addMedia($sourcePath)
                        ->preservingOriginal()
                        ->toMediaCollection('thumbnail');
                $this->command->info("✅ Đã thêm ảnh cho: {$product->name}");
            } else {
                $this->command->warn("⚠ Không tìm thấy ảnh: {$imgSource}");
            }
        }

        $this->command->info('✨ Hoàn thành tái cấu trúc!');
    }
}
