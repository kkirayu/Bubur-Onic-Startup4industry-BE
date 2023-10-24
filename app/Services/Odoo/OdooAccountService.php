<?php


namespace App\Services\Odoo;

use App\Services\Odoo\Const\AccountType;
use Illuminate\Support\Facades\Cache;
use Ripcord\Ripcord;

class OdooAccountService extends OdooApiService
{



    function getAkun()
    {

        $models =  $this->createRpcModel();

        $kwarg  = [
            "category_domain" => [],
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
                ["tag_ids", "ilike", "used"]


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



    function  getAkunType()
    {
        $model = $this->createRpcModel();
        $data = $model->execute_kw($this->db, $this->uid, $this->password, 'account.account.type', 'search_read',  [], [

            "domain" => [],
        ]);
        return $data;
    }
    function  getAkunList($filterDomain = [])
    {

        $domain = [];
        if (request()->has("filter")) {

            if (request()->filter['is_kas'] == "1") {
                $domain[] = ["tag_ids", "ilike", "bank"];
            }
        }
        $domain[] = ["tag_ids", "ilike", "used"];
        $domain = array_merge($domain,  $filterDomain);
        $model = $this->createRpcModel();
        $data = $model->execute_kw($this->db, $this->uid, $this->password, 'account.account', 'web_search_read',  [], [

            "domain" => $domain,
        ]);

        return  $data;
    }


    function  getTagsList($domain = [])
    {


        $model = $this->createRpcModel();
        $data = $model->execute_kw($this->db, $this->uid, $this->password, 'account.account.tag', 'search_read',  [], [

            "domain" => $domain,
        ]);

        return  $data;
    }

    function  getRequredTagsList()
    {
        // return  Cache::rememberForever("odoo_account_tags", function () {
            $domain = [
                "|",
                ["name", "ilike", "used"],
                ["name", "ilike", "bank"],
            ];
            return $this->getTagsList($domain);
        // });
    }
    function  createAkun($name,  $code, $account_type, $isKas = false)
    {
        $requiredTaskList = collect($this->getRequredTagsList());
        $usedTag = $requiredTaskList->where("name", "used")->first()['id'];


        $usedTag = [$usedTag];
        if ($isKas) {
            $bankTag = $requiredTaskList->where("name", "bank")->first()['id'];
            $usedTag[] = $bankTag;
        }
        $payload =
            [
                [
                    "name" => $name,
                    "currency_id" => false,
                    "code" => $code,
                    "account_type" => $account_type,
                    "reconcile" => false,
                    "tax_ids" => [],
                    "tag_ids" => [
                        [
                            6,
                            false,
                            $usedTag
                        ]
                    ],
                    "allowed_journal_ids" => [],
                    "non_trade" => false
                ]
            ];

        $kwarg = [];

        $data = $this->createRpcModel()
            ->execute_kw($this->db, $this->uid, $this->password, 'account.account', 'create', $payload, $kwarg);
        return $data;
    }
}
