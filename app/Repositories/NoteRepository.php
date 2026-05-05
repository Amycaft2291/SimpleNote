<?php

namespace App\Repositories;

use App\Contracts\Repositories\INoteRepository;
use App\Models\Note;
use Illuminate\Database\Eloquent\Collection;

class NoteRepository implements INoteRepository
{
    public function findById(int $id): ?Note
    {
        return Note::query()->find($id);
    }

    public function getAll(): Collection
    {
        return Note::query()->get();
    }

    public function save(Note $note): Note
    {
        $note->save();
        return $note;
    }
}
