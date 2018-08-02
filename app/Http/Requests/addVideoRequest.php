<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Auth;

class addVideoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->isAdmin;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id' => 'required|unique:videos,youtube_id',
            'emotion_id' => 'required',
            'event_id' => 'required',
        ];
    }

    public function messages()
{
    return [
        'id.required' => 'Please, specify id!',
        'id.unique' => 'Video with such ID already in database.',
        'emotion_id.required' => 'Please, specify emotion!',
        'event_id' => 'Please, specify event!',
    ];
}
}
