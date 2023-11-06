<?php


namespace App\Services\Odoo;

use App\Services\Odoo\Const\AccountType;
use Illuminate\Support\Facades\Cache;
use Ripcord\Ripcord;

class OdooJournalService extends OdooApiService
{



    function getJournalWithMoveName($moveName)
    {

        $models =  $this->createRpcModel();

        $kwarg  = [
            "domain" => [
                "&",
                ["state", "=", "posted"],
                ["name", "in", $moveName],
            ]
        ];



        $payload = [
        ];
        $data = $models->execute_kw(
            $this->db,
            $this->uid,
            $this->password,
            'account.move',
            'web_search_read',
            $payload,
            $kwarg
        );
        return $data;
    }
}
