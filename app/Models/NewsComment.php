<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsComment extends Model
{
    protected $table = 'news_comments';
    protected $fillable = [
        'news_id',
        'name',
        'email',
        'website',
        'content',
        'save_info',
    ];
    public function news()
    {
        return $this->belongsTo(News::class, 'news_id');
    }
}
