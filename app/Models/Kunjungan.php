<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kunjungan extends Model
{
    use SoftDeletes;
    protected $table = 'kunjungan';
    protected $fillable = [
        'ip_address', 'url'
    ];
}
