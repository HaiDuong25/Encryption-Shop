<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    protected $fillable = ['name', 'color_code'];

    public function productVariants()
    {
        return $this->hasMany(ProductVariant::class);
    }
}
