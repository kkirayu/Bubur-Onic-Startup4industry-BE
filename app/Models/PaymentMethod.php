<?php

namespace App\Models;

use Laravolt\Crud\CrudModel;
use Laravolt\Crud\Enum\AutoMode;

class PaymentMethod extends CrudModel
{
    protected $table = 'payment_methods';

    protected string $path = "/api/keuangan/payment-method";

    public AutoMode $filterMode = AutoMode::BLACKLIST;
    public AutoMode $searchMode = AutoMode::BLACKLIST;
    public AutoMode $sortMode = AutoMode::BLACKLIST;


}
