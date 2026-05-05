<?php

namespace App\Services;

use App\Models\Note;

class NoteService
{
    public function __construct()
    {

    }

    public function createNote(string $title, int $userId, ?string $content, ?string password): Note
    {
        return $note;
    }
}
