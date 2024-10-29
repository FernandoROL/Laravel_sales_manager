<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salles extends Model
{
    use HasFactory;

    protected $fillable = [
        'salleNumber',
        'productId',
        'clientId',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'productId');
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'clientId');
    }

    public function getSalleSearchIndex(string $search = '')
    {
        $salle = $this->where(function ($query) use ($search) {
            if ($search) {
                $query->where('salleNumber', $search);
                $query->orWhere('salleNumber', 'like', '%' . $search . '%');
            }
        })->get();

        return $salle;
    }
}
