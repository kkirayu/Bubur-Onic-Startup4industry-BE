<?php

namespace App\Models;

use App\Traits\HideCompanyTrait;
use Laravolt\Crud\CrudModel;

class JournalAkun extends CrudModel
{

    use HideCompanyTrait;
    protected $table = 'journal_akuns';

    protected string $path = "/api/journal/journal-akun";

}
