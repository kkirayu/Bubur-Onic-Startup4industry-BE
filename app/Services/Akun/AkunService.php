<?php

namespace App\Services\Akun;

use App\Models\Akun\Akun;
use App\Models\KategoriAkun;
use App\Services\Odoo\OdooApiService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Laravolt\Crud\Contracts\StoreRequestContract;
use Laravolt\Crud\CrudModel;
use Laravolt\Crud\CrudService;

class AkunService extends CrudService
{


    // {"id":18,"jsonrpc":"2.0","method":"call","params":{"model":"account.account","method":"create","args":[{"name":"Testing","currency_id":false,"code":"112311232","account_type":"asset_current","reconcile":false,"tax_ids":[],"tag_ids":[],"allowed_journal_ids":[],"non_trade":false}],"kwargs":{"context":{"lang":"en_US","tz":"Asia/Jakarta","uid":2,"allowed_company_ids":[1]}}}}



    public function get(Request $request): LengthAwarePaginator
    {

        $data = new OdooApiService();

        $akunData = $data->getAkunList();
        $tagData = collect($data->getTagsList());
        $kategori_akun = KategoriAkun::where("perusahaan_id", $request->perusahaan_id)->where("cabang_id", $request->cabang_id)->get();



        // dump($akunData);


        $data = collect($akunData)->map(function ($data) use ($kategori_akun,  $tagData) {


            $tagData =  $tagData->whereIn('id', $data['tag_ids'])->pluck("name")->map(function ($item) {
                return strtolower($item);
            });
            $isAkunKas =  in_array("bank", $tagData->toArray()) ?  1 :  0;
            $kategoriAkun = $kategori_akun->where('code', $data['account_type'])->first();
            $formatted = [
                "id" => $data['id'],
                "kode_akun" => $data['code'],
                "perusahaan_id" => 1,
                "cabang_id" => 1,
                "nama" => $data['name'],
                "deskripsi" => $data['name'],
                "kategori_akun_id" => $kategoriAkun?->id,
                "is_kas" => $isAkunKas,
                "parent_akun" => null,
                "created_at" => "2023-10-08T10:30:04.000000Z",
                "updated_at" => "2023-10-08T10:30:21.000000Z",
                "saldo" => $data['current_balance'],
                "created_by" => 3,
                "updated_by" => null,
                "deleted_by" => null,
                "deleted_at" => null,
                "is_kas_selection" => ["key" => $isAkunKas, "label" => $isAkunKas ? "Ya" : "Tidak"],
                "kategori_akun" => $kategoriAkun,
                "parent" => null,
            ];

            return $formatted;
        });

        return  collect($data)->paginate(10,  100, 1);
    }
}
