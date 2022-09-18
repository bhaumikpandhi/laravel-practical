<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNoteRequest;
use App\Http\Requests\UpdateNoteRequest;
use App\Http\Resources\NoteResource;
use App\Http\Resources\TaskResource;
use App\Models\Note;
use App\Models\Task;
use App\Services\FileUploadService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class NoteController extends Controller
{

    protected FileUploadService $fileUploadService;

    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct(FileUploadService $fileUploadService)
    {
        $this->fileUploadService = $fileUploadService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        return NoteResource::collection(Note::query()->latest()->paginate(10));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreNoteRequest $request
     * @return JsonResponse
     */
    public function store(StoreNoteRequest $request): JsonResponse
    {
        try {
            Task::query()->findOrFail($request->get('task_id'));
            $note = Note::query()->create([
                'task_id' => $request->get('task_id'),
                'subject' => $request->get('subject'),
                'note' => $request->get('note')
            ]);

            if ($request->has('noteFiles')) {
                $this->fileUploadService->uploadNoteFiles($request->file('noteFiles'), $note->id);
            }

            return response()->json([
                'note' => (new NoteResource($note)),
                'message' => trans('Note created successfully')
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateNoteRequest $request
     * @param Note $note
     * @return JsonResponse
     */
    public function update(UpdateNoteRequest $request, Note $note): JsonResponse
    {
        $task = Task::query()->findOrFail($request->get('task_id'));
        if (!$this->authorize('update-note', [$task, $note])) {
            abort(403, "Not allowed");
        }
        try {
            $note->subject = $request->get('subject');
            $note->note = $request->get('note');
            $note->save();

            return response()->json([
                'note' => (new NoteResource($note)),
                'message' => trans('Note updated successfully')
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Note $note
     * @return JsonResponse
     */
    public function destroy(Note $note): JsonResponse
    {
        try {
            $note->delete();

            return response()->json([
                'message' => trans('Note deleted successfully')
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }
}
