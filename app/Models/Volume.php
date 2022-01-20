<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Episode;
class Volume extends Model
{
    use HasFactory;
    protected $fillable = [
     'mangaInfoId',
     'volume',
    ];

    public function episodes()
    {
        $this->hasMany(Episode::class);
    }
}
