<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravolt\Camunda\Http\ExternalTaskClient;
use Laravolt\Crud\CrudManager;
use Laravolt\Crud\PrimaryKeyFormat;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        // CrudManager::setPrimaryKeyFormat(PrimaryKeyFormat::UUID);
        ExternalTaskClient::subscribe('PengajuanJournalDanKas.notifikasiReject', \App\Jobs\PengajuanPerubahanJournalDanKas\NotifikasiRejectJob::class);
        ExternalTaskClient::subscribe('PengajuanJournalDanKas.notifikasiDireksi', \App\Jobs\PengajuanPerubahanJournalDanKas\NotifikasiDireksiJob::class);
        ExternalTaskClient::subscribe('PengajuanJournalDanKas.notifikasiSelesaiProses', \App\Jobs\PengajuanPerubahanJournalDanKas\NotifikasiSelesaiProsesJob::class);
        ExternalTaskClient::subscribe('PengajuanJournalDanKas.penghapusanJournal', \App\Jobs\PengajuanPerubahanJournalDanKas\PenghapusanJournalJob::class);
        ExternalTaskClient::subscribe('PengajuanJournalDanKas.pengeditanJournal', \App\Jobs\PengajuanPerubahanJournalDanKas\PengeditanJournalJob::class);
        ExternalTaskClient::subscribe('PengajuanJournalDanKas.pembuatanKas', \App\Jobs\PengajuanPerubahanJournalDanKas\PembuatanKasJob::class);
        ExternalTaskClient::subscribe('PengajuanJournalDanKas.pembuatanJournal', \App\Jobs\PengajuanPerubahanJournalDanKas\PembuatanJournalJob::class);
        ExternalTaskClient::subscribe('PengajuanJournalDanKas.perubahanKas', \App\Jobs\PengajuanPerubahanJournalDanKas\PerubahanKasJob::class);
    
    }
}