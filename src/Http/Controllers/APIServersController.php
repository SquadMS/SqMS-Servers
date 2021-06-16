<?php

namespace SquadMS\Servers\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Response;
use SquadMS\Servers\Http\Requests\CreateServerChatMessageRequest;
use SquadMS\Servers\Models\Server;
use SquadMS\Servers\Models\ServerChatMessage;

class APIServersController extends Controller
{
    /**
     * Returns all servers with their rcon information included.
     *
     * @return \Illuminate\Http\Response
     */
    public function servers()
    {
        return Response::json(Server::hasRconData()->get());
    }

    /**
     * Add a chat message to the defined Server instance
     *
     * @return \Illuminate\Http\Response
     */
    public function chatMessage(CreateServerChatMessageRequest $request)
    {
        /* Create and store the new ChatMessage in the database */
        ServerChatMessage::create($request->validated());

        return $this->respondSuccess();
    }

    private function respondSuccess()
    {
        return response()->json([
            'success' => true,
            'message' => 'Received Event',
        ]);
    }
}