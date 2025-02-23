<?php

namespace App\Jobs;

use App\Models\Record;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

final class GetRecordsCountJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public string $startDate;

    public string $endDate;

    public int $result;

    public function __construct(string $startDate, string $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function handle(): void
    {
        $this->result = Record::query()
            ->whereDate('dateTime', '>=', $this->startDate)
            ->whereDate('dateTime', '<=', $this->endDate)
            ->count();
    }
}
