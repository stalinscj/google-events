<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class UpsertEventRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'summary'        => 'required|string',
            'location'       => 'required|string',
            'description'    => 'required|string',
            'start'          => 'required',
            'start.dateTime' => 'required|date_format:Y-m-d\TH:i:sP|after:now',
            'start.timeZone' => 'required|timezone',
            'end'            => 'required',
            'end.dateTime'   => 'required|date_format:Y-m-d\TH:i:sP|after:start.dateTime',
            'end.timeZone'   => 'required|timezone',
            'status'         => 'required|string|in:confirmed,tentative,cancelled',
        ];
    }
}
