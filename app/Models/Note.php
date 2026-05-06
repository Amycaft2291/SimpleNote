<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;

    // Cho phép insert tất cả các cột
    protected $guarded = []; 

    /**
     * Quan hệ Nhiều - Nhiều với bảng Label thông qua bảng trung gian note_label
     */
    public function labels()
    {
        return $this->belongsToMany(Label::class, 'note_label');
    }

    /**
     * Quan hệ Một - Nhiều với bảng NoteImage
     */
    public function images()
    {
        return $this->hasMany(NoteImage::class);
    }
}