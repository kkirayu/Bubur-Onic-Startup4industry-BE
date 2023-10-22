<?php

namespace App\Services\Saas;

use App\Http\Requests\CreatePerusahaanRequest;
use App\Models\KategoriAkun;
use App\Models\Perusahaan;
use App\Models\Role;
use App\Models\User;
use App\Models\UserRoleCabang;
use Illuminate\Support\Facades\DB;
use Laravolt\Crud\CrudModel;
use Laravolt\Crud\CrudService;

class PerusahaanService extends CrudService
{

    function registerPerusahaan(CreatePerusahaanRequest $request): CrudModel
    {

        $perusahaan = DB::transaction(function () use ($request) {

            $perusahaan = Perusahaan::create([
                "nama" => $request->nama,
                "kode_perusahaan" => "PERU" . date('YmdHis'),
                "status_perusahaan" => "AKTIF",
                "alamat" => $request->alamat,
                "domain_perusahaan" => $request->domain,
            ]);

            $cabang = $perusahaan->cabang()->create([
                "nama" => $request->cabang['nama'],
                "alamat" => $request->cabang['alamat'],
                "kode_cabang" =>  "CAB" . date('YmdHis'),
                "perusahaan_id" => $perusahaan->id,
            ]);

            $owner = User::create([
                "name" => $request->owner['nama'],
                "email" => $request->owner['email'],
                "password" => bcrypt($request->owner['password']),
            ]);

            $roleOwner = Role::where('name', 'OWNER_CABANG')->first();
            $this->registerAccountList($perusahaan->id,$cabang->id);
            $user_role_cabang = new  UserRoleCabang();
            $user_role_cabang->cabang_id = $cabang->id;
            $user_role_cabang->user_id = $owner->id;
            $user_role_cabang->perusahaan_id = $perusahaan->id;
            $user_role_cabang->acl_roles_id = $roleOwner->id;
            $user_role_cabang->save();

            return  $perusahaan;
        });




        return $perusahaan;
    }


    function registerAccountList($perusahaan, $cabang)
    {
        $accountType = [
            [
                "nama" => "Receivable",
                "deskripsi" => "Receivable",
                "code" => "asset_receivable",
                "prefix_akun" => "1",
            ],
            [
                "nama" => "Payable",
                "deskripsi" => "Payable",
                "code" => "liability_payable",
                "prefix_akun" => "1",
            ],
            [
                "nama" => "Bank and Cash",
                "deskripsi" => "Bank and Cash",
                "code" => "asset_cash",
                "prefix_akun" => "1",
            ],
            [
                "nama" => "Credit Card",
                "deskripsi" => "Credit Card",
                "code" => "liability_credit_card",
                "prefix_akun" => "1",
            ],
            [
                "nama" => "Current Assets",
                "deskripsi" => "Current Assets",
                "code" => "asset_current",
                "prefix_akun" => "1",
            ],
            [
                "nama" => "Non-current Assets",
                "deskripsi" => "Non-current Assets",
                "code" => "asset_non_current",
                "prefix_akun" => "1",
            ],
            [
                "nama" => "Prepayments",
                "deskripsi" => "Prepayments",
                "code" => "asset_prepayments",
                "prefix_akun" => "1",
            ],
            [
                "nama" => "Fixed Assets",
                "deskripsi" => "Fixed Assets",
                "code" => "asset_fixed",
                "prefix_akun" => "1",
            ],
            [
                "nama" => "Current Liabilities",
                "deskripsi" => "Current Liabilities",
                "code" => "liability_current",
                "prefix_akun" => "1",
            ],
            [
                "nama" => "Non-current Liabilities",
                "deskripsi" => "Non-current Liabilities",
                "code" => "liability_non_current",
                "prefix_akun" => "1",
            ],
            [
                "nama" => "Equity",
                "deskripsi" => "Equity",
                "code" => "equity",
                "prefix_akun" => "1",
            ],
            [
                "nama" => "Current Year Earnings",
                "deskripsi" => "Current Year Earnings",
                "code" => "equity_unaffected",
                "prefix_akun" => "1",
            ],
            [
                "nama" => "Income",
                "deskripsi" => "Income",
                "code" => "income",
                "prefix_akun" => "1",
            ],
            [
                "nama" => "Other Income",
                "deskripsi" => "Other Income",
                "code" => "income_other",
                "prefix_akun" => "1",
            ],
            [
                "nama" => "Expenses",
                "deskripsi" => "Expenses",
                "code" => "expense",
                "prefix_akun" => "1",
            ],
            [
                "nama" => "Depreciation",
                "deskripsi" => "Depreciation",
                "code" => "expense_depreciation",
                "prefix_akun" => "1",
            ],
            [
                "nama" => "Cost of Revenue",
                "deskripsi" => "Cost of Revenue",
                "code" => "expense_direct_cost",
                "prefix_akun" => "1",
            ],
            [
                "nama" => "Off-Balance Sheet",
                "deskripsi" => "Off-Balance Sheet",
                "code" => "off_balance",
                "prefix_akun" => "1",
            ],



        ];

        foreach ($accountType as $key => $value) {
            # code...
            $value['perusahaan_id'] = $perusahaan;
            $value['cabang_id'] = $cabang;
            KategoriAkun::insert($value);
        }
    }
}
