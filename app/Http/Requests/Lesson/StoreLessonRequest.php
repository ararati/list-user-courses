<?php

namespace App\Http\Requests\Lesson;

use App\Http\Requests\ApiRequest;

class StoreLessonRequest extends ApiRequest
{
    const NAME_MAX_LENGTH = 255;
    const URL_MAX_LENGTH = 1024;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'max:' . self::NAME_MAX_LENGTH],
            'url' => ['required', 'url', 'max:' . self::URL_MAX_LENGTH],
            'user_id' => ['required', 'integer', 'exists:users,id'],
        ];
    }
}
