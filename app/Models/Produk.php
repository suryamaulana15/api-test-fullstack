<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    protected $table = 'produk';

    protected $fillable = [
        'nama', 'deskripsi', 'foto'
    ];

    public function varian()
    {
        return $this->hasMany('App\Models\Varian', 'id_produk');
    }
}
