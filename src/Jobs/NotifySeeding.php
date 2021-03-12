<?php

namespace SquadMS\Servers\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use SquadMS\Servers\Services\SeedingService;

class NotifySeeding implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** @var int */
    public $playerCount = 0;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(int $playerCount)
    {
        $this->playerCount = $playerCount;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(SeedingService $seedingService)
    {
        $seedingService->createSeedingAnnouncement($this->playerCount);
    }
}