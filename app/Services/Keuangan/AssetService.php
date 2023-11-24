<?php

namespace App\Services\Keuangan;

use App\Models\Asset;
use App\Models\AssetDepreciationHistory;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Laravolt\Crud\CrudService;

class AssetService extends CrudService
{


    public function postAsset()
    {
        $asset = Asset::where("id", request()->asset_id)->first();

        $asset->post_status = "POSTED";
        $asset->update();
        return $asset;
    }
    public function unPostAsset()
    {
        $asset = Asset::where("id", request()->asset_id)->first();

        $asset->post_status = "DRAFT";
        $asset->update();
        return $asset;
    }
    public function depresiasiAsset()
    {
        $asset = Asset::where("id", request()->asset_id)->first();

        AssetDepreciationHistory::create([
            "date" => request()->date,
            "asset_id" => request()->asset_id,
            "description"=> request()->description,
            "depreciation_value"=> request()->depreciation_value,

        ]);

        $asset->load("asset_depreciation_history");
        return $asset;
    }


}
