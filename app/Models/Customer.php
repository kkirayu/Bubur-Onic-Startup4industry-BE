<?php

namespace App\Models;

use App\Traits\HideCompanyTrait;
use Laravolt\Crud\CrudModel;
use Laravolt\Crud\Enum\AutoMode;

class Customer extends CrudModel
{
    use HideCompanyTrait;
    protected $table = 'customers';

    protected string $path = "/api/keuangan/customer";

    public AutoMode $filterMode = AutoMode::BLACKLIST;
    public AutoMode $searchMode = AutoMode::BLACKLIST;
    public AutoMode $sortMode = AutoMode::BLACKLIST;


}
