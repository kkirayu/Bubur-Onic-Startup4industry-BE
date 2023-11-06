<?php


namespace App\Services\Odoo;

use App\Services\Odoo\Const\AccountType;
use Illuminate\Support\Facades\Cache;
use Ripcord\Ripcord;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class OdooApiService
{


  // Access ke odoo 
  protected   $url =  "";
  protected $db = "";
  protected $username = "";
  protected $password =  "";

  protected  $uid = 2;

  function __construct()
  {

    $this->url =  config("services.odoo.url");
    $this->db = config("services.odoo.database");
    $this->username = config("services.odoo.user");
    $this->password =  config("services.odoo.password");
  }
  function createRpcModel()
  {

    // require_once('ripcord.php');
    $common = Ripcord::client("$this->url/xmlrpc/2/common");
    $common->version();
    $this->uid = Cache::rememberForever("odoo_uid", function () use ($common) {
      return $common->authenticate($this->db, $this->username, $this->password, array());
    });
    $models = Ripcord::client("$this->url/xmlrpc/2/object");

    return  $models;
  }

  public function executeKw($model,  $function,  $payload,  $kwarg)

  {
    $responseType = request()->res_type;


    $models =  $this->createRpcModel();

    $data = $models->execute_kw(
      $this->db,
      $this->uid,
      $this->password,
      $model,
      $function,
      $payload,
      $kwarg
    );

    if (is_array($data) &&  array_key_exists("faultCode", $data)) {
      throw new BadRequestException($data["faultString"]);
    }

    if ($responseType == "STATEMENT") {
      return collect($data);
    } else if ($responseType == "RECORD") {
      return collect($data[0]);
    } else if ($responseType == "RAWLIST") {
      return collect($data);
    } else if ($responseType == "PAGINATEDLIST") {

      $dataSize = 100;

      if (array_key_exists("length", $data)) {
        $dataSize = $data['length'];
      }
      if (array_key_exists("records", $data)) {
        $data = collect($data['records']);
      } else {

        $data = collect($data);
      }

      $page = request()->page ?: 1;
      $limit = request()->limit ?: 10;
      $offset = ($page - 1) * $limit;
      return $data->paginate($limit,  $dataSize, $page);
    }

    return collect($data);
  }

  function createJournal($payload,)
  {


    $models =  $this->createRpcModel();

    $kwarg = [];    // $payload =  json_decode($payload);
    $data = $models->execute_kw(
      $this->db,
      $this->uid,
      $this->password,
      'account.move',
      'create',
      $payload,
      $kwarg
    );
    return $data;
  }

  public function getBukuBesarReport($start, $end,  $company_id,  $group,  $groupBy = "account_id")
  {


    $models =  $this->createRpcModel();
    $kwarg = [
      "domain" => [
        "&",
        "&",
        [
          "display_type",
          "not in",
          [
            "line_section",
            "line_note"
          ]
        ],
        [
          "parent_state",
          "!=",
          "cancel"
        ],
        "&",
        [
          "parent_state",
          "=",
          "posted"
        ],
        [
          "account_id",
          "ilike",
          $group
        ],
      ],
      "fields" => [
        "analytic_precision",
        "move_id",
        "date",
        "company_id",
        "journal_id",
        "move_name",
        "account_id",
        "partner_id",
        "ref",
        "product_id",
        "name",
        "tax_ids",
        "amount_currency",
        "currency_id",
        "debit",
        "credit",
        "tax_tag_ids",
        "discount_date",
        "discount_amount_currency",
        "tax_line_id",
        "date_maturity",
        "balance",
        "matching_number",
        "amount_residual",
        "amount_residual_currency",
        "analytic_distribution",
        "move_type",
        "parent_state",
        "account_type",
        "statement_line_id",
        "company_currency_id",
        "is_same_currency",
        "is_account_reconcile",
        "sequence"
      ],
    ];



    $payload = [];
    $data = $models->execute_kw(
      $this->db,
      $this->uid,
      $this->password,
      'account.move.line',
      'web_search_read',
      $payload,
      $kwarg
    );
    return $data;
  }


  public function getJournalItemWithIds($ids)
  {


    $models =  $this->createRpcModel();

    $kwarg  = [];


    $payload = [$ids];
    $data = $models->execute_kw(
      $this->db,
      $this->uid,
      $this->password,
      'account.move.line',
      'read',
      $payload,
      $kwarg
    );

    $journalData = collect($data)->map(function ($item,  $key) {
      return  $this->mapResponseDetailData($item);
    });

    return $journalData;
  }



  public function postJournal($ids)
  {


    $models =  $this->createRpcModel();

    $kwarg  = [];


    $payload = [$ids];
    $data = $models->execute_kw(
      $this->db,
      $this->uid,
      $this->password,
      'account.move',
      'action_post',
      $payload,
      $kwarg
    );
    return $data;
  }


  public function unlinkJournal($ids)
  {


    $models =  $this->createRpcModel();

    $kwarg  = [];


    $payload = [$ids];
    $data = $models->execute_kw(
      $this->db,
      $this->uid,
      $this->password,
      'account.move',
      'unlink',
      $payload,
      $kwarg
    );
    return $data;
  }

  public function unPostJournal($ids)
  {


    $models =  $this->createRpcModel();

    $kwarg  = [];


    $payload = [$ids];
    $data = $models->execute_kw(
      $this->db,
      $this->uid,
      $this->password,
      'account.move',
      'button_draft',
      $payload,
      $kwarg
    );
    return $data;
  }

  public function getBukuBesarWithRootKey($start, $end,  $company_id,  $group,  $root_keys)
  {

    $domain = [
      "&",
      ["display_type", "not in", ["line_section", "line_note"]],
      ["parent_state", "=", "posted"],
      ["date", "<=", $end],
      ["account_id.internal_group", "in", $group],
      ["account_root_id", "in",   $root_keys]
    ];

    if ($start != null) {
      $domain[] = ["date", ">=", $start];
    }

    $models =  $this->createRpcModel();
    $kwarg = [
      "orderby" => "",
      "lazy" => true,
      "expand" => null,
      "expand_orderby" => null,
      "expand_limit" => null,
      "offset" => 0,
      "limit" => 1000,
      "groupby" => ["account_id"],
      "domain" => $domain,
      "fields" => [
        "analytic_precision",
        "move_id",
        "date",
        "company_id",
        "journal_id",
        "move_name",
        "account_id",
        "partner_id",
        "ref",
        "product_id",
        "account_root_id",
        "name",
        "tax_ids",
        "amount_currency",
        "currency_id",
        "debit",
        "credit",
        "tax_tag_ids",
        "discount_date",
        "discount_amount_currency",
        "tax_line_id",
        "date_maturity",
        "balance",
        "matching_number",
        "amount_residual",
        "amount_residual_currency",
        "analytic_distribution",
        "move_type",
        "parent_state",
        "account_type",
        "statement_line_id",
        "company_currency_id",
        "is_same_currency",
        "is_account_reconcile",
        "sequence",
      ],
    ];



    $payload = [];
    $data = $models->execute_kw(
      $this->db,
      $this->uid,
      $this->password,
      'account.move.line',
      'web_read_group',
      $payload,
      $kwarg
    );
    return $data;
  }

    public function getBukuBesarAkun($start, $end,  $company_id,  $coa)
    {
  
      $domain = [
        "&",
        ["display_type", "not in", ["line_section", "line_note"]],
        ["parent_state", "=", "posted"],
        ["date", "<=", $end],
        ["account_id", "=", $coa],
      ];
  
      if ($start != null) {
        $domain[] = ["date", ">=", $start];
      }
  
      $models =  $this->createRpcModel();
      $kwarg = [
        "orderby" => "",
        "lazy" => true,
        "expand" => null,
        "expand_orderby" => null,
        "expand_limit" => null,
        "offset" => 0,
        "limit" => 1000,
        "groupby" => ["account_id"],
        "domain" => $domain,
        "fields" => [
          "analytic_precision",
          "move_id",
          "date",
          "company_id",
          "journal_id",
          "move_name",
          "account_id",
          "partner_id",
          "ref",
          "product_id",
          "account_root_id",
          "name",
          "tax_ids",
          "amount_currency",
          "currency_id",
          "debit",
          "credit",
          "tax_tag_ids",
          "discount_date",
          "discount_amount_currency",
          "tax_line_id",
          "date_maturity",
          "balance",
          "matching_number",
          "amount_residual",
          "amount_residual_currency",
          "analytic_distribution",
          "move_type",
          "parent_state",
          "account_type",
          "statement_line_id",
          "company_currency_id",
          "is_same_currency",
          "is_account_reconcile",
          "sequence",
        ],
      ];
  
  
  
      $payload = [];
      $data = $models->execute_kw(
        $this->db,
        $this->uid,
        $this->password,
        'account.move.line',
        'web_read_group',
        $payload,
        $kwarg
      );
      return $data;
  }


  public function getBukuBesarDetail($akun_id, $company_id,  $start,  $end)
  {


    $models =  $this->createRpcModel();

    $kwarg  = [
      "limit" => 80,
      "offset" => 0,
      "order" => "",
      "count_limit" => 81,
      "domain" => [
        "&",
        ["account_id", "=", $akun_id],
        ["company_id", "=", $company_id],
        ["display_type", "not in", ["line_section", "line_note"]],
        ["parent_state", "=", "posted"], ["date", ">=", $start],  ["date", "<=", $end]
      ],
      "fields" => [
        "analytic_precision",
        "move_id",
        "date",
        "company_id",
        "journal_id",
        "move_name",
        "account_id",
        "partner_id",
        "ref",
        "product_id",
        "name",
        "tax_ids",
        "amount_currency",
        "currency_id",
        "debit",
        "credit",
        "tax_tag_ids",
        "discount_date",
        "discount_amount_currency",
        "tax_line_id",
        "date_maturity",
        "balance",
        "matching_number",
        "amount_residual",
        "amount_residual_currency",
        "analytic_distribution",
        "move_type",
        "parent_state",
        "account_type",
        "statement_line_id",
        "company_currency_id",
        "is_same_currency",
        "is_account_reconcile",
        "sequence",
      ],
    ];



    $payload = [];
    $data = $models->execute_kw(
      $this->db,
      $this->uid,
      $this->password,
      'account.move.line',
      'web_search_read',
      $payload,
      $kwarg
    );
    return $data;
  }

  function  getTagsList()
  {
    $model = $this->createRpcModel();
    $data = $model->execute_kw($this->db, $this->uid, $this->password, 'account.account.tag', 'search_read',  [], [

      "domain" => [],
    ]);

    return  $data;
  }
  function getJournalList($domain = [],  $withJournalDetail =  false)
  {

    $search = request()->search;
    if ($search) {

      $domain[] = "|";
      $domain[] = "|";
      $domain[] = ["name", "ilike", $search];
      $domain[] = ["ref", "ilike", $search];
      $domain[] = ["partner_id", "ilike", $search];
    }
    $model = $this->createRpcModel();
    $data = $model->execute_kw($this->db, $this->uid, $this->password, 'account.move', 'web_search_read',  [], [

      "domain" => $domain,
    ]);


    //   $akunDataRaw = $data->getJournalList();


    $akunData =  collect($data['records']);
    $invoice_line_ids = (array_merge(...$akunData->pluck("invoice_line_ids")->toArray()));

    $journalData = [];
    if ($withJournalDetail) {
      $journalData = $this->getJournalItemWithIds($invoice_line_ids);

      $journalData = collect($journalData);
    }

    $data = $akunData->map(function ($item,  $key) use ($journalData) {
      return  $this->mapResponseData($item, collect($journalData));
    });

    return  $data;
  }
  function getJournalDetail($id)
  {
    $model = $this->createRpcModel();
    $data = $model->execute_kw($this->db, $this->uid, $this->password, 'account.move', 'read',  [[(int) $id]], []);



    $akunData = (object) $data[0];

    $journalData = $this->getJournalItemWithIds($akunData->invoice_line_ids);

    $data = $this->mapResponseData($akunData, collect($journalData));

    return  $data;
  }

  function getJournalDetails($id)
  {
    $model = $this->createRpcModel();
    $data = $model->execute_kw($this->db, $this->uid, $this->password, 'account.move', 'read',  $id, []);



    $akunData = (object) $data;

    $journalData = $this->getJournalItemWithIds($akunData->invoice_line_ids);

    $data = $this->mapResponseData($akunData, collect($journalData));

    return  $data;
  }

  function mapResponseData($item,  $journalData)
  {

    $data = (object) $item;

    $refData =  explode(";", $data->ref);
    $formatted = [
      "id" => $data->id,
      "kode_jurnal" => $data->name,
      "perusahaan_id" => 1,
      "cabang_id" => 1,
      "total_debit" => $data->amount_total,
      "total_kredit" => $data->amount_total,
      "deskripsi" =>  sizeOf($refData) >  1 ?  $refData[1] : "",
      "posted_at" => $data->state == 'posted' ? $data->__last_update : null,
      "created_at" => $data->date,
      "updated_at" => "2023-10-06T06:36:51.000000Z",
      "created_by" => null,
      "updated_by" => null,
      "deleted_by" => null,
      "judul" =>   sizeOf($refData) >  0 ?  $refData[0] : "",
      "tanggal_transaksi" => $data->date,
      "deleted_at" => null,
      "journal_akuns" => $journalData->whereIn("id", $data->invoice_line_ids)->toArray(),
    ];
    return $formatted;
  }

  function mapResponseDetailData($item)
  {
    $item = (object) $item;
    return [
      "id" => $item->id,
      "perusahaan_id" => 1,
      "cabang_id" => 1,
      "akun" => $item->account_id[0],
      "akun_label" => $item->account_id[1],
      "posisi_akun" => $item->credit > 0 ? "CREDIT" : "DEBIT",
      "deskripsi" => "asdf",
      "jumlah" => $item->credit ? $item->balance * -1 : $item->balance,
      "created_at" => $item->date,
      "updated_at" => "2023-10-06T06:08:34.000000Z",
      "created_by" => null,
      "updated_by" => null,
      "deleted_by" => null,
    ];
  }
}
