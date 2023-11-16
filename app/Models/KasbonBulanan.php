<?php

namespace App\Models;

use App\Traits\HideCompanyTrait;
use Laravolt\Crud\CrudModel;
use Laravolt\Crud\Enum\AutoMode;

class KasbonBulanan extends CrudModel
{
    use HideCompanyTrait;

    protected $table = 'kasbon_bulanans';

    protected string $path = "/api/pegawai/kasbon-bulanan";


    public AutoMode $filterMode = AutoMode::BLACKLIST;
    public AutoMode $searchMode = AutoMode::BLACKLIST;
    public AutoMode $sortMode = AutoMode::BLACKLIST;

    public function addRuleExceptField(): array
    {
        return ['status'];
    }


}
