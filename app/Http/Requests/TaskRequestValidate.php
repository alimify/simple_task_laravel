<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskRequestValidate extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'projectId' => 'required_without:batch_update|exists:projects,id',
            'title'     => 'required_without:batch_update|max:255',
            'batch_update' => 'required_without:projectId|required_without:title|array'
        ];
    }
}
