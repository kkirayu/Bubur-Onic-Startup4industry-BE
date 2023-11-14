<?php

namespace App\Models;

use App\Traits\HideCompanyTrait;
use Laravolt\Crud\CrudModel;
use Laravolt\Crud\Enum\AutoMode;

class Unit extends CrudModel
{

    use HideCompanyTrait;
    protected $table = 'units';

    protected string $path = "/api/product/unit";

    public AutoMode $filterMode = AutoMode::BLACKLIST;
    public AutoMode $searchMode = AutoMode::BLACKLIST;
    public AutoMode $sortMode = AutoMode::BLACKLIST;


}
