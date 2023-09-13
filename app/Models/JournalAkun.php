<?php

namespace App\Models;

use Laravolt\Crud\CrudModel;

class JournalAkun extends CrudModel
{
    protected $table = 'journal_akuns';

    protected string $path = "/api/journal/journal-akun";

}
