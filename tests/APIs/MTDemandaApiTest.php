<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\MTDemanda;

class MTDemandaApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_m_t_demanda()
    {
        $mTDemanda = factory(MTDemanda::class)->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/m_t_demandas', $mTDemanda
        );

        $this->assertApiResponse($mTDemanda);
    }

    /**
     * @test
     */
    public function test_read_m_t_demanda()
    {
        $mTDemanda = factory(MTDemanda::class)->create();

        $this->response = $this->json(
            'GET',
            '/api/m_t_demandas/'.$mTDemanda->id
        );

        $this->assertApiResponse($mTDemanda->toArray());
    }

    /**
     * @test
     */
    public function test_update_m_t_demanda()
    {
        $mTDemanda = factory(MTDemanda::class)->create();
        $editedMTDemanda = factory(MTDemanda::class)->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/m_t_demandas/'.$mTDemanda->id,
            $editedMTDemanda
        );

        $this->assertApiResponse($editedMTDemanda);
    }

    /**
     * @test
     */
    public function test_delete_m_t_demanda()
    {
        $mTDemanda = factory(MTDemanda::class)->create();

        $this->response = $this->json(
            'DELETE',
             '/api/m_t_demandas/'.$mTDemanda->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/m_t_demandas/'.$mTDemanda->id
        );

        $this->response->assertStatus(404);
    }
}
