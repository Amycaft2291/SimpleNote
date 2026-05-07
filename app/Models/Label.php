<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Label extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $fillable = ['name', 'color', 'user_id'];

    /**
     * Quan hệ Nhiều - Nhiều với bảng Note
     */
    public function notes()
    {
        return $this->belongsToMany(Note::class, 'note_label');
    }
}