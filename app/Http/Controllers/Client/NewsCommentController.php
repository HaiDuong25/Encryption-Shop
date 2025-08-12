<?php
namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\NewsComment;
use App\Models\News;

class NewsCommentController extends Controller
{
    public function store(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100',
            'content' => 'required|string|max:1000',
            'website' => 'nullable|string|max:100',
            'save_info' => 'nullable|boolean',
        ]);
        $news = News::findOrFail($id);
        $comment = NewsComment::create([
            'news_id' => $news->id,
            'name' => $request->name,
            'email' => $request->email,
            'website' => $request->website,
            'content' => $request->content,
            'save_info' => $request->has('save_info'),
        ]);
        return redirect()->back()->with('success', 'Bình luận của bạn đã được gửi!');
    }
}
