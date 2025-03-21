<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'photo',
        'parent_id',
     ];
 
     public function parent()
     {
         return $this->belongsTo(Category::class, 'parent_id');
     }
 
     public function children()
     {
         return $this->hasMany(Category::class, 'parent_id');
     }
 
     public function products()
     {
         return $this->belongsToMany(Product::class, 'product_category');
     }
   
}
