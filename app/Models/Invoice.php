<?php

namespace App\Models;

use App\Traits\HideCompanyTrait;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Laravolt\Crud\CrudModel;
use Laravolt\Crud\Enum\AutoMode;
use Laravolt\Crud\Spec\BaseTableValue;

class Invoice extends CrudModel
{
    use HideCompanyTrait;
    protected $table = 'invoices';

    protected string $path = "/api/keuangan/invoice";

    public AutoMode $filterMode = AutoMode::BLACKLIST;
    public AutoMode $searchMode = AutoMode::BLACKLIST;
    public AutoMode $sortMode = AutoMode::BLACKLIST;

    public function invoiceDetails(): HasMany
    {
        return $this->hasMany(InvoiceItem::class, 'invoice_id', 'id');
    }
    public function customer(): HasOne
    {
        return $this->hasOne(Customer::class, 'id', 'customer_id');
    }
}
