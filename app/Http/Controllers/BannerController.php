<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::all();
        return view('banners.index', compact('banners'));
    }

    public function create()
    {
        return view('banners.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'images' => 'required',
            'images.*' => 'image',
        ]);
        $imagePaths = [];
        if ($request->hasFile('images')) {
            $files = $request->file('images');
            $count = 0;
            foreach ($files as $file) {
                if ($count >= 8) break; // Đổi 5 thành 8
                $imagePaths[] = $file->store('banners', 'public');
                $count++;
            }
        }
        Banner::create([
            'title' => $request->title,
            'image' => json_encode($imagePaths),
            'link' => $request->link,
            'position' => $request->position ?? 0,
            'is_active' => $request->is_active ? true : false,
        ]);
        return redirect()->route('banners.index')->with('success', 'Tạo banner thành công!');
    }

    public function edit(Banner $banner)
    {
        $banner->images = json_decode($banner->image, true) ?: [];
        return view('banners.edit', compact('banner'));
    }

    public function update(Request $request, $id)
    {
        $banner = Banner::findOrFail($id);

        // Validate nếu cần

        $banner->title = $request->title;

        // Xử lý cập nhật nhiều ảnh (1 hoặc nhiều, tối đa 4)
        if ($request->hasFile('images')) {
            // Xóa ảnh cũ nếu có
            if ($banner->image) {
                $images = json_decode($banner->image, true);
                if (is_array($images)) {
                    foreach ($images as $img) {
                        if (Storage::disk('public')->exists($img)) {
                            Storage::disk('public')->delete($img);
                        }
                    }
                } else {
                    if (Storage::disk('public')->exists($banner->image)) {
                        Storage::disk('public')->delete($banner->image);
                    }
                }
            }
            $imagePaths = [];
            $files = $request->file('images');
            $count = 0;
            foreach ($files as $file) {
                if ($count >= 8) break; // Giới hạn tối đa 8 ảnh
                $imagePaths[] = $file->store('banners', 'public');
                $count++;
            }
            $banner->image = json_encode($imagePaths);
        }

        $banner->position = $request->position;
        $banner->is_active = $request->is_active;
        $banner->save();

        return redirect()->route('banners.index')->with('success', 'Cập nhật thành công!');
    }

    // Thêm phương thức destroy
    public function destroy($id)
    {
        $banner = Banner::findOrFail($id);

        if ($banner->image) {
            $images = json_decode($banner->image, true);
            if (is_array($images)) {
                foreach ($images as $img) {
                    if (Storage::disk('public')->exists($img)) {
                        Storage::disk('public')->delete($img);
                    }
                }
            } else {
                if (Storage::disk('public')->exists($banner->image)) {
                    Storage::disk('public')->delete($banner->image);
                }
            }
        }

        $banner->delete();

        return redirect()->route('banners.index')->with('success', 'Xóa banner thành công!');
    }
}