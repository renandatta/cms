<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Konten extends Model
{
    use SoftDeletes;
    protected $table = 'konten';
    protected $fillable = [
        'halaman_id', 'nama', 'konten'
    ];

    public function halaman()
    {
        return $this->belongsTo(Halaman::class, 'halaman_id', 'id');
    }
}
