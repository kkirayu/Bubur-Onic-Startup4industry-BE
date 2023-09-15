<?php

namespace Tests\Feature;

use App\Models\Bpmn\PengajuanPerubahanJournalDanKas;
use App\Models\Cabang;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravolt\Camunda\Http\TaskClient;
use Laravolt\Crud\Tests\BaseLowcodeBpmnTest;
use Tests\TestCase;

class PengajuanPerubahanJournalDanKasTest extends TestCase
{  
    protected $processDefinitionKey = "PengajuanPerubahanJournalDanKas";

    protected $baseurl = "/api/pengajuan-perubahan-journal-dan-kas";
    use WithFaker;

    /**
     * A basic feature test example.
     */
    // Your code here

    public function createData($data)
    {
        $url = $this->baseurl;
        $response = $this
            ->post($url, $data);
        return $response->json("data.id");
    }


    public function submitUserTask($busniessKey, $id, $userTask, $data)
    {
        $url = $this->baseurl . '/' . $userTask . '/' . $busniessKey . '/task/' . $id . "/submit";
        $response = $this
            ->post($url, $data);

        return $response;

    }

    public function createProcessInstance($id, $variables)
    {
        $url = $this->baseurl . '/' . $id . "/start";
        $data = $variables;
//        dd($url);
        $response = $this
            ->post($url, $data);


        return $response->json("data.process_instance_id");

    }

    function instanceToFinish($journalOrKasData , $type_transaksi) : void {
        
        $this->artisan("db:seed");

        $payload = [
            "nama" => $this->faker->name,
            "alamat" => "Jl. Test",
            "domain" => "test.com",
            "cabang" => [
                "nama" => $this->faker->name,
                "alamat" => "Jl. Cabang Test",
                "kode" => "CT"
            ],
            "owner" => [
                "nama" => $this->faker->name,
                "email" => $this->faker->email,
                "password" => "password"
            ]
        ];


        $user = \App\Models\User::factory()->create();
        // $this->actingAs($user);
        
        

        $this->actingAs($user);

        $response = $this->postJson('api/saas/perusahaan/register-perusahaan', $payload);

        $companyId = $response->json("data.id");
        $cabang = Cabang::where("perusahaan_id", $companyId)->first();


        $request = [

            "perusahaan_id" => $companyId,
            "cabang_id" => $cabang->id,
            "jenis_aksi" => $type_transaksi, 
            "payload" => $journalOrKasData,
            "nama" =>"Tambah Kas "

        ];

        $id = PengajuanPerubahanJournalDanKas::create($request)->id;
        $prc = $this->createProcessInstance($id, []);

        $this->artisan("camunda:consume-external-task --workerId=worker1");
        $tasks = TaskClient::getByProcessInstanceId($prc);
        // dd($tasks);
        $taskid = $tasks[0]->id;
        $response = $this->submitUserTask($id, $taskid, "review-direksi", [
            'review_direksi' => 'TERIMA',
            'keterangan_konfirmasi' => 'ga oke direvisi dulu',
        ]);
        $this->artisan("camunda:consume-external-task --workerId=worker1");
        $this->artisan("camunda:consume-external-task --workerId=worker1");
        
        $tasks = TaskClient::getByProcessInstanceId($prc);
        $this->assertEmpty($tasks);
    }
    public function testBuatJurnalMustFinished()
    {

        $this->instanceToFinish(json_encode([
            "nama" => "Tambah Kas ",
        ]), "PEMBUATAN_JURNAL");
    }

    public function testEditJurnalMustFinished()
    {

        $this->instanceToFinish(json_encode([
            "nama" => "Tambah Kas ",
        ]), "PENGHAPUSAN_JURNAL");
    }

    public function testHapusJurnalMustFinished()
    {

        $this->instanceToFinish(json_encode([
            "nama" => "Tambah Kas ",
        ]), "PENGEDITAN_JURNAL");
    }

    public function testTambahKasJurnalMustFinished()
    {

        $this->instanceToFinish(json_encode([
            "nama" => "Tambah Kas ",
        ]), "PEMBUATAN_KAS");
    }

    public function testHapusKasMustFinished()
    {

        $this->instanceToFinish(json_encode([
            "nama" => "Tambah Kas ",
        ]), "PERUBAHAN_KAS");
    }


    public function testRevisiJournalFirst()
    {
        $this->artisan("db:seed");

        $payload = [
            "nama" => $this->faker->name,
            "alamat" => "Jl. Test",
            "domain" => "test.com",
            "cabang" => [
                "nama" => $this->faker->name,
                "alamat" => "Jl. Cabang Test",
                "kode" => "CT"
            ],
            "owner" => [
                "nama" => $this->faker->name,
                "email" => $this->faker->email,
                "password" => "password"
            ]
        ];


        $user = \App\Models\User::factory()->create();
        // $this->actingAs($user);
        
        

        $this->actingAs($user);

        $response = $this->postJson('api/saas/perusahaan/register-perusahaan', $payload);

        $companyId = $response->json("data.id");
        $cabang = Cabang::where("perusahaan_id", $companyId)->first();


        $request = [

            "perusahaan_id" => $companyId,
            "cabang_id" => $cabang->id,
            "jenis_aksi" => "PEMBUATAN_JURNAL", 
            "payload" => json_encode([
                "nama" => "Tambah Kas ",
            ]),
            "nama" =>"Tambah Kas "

        ];

        $id = PengajuanPerubahanJournalDanKas::create($request)->id;
        $prc = $this->createProcessInstance($id, []);

        $this->artisan("camunda:consume-external-task --workerId=worker1");
        $tasks = TaskClient::getByProcessInstanceId($prc);
        // dd($tasks);
        $taskid = $tasks[0]->id;
        $response = $this->submitUserTask($id, $taskid, "review-direksi", [
            'review_direksi' => 'REVISI',
            'keterangan_konfirmasi' => 'ga oke direvisi dulu',
        ]);

        $tasks = TaskClient::getByProcessInstanceId($prc);
        $taskid = $tasks[0]->id;
        $response = $this->submitUserTask($id, $taskid, "revisi-pengajuan", []);

        $this->artisan("camunda:consume-external-task --workerId=worker1");
        $tasks = TaskClient::getByProcessInstanceId($prc);
        $taskid = $tasks[0]->id;
        $response = $this->submitUserTask($id, $taskid, "review-direksi", [
            'review_direksi' => 'TERIMA',
            'keterangan_konfirmasi' => 'ga oke direvisi dulu',
        ]);
        $this->artisan("camunda:consume-external-task --workerId=worker1");
        $this->artisan("camunda:consume-external-task --workerId=worker1");

        
        $tasks = TaskClient::getByProcessInstanceId($prc);
        $this->assertEmpty($tasks);

        // $tasks = TaskClient::getByProcessInstanceId($prc);
        // $taskid = $tasks[0]->id;
        // $response = $this->submitUserTask($id, $taskid, "cuti-revisi-cuti", [
        //     'keterangan_revisi' => 'ga oke direvisi dulu',

        // ]);
        // $tasks = TaskClient::getByProcessInstanceId($prc);
        // $taskid = $tasks[0]->id;
        // $response = $this->submitUserTask($id, $taskid, "cuti-konfirmasi-cuti", [
        //     'decision' => 'terima',
        //     'keterangan_konfirmasi' => 'sudah oke lanjut aja',

        // ]);

    }

}
