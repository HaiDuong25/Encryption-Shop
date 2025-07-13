<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Auth;
use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends \App\Http\Controllers\Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\News::query();
        if ($request->filled('title')) {
            $query->where('title', 'like', '%' . $request->title . '%');
        }
        $news = $query->get();
        return view('admin.news.index', compact('news'));
    }

    public function create()
    {
        return view('admin.news.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'image' => 'nullable|image|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('news', 'public');
        }

        $news = News::create([
            'title' => $request->title,
            'content' => $request->content,
            'image' => $imagePath,
            'author' => $request->input('author'),
            'is_published' => $request->has('is_published'),
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Thêm tin tức thành công!',
                'news' => $news
            ]);
        }
        return redirect()->route('news.index')->with('success', 'Thêm tin tức thành công!');
    }

    public function edit($id)
    {
        $news = News::findOrFail($id);
        return view('admin.news.edit', compact('news'));
    }

    public function show($id)
    {
        $news = News::findOrFail($id);
        return view('admin.news.show', compact('news'));
    }

    public function update(Request $request, $id)
    {
        $news = News::findOrFail($id);

        $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'image' => 'nullable|image|max:2048',
        ]);

        $imagePath = $news->image;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('news', 'public');
        }

        $news->update([
            'title' => $request->title,
            'content' => $request->content,
            'image' => $imagePath,
            'author' => $request->input('author'),
            'is_published' => $request->has('is_published'),
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Cập nhật tin tức thành công!',
                'news' => $news
            ]);
        }
        return redirect()->route('news.index')->with('success', 'Cập nhật tin tức thành công!');
    }

    public function destroy($id)
    {
        $news = News::findOrFail($id);
        
        // Delete image file if exists
        if ($news->image && \Illuminate\Support\Facades\Storage::disk('public')->exists($news->image)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($news->image);
        }
        
        $news->delete();
        
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Xóa tin tức thành công!'
            ]);
        }
        return redirect()->route('news.index')->with('success', 'Xóa tin tức thành công!');
    }
}
