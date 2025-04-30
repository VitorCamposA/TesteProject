<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Word extends Model
{
    use HasFactory;

    protected $table = 'words';

    protected $fillable = [
        'word',
        'created_at',
        'updated_at'
    ];

    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorites');
    }
}



