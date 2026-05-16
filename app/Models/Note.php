<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;

    protected $guarded = [];
    
    protected $casts = [
        'is_pinned' => 'boolean',
        'pinned_at' => 'datetime',
        'note_password' => 'hashed',
        'created_at' => 'datetime:H:i d/m/Y',
        'updated_at' => 'datetime:H:i d/m/Y',
    ];

    public function labels()
    {
        return $this->belongsToMany(Label::class, 'note_label');
    }

    public function images()
    {
        return $this->hasMany(NoteImage::class);
    }
}