<?php

namespace App\Http\Requests\Lesson;

use App\Http\Requests\ApiRequest;

class ListLessonsRequest extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'page' => ['integer', 'gt:0'],
            'items_per_page' => ['integer'],
        ];
    }
}
