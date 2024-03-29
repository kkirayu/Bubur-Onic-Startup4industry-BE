<?php

namespace App\Services\Akun;

use App\Http\Requests\CreateAkunRequest;
use App\Models\Akun;
use App\Models\JournalAkun;
use App\Models\KategoriAkun;
use App\Services\Odoo\OdooAccountService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Laravolt\Crud\Contracts\StoreRequestContract;
use Laravolt\Crud\CrudModel;
use Laravolt\Crud\CrudService;

class AkunService extends CrudService
{


    // {"id":18,"jsonrpc":"2.0","method":"call","params":{"model":"account.account","method":"create","args":[{"name":"Testing","currency_id":false,"code":"112311232","account_type":"asset_current","reconcile":false,"tax_ids":[],"tag_ids":[],"allowed_journal_ids":[],"non_trade":false}],"kwargs":{"context":{"lang":"en_US","tz":"Asia/Jakarta","uid":2,"allowed_company_ids":[1]}}}}

    public function avaiableAccount(Request $request)
    {

        return  $this->model->where("perusahaan_id", $request->perusahaan_id)->where("cabang_id", $request->cabang_id)->get();
        // $data = new OdooAccountService();

        // $filterDomain = [];

        // if ($request->has("search")) {
        //     $filterDomain = [
        //         ["name", "ilike", $request->search]
        //     ];
        // }

        // $akunData = $data->getAkunList($filterDomain);
        // $akunData = $akunData['records'];
        // $tagData = collect($data->getTagsList());
        // $kategori_akun = KategoriAkun::where("perusahaan_id", $request->perusahaan_id)->where("cabang_id", $request->cabang_id)->get();



        // // dump($akunData);


        // $data = collect($akunData)->map(function ($data) use ($kategori_akun,  $tagData) {


        //     $tagData =  $tagData->whereIn('id', $data['tag_ids'])->pluck("name")->map(function ($item) {
        //         return strtolower($item);
        //     });
        //     $isAkunKas =  in_array("bank", $tagData->toArray()) ?  1 :  0;
        //     $kategoriAkun = $kategori_akun->where("prefix_akun", substr($data['code'], 0, 3))->first();
        //     $formatted = [
        //         "id" => $data['id'],
        //         "kode_akun" => $data['code'],
        //         "perusahaan_id" => 1,
        //         "cabang_id" => 1,
        //         "nama" => $data['name'],
        //         "deskripsi" => $data['name'],
        //         "kategori_akun_id" => $kategoriAkun?->id,
        //         "is_kas" => $isAkunKas,
        //         "parent_akun" => null,
        //         "created_at" => "2023-10-08T10:30:04.000000Z",
        //         "updated_at" => "2023-10-08T10:30:21.000000Z",
        //         "saldo" => $data['current_balance'],
        //         "created_by" => 3,
        //         "updated_by" => null,
        //         "deleted_by" => null,
        //         "deleted_at" => null,
        //         "is_kas_selection" => ["key" => $isAkunKas, "label" => $isAkunKas ? "Ya" : "Tidak"],
        //         "kategori_akun" => $kategoriAkun,
        //         "parent" => null,
        //     ];

        //     return $formatted;
        // });

        // $dataCount = $data->count();
        // return  collect($data)->paginate($dataCount, $dataCount, 1);
    }


    // public function get(Request $request): LengthAwarePaginator
    // {


    //     $data = new OdooAccountService();

    //     $filterDomain = [];

    //     if ($request->has("search")) {
    //         $filterDomain = [
    //             ["name", "ilike", $request->search]
    //         ];
    //     }

    //     $akunData = $data->getAkunList($filterDomain);
    //     $akunData = $akunData['records'];
    //     $tagData = collect($data->getTagsList());
    //     $kategori_akun = KategoriAkun::where("perusahaan_id", $request->perusahaan_id)->where("cabang_id", $request->cabang_id)->get();



    //     // dump($akunData);


    //     $data = collect($akunData)->map(function ($data) use ($kategori_akun,  $tagData) {


    //         $tagData =  $tagData->whereIn('id', $data['tag_ids'])->pluck("name")->map(function ($item) {
    //             return strtolower($item);
    //         });
    //         $isAkunKas =  in_array("bank", $tagData->toArray()) ?  1 :  0;
    //         $kategoriAkun = $kategori_akun->where("prefix_akun", substr($data['code'], 0, 3))->first();
    //         $formatted = [
    //             "id" => $data['id'],
    //             "kode_akun" => $data['code'],
    //             "perusahaan_id" => 1,
    //             "cabang_id" => 1,
    //             "nama" => $data['name'],
    //             "deskripsi" => $data['name'],
    //             "kategori_akun_id" => $kategoriAkun?->id,
    //             "is_kas" => $isAkunKas,
    //             "parent_akun" => null,
    //             "created_at" => "2023-10-08T10:30:04.000000Z",
    //             "updated_at" => "2023-10-08T10:30:21.000000Z",
    //             "saldo" => $data['current_balance'],
    //             "created_by" => 3,
    //             "updated_by" => null,
    //             "deleted_by" => null,
    //             "deleted_at" => null,
    //             "is_kas_selection" => ["key" => $isAkunKas, "label" => $isAkunKas ? "Ya" : "Tidak"],
    //             "kategori_akun" => $kategoriAkun,
    //             "parent" => null,
    //         ];

    //         return $formatted;
    //     });

    //     $currentPage = (($request->page  ?? 0) +  1);
    //     $dataCount = ($data->count());
    //     $paginatedData = $data->splice($currentPage * 10 - 10, 10);
    //     return  collect($paginatedData)->paginate(10, $dataCount, $currentPage);
    // }

    // function createAkun(CreateAkunRequest $createAkunRequest)
    // {

    //     $odoo = new OdooAccountService();

    //     $data = $odoo->createAkun($createAkunRequest->nama_akun, $createAkunRequest->kode_akun, $createAkunRequest->account_type, $createAkunRequest->is_akun_bank);

    //     if ($data) {
    //         return collect($createAkunRequest);
    //     }
    //     throw new \Exception("Gagal membuat akun");
    // }

}
