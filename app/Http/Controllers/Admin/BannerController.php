<?php

namespace App\Http\Controllers\Admin;

use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerController extends \App\Http\Controllers\Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\Banner::query();
        if ($request->filled('title')) {
            $query->where('title', 'like', '%' . $request->title . '%');
        }
        $banners = $query->paginate(15);
        return view('admin.banners.index', compact('banners'));
    }

    public function create()
    {
        return view('admin.banners.create');
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
            foreach ($files as $index => $file) {
                if ($index >= 8)
                    break;
                $imagePaths[] = $file->store('banners', 'public');
            }
        }

        $banner = Banner::create([
            'title' => $request->title,
            'description' => $request->description,
            'image' => json_encode($imagePaths),
            'link' => $request->link,
            'position' => $request->position ?? 0,
            'is_active' => $request->is_active ? true : false,
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Tạo banner thành công!',
                'banner' => $banner
            ]);
        }
        return redirect()->route('banners.index')->with('success', 'Tạo banner thành công!');
    }

    public function edit(Banner $banner)
    {
        $banner->images = json_decode($banner->image, true) ?: [];
        return view('admin.banners.edit', compact('banner'));
    }

    public function show($id)
    {
        $banner = Banner::findOrFail($id);
        return view('admin.banners.show', compact('banner'));
    }

    public function update(Request $request, $id)
    {
        $banner = Banner::findOrFail($id);
        $banner->title = $request->title;
        $banner->description = $request->description;
        if ($request->hasFile('images')) {
            if ($banner->image) {
                $images = json_decode($banner->image, true);
                if (is_array($images)) {
                    foreach ($images as $img) {
                        if (Storage::disk('public')->exists($img)) {
                            Storage::disk('public')->delete($img);
                        }
                    }
                } elseif (is_string($banner->image) && $banner->image) {
                    if (Storage::disk('public')->exists($banner->image)) {
                        Storage::disk('public')->delete($banner->image);
                    }
                }
            }
            $files = $request->file('images');
            $imagePaths = [];
            foreach ($files as $index => $file) {
                if ($index >= 8)
                    break;
                $imagePaths[] = $file->store('banners', 'public');
            }
            $banner->image = json_encode($imagePaths);
        }
        $banner->link = $request->link;
        $banner->position = $request->position ?? 0;
        $banner->is_active = $request->is_active ? true : false;
        $banner->save();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Cập nhật banner thành công!',
                'banner' => $banner
            ]);
        }
        return redirect()->route('banners.index')->with('success', 'Cập nhật banner thành công!');
    }

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
            } elseif (is_string($banner->image) && $banner->image) {
                if (Storage::disk('public')->exists($banner->image)) {
                    Storage::disk('public')->delete($banner->image);
                }
            }
        }
        $banner->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Xóa banner thành công!'
            ]);
        }
        return redirect()->route('banners.index')->with('success', 'Xóa banner thành công!');
    }
}
