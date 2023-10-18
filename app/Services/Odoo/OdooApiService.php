<?php


namespace App\Services\Odoo;

use App\Services\Odoo\Const\AccountType;
use Ripcord\Ripcord;

class OdooApiService
{


  // Access ke odoo 
  protected   $url = "https://odoo-16.merapi.javan.id";

  protected $db = "odoo_16";
  protected $username = "admin@merapi.javan.id";
  protected $password = "a1def3761a961aaa03b2da0c9160b8c3b5803603";

  protected  $uid = 2;
  function createRpcModel()
  {

    // require_once('ripcord.php');
    $common = Ripcord::client("$this->url/xmlrpc/2/common");
    $common->version();
    // $uid = $common->authenticate($db, $username, $password, array());

    $models = Ripcord::client("$this->url/xmlrpc/2/object");

    return  $models;
  }

  function createJournal($date,  $ref, $journal_item = [])
  {

    if ($date == null || $ref == null) {
      return "Data tidak boleh kosong";
    }

    $models =  $this->createRpcModel();

    $kwarg = json_decode('{
      "context": {
        "lang": "en_US",
        "tz": "Asia/Jakarta",
        "uid": 2,
        "allowed_company_ids": [
          1
        ],
        "default_move_type": "entry",
        "search_default_posted": 1,
        "view_no_maturity": true
      }
  }');

    $payload =  json_decode('[
    {
      "date": "' . $date . '",
      "auto_post": "no",
      "auto_post_until": false,
      "company_id": 1,
      "journal_id": 3,
      "show_name_warning": false,
      "posted_before": false,
      "payment_state": "not_paid",
      "currency_id": 12,
      "statement_line_id": false,
      "payment_id": false,
      "tax_cash_basis_created_move_ids": [],
      "name": "/",
      "partner_id": false,
      "l10n_id_kode_transaksi": false,
      "l10n_id_replace_invoice_id": false,
      "quick_edit_total_amount": 0,
      "ref": "' . $ref . '",
      "invoice_vendor_bill_id": false,
      "invoice_date": false,
      "payment_reference": false,
      "partner_bank_id": false,
      "invoice_date_due": "2023-10-15",
      "invoice_payment_term_id": false,
      "invoice_line_ids": [],
      "narration": false,
      "line_ids": [
        [
          0,
          "virtual_3",
          {
            "analytic_precision": 2,
            "asset_category_id": false,
            "account_id": 3,
            "partner_id": false,
            "name": false,
            "analytic_distribution": false,
            "date_maturity": false,
            "amount_currency": 100000,
            "currency_id": 12,
            "tax_ids": [
              [
                6,
                false,
                []
              ]
            ],
            "debit": 100000,
            "credit": 0,
            "balance": 100000,
            "discount_date": false,
            "discount_amount_currency": 0,
            "tax_tag_ids": [
              [
                6,
                false,
                []
              ]
            ],
            "display_type": "product",
            "sequence": 100
          }
        ],
        [
          0,
          "virtual_4",
          {
            "analytic_precision": 2,
            "asset_category_id": false,
            "account_id": 6,
            "partner_id": false,
            "name": false,
            "analytic_distribution": false,
            "date_maturity": false,
            "amount_currency": -100000,
            "currency_id": 12,
            "tax_ids": [
              [
                6,
                false,
                []
              ]
            ],
            "debit": 0,
            "credit": 100000,
            "balance": -100000,
            "discount_date": false,
            "discount_amount_currency": 0,
            "tax_tag_ids": [
              [
                6,
                false,
                []
              ]
            ],
            "display_type": "product",
            "sequence": 100
          }
        ]
      ],
      "user_id": ' . $this->uid . ',
      "invoice_user_id": ' . $this->uid . ',
      "team_id": 1,
      "invoice_origin": false,
      "qr_code_method": false,
      "invoice_incoterm_id": false,
      "fiscal_position_id": false,
      "invoice_source_email": false,
      "to_check": false,
      "l10n_id_tax_number": false,
      "campaign_id": false,
      "medium_id": false,
      "source_id": false,
      "edi_document_ids": []
    }]');
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

  public function getBukuBesarReport($start, $end,  $company_id,  $group ,  $groupBy = "account_id")
  {


    $models =  $this->createRpcModel();
    $kwarg = [
      "orderby" => "",
      "lazy" => true,
      "expand" => null,
      "expand_orderby" => null,
      "expand_limit" => null,
      "offset" => 0,
      "limit" => 1000,
      "context" => [
        "lang" => "id_ID",
        "tz" => "Asia/Jakarta",
        "uid" => $this->uid,
        "allowed_company_ids" => [$company_id],
        "params" => [
          "action" => 275,
          "model" => "account.move.line",
          "view_type" => "list",
          "cids" => 1,
          "menu_id" => 115,
        ],
        "journal_type" => "general",
      ],
      "groupby" => [$groupBy],
      "domain" => [
        "&",
        ["display_type", "not in", ["line_section", "line_note"]],
        ["parent_state", "=", "posted"],
        ["date", ">=", $start],
        ["date", "<=", $end],
        ["account_id.internal_group", "in", $group]
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


  public function getJournalItemByGroupNames($moveNames, $company_id,  $start,  $end)
  {


    $models =  $this->createRpcModel();

    $kwarg  = [
      "limit" => 1000,
      "offset" => 0,
      "order" => "",
      "context" => [
        "lang" => "id_ID",
        "tz" => "Asia/Jakarta",
        "uid" => 2,
        "allowed_company_ids" => [1],
        "bin_size" => true,
        "params" => [
          "action" => 275,
          "model" => "account.move.line",
          "view_type" => "list",
          "cids" => 1,
          "menu_id" => 115,
        ],
        "journal_type" => "general",
      ],
      "count_limit" => 81,
      "domain" => [
        "&",
        ["move_name", "in", $moveNames],
        ["company_id", "=", $company_id],
        ["display_type", "not in", ["line_section", "line_note"]],
        ["parent_state", "=", "posted"],
        ["date", ">=", $start],
        ["date", "<=", $end],
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
    ];;


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

public function getBukuBesarWithRootKey($start, $end,  $company_id,  $group ,  $root_keys)
{


  $models =  $this->createRpcModel();
  $kwarg = [
    "orderby" => "",
    "lazy" => true,
    "expand" => null,
    "expand_orderby" => null,
    "expand_limit" => null,
    "offset" => 0,
    "limit" => 1000,
    "context" => [
      "lang" => "id_ID",
      "tz" => "Asia/Jakarta",
      "uid" => $this->uid,
      "allowed_company_ids" => [$company_id],
      "params" => [
        "action" => 275,
        "model" => "account.move.line",
        "view_type" => "list",
        "cids" => 1,
        "menu_id" => 115,
      ],
      "journal_type" => "general",
    ],
    "groupby" => ["account_id"],
    "domain" => [
      "&",
      ["display_type", "not in", ["line_section", "line_note"]],
      ["parent_state", "=", "posted"],
      ["date", ">=", $start],
      ["date", "<=", $end],
      ["account_id.internal_group", "in", $group],
      ["account_root_id" , "in",   $root_keys]
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
      "context" => [
        "lang" => "id_ID",
        "tz" => "Asia/Jakarta",
        "uid" => $this->uid,
        "allowed_company_ids" => [$company_id],
        "bin_size" => true,
        "default_account_id" => 2,
        "params" => [
          "action" => 275,
          "model" => "account.move.line",
          "view_type" => "list",
          "cids" => 1,
          "menu_id" => 115,
        ],
        "journal_type" => "general",
      ],
      "count_limit" => 81,
      "domain" => [
        "&",
        ["account_id", "=", $akun_id],
        ["company_id", "=", $company_id],
        ["display_type", "not in", ["line_section", "line_note"]],
        ["parent_state", "=", "posted"],
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


  function getAkun()
  {

    $models =  $this->createRpcModel();

    $kwarg  = [
      "category_domain" => [],
      "context" => [
        "lang" => "id_ID",
        "tz" => "Asia/Jakarta",
        "uid" => 2,
        "allowed_company_ids" => [
          1
        ],
        "params" => [
          "action" => 240,
          "model" => "account.account",
          "view_type" => "list",
          "cids" => 1,
          "menu_id" => 115
        ]
      ],

      "enable_counters" => false,
      "expand" => false,
      "filter_domain" => [],
      "hierarchize" => true,
      "limit" => 0,
      "fields" => [
        "name"
      ],
      "search_domain" => [
        [
          "deprecated",
          "=",
          false
        ],

      ]
    ];



    $payload = [
        "root_id"
      
    ];
    $data = $models->execute_kw(
      $this->db,
      $this->uid,
      $this->password,
      'account.account',
      'search_panel_select_range',
      $payload,
      $kwarg
    );
    return $data;
  }
}
