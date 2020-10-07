<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Berita extends Model
{
    use SoftDeletes;
    protected $table = 'berita';
    protected $with = ['kategori'];
    protected $fillable = [
        'kategori_id', 'judul', 'slug', 'tanggal', 'konten', 'tags', 'featured_image'
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id', 'id');
    }
}
