<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NoteImage extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Quan hệ thuộc về 1 Note
     */
    public function note()
    {
        return $this->belongsTo(Note::class);
    }
}