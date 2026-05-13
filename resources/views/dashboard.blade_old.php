```blade
<x-app-layout>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-5 mb-8">

        <div>
            <h1 class="text-3xl font-black tracking-tight text-slate-900">
                My Notes
            </h1>

            <p class="text-sm text-slate-500 mt-1">
                Organize your ideas beautifully.
            </p>
        </div>

        {{-- RIGHT ACTIONS --}}
        <div class="flex items-center gap-3">

            {{-- SEARCH --}}
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-[20px]">
                    search
                </span>

                <input
                    data-search-input
                    type="text"
                    placeholder="Search notes..."
                    class="w-64 pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 bg-white
                           text-sm focus:outline-none focus:ring-4 focus:ring-blue-100
                           focus:border-blue-400 transition">
            </div>

            {{-- VIEW TOGGLE --}}
            <div class="bg-white border border-slate-200 rounded-xl p-1 flex items-center shadow-sm">
                <button id="gridViewBtn"
                        onclick="setView('grid')"
                        class="p-2 rounded-lg bg-blue-50 text-blue-600">
                    <span class="material-symbols-outlined">grid_view</span>
                </button>

                <button id="listViewBtn"
                        onclick="setView('list')"
                        class="p-2 rounded-lg text-slate-400 hover:bg-slate-50">
                    <span class="material-symbols-outlined">view_list</span>
                </button>
            </div>

        </div>
    </div>

    {{-- CREATE NOTE --}}
    <div class="max-w-3xl mx-auto mb-10">

        <div class="bg-white border border-slate-200 rounded-3xl shadow-sm hover:shadow-md transition">

            {{-- PLACEHOLDER --}}
            <div id="createPlaceholder"
                 onclick="openCreateForm()"
                 class="flex items-center justify-between px-6 py-5 cursor-pointer">

                <span class="text-slate-400 font-medium">
                    Take a note...
                </span>

                <span class="material-symbols-outlined text-slate-400">
                    edit_square
                </span>
            </div>

            {{-- FORM --}}
            <div id="createForm" class="hidden p-6">

                <input
                    id="newTitle"
                    type="text"
                    placeholder="Title"
                    class="w-full text-lg font-bold border-none outline-none mb-4 placeholder:text-slate-300">

                <textarea
                    id="newContent"
                    rows="5"
                    placeholder="Write something..."
                    class="w-full resize-none border-none outline-none text-sm text-slate-600 placeholder:text-slate-300"></textarea>

                <div class="flex justify-end gap-3 mt-5">

                    <button onclick="closeCreateForm()"
                            class="px-4 py-2 rounded-xl text-slate-500 hover:bg-slate-100 transition">
                        Cancel
                    </button>

                    <button onclick="saveNote()"
                            class="px-5 py-2 rounded-xl bg-blue-600 hover:bg-blue-700
                                   text-white font-semibold transition shadow-sm">
                        Save Note
                    </button>

                </div>
            </div>

        </div>

    </div>

    {{-- NOTE COUNT --}}
    <div class="flex items-center justify-between mb-6">

        <div class="flex items-center gap-3">
            <h2 class="font-bold text-slate-800 text-lg">
                All Notes
            </h2>

            <span id="noteCounter"
                  class="px-3 py-1 rounded-full bg-slate-100 text-slate-500 text-sm">
                {{ $notes->count() }}
            </span>
        </div>

        <button onclick="toggleSort()"
                class="flex items-center gap-2 text-sm font-medium
                       bg-white border border-slate-200 px-4 py-2 rounded-xl
                       hover:bg-slate-50 transition">
            <span class="material-symbols-outlined text-[18px]">swap_vert</span>
            <span id="sortText">Newest</span>
        </button>

    </div>

    {{-- EMPTY --}}
    <div id="emptyState"
         class="{{ $notes->isEmpty() ? '' : 'hidden' }}">

        <div class="bg-white border border-dashed border-slate-300 rounded-3xl p-16 text-center">

            <div class="w-20 h-20 rounded-full bg-slate-100 mx-auto flex items-center justify-center mb-5">
                <span class="material-symbols-outlined text-slate-400 text-5xl">
                    note_stack
                </span>
            </div>

            <h2 class="text-xl font-bold text-slate-800">
                No notes yet
            </h2>

            <p class="text-slate-500 mt-2">
                Create your first beautiful note above.
            </p>

        </div>

    </div>

    {{-- NOTES --}}
    <style>
        .masonry-grid{
            column-count:1;
            column-gap:1.5rem;
        }

        @media(min-width:640px){
            .masonry-grid{
                column-count:2;
            }
        }

        @media(min-width:1024px){
            .masonry-grid{
                column-count:3;
            }
        }

        .masonry-item{
            break-inside:avoid;
            margin-bottom:1.5rem;
        }

        .list-view{
            column-count:1 !important;
        }

        .note-card{
            animation:fadeIn .25s ease;
        }

        @keyframes fadeIn{
            from{
                opacity:0;
                transform:translateY(10px);
            }
            to{
                opacity:1;
                transform:translateY(0);
            }
        }
    </style>

    <div id="notesContainer" class="masonry-grid">

        @foreach($notes as $note)

            <div class="masonry-item note-card group cursor-pointer"
                 data-id="{{ $note->id }}"
                 data-pinned="{{ $note->is_pinned ? 'true' : 'false' }}"
                 data-created="{{ $note->created_at->toISOString() }}"
                 data-title="{{ addslashes($note->title) }}"
                 data-content="{{ addslashes($note->content ?? '') }}"
                 onclick="openEditModal(this)">

                <div class="bg-white border border-slate-200 rounded-3xl overflow-hidden
                            hover:shadow-xl hover:-translate-y-1 transition-all duration-300">

                    {{-- TOP --}}
                    <div class="flex items-center justify-between px-5 pt-5">

                        @if($note->is_pinned)
                            <span class="text-[11px] uppercase tracking-widest
                                         bg-yellow-100 text-yellow-700 px-2 py-1 rounded-full font-bold">
                                pinned
                            </span>
                        @else
                            <div></div>
                        @endif

                        <button
                            onclick="event.stopPropagation(); pinNote(this, {{ $note->id }});"
                            class="opacity-0 group-hover:opacity-100 transition
                                   p-2 rounded-xl hover:bg-slate-100">

                            <span class="material-symbols-outlined text-slate-500">
                                push_pin
                            </span>

                        </button>

                    </div>

                    {{-- CONTENT --}}
                    <div class="px-5 pb-5 pt-3">

                        <h2 class="text-lg font-bold text-slate-900 leading-snug mb-2 break-words">
                            {{ $note->title }}
                        </h2>

                        @if($note->content)
                            <p class="text-sm text-slate-500 leading-relaxed break-words line-clamp-5">
                                {{ $note->content }}
                            </p>
                        @endif

                        <div class="flex items-center justify-between mt-5">

                            <p class="text-xs text-slate-300">
                                {{ $note->created_at->diffForHumans() }}
                            </p>

                            <span class="material-symbols-outlined text-slate-300 text-[18px]">
                                arrow_outward
                            </span>

                        </div>

                    </div>

                </div>

            </div>

        @endforeach

    </div>

</div>

{{-- KEEP ALL YOUR EXISTING JAVASCRIPT BELOW --}}
</x-app-layout>
```
