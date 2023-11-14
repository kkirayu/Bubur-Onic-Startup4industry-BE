<?php

namespace App\Models;

use Laravolt\Crud\CrudModel;
use Laravolt\Crud\Enum\AutoMode;

class BillItem extends CrudModel
{
    protected $table = 'bill_items';

    protected string $path = "/api/keuangan/bill_items";

    public AutoMode $filterMode = AutoMode::BLACKLIST;
    public AutoMode $searchMode = AutoMode::BLACKLIST;
    public AutoMode $sortMode = AutoMode::BLACKLIST;

    public function product_instance()
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }

    public function account_instance()
    {
        return $this->hasOne(Akun::class, 'id', 'account_id');
    }
    protected $with = [
        'product_instance',
        'account_instance'
    ];
}
