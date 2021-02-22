<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Lesson\ListLessonsRequest;
use App\Http\Resources\LessonResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\Lesson\StoreLessonRequest;
use App\Models\Lesson;

class LessonController extends Controller
{
    /**
     * Display a listing of the lesson.
     *
     * @param ListLessonsRequest $request
     * @return AnonymousResourceCollection
     */
    public function index(ListLessonsRequest $request)
    {
        $lessons = Lesson::with('user');
        $itemsPerPage = $request->input('items_per_page');
        $page = $request->input('page');

        if ($itemsPerPage > 0) {
            $lessons = $lessons->paginate($itemsPerPage, ['*'], 'page', $page);
        } else {
            $lessons = $lessons->get();
        }

        return LessonResource::collection($lessons);
    }

    /**
     * Store a newly created lesson in storage.
     *
     * @param StoreLessonRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreLessonRequest $request)
    {
        $createdLessonId = Lesson::create($request->validated())->id;
        $lessonWithRelationship = Lesson::with('user')->find($createdLessonId);
        return response(['data' => new LessonResource($lessonWithRelationship)], Response::HTTP_CREATED);
    }

    /**
     * Remove the specified lesson from storage.
     *
     * @param Lesson $lesson
     * @return JsonResponse
     */
    public function destroy(Lesson $lesson)
    {
        $lesson->delete();
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
