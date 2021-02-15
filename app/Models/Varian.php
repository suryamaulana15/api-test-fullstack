<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class Varian extends Model implements HasMedia
{
    use HasMediaTrait;

    protected $table = 'varian';

    protected $fillable = [
        'id_produk', 'nama', 'deskripsi', 'harga', 'foto', 'ukuran'
    ];

    public function produk()
    {
        return $this->belongsTo('App\Models\Produk', 'id_produk');
    }

    public function diskon()
    {
        return $this->hasOne('App\Models\Diskon', 'id_varian');
    }

    /**
     * PACKAGE
     */

    //MEDIA
    public function registerMediaCollections()
    {
        $this->addMediaCollection('foto_varian')->singleFile();
        $this->addMediaConversion('thumb')
              ->width(368)
              ->height(232)
              ->performOnCollections('foto_varian');
    }
}
