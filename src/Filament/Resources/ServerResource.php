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

                Forms\Components\Toggle::make('account_playtime')->required()->default(false),

                Forms\Components\Section::make('Host')->schema([
                    Forms\Components\TextInput::make('host')->required()->default('127.0.0.1'),
                    Forms\Components\TextInput::make('game_port')->required()->default(7787),
                    Forms\Components\TextInput::make('query_port')->required()->default(27165),
                ]),

                Forms\Components\Section::make('RCON')->schema([
                    Forms\Components\TextInput::make('rcon_port')
                        ->integer()
                        ->minValue(1)
                        ->maxValue(65535)
                        ->rules('required_with:data.rcon_password|min:1|max:65535'),
                    Forms\Components\TextInput::make('rcon_password')
                        ->rules('required_with:data.server.rcon_port'),
                ]),                
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->sortable(),
                Tables\Columns\BooleanColumn::make('account_playtime')->sortable()
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
