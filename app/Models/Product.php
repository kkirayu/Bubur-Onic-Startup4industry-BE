<?php

namespace App\Models;

use App\Traits\HideCompanyTrait;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Laravolt\Crud\CrudModel;
use Laravolt\Crud\Enum\AutoMode;
use Laravolt\Crud\Spec\BaseTableValue;

class Product extends CrudModel
{

    use HideCompanyTrait;
    protected $table = 'products';

    protected string $path = "/api/product/product";

    public AutoMode $filterMode = AutoMode::BLACKLIST;
    public AutoMode $searchMode = AutoMode::BLACKLIST;
    public AutoMode $sortMode = AutoMode::BLACKLIST;


    public function unit_instance(): HasOne
    {
        return $this->hasOne(Unit::class, 'id', 'unit');
    }
    public function kategori_instance(): HasOne
    {
        return $this->hasOne(ProductKategori::class, 'id', 'kategori');
    }
    public function brand_instance(): HasOne
    {
        return $this->hasOne(Brand::class, 'id', 'brand');
    }
    public function tableValueMapping(): array
    {
        return [
            new BaseTableValue("unit", "hasOne", "unit_instance", "name"),
            new BaseTableValue("kategori", "hasOne", "kategori_instance", "name"),
            new BaseTableValue("brand", "hasOne", "brand_instance", "name")
        ];
    }
}
