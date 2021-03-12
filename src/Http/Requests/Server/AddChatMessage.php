<?php

namespace SquadMS\Servers\Http\Requests\Server;

use Illuminate\Foundation\Http\FormRequest;

class AddChatMessage extends FormRequest
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
            'server'    => 'required|integer|exists:servers,id',
            'chat'      => 'required|string|in:ChatAll,ChatTeam,ChatSquad,ChatAdmin',
            'steamId64' => 'required|integer|digits:17',
            'player'    => 'required|string',
            'message'   => 'required|string',
            'time'      => 'required|date',
        ];
    }
}