<?php

namespace SquadMS\Servers\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use SquadMS\Foundation\Auth\SteamUser;
use SquadMS\Foundation\Repositories\UserRepository;

class CreateServerChatMessageRequest extends FormRequest
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
            'steamId64' => 'nullable|integer|digits:17',
            'player'    => 'required|string',
            'message'   => 'required|string',
            'time'      => 'required|date',
        ];
    }

    /**
     * Get the validated data from the request.
     *
     * @return array
     */
    public function validated()
    {
        $validated = parent::validated();

        /* Rename the field for db */
        $validated['server_id'] = $validated['server'];
        $validated['name'] = $validated['player'];
        $validated['type'] = $validated['chat'];
        $validated['content'] = $validated['message'];

        /* Convert to Carbon */
        $validated['time'] = Carbon::parse($validated['time']);

        /* Find the User or create shallowly */
        if ($validated['steamId64']) {
            $validated['user_id'] = UserRepository::createOrUpdate(new SteamUser($validated['steamId64']), true)->id;
        }

        /* Remove unecessary */
        unset($validated['chat']);
        unset($validated['player']);
        unset($validated['server']);
        unset($validated['steamId64']);
        unset($validated['message']);

        return $validated;
    }
}
