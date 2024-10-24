<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
    ];

    public function getProductSearchIndex(string $search = '') {
        $product = $this->where(function ($query) use ($search){
            if ($search) {
                $query->where('name', $search);
                $query->orWhere('name', 'like','%'. $search .'%');
            }
        })->get();

        return $product;
    }
}