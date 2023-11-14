<?php

namespace App\Models;

use App\Traits\HideCompanyTrait;
use Laravolt\Crud\CrudModel;
use Laravolt\Crud\Enum\AutoMode;

class Brand extends CrudModel
{

    use HideCompanyTrait;
    protected $table = 'brands';

    protected string $path = "/api/product/brand";

    public AutoMode $filterMode = AutoMode::BLACKLIST;
    public AutoMode $searchMode = AutoMode::BLACKLIST;
    public AutoMode $sortMode = AutoMode::BLACKLIST;


}
