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
    $banner = \App\Models\Banner::findOrFail($id);

    // Xử lý validate ở đây nếu có

    $banner->title = $request->title;

    // Nếu có đổi ảnh:
    if ($request->hasFile('image')) {
        // Xử lý upload file, lưu lại $path
        $path = $request->file('image')->store('banners', 'public');
        $banner->image = $path;
    }

    $banner->position = $request->position;           // SỬA ĐÚNG VỊ TRÍ
    $banner->is_active = $request->is_active;         // SỬA ĐÚNG TRẠNG THÁI KÍCH HOẠT
    $banner->save();

    return redirect()->route('banners.index')->with('success', 'Cập nhật thành công!');
}
}