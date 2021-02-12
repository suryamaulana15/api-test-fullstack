<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Varian extends Model
{
    protected $table = 'varian';

    protected $fillable = [
        'id_produk', 'nama', 'deskripsi', 'harga', 'foto'
    ];

    public function produk()
    {
        return $this->belongsTo('App\Models\Produk', 'id_produk');
    }

    public function diskon()
    {
        return $this->hasOne('App\Models\Diskon', 'id_varian');
    }
}
