<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'photo',
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'product_category');
    }

    public function tariffs()
    {
        return $this->hasMany(ProductTariff::class);
    }

    public function orders()
    {   
    return $this->belongsToMany(Order::class)->withPivot('units')->withTimestamps();
    }

}
