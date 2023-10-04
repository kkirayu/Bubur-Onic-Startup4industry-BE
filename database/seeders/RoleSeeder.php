<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $roles = [
            "OWNER_CABANG" => "Owner Cabang",
            "STAFF_KEUANGAN_CABANG" => "Staff Keuangan Cabang",
            "STAFF_HRD_CABANG" => "Saff HRD Cabang",
            "ADMIN_CABANG" => "Admin Cabang",
            "MANAGEMENT_CABANG" => "Managementm Cabang",
        ];

        foreach ($roles as $key => $value) {
            if(Role::where('name', $key)->count() == 0){
                Role::insert([
                    'id' => Uuid::uuid4(),
                    'name' => $key
                ]);
            }
        }
    }
}
