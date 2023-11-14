<?php

namespace App\Models;

use App\Traits\HideCompanyTrait;
use Laravolt\Crud\CrudModel;
use Laravolt\Crud\Enum\AutoMode;

class ProductKategori extends CrudModel
{

    use HideCompanyTrait;
    protected $table = 'product_kategoris';

    protected string $path = "/api/product/product-kategori";

    public AutoMode $filterMode = AutoMode::BLACKLIST;
    public AutoMode $searchMode = AutoMode::BLACKLIST;
    public AutoMode $sortMode = AutoMode::BLACKLIST;


}
