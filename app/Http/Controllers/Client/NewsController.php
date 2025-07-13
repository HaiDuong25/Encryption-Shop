<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    /**
     * Display a listing of news articles.
     */
    public function index(Request $request)
    {
        $query = News::where('is_published', 1)->orderBy('created_at', 'desc');

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'like', '%' . $searchTerm . '%')
                    ->orWhere('content', 'like', '%' . $searchTerm . '%');
            });
        }

        $news = $query->paginate(9);

        // Get recent news for sidebar
        $recentNews = News::where('is_published', 1)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('client.news.index', compact('news', 'recentNews'));
    }

    /**
     * Display the specified news article.
     */
    public function show($id)
    {
        $article = News::where('is_published', 1)->findOrFail($id);

        // Get related news (same category or recent)
        $relatedNews = News::where('is_published', 1)
            ->where('id', '!=', $id)
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        // Get recent news for sidebar
        $recentNews = News::where('is_published', 1)
            ->where('id', '!=', $id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('client.news.show', compact('article', 'relatedNews', 'recentNews'));
    }
}
