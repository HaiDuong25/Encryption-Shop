<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    // Hiển thị danh sách tin tức
    public function index()
    {
        $news = News::orderBy('created_at', 'desc')->paginate(10);
        return view('news.index', compact('news'));
    }

    // Hiển thị form tạo mới
    public function create()
    {
        return view('news.create');
    }

    // Lưu tin tức mới
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'image' => 'nullable|image|max:2048',
        ]);

        $imagePath = null;
        if($request->hasFile('image')){
            $imagePath = $request->file('image')->store('news', 'public');
        }

        News::create([
            'title' => $request->title,
            'content' => $request->content,
            'image' => $imagePath,
            'author' => auth()->user()->name ?? 'Admin',
            'is_published' => $request->has('is_published'),
        ]);

        return redirect()->route('news.index')->with('success', 'Thêm tin tức thành công!');
    }

    // Hiển thị form sửa tin
    public function edit($id)
    {
        $news = News::findOrFail($id);
        return view('news.edit', compact('news'));
    }

    // Lưu cập nhật tin tức
    public function update(Request $request, $id)
    {
        $news = News::findOrFail($id);

        $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'image' => 'nullable|image|max:2048',
        ]);

        $imagePath = $news->image;
        if($request->hasFile('image')){
            $imagePath = $request->file('image')->store('news', 'public');
        }

        $news->update([
            'title' => $request->title,
            'content' => $request->content,
            'image' => $imagePath,
            'is_published' => $request->has('is_published'),
        ]);

        return redirect()->route('news.index')->with('success', 'Cập nhật thành công!');
    }

    // Xóa tin tức
    public function destroy($id)
    {
        $news = News::findOrFail($id);
        $news->delete();

        return redirect()->route('news.index')->with('success', 'Đã xóa tin tức!');
    }
}