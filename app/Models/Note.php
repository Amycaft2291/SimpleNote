<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property string $title
 * @property string|null $content
 * @property string|null $password
 * @property int created_by // created by user
 */
class Note extends Model
{
    protected $table = 'notes';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    public $incrementing = true;

    protected $fillable = [
        'title',
        'content',
        'password',
        'created_by'
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public static function make(string $title, ?string $content = null, ?string $password = null, int $userId): static
    {
        return new static ([
            'title' => $title,
            'content' => $content,
            'password' => $password,
            'created_by' => $userId
        ]);
    }

    // mối quan hệ
    public function createdByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
}
