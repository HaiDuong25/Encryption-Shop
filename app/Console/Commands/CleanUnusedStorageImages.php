<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Models\Banner;
use App\Models\Brand;
use App\Models\Category;
use App\Models\News;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;

class CleanUnusedStorageImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'storage:clean-unused-images';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Xóa các ảnh không còn được sử dụng trong storage/app/public';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $usedImages = [];

        // Banner (nhiều ảnh, dạng json)
        foreach (Banner::all() as $banner) {
            $imgs = json_decode($banner->image, true);
            if (is_array($imgs)) {
                $usedImages = array_merge($usedImages, $imgs);
            } elseif (is_string($banner->image) && $banner->image) {
                $usedImages[] = $banner->image;
            }
        }

        // Brand
        foreach (Brand::all() as $brand) {
            if ($brand->image) $usedImages[] = $brand->image;
        }

        // Category
        foreach (Category::all() as $cat) {
            if ($cat->image) $usedImages[] = $cat->image;
        }

        // News
        foreach (News::all() as $news) {
            if ($news->image) $usedImages[] = $news->image;
        }

        // Product
        foreach (Product::all() as $product) {
            if ($product->image) $usedImages[] = $product->image;
            // Bổ sung xử lý gallery nếu có
            if ($product->gallery) {
                $galleryImgs = json_decode($product->gallery, true);
                if (is_array($galleryImgs)) {
                    $usedImages = array_merge($usedImages, $galleryImgs);
                } elseif (is_string($product->gallery) && $product->gallery) {
                    $usedImages[] = $product->gallery;
                }
            }
        }

        // ProductVariant
        foreach (ProductVariant::all() as $variant) {
            if ($variant->image) $usedImages[] = $variant->image;
        }

        // User avatar và cover
        foreach (User::all() as $user) {
            if ($user->avatar) $usedImages[] = $user->avatar;
            if ($user->cover_image) $usedImages[] = $user->cover_image;
        }

        // Nếu có model khác có ảnh, thêm vào đây...

        $usedImages = array_unique($usedImages);

        // Lấy tất cả file trong storage/app/public và các thư mục con
        $allFiles = Storage::disk('public')->allFiles();

        $unusedFiles = [];
        foreach ($allFiles as $file) {
            // Bỏ qua file hệ thống
            if ($file === '.gitignore') continue;
            if (!in_array($file, $usedImages)) {
                $unusedFiles[] = $file;
            }
        }

        $storagePath = Storage::disk('public')->path('');
        // Hiển thị danh sách ảnh dư thừa
        if (count($unusedFiles)) {
            $this->info("Danh sách ảnh dư thừa (không sử dụng trong database):");
            foreach ($unusedFiles as $file) {
                $this->line($storagePath . $file);
            }
            $this->line("\nNhấn Enter để xóa toàn bộ các ảnh trên, hoặc nhập bất kỳ ký tự nào rồi Enter để hủy bỏ.");
            $input = trim(fgets(STDIN));
            if ($input === '') {
                $deleted = 0;
                foreach ($unusedFiles as $file) {
                    Storage::disk('public')->delete($file);
                    $deleted++;
                }
                $this->info("Đã xóa $deleted ảnh dư thừa trong storage/app/public.");
            } else {
                $this->info("Đã hủy thao tác xóa ảnh dư thừa.");
            }
        } else {
            $this->info("Không có ảnh dư thừa nào để xóa.");
        }
    }
}
