<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RouteTest extends TestCase
{

    public function test_unloged_dashboard()
    {
        $response = $this->get('/dashboard');

        $response->assertStatus(302);
    }


    public function test_unloged_account()
    {
        $response = $this->get('/account');

        $response->assertStatus(302);
    }

    public function test_unloged_patients()
    {
        $response = $this->get('/patients/a/b');

        $response->assertStatus(302);
    }

    public function test_unloged_blackcodes()
    {
        $response = $this->get('/blackcodes/a/b');

        $response->assertStatus(302);
    }

    public function test_unloged_factures()
    {
        $response = $this->get('/factures');

        $response->assertStatus(302);
    }

    public function test_unloged_logistique()
    {
        $response = $this->get('/logistique/a');

        $response->assertStatus(302);
    }

    public function test_unloged_personnel()
    {
        $response = $this->get('/personnel');

        $response->assertStatus(302);
    }

    public function test_unloged_sams()
    {
        $response = $this->get('/SAMS/a/b');

        $response->assertStatus(302);
    }

    public function test_unloged_lscofd()
    {
        $response = $this->get('/LSCoFD/a/b');

        $response->assertStatus(302);
    }

    public function test_unloged_logout()
    {
        $response = $this->get('/logout');

        $response->assertStatus(302);
    }

    public function test_unloged_informations()
    {
        $response = $this->get('/informations/a');

        $response->assertStatus(302);
    }



}
