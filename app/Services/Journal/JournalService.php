<?php

namespace App\Services\Journal;

use App\Http\Requests\CreateJournalRequest;
use App\Models\Akun;
use App\Models\Journal;
use App\Models\JournalAkun;
use App\Services\Odoo\OdooApiService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;
use Laravolt\Crud\CrudModel;
use Laravolt\Crud\CrudService;
use Laravolt\Crud\Sys\ActivityLog\AkActivityLog;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class JournalService extends CrudService
{

    public function createJournal(CreateJournalRequest $createJournalRequest)
    {

        // dd($createJournalRequest->all());

        $akun = collect($createJournalRequest->input("akuns"));
        $totalDebit = $akun->pluck("debit")->sum();
        $totalCredit = $akun->pluck("credit")->sum();

        if ($totalDebit != $totalCredit) {

            throw ValidationException::withMessages(['akuns' => 'Total debit dan kredit tidak sama']);
        }
        $transaction_type = "non-kas";
        // Check if akun is kas
        if (Akun::whereIn("id", collect($akun)->pluck("id")->toArray())->where("is_kas", "1")->count() > 0) {
            $transaction_type = "kas";
        }

        // $journal = Journal::create([
        //     "perusahaan_id" => $createJournalRequest->perusahaan_id,
        //     "cabang_id" => $createJournalRequest->cabang_id,
        //     "transaction_type" => $transaction_type, 
        //     "kode_jurnal" => "JOURNAL-" . date("YmdHis") . "-" . rand(100, 999),
        //     "deskripsi" => $createJournalRequest->input("deskripsi"),
        //     "tanggal_transaksi" => $createJournalRequest->input("tanggal_transaksi"),
        //     "judul" => $createJournalRequest->input("judul"),
        //     "total_debit" => $totalDebit,
        //     "total_kredit" => $totalCredit,
        // ]);



        $line_ids = [];

        foreach ($akun as $key => $value) {
            $value = (object) $value;
            $line_ids[] =
                [
                    0,
                    "virtual_" . $key + 1,
                    [
                        "analytic_precision" => 2,
                        "asset_category_id" => false,
                        "account_id" => $value->id,
                        "partner_id" => false,
                        "name" => false,
                        "analytic_distribution" => false,
                        "date_maturity" => false,
                        "amount_currency" => $value->credit >  0 ? -$value->credit :  $value->debit,
                        "currency_id" => 12,
                        "tax_ids" => [[6, null, []]],
                        "debit" => $value->debit ?: 0,
                        "credit" => $value->credit ?: 0,
                        "balance" => $value->credit >  0 ?   -$value->credit :  $value->debit,
                        "discount_date" => false,
                        "discount_amount_currency" => 0,
                        "tax_tag_ids" => [[6, null, []]],
                        "display_type" => "product",
                        "sequence" => 100,
                    ],
                ];
            // JournalAkun::create([
            //     "perusahaan_id" => $createJournalRequest->perusahaan_id,
            //     "cabang_id" => $createJournalRequest->cabang_id,
            //     "journal_id" => $journal->id,
            //     "akun" => $value["id"],
            //     "posisi_akun" => $value["debit"] > 0 ? "DEBIT" : "CREDIT",
            //     "deskripsi" => array_key_exists("description", $value) ? $value["description"] : "",
            //     "jumlah" => $value["debit"] > 0 ? $value["debit"] : $value["credit"],
            // ]);
        }

        $journal = [
            [
                "date" => $createJournalRequest->tanggal_transaksi,
                "auto_post" => "no",
                "auto_post_until" => false,
                "company_id" => 1,
                "journal_id" => 3,
                "show_name_warning" => false,
                "posted_before" => false,
                "payment_state" => "not_paid",
                "currency_id" => 12,
                "statement_line_id" => false,
                "payment_id" => false,
                "tax_cash_basis_created_move_ids" => [],
                "name" => "/",
                "partner_id" => false,
                "l10n_id_kode_transaksi" => false,
                "l10n_id_replace_invoice_id" => false,
                "quick_edit_total_amount" => 0,
                "ref" =>  $createJournalRequest->judul,
                "invoice_vendor_bill_id" => false,
                "invoice_date" => false,
                "payment_reference" => false,
                "partner_bank_id" => false,
                "invoice_date_due" => "2023-10-15",
                "invoice_payment_term_id" => false,
                "invoice_line_ids" => [],
                "narration" => false,
                "line_ids" => $line_ids,
                "user_id" => "",
                "invoice_user_id" => "",
                "team_id" => 1,
                "invoice_origin" => false,
                "qr_code_method" => false,
                "invoice_incoterm_id" => false,
                "fiscal_position_id" => false,
                "invoice_source_email" => false,
                "to_check" => false,
                "l10n_id_tax_number" => false,
                "campaign_id" => false,
                "medium_id" => false,
                "source_id" => false,
                "edi_document_ids" => [],
            ],
        ];
        $service = new OdooApiService();
        $response = $service->createJournal($journal);

        return collect($response);
    }
    public function postJournal($journalId)
    {
        // $journal = Journal::find($journalId);


        // if ($journal->posted_at != null) {
        //     throw ValidationException::withMessages(['id' => 'Journal sudah diposting']);
        // }
        // $journal->posted_at = date("Y-m-d H:i:s");
        // $journal->posted_by = $this->user->id;
        // $journal->save();

        // $journal->load("journalAkuns");
        // return $journal;

        $service = new OdooApiService();
        $response = $service->postJournal((int)$journalId);
        return collect($response);

    }
    public function unPostJournal($journalId)
    {
        
        $service = new OdooApiService();
        $response = $service->unPostJournal((int)$journalId);
        return collect($response);
    }


    public function get(Request $request): LengthAwarePaginator
    {

        $data = new OdooApiService();

        $akunData = $data->getJournalList();

        $data = collect($akunData['records'])->map(function ($item,  $key) {


            // $tagData =  $tagData->whereIn('id', $data['tag_ids'])->pluck("name")->map(function ($item) {
            //     return strtolower($item);
            // });
            // dd($item);
            // $isAkunKas =  in_array("bank", $tagData->toArray()) ?  1 :  0;
            // $kategoriAkun = $kategori_akun->where('code', $data['account_type'])->first();
            $data = (object) $item;
            $formatted = [
                "id" => $data->id,
                "kode_jurnal" => $data->name,
                "perusahaan_id" => 1,
                "cabang_id" => 1,
                "total_debit" => $data->amount_total ,
                "total_kredit" => $data->amount_total,
                "deskripsi" =>  $data->ref,
                "posted_at" => $data->state == 'posted' ? $data->__last_update : null,
                "created_at" => "2023-10-06T06:08:34.000000Z",
                "updated_at" => "2023-10-06T06:36:51.000000Z",
                "created_by" => null,
                "updated_by" => null,
                "deleted_by" => null,
                "judul" => $data->ref,
                "tanggal_transaksi" => $data->date,
                "deleted_at" => null,

            ];


            return $formatted;
        });

        return  collect($data)->paginate(10,  $akunData['length'], 1);
    }

    public function find(mixed  $id): CrudModel | Collection
    {

        $data = new OdooApiService();

        $akunData = $data->getJournalDetail($id);
        // dd($akunData);
        $akunData = (object) $akunData[0];

        $journalData = $data->getJournalItemWithIds($akunData->invoice_line_ids);

        $journalData = collect($journalData)->map(function ($item,  $key) {
            $item = (object) $item;
            return [
                "id" => $item->id,
                "perusahaan_id" => 1,
                "cabang_id" => 1,
                "akun" => $item->account_id[0],
                "posisi_akun" => $item->credit > 0 ? "CREDIT" : "DEBIT",
                "deskripsi" => "asdf",
                "jumlah" => $item->credit ? $item->balance * -1 : $item->balance, 
                "created_at" => $item->date,
                "updated_at" => "2023-10-06T06:08:34.000000Z",
                "created_by" => null,
                "updated_by" => null,
                "deleted_by" => null,
            ];
        });

        $formatted = [
            "id" => $akunData->id,
            "kode_jurnal" => $akunData->name,
            "perusahaan_id" => 1,
            "cabang_id" => 1,
            "total_debit" => $akunData->amount_total ,
            "total_kredit" => $akunData->amount_total,
            "deskripsi" =>  $akunData->ref,
            "posted_at" => $akunData->state == 'posted' ? $akunData->__last_update : null,
            "created_at" => "2023-10-06T06:08:34.000000Z",
            "updated_at" => "2023-10-06T06:36:51.000000Z",
            "created_by" => null,
            "updated_by" => null,
            "deleted_by" => null,
            "judul" => $akunData->ref,
            "tanggal_transaksi" => $akunData->date,
            "deleted_at" => null,
            "journal_akuns" => $journalData,

        ];



        return  collect($formatted);
    }


    public function delete(mixed $model): ?bool
    {
        
        $data = new OdooApiService();
        $response = $data->unlinkJournal((int)$model);
        // check response is array 
        if($response !== true){
            throw new BadRequestException($response['faultString']);
        }
        return $response;
    }
}
