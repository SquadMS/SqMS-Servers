<?php

namespace SquadMS\Servers\Http\Requests\Server;

use Illuminate\Foundation\Http\FormRequest;
use SquadMS\Servers\Server;

class StoreServer extends FormRequest
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
            'name'              => 'required|string|min:1',
            'host'              => 'required|ipv4',
            'port'              => 'required|integer|min:0|max:65535',
            'enable_query'      => 'nullable|boolean',
            'query_port'        => 'required|integer|min:0|max:65535',
            'rcon_port'         => 'integer|min:0|max:65535',
            'rcon_password'     => 'string|min:1',
            'main'              => 'nullable|boolean',
            'battlemetrics_id'  => 'nullable|integer',
            'reserved_playtime' => 'nullable|boolean',
            'announce_seeding'  => 'nullable|boolean',
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

        if (!$this->has('main')) {
            $validated['main'] = false;
        }
        if (!$this->has('enable_query')) {
            $validated['enable_query'] = false;
        }
        if (!$this->has('reserved_playtime')) {
            $validated['reserved_playtime'] = false;
        }

        return $validated;
    }
}