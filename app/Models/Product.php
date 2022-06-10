<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'price', 'quantity', 'image',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product_categories()
    {
        return $this->belongsToMany(ProductCategory::class);
    }
}
