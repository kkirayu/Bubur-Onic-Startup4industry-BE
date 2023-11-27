<?php

namespace App\Console\Commands;

use App\Models\Akun;
use App\Models\Journal;
use App\Models\JournalAkun;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SyncJournal extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-journal';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        $map_akun = [
            ["akun_baru" => "100002", "akun_lama" => "1001002"],
            ["akun_baru" => "100001", "akun_lama" => "1001001"],
            ["akun_baru" => "100003", "akun_lama" => "1001003"],
            ["akun_baru" => "102001", "akun_lama" => "1001101"],
            ["akun_baru" => "102002", "akun_lama" => "1001102"],
            ["akun_baru" => "102003", "akun_lama" => "1001201"],
            ["akun_baru" => "110001", "akun_lama" => "1002001"],
            ["akun_baru" => "110002", "akun_lama" => "1002004"],
            ["akun_baru" => "112001", "akun_lama" => "1002006"],
            ["akun_baru" => "112002", "akun_lama" => "1002002"],
            ["akun_baru" => "113002", "akun_lama" => "1002003"],
            ["akun_baru" => "114001", "akun_lama" => "1004001"],
            ["akun_baru" => "114003", "akun_lama" => "1005001"],
            ["akun_baru" => "120001", "akun_lama" => "1003001"],
            ["akun_baru" => "130001", "akun_lama" => "1003004"],
            ["akun_baru" => "140001", "akun_lama" => "1003002"],
            ["akun_baru" => "140002", "akun_lama" => "1003003"],
            ["akun_baru" => "140003", "akun_lama" => "1003005"],
            ["akun_baru" => "150001", "akun_lama" => "1006001"],
            ["akun_baru" => "150002", "akun_lama" => "1006002"],
            ["akun_baru" => "150003", "akun_lama" => "1006003"],
            ["akun_baru" => "150004", "akun_lama" => "1006004"],
            ["akun_baru" => "150005", "akun_lama" => "1006005"],
            ["akun_baru" => "150006", "akun_lama" => "1004201"],
            ["akun_baru" => "151001", "akun_lama" => "1007001"],
            ["akun_baru" => "151002", "akun_lama" => "1007002"],
            ["akun_baru" => "151003", "akun_lama" => "1007003"],
            ["akun_baru" => "151004", "akun_lama" => "1007004"],
            ["akun_baru" => "160001", "akun_lama" => "1004101"],
            ["akun_baru" => "170001", "akun_lama" => "1002005"],
            ["akun_baru" => "200001", "akun_lama" => "2001001"],
            ["akun_baru" => "202001", "akun_lama" => "2004001"],
            ["akun_baru" => "210001", "akun_lama" => "2002001"],
            ["akun_baru" => "210007", "akun_lama" => "2002002"],
            ["akun_baru" => "220001", "akun_lama" => "2003001"],
            ["akun_baru" => "240001", "akun_lama" => "2001002"],
            ["akun_baru" => "300001", "akun_lama" => "3001001"],
            ["akun_baru" => "340001", "akun_lama" => "3002001"],
            ["akun_baru" => "340002", "akun_lama" => "3002002"],
            ["akun_baru" => "401001", "akun_lama" => "4001001"],
            ["akun_baru" => "401002", "akun_lama" => "4001003"],
            ["akun_baru" => "401003", "akun_lama" => "4002001"],
            ["akun_baru" => "402001", "akun_lama" => "4001002"],
            ["akun_baru" => "410001", "akun_lama" => "4001901"],
            ["akun_baru" => "410002", "akun_lama" => "4001190"],
            ["akun_baru" => "510001", "akun_lama" => "5001001"],
            ["akun_baru" => "510002", "akun_lama" => "5001004"],
            ["akun_baru" => "510003", "akun_lama" => "5001002"],
            ["akun_baru" => "510004", "akun_lama" => "5001003"],
            ["akun_baru" => "520001", "akun_lama" => "5002001"],
            ["akun_baru" => "520002", "akun_lama" => "5002002"],
            ["akun_baru" => "520003", "akun_lama" => "5002003"],
            ["akun_baru" => "520004", "akun_lama" => "5002004"],
            ["akun_baru" => "530001", "akun_lama" => "5003001"],
            ["akun_baru" => "530002", "akun_lama" => "5003002"],
            ["akun_baru" => "530003", "akun_lama" => "5003003"],
            ["akun_baru" => "530004", "akun_lama" => "5003004"],
            ["akun_baru" => "530005", "akun_lama" => "5003005"],
            ["akun_baru" => "530006", "akun_lama" => "5003006"],
            ["akun_baru" => "530007", "akun_lama" => "5003007"],
            ["akun_baru" => "530008", "akun_lama" => "5003008"],
            ["akun_baru" => "600001", "akun_lama" => "6100001"],
            ["akun_baru" => "600002", "akun_lama" => "6100002"],
            ["akun_baru" => "600003", "akun_lama" => "6100003"],
            ["akun_baru" => "600004", "akun_lama" => "6100004"],
            ["akun_baru" => "600005", "akun_lama" => "6200104"],
            ["akun_baru" => "600006", "akun_lama" => "6100201"],
            ["akun_baru" => "600007", "akun_lama" => "6200101"],
            ["akun_baru" => "600008", "akun_lama" => "6200102"],
            ["akun_baru" => "600009", "akun_lama" => "6200103"],
            ["akun_baru" => "600010", "akun_lama" => "6100005"],
            ["akun_baru" => "600011", "akun_lama" => "6100101"],
            ["akun_baru" => "600012", "akun_lama" => "6100102"],
            ["akun_baru" => "600013", "akun_lama" => "6100103"],
            ["akun_baru" => "600014", "akun_lama" => "6100104"],
            ["akun_baru" => "610001", "akun_lama" => "6300101"],
            ["akun_baru" => "610002", "akun_lama" => "6300102"],
            ["akun_baru" => "610003", "akun_lama" => "6300103"],
            ["akun_baru" => "610004", "akun_lama" => "6300104"],
            ["akun_baru" => "620001", "akun_lama" => "6300201"],
            ["akun_baru" => "620002", "akun_lama" => "6300202"],
            ["akun_baru" => "620003", "akun_lama" => "6300106"],
            ["akun_baru" => "620004", "akun_lama" => "6300105"],
            ["akun_baru" => "620005", "akun_lama" => "6400101"],
            ["akun_baru" => "620006", "akun_lama" => "6400102"],
            ["akun_baru" => "620007", "akun_lama" => "6400103"],
            ["akun_baru" => "620008", "akun_lama" => "6400104"],
            ["akun_baru" => "620009", "akun_lama" => "6400105"],
            ["akun_baru" => "620010", "akun_lama" => "6400106"],
            ["akun_baru" => "620011", "akun_lama" => "6400107"],
            ["akun_baru" => "620012", "akun_lama" => "6400108"],
            ["akun_baru" => "620013", "akun_lama" => "6400201"],
            ["akun_baru" => "620014", "akun_lama" => "6400402"],
            ["akun_baru" => "630001", "akun_lama" => "6400202"],
            ["akun_baru" => "630002", "akun_lama" => "6400301"],
            ["akun_baru" => "630003", "akun_lama" => "6400401"],
            ["akun_baru" => "630004", "akun_lama" => "6400203"],
            ["akun_baru" => "640002", "akun_lama" => "6400502"],
            ["akun_baru" => "640003", "akun_lama" => "6400501"],
            ["akun_baru" => "650004", "akun_lama" => "6400601"],
            ["akun_baru" => "650001", "akun_lama" => "6900101"],
            ["akun_baru" => "650002", "akun_lama" => "6900102"],
            ["akun_baru" => "650003", "akun_lama" => "6900201"],
            ["akun_baru" => "650901", "akun_lama" => "6900301"],
            ["akun_baru" => "690001", "akun_lama" => "6500101"],
            ["akun_baru" => "690002", "akun_lama" => "6500102"],
            ["akun_baru" => "690003", "akun_lama" => "6500103"],
            ["akun_baru" => "690004", "akun_lama" => "6500104"],
            ["akun_baru" => "700001", "akun_lama" => "6600101"],
        ];


        $akun = collect($map_akun)->pluck("akun_baru")->toArray();

        $akunList = Akun::whereIn("kode_akun", $akun)->get()->pluck("id", "kode_akun");

        $akunLama = DB::connection("onic_db")->select("select da.ak_id ,  CONCAT(dhs.hs_nomor, dhd.hd_nomor,  da.ak_nomor)  as nomor_akun,  da.ak_nama  from  dk_akun da
join dk_hierarki_dua dhd on dhd.hd_id  = da.ak_kelompok
join dk_hierarki_satu dhs on dhd.hd_level_1  = dhs.hs_id   where da.ak_perusahaan  =  1");



        $akunLama = collect($akunLama)->pluck("nomor_akun", "ak_id")->toArray();


        $bukuBesar = DB::connection("onic_db")->select("SELECT x.* FROM buburonic.dk_buku_besar x
        WHERE bb_company_id = 1
        ORDER BY x.bb_id DESC");



        $ids = (collect($bukuBesar)->pluck("bb_id"));


        $detailBukuBesar = DB::connection("onic_db")->select("SELECT x.* FROM buburonic.dk_buku_besar_detail x where bbdt_buku_besar  in (" . implode(",", $ids->toArray()) . ")");

        $jounals = collect($bukuBesar)->map(function ($bukuBesarData) use ($detailBukuBesar, $akunLama, $akunList, $map_akun) {
            $bukuBesarData = (object)$bukuBesarData;

            $invalid = false;
            $akuns = collect($detailBukuBesar)->where("bbdt_buku_besar", $bukuBesarData->bb_id)->map(function ($item) use ($akunLama, $akunList, $map_akun) {

                $item = (object)$item;
                $akunBaru = collect($map_akun)->where("akun_lama", $akunLama[$item->bbdt_coa])->first();

                $item = [
                    "id" => $akunBaru ? $akunList[$akunBaru['akun_baru']] : null,
                    "credit" => $item->bbdt_dk == "K" ? $item->bbdt_value : 0,
                    "debit" => $item->bbdt_dk == "D" ? $item->bbdt_value : 0,
                ];

                return $item;
            });
            $data = [
                "posted_at" => Carbon::now()->toDate(),
                "posted_by" => 1,
                "deskripsi" => $bukuBesarData->bb_note ?: "-",
                "tanggal_transaksi" => $bukuBesarData->bb_tanggal_transaksi,
                "judul" => $bukuBesarData->bb_nota_ref,
                "akuns" => $akuns->toArray(),
            ];
            $this->info("Inserting " . $data['judul'] . " Journals");

            if(!$this->saveJournal($data)) {
                    $this->error("Failed Inserting " . $data['judul'] . " Journals");
            }
        });


        //
    }

    function saveJournal($createJournalRequest)
    {

        $createJournalRequest = (object)$createJournalRequest;
        $akun = collect($createJournalRequest->akuns);
        if ($akun->where("id", null)->count() > 0) {
            return false;
        }
        try {

            $totalDebit = $akun->pluck("debit")->sum();
            $totalCredit = $akun->pluck("credit")->sum();

            $transaction_type = "non-kas";
            // Check if akun is kas
            if (Akun::whereIn("id", collect($akun)->pluck("id")->toArray())->where("is_kas", "1")->count() > 0) {
                $transaction_type = "kas";
            }

            $journal = Journal::create([
                "perusahaan_id" => 1,
                "cabang_id" => 1,
                "posted_at" => Carbon::now()->toDate(),
                "posted_by" => 1,
                "transaction_type" => $transaction_type,
                "kode_jurnal" => "JOURNAL-" . date("YmdHis") . "-" . rand(100, 999),
                "deskripsi" => $createJournalRequest->deskripsi,
                "tanggal_transaksi" => $createJournalRequest->tanggal_transaksi,
                "judul" => $createJournalRequest->judul,
                "total_debit" => $totalDebit,
                "total_kredit" => $totalCredit,
            ]);


            foreach ($akun as $key => $value) {
                JournalAkun::create([
                    "perusahaan_id" => 1,
                    "cabang_id" => 1,
                    "journal_id" => $journal->id,
                    "akun" => $value["id"],
                    "posisi_akun" => $value["debit"] > 0 ? "DEBIT" : "CREDIT",
                    "deskripsi" => array_key_exists("description", $value) ? $value["description"] : "",
                    "jumlah" => $value["debit"] > 0 ? $value["debit"] : $value["credit"],
                ]);
            }
        } catch (\Exception $e) {

            return false;
        }

        return true;

    }
}
