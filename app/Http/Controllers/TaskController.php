<?php

namespace App\Http\Controllers;

use App\Enum\TaskPriorityEnum;
use App\Enum\TaskStatusEnum;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use App\Services\FileUploadService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Http\Resources\TaskResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class TaskController extends Controller
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
        return TaskResource::collection(
            Task::query()
                ->with('notes.noteFiles')
                ->ofUser(auth('api')->user()->id)
                ->latest()
                ->paginate(10)
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreTaskRequest $request
     * @return JsonResponse
     */
    public function store(StoreTaskRequest $request): JsonResponse
    {
        try {
            $task = Task::query()->create([
                'user_id' => '1',
                'title' => $request->get('title'),
                'description' => $request->get('description'),
                'start_date' => $request->get('start_date'),
                'due_date' => $request->get('due_date'),
                'status' => $request->get('status'),
                'priority' => $request->get('priority'),
            ]);
            if ($request->has('notes')) {
                $task->notes()->createMany($request->get('notes'));
                $task->load('notes');

                foreach ($request->get('notes') as $key => $value) {
                    $singleNote = $task->notes->get($key);
                    $this->fileUploadService->uploadNoteFiles($request->file('notes.' . $key . '.files'), $singleNote->id);
                }
            }
            return response()->json([
                'task' => (new TaskResource($task)),
                'message' => trans('Task created successfully')
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param Task $task
     * @return JsonResponse
     */
    public function show(Task $task): JsonResponse
    {
        return response()->json(new TaskResource($task));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateTaskRequest $request
     * @param Task $task
     * @return JsonResponse
     */
    public function update(UpdateTaskRequest $request, Task $task): JsonResponse
    {
        try {
            $task->title = $request->get('title');
            $task->description = $request->get('description');
            $task->start_date = $request->get('start_date');
            $task->due_date = $request->get('due_date');
            $task->status = $request->get('status');
            $task->priority = $request->get('priority');
            $task->save();

            return response()->json([
                'task' => (new TaskResource($task)),
                'message' => trans('Task updated successfully')
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Task $task
     * @return JsonResponse
     */
    public function destroy(Task $task): JsonResponse
    {
        if (!$this->authorize('delete', $task)) {
            abort(403, "Not Allowed");
        }
        try {
            $task->delete();
            return response()->json([
                'message' => trans('Task deleted successfully')
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }

    }


    /**
     * Get tasks lists with advance filter
     *
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function filteredTasks(Request $request): AnonymousResourceCollection
    {
        $request->validate([
            'filter.status' => ['nullable', Rule::in(array_column(TaskStatusEnum::cases(), 'value'))],
            'filter.priority' => ['nullable', Rule::in(array_column(TaskPriorityEnum::cases(), 'value'))],
            'filter.due_date' => ['nullable', 'date', 'date_format:Y-m-d'],
            'order_direction' => ['nullable', Rule::in(['desc', 'asc'])]
        ]);

        return TaskResource::collection(
            Task::query()
                ->withCount('notes')
                ->search($request)
                ->paginate(10)
        );
    }
}
