<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class ListEventRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'timeMin'     => 'sometimes|date_format:Y-m-d\TH:i:sP',
            'timeMax'     => 'sometimes|date_format:Y-m-d\TH:i:sP',
            'timeZone'    => 'sometimes|timezone',
            'pageToken'   => 'sometimes|string',
            'showDeleted' => 'sometimes|boolean',
        ];
    }
}
