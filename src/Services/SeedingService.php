<?php

namespace SquadMS\Servers\Services;

use Carbon\Carbon;
use Illuminate\Support\Str;
use SquadMS\Foundation\Helpers\TimeHelper;
use SquadMS\Foundation\Services\Abstracts\DiscordConsumerService;
use SquadMS\Foundation\Services\DiscordService;

class SeedingService extends DiscordConsumerService
{
    function __construct(DiscordService $discordService)
    {
        parent::__construct($discordService);
        
        if (!config('sqms.discord.mute')) {
            $this->channelId = setting('discord.channel.seeding');
        }
    }   

    public function createSeedingAnnouncement(int $playerCount) : void
    {
        if ($this->channelId) {
            $this->discordService->createEmbedMessage($this->channelId, $this->getNotificationEmbed($playerCount));
        }
    }

    private function getMessages() : array
    {
        $genericMesssages = [
            'Das seeden hat begonnen, kommt ran!',
            'Der Server füllt sich immer schneller! Jetzt sind wir schon :count! :slight_smile: Kommense ran!',
            'Der Server füllt sich, das bedeudet Seeding :alarm:  Die ersten 20 Bleiverteiler bekommen Kekse (Nur solange der Vorrat reicht), kommt ran Leute!',
            'Der Server wird angeseedet und wir brauchen euere Unterstützung um den Server zu füllen!',
            'Auch :days wird bei uns geseedet, kommt vorbei und macht mit! :count Spieler sind schon dabei!',
            'Seedingbären' . PHP_EOL . 'Seeden hier und dort und überall' . PHP_EOL . 'Sie sind für dich da wenn du sie brauchst' . PHP_EOL . 'Das sind die Seedingbären!' . PHP_EOL . PHP_EOL . 'Kommense ran!',
            'Die Heinzelmännchen haben euch schon eine HUB errichtet und alles steht bereit. Es wird angeseedet. Kommt vorbei und spielt mit uns!',
            'Da sich bereits :count Spieler auf den Server verirrt haben, eröffne ich hiermit feierlich das Seeding!',
            'Wir sind am Seeden! Kommt und bringt den Server mit uns zusammen zum Laufen.',
            'Der DSG Server wartet auf seine Spieler! An diesem wunderschönen :day wird geseedet. Kommt und helft mit!',
            'Auch ein :day ist ein Seedingtag. Kommt auf den Server und helft uns beim Seeden!',
        ];

        return array_merge($genericMesssages, []);
    }

    private function getNotificationEmbed(int $playerCount) : array
    {
        /* Get a random message and insert variables */
        $message = (string)Str::of(array_rand($this->getMessages()))
                   ->replace(':count', $playerCount)
                   ->replace(':day', Carbon::now()->locale('de')->dayName)
                   ->replace(':tod', TimeHelper::timeOfDay());

        /* Add user reference */
        $message .= PHP_EOL . '@everyone';

        return [
            'title' => 'Das Seeding hat begonnen!',
            'description' => $message,
            'color' => '15844367',
        ];
    }
}