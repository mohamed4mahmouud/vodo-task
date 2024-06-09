<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreNoteRequest;
use App\Http\Requests\UpdateNoteRequest;
use App\Models\Note;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {

            $notes = Note::where('user_id' , $request->user()->id)->get();
            return response()->json($notes);

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
            return response()->json($note , 201);

        } catch (\Throwable $th) {

             return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ],500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {

            $note = Note::find($id);
            if(!$note){
                return response()->json(['error' => 'Note not found'], 404);
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
    public function update(UpdateNoteRequest $request, string $id)
    {
        try {
            $note = Note::find($id);
            if(!$note){
                return response()->json(['error' => 'Note not found'], 404);
            }

            $note->update([
                'content' => $request->content,
                'user_id' => $request->user()->id
            ]);
            return response()->json($note);

        } catch (\Throwable $th) {

            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ],500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $note = Note::find($id);
            if(!$note){
                return response()->json(['error' => 'Note not found'], 404);
            }

            $note->delete();
            return response()->json(['message' => 'Note deleted successfully']);

        } catch (\Throwable $th) {

             return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ],500);
        }
    }
}
