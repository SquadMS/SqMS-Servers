<?php

namespace SquadMS\Servers\Filament\Resources;

use SquadMS\Servers\Filament\Resources\ServerResource\Pages;
use SquadMS\Servers\Filament\Resources\ServerResource\RelationManagers;
use SquadMS\Servers\Models\Server;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class ServerResource extends Resource
{
    protected static ?string $model = Server::class;

    protected static ?string $navigationIcon = 'heroicon-o-server';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->required(),

                Forms\Components\Toggle::make('account_playtime')->required(),

                Forms\Components\Section::make('Host')->schema([
                    Forms\Components\TextInput::make('host')->required(),
                    Forms\Components\TextInput::make('game_port')->required(),
                    Forms\Components\TextInput::make('query_port')->required(),
                ]),

                Forms\Components\Section::make('RCON')->schema([
                    Forms\Components\TextInput::make('rcon_port')->required(),
                    Forms\Components\TextInput::make('rcon_password')->required(),
                ]),                
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ]);
    }
    
    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListServers::route('/'),
            'create' => Pages\CreateServer::route('/create'),
            'edit' => Pages\EditServer::route('/{record}/edit'),
        ];
    }
}
