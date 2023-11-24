<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Laravolt\Crud\CrudModel;
use Laravolt\Crud\Enum\AutoMode;
use Laravolt\Fields\BelongsTo;

class Asset extends CrudModel
{
    protected $table = 'assets';

    protected string $path = "/api/keuangan/asset";

    public AutoMode $filterMode = AutoMode::BLACKLIST;
    public AutoMode $searchMode = AutoMode::BLACKLIST;
    public AutoMode $sortMode = AutoMode::BLACKLIST;

    function asset_category(): BelongsTo
    {
        return $this->belongsTo(AssetCategory::class, "asset_category_id");
    }
    function asset_depreciation_history(): HasMany
    {
        return $this->hasMany(AssetDepreciationHistory::class, "asset_id");
    }
}
