<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\MTMes;

class MTMesApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_m_t_mes()
    {
        $mTMes = factory(MTMes::class)->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/m_t_mes', $mTMes
        );

        $this->assertApiResponse($mTMes);
    }

    /**
     * @test
     */
    public function test_read_m_t_mes()
    {
        $mTMes = factory(MTMes::class)->create();

        $this->response = $this->json(
            'GET',
            '/api/m_t_mes/'.$mTMes->id
        );

        $this->assertApiResponse($mTMes->toArray());
    }

    /**
     * @test
     */
    public function test_update_m_t_mes()
    {
        $mTMes = factory(MTMes::class)->create();
        $editedMTMes = factory(MTMes::class)->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/m_t_mes/'.$mTMes->id,
            $editedMTMes
        );

        $this->assertApiResponse($editedMTMes);
    }

    /**
     * @test
     */
    public function test_delete_m_t_mes()
    {
        $mTMes = factory(MTMes::class)->create();

        $this->response = $this->json(
            'DELETE',
             '/api/m_t_mes/'.$mTMes->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/m_t_mes/'.$mTMes->id
        );

        $this->response->assertStatus(404);
    }
}
