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
            $this->registerAccountList($perusahaan->id, $cabang->id);
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
        $parentAccountType = [
            [
                "nama" => "AKTIVA",
                "deskripsi" => "AKTIVA",
                "code" => "asset",
                "prefix_akun" => "1",
            ],
            [
                "nama" => "PASIVA",
                "deskripsi" => "PASIVA",
                "code" => "liability",
                "prefix_akun" => "2",
            ],
            [
                "nama" => "MODAL",
                "deskripsi" => "MODAL",
                "code" => "equity",
                "prefix_akun" => "3",
            ],
            [
                "nama" => "PENDAPATAN",
                "deskripsi" => "PENDAPATAN",
                "code" => "income",
                "prefix_akun" => "4",
            ],

            [
                "nama" => "HPP",
                "deskripsi" => "HPP",
                "code" => "hpp",
                "prefix_akun" => "5",
            ],
            [
                "nama" => "BIAYA OPERASIONAL",
                "deskripsi" => "BIAYA OPERASIONAL",
                "code" => "operating_expense",
                "prefix_akun" => "6",
            ],
            [
                "nama" => "ZISWAF",
                "deskripsi" => "ZISWAF",
                "code" => "ziswaf",
                "prefix_akun" => "6",
            ],
            [
                "nama" => "PAJAK",
                "deskripsi" => "PAJAK",
                "code" => "tax",
                "prefix_akun" => "7",
            ],
        ];


        foreach ($parentAccountType as $key => $value) {
            # code...
            $value['perusahaan_id'] = $perusahaan;
            $value['cabang_id'] = $cabang;
            $data = KategoriAkun::insert($value);
        }


        $accountType = [
            "100;AKTIVA;KAS;asset_cash",
            "101;AKTIVA;GIRO;asset_cash",
            "102;AKTIVA;TABUNGAN;asset_cash",
            "103;AKTIVA;DEPOSITO;asset_cash",
            "110;AKTIVA;PIUTANG;asset_receivable",
            "111;AKTIVA;PIUTANG USAHA;asset_receivable",
            "112;AKTIVA;PIUTANG KARYAWAN;asset_receivable",
            "113;AKTIVA;PIUTANG LAINNYA;asset_receivable",
            "114;AKTIVA;UANG MUKA;asset_prepayments",
            "120;AKTIVA;PERSEDIAAN BAHAN BAKU;asset_current",
            "130;AKTIVA;PERSEDIAAN DALAM PROSES;asset_current",
            "140;AKTIVA;PERSEDIAAN PRODUK JADI;asset_current",
            "150;AKTIVA;AKTIVA TETAP;asset_fixed",
            "151;AKTIVA;AKUM.PENYUSUTAN AKTIVA TETAP;asset_fixed",
            "160;AKTIVA;BIAYA DIBAYAR DIMUKA;asset_fixed",
            "170;AKTIVA;PIUTANG ANTAR CABANG;asset_fixed",
            "180;AKTIVA;AKTIVA LAINNYA;asset_fixed",
            "200;PASIVA;HUTANG;liability_current",
            "201;PASIVA;HUTANG JANGKA PENDEK;liability_current",
            "202;PASIVA;HUTANG JANGKA PANJANG;liability_current",
            "210;PASIVA;HUTANG PAJAK;liability_current",
            "220;PASIVA;PENDAPATAN DITERIMA DIMUKA;liability_current",
            "230;PASIVA;HUTANG LAINNYA;liability_current",
            "240;PASIVA;HUTANG ANTAR CABANG;liability_current",
            "300;MODAL;MODAL PENDIRI;equity",
            "310;MODAL;MODAL INVESTOR;equity",
            "320;MODAL;PRIVE;equity",
            "330;MODAL;PERMODALAN LAINNYA;equity",
            "340;MODAL;R/L DITAHAN;equity",
            "350;MODAL;R/L DIBAGIKAN U/ PENDIRI;equity",
            "360;MODAL;R/L DIBAGIKAN U/ INVESTOR;equity",
            "400;PENDAPATAN;PENDAPATAN OPERASIONAL;income",
            "401;PENDAPATAN;PENDAPATAN UTAMA;income",
            "402;PENDAPATAN;POTONGAN PENJUALAN;income",
            "410;PENDAPATAN;PENDAPATAN LAINNYA;income",
            "500;HPP;HARGA POKOK PRODUKSI;expense",
            "510;HPP;BIAYA BAHAN BAKU;expense",
            "520;HPP;BIAYA TENAGA KERJA LANGSUNG;expense",
            "530;HPP;BIAYA OVERHEAD PRODUKSI;expense",
            "600;BIAYA OPERASIONAL;BIAYA MARKETING;expense",
            "610;BIAYA OPERASIONAL;BIAYA TENAGA KERJA;expense",
            "620;BIAYA OPERASIONAL;BIAYA ADMININISTRASI & UMUM;expense",
            "630;BIAYA OPERASIONAL;BIAYA PEMELIHARAAN & PERBAIKAN;expense",
            "640;BIAYA OPERASIONAL;BIAYA PENYUSUTAN AKTIVA TETAP;expense",
            "650;BIAYA OPERASIONAL;BIAYA OPERASIONAL LAINNYA;expense",
            "690;ZISWAF;INFAQ & SEDEKAH;expense",
            "700;PAJAK;BIAYA PAJAK-PAJAK;expense",
        ];

        $kategoriAkunParent = KategoriAkun::where("perusahaan_id", $perusahaan)->where("cabang_id", $cabang)->where("parent_kategori_akun" ,  null)->get();
        foreach ($accountType as $key => $value) {

            $data = explode(";", $value);
            $parentAkun= $kategoriAkunParent->firstWhere("nama", $data[1]);
            $value = [

                "nama" => $data[2],
                "parent_kategori_akun" => $parentAkun?->id,
                "deskripsi" => $data[2] . "Perusahaan",
                "code" => $data[3],
                "prefix_akun" => $data[0],
            ];
            # code...
            $value['perusahaan_id'] = $perusahaan;
            $value['cabang_id'] = $cabang;
            KategoriAkun::insert($value);
        }
    }
}
