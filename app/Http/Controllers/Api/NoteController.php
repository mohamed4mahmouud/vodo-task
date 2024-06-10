<?php

namespace App\Http\Controllers\Api;

use App\Models\Note;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\StoreNoteRequest;
use App\Http\Requests\UpdateNoteRequest;

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $notes = Note::where('user_id' , $request->user()->id)->get();
            return response()->json(['
                status' => true,
                'message' => "success",
                "data"=>$notes
            ]);

        } catch (\Throwable $th) {

            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ],500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreNoteRequest $request)
    {
        try {

            $note = Note::create([
                'content' => $request->content,
                'user_id' => $request->user()->id
            ]);
            return response()->json([
                'status' => true,
                'message' => "success",
                'data' => $note
            ],201);

        }  catch (\Throwable $th) {

             return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ],500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Note $note )
    {
        try {
            if(Gate::denies('view-update-delete' , $note)) {
                abort(403 , 'You are not allowed to show this note');
            }
            return response()->json($note);

        } catch (\Throwable $th) {

            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ],500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateNoteRequest $request, Note $note)
    {
        try {

            if(Gate::denies('view-update-delete' , $note)) {
                abort(403 , 'You are not allowed to update this note');
            }
            $note->update([
                'content' => $request->content,
            ]);

            return response()->json(['message' => 'Note updated successfully', 'note' => $note]);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Note $note)
    {
        try {
            if(Gate::denies('view-update-delete' , $note)) {
                abort(403 , 'You are not allowed to delete this note');
            }

            $note->delete();
            return response()->json(['message' => 'Note deleted successfully'],204);

        } catch (\Throwable $th) {

            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ],500);
        }
    }
}
