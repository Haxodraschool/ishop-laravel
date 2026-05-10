<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use App\Models\Product;

class MediaMigrationSeeder extends Seeder
{
    /**
     * Di chuyển ảnh từ storage/app/public/productsimg/ sang Spatie Media Library.
     * Chỉ chạy 1 lần sau khi cài đặt Media Library.
     */
    public function run(): void
    {
        $this->command->info('🖼️  Bắt đầu migrate ảnh sản phẩm sang Media Library...');

        $products = Product::all();
        $migrated = 0;
        $skipped  = 0;

        foreach ($products as $product) {
            if (empty($product->img)) {
                $skipped++;
                continue;
            }

            // Đường dẫn file hiện tại
            $filePath = storage_path('app/public/' . $product->img);

            if (! file_exists($filePath)) {
                $this->command->warn("  ⚠ File không tồn tại: {$product->img} (product: {$product->name})");
                $skipped++;
                continue;
            }

            // Kiểm tra nếu đã có thumbnail thì bỏ qua
            if ($product->hasMedia('thumbnail')) {
                $this->command->line("  ⏭ Đã có ảnh: {$product->name}");
                $skipped++;
                continue;
            }

            try {
                $product->addMedia($filePath)
                        ->preservingOriginal() // Giữ file gốc, không xóa
                        ->toMediaCollection('thumbnail');

                $this->command->info("  ✅ Migrated: {$product->name}");
                $migrated++;
            } catch (\Exception $e) {
                $this->command->error("  ❌ Lỗi migrate {$product->name}: " . $e->getMessage());
            }
        }

        $this->command->newLine();
        $this->command->info("✅ Hoàn thành! Migrated: {$migrated}, Bỏ qua: {$skipped}");
        $this->command->info('💡 Gợi ý: Sau khi xác nhận ảnh đúng, có thể xóa cột img trong bảng products.');
    }
}
