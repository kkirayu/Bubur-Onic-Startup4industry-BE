<?php

namespace Tests\Feature;

use App\Models\Cabang;
use App\Models\Perusahaan;
use App\Models\Role;
use App\Models\UserRoleCabang;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class CreatePerusahaanTest extends TestCase
{
    use WithFaker;

    public function setUp(): void
    {
        parent::setUp();
    }


    public function testCreatePerusahaanWithValidData(): void
    {
        // create payload from  CreatePeruysahaanRequest
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
        $this->actingAs($user);

        $response = $this->postJson('api/saas/perusahaan/register-perusahaan', $payload);

        $perusahaan = DB::table('perusahaans')->where('nama', $payload['nama'])->first();
        $cabang = DB::table('cabangs')->where('nama', $payload['cabang']['nama'])->first();
        $owner = DB::table('users')->where('name', $payload['owner']['nama'])->first();
        $this->assertNotNull($perusahaan);
        $this->assertNotNull($cabang);
        $this->assertNotNull($owner);

        $response->assertStatus(ResponseAlias::HTTP_CREATED)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'nama',
                    'alamat',
                    'domain_perusahaan',
                    'kode_perusahaan',
                    'status_perusahaan',
                    'created_at',
                    'updated_at',
                    'created_by',
                    'updated_by',
                ],
                'message',
                'status'
            ]);
    }

    public function testCreatePerusahaanWithExsistingPerusahaanName(): void
    {
        // create payload from  CreatePeruysahaanRequest
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
        $this->actingAs($user);

        $response = $this->postJson('api/saas/perusahaan/register-perusahaan', $payload);
        $response->assertStatus(201);

        $response = $this->postJson('api/saas/perusahaan/register-perusahaan', $payload);
        $response->assertStatus(ResponseAlias::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors([
                'nama',
            ]);

    }
    public function testValidateOwnerNotSend(): void
    {
        // create payload from  CreatePeruysahaanRequest
        $payload = [
            "nama" => $this->faker->name,
            "alamat" => "Jl. Test",
            "domain" => "test.com",
            "cabang" => [
                "nama" => $this->faker->name,
                "alamat" => "Jl. Cabang Test",
                "kode" => "CT"
            ]
        ];

        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);

        $response = $this->postJson('api/saas/perusahaan/register-perusahaan', $payload);

        $response->assertStatus(ResponseAlias::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors([
                'owner.nama',
                'owner.email',
                'owner.password',
            ]);

    }

    public function testGetCompanyDataDetail(): void
    {
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
        $this->actingAs($user);

        $response = $this->postJson('api/saas/perusahaan/register-perusahaan', $payload);

        $companyId = $response->json("data.id");

        \App\Models\User::factory()->create();

        $roleKeuangan = Role::where('name', 'STAFF_KEUANGAN_CABANG')->first();

        $user_role_cabang = new UserRoleCabang();
        $user_role_cabang->cabang_id = Cabang::where("perusahaan_id", "=", $companyId)->first()->id;
        $user_role_cabang->user_id = $user->id;
        $user_role_cabang->perusahaan_id = $companyId;
        $user_role_cabang->acl_roles_id = $roleKeuangan->id;
        $user_role_cabang->save();

        $response = $this->getJson('api/saas/perusahaan/' . $companyId);
        $response->assertStatus(ResponseAlias::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'nama',
                    'alamat',
                    'domain_perusahaan',
                    'kode_perusahaan',
                    'status_perusahaan',
                    'created_at',
                    'updated_at',
                    'created_by',
                    'updated_by',
                    'cabang' => [
                        '*' => [
                            'id',
                            'nama',
                            'alamat',
                            'kode_cabang',
                            'perusahaan_id',
                            'created_at',
                            'updated_at',
                            'created_by',
                            'updated_by',
                        ]
                    ],
                ],
                'status',
                'message'
            ]);
    }
}
