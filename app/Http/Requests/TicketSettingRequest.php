<?php

namespace App\Http\Requests;

use App\Rules\ImageMimeTypeRule;
use Illuminate\Foundation\Http\FormRequest;

class TicketSettingRequest extends FormRequest
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
            'ticket_image' => $this->hasFile('ticket_image') ? new ImageMimeTypeRule() : '',
            'ticket_logo' => $this->hasFile('ticket_logo') ? new ImageMimeTypeRule() : ''
        ];
    }
}
