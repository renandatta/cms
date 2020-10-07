<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Halaman extends Model
{
    use SoftDeletes;
    protected $table = 'halaman';
    protected $fillable = [
        'nama', 'slug', 'kode', 'parent_kode'
    ];

    public function konten()
    {
        return $this->hasMany(Konten::class, 'halaman_id', 'id');
    }
}
