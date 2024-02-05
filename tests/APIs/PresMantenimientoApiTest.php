<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\MTPresMantenimiento;

class PresMantenimientoApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_pres_mantenimiento()
    {
        $presMantenimiento = factory(MTPresMantenimiento::class)->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/pres_mantenimientos', $presMantenimiento
        );

        $this->assertApiResponse($presMantenimiento);
    }

    /**
     * @test
     */
    public function test_read_pres_mantenimiento()
    {
        $presMantenimiento = factory(MTPresMantenimiento::class)->create();

        $this->response = $this->json(
            'GET',
            '/api/pres_mantenimientos/'.$presMantenimiento->id
        );

        $this->assertApiResponse($presMantenimiento->toArray());
    }

    /**
     * @test
     */
    public function test_update_pres_mantenimiento()
    {
        $presMantenimiento = factory(MTPresMantenimiento::class)->create();
        $editedPresMantenimiento = factory(MTPresMantenimiento::class)->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/pres_mantenimientos/'.$presMantenimiento->id,
            $editedPresMantenimiento
        );

        $this->assertApiResponse($editedPresMantenimiento);
    }

    /**
     * @test
     */
    public function test_delete_pres_mantenimiento()
    {
        $presMantenimiento = factory(MTPresMantenimiento::class)->create();

        $this->response = $this->json(
            'DELETE',
             '/api/pres_mantenimientos/'.$presMantenimiento->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/pres_mantenimientos/'.$presMantenimiento->id
        );

        $this->response->assertStatus(404);
    }
}
