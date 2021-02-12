<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Diskon extends Model
{
    protected $table = 'diskon';

    protected $fillable = [
        'id_variant','nama', 'deskripsi', 'persentasi'
    ];

    public function varian()
    {
        return $this->belongsTo('App\Models\Varian', 'id_variant');
    }
}
