<?php

namespace App\Services\BonusKaryawan;

use App\Models\BonusKaryawan;
use Illuminate\Http\Request;
use Laravolt\Crud\CrudService;

class BonusKaryawanService extends CrudService
{
    public function createBonus(array $data)
    {
        $data['status'] = 'BARU';
        $bonus = BonusKaryawan::create($data);

        return $bonus;
    }
}

