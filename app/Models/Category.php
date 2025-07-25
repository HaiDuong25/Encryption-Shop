<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'image', 'status', 'parent_id'];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

  public function children()
{
    return $this->hasMany(Category::class, 'parent_id');
}
public function childrenRecursive()
{
    return $this->children()->with('childrenRecursive');
}

public function totalProductsCount()
{
    $count = $this->products()->where('status', 1)->count();

    foreach ($this->children as $child) {
        $count += $child->totalProductsCount();
    }

    return $count;
}

public function getAllChildrenIds()
{
    $ids = [$this->id];

    foreach ($this->children as $child) {
        $ids = array_merge($ids, $child->getAllChildrenIds());
    }

    return $ids;
}

}

