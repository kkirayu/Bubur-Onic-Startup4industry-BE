<?php
namespace App\Jobs\PengajuanPerubahanJournalDanKas;
use Laravolt\Camunda\Dto\ExternalTask;
use Laravolt\Camunda\Http\ExternalTaskClient;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NotifikasiDireksiJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public string $workerId,
        public ExternalTask $task
    ) {
    }


    /**
     * Execute the job.
     */
    public function handle()
    {
        $status = ExternalTaskClient::complete($this->task->id, $this->workerId);
    }
}

