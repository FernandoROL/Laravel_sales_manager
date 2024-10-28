<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "email",
        "adress",
        "road",
        "zip",
        "neighborhood",
    ];

    public function getClientSearchIndex(string $search = '') {
        $client = $this->where(function ($query) use ($search){
            if ($search) {
                $query->where('name', $search);
                $query->orWhere('name', 'like','%'. $search .'%');
            }
        })->get();

        return $client;
    }
}
