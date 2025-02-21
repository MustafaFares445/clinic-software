<?php

namespace App\Jobs;

use App\Models\Patient;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class GetPatentGenderCountJob implements ShouldQueue
{
    use Queueable;

    public string $gender;
    public int $result;
    /**
     * Create a new job instance.
     */
    public function __construct(string $gender)
    {
        $this->gender = $gender;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->result = Patient::query()
            ->where('gender' , $this->gender)
            ->count();
    }
}
