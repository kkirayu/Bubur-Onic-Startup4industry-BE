<?php

namespace App\Models;

use App\Traits\HideCompanyTrait;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Laravolt\Crud\CrudModel;
use Laravolt\Crud\Enum\AutoMode;

class Bill extends CrudModel
{
    use HideCompanyTrait;
    protected $table = 'bills';

    protected string $path = "/api/keuangan/bill";

    public AutoMode $filterMode = AutoMode::BLACKLIST;
    public AutoMode $searchMode = AutoMode::BLACKLIST;
    public AutoMode $sortMode = AutoMode::BLACKLIST;


    public function billDetails(): HasMany
    {
        return $this->hasMany(BillItem::class, 'bill_id', 'id');
    }
    public function supplier(): HasOne
    {
        return $this->hasOne(Supplier::class, 'id', 'supplier_id');
    }
}
