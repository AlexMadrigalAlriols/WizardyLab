<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Notes\StoreRequest;
use App\Http\Requests\Notes\UpdateRequest;
use App\Models\LeaveType;
use App\Models\Note;
use App\Models\User;
use App\UseCases\Notes\StoreUseCase;
use App\UseCases\Notes\UpdateUseCase;
use Carbon\Carbon;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    public function index(Request $request)
    {
        $notes = Note::where('user_id', auth()->id())
            ->orderBy('updated_at', 'desc')->get();

        return view('dashboard.notes.index', compact('notes'));
    }

    public function create()
    {
        $note = new Note();

        return view('dashboard.notes.create', compact('note'));
    }

    public function store(StoreRequest $request) {
        $note = (new StoreUseCase(
            $request->input('content'),
            $request->input('date', null) ? Carbon::parse($request->input('date')) : null,
            auth()->user()
        ))->action();

        toast('Note created', 'success');
        return redirect()->route('dashboard.notes.index');
    }

    public function edit(Note $note)
    {
        return view('dashboard.notes.edit', compact('note'));
    }

    public function update(UpdateRequest $request, Note $note)
    {
        $note = (new UpdateUseCase(
            $note,
            $request->input('content'),
            $request->input('date', null) ? Carbon::parse($request->input('date')) : null,
        ))->action();

        toast('Note updated', 'success');
        return redirect()->route('dashboard.notes.index');
    }

    public function destroy(Note $note)
    {
        $note->delete();

        toast('Note deleted', 'success');
        return back();
    }
}
