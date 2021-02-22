<?php

namespace Tests\Unit\Http\Controllers\Api;

use App\Models\Lesson;
use App\Models\User;
use Database\Factories\LessonFactory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class LessonControllerTest extends TestCase
{

    public function testLessonsAreListedCorrectly()
    {
        Lesson::factory()->create();
        Lesson::factory()->create();

        $response = $this->get(route('lessons.index'));
        $response
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => ['*' => ['id', 'name', 'url', 'user']]
            ]);
    }

    public function testLessonCreatedCorrectly()
    {
        $user = User::factory()->create();
        $lesson = Lesson::factory()->make();

        $response = $this->postJson(route('lessons.store'), [
            'name' => $lesson->name,
            'url' => $lesson->url,
            'user_id' => $user->id,
        ]);

        $response
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure([
                'data' => ['id', 'name', 'url', 'user']
            ]);
    }
}
