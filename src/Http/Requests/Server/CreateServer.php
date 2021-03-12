<?php

namespace SquadMS\Servers\Http\Requests\Server;

use Illuminate\Foundation\Http\FormRequest;
use SquadMS\Servers\Server;

class CreateServer extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('admin servers');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }
}