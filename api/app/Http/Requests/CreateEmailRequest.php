<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateEmailRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'from'          => 'required|array|max:2',
            'from.email'    => 'required|email',
            'from.name'     => 'nullable|string',
            'to'            => 'required|array|between:1,50',
            'to.*.email'    => 'required|email',
            'to.*.name'     => 'nullable|string',
            'cc'            => 'nullable|array|max:10',
            'cc.*.email'    => 'required|email',
            'cc.*.name'     => 'nullable|string',
            'bcc'           => 'nullable|array|max:10',
            'bcc.*.email'   => 'required|email',
            'bcc.*.name'    => 'nullable|string',
            'subject'       => 'required|string',
            'text'          => 'required|string',
            'html'          => 'required|string',
            'attachments'   => 'nullable|array',
            'attachments.*.content' => 'required|base64max:50000',
            'attachments.*.filename'=> 'required|string'
        ];
    }
}
