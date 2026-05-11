<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CheckProductImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-product-images';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🖼️  Bắt đầu gắn lại ảnh sản phẩm sang Media Library...');

        $products = \App\Models\Product::all();
        $migrated = 0;
        $skipped  = 0;
        $errors   = 0;

        foreach ($products as $product) {
            if (empty($product->img)) {
                $this->line("  ⏭ Bỏ qua (không có ảnh): {$product->name}");
                $skipped++;
                continue;
            }

            // Xóa cũ để gắn lại mới
            $product->clearMediaCollection('thumbnail');
            // $product->clearMediaCollection('gallery'); // Có thể giữ lại gallery nếu muốn

            try {
                if (filter_var($product->img, FILTER_VALIDATE_URL)) {
                    // Nếu là URL
                    $product->addMediaFromUrl($product->img)
                            ->toMediaCollection('thumbnail');
                } else {
                    // Nếu là đường dẫn cục bộ
                    $fileName = basename($product->img);
                    $filePath = storage_path('app/public/' . $product->img);
                    
                    if (!file_exists($filePath)) {
                        $filePath = public_path($product->img);
                    }

                    // Nếu vẫn không thấy, thử tìm kiếm file cùng tên trong toàn bộ storage/app/public
                    if (!file_exists($filePath)) {
                        $this->line("  🔍 Đang tìm file {$fileName} trong storage...");
                        $foundFiles = glob(storage_path('app/public/*/' . $fileName));
                        if (!empty($foundFiles)) {
                            $filePath = $foundFiles[0];
                        }
                    }

                    if (file_exists($filePath)) {
                        $product->addMedia($filePath)
                                ->preservingOriginal()
                                ->toMediaCollection('thumbnail');
                    } else {
                        // Fallback: Sử dụng ảnh placeholder nếu hoàn toàn không tìm thấy
                        $this->warn("  ⚠ Không tìm thấy file: {$product->img}. Sử dụng ảnh placeholder.");
                        $placeholderUrl = "https://placehold.co/800x600/000000/FFFFFF/png?text=" . urlencode($product->name);
                        $product->addMediaFromUrl($placeholderUrl)
                                ->toMediaCollection('thumbnail');
                    }
                }

                $this->info("  ✅ Đã gắn lại: {$product->name}");
                $migrated++;
            } catch (\Exception $e) {
                $this->error("  ❌ Lỗi cho {$product->name}: " . $e->getMessage());
                $errors++;
            }
        }

        $this->newLine();
        $this->info("✅ Hoàn thành! Đã gắn: {$migrated}, Bỏ qua: {$skipped}, Lỗi: {$errors}");
    }
}
