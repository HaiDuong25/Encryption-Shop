<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use Illuminate\Http\Request;

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
            'image' => 'required|image',
        ]);
        $imagePath = $request->file('image')->store('banners', 'public');

        Banner::create([
            'title' => $request->title,
            'image' => $imagePath,
            'link' => $request->link,
            'position' => $request->position ?? 0,
            'is_active' => $request->is_active ? true : false,
        ]);
        return redirect()->route('banners.index')->with('success', 'Tạo banner thành công!');
    }

    public function edit(Banner $banner)
    {
        return view('banners.edit', compact('banner'));
    }

    public function update(Request $request, $id)
    {
        $banner = Banner::findOrFail($id);

        // Validate nếu cần

        $banner->title = $request->title;

        if ($request->hasFile('image')) {
            // Xóa ảnh cũ nếu có
            if ($banner->image && \Storage::disk('public')->exists($banner->image)) {
                \Storage::disk('public')->delete($banner->image);
            }

            $path = $request->file('image')->store('banners', 'public');
            $banner->image = $path;
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

        if ($banner->image && \Storage::disk('public')->exists($banner->image)) {
            \Storage::disk('public')->delete($banner->image);
        }

        $banner->delete();

        return redirect()->route('banners.index')->with('success', 'Xóa banner thành công!');
    }
}