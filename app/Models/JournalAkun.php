<?php

namespace App\Models;

use App\Traits\HideCompanyTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laravolt\Crud\CrudModel;

class JournalAkun extends CrudModel
{

    use HideCompanyTrait;
    protected $table = 'journal_akuns';

    protected string $path = "/api/journal/journal-akun";

    

    function akun_instance(): BelongsTo
    {
        return $this->belongsTo(Akun::class,  "akun");
    }

    function journal(): BelongsTo
    {
        return $this->belongsTo(Journal::class,  "journal_id");
    }
}
