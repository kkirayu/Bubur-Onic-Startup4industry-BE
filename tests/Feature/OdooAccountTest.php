<?php

namespace Tests\Feature;

use App\Services\Odoo\OdooAccountService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OdooAccountTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function testOdooAccountList(): void
    {
        $odoo =  new OdooAccountService();
        $data = $odoo->getAkunList();
        dump($data);

        $this->assertIsArray($data);
    }
    public function testOdooCreateAccount(): void
    {
        $odoo =  new OdooAccountService();
        $data = $odoo->getTagsList([["name", "=", "used"]]);

        dump($data);

        $this->assertIsArray($data);
    }
}
