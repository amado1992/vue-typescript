<?php namespace Tests\Repositories;

use App\Models\MTDemanda;
use App\Repositories\MTDemandaRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class MTDemandaRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var MTDemandaRepository
     */
    protected $mTDemandaRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->mTDemandaRepo = \App::make(MTDemandaRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_m_t_demanda()
    {
        $mTDemanda = factory(MTDemanda::class)->make()->toArray();

        $createdMTDemanda = $this->mTDemandaRepo->create($mTDemanda);

        $createdMTDemanda = $createdMTDemanda->toArray();
        $this->assertArrayHasKey('id', $createdMTDemanda);
        $this->assertNotNull($createdMTDemanda['id'], 'Created MTDemanda must have id specified');
        $this->assertNotNull(MTDemanda::find($createdMTDemanda['id']), 'MTDemanda with given id must be in DB');
        $this->assertModelData($mTDemanda, $createdMTDemanda);
    }

    /**
     * @test read
     */
    public function test_read_m_t_demanda()
    {
        $mTDemanda = factory(MTDemanda::class)->create();

        $dbMTDemanda = $this->mTDemandaRepo->find($mTDemanda->id);

        $dbMTDemanda = $dbMTDemanda->toArray();
        $this->assertModelData($mTDemanda->toArray(), $dbMTDemanda);
    }

    /**
     * @test update
     */
    public function test_update_m_t_demanda()
    {
        $mTDemanda = factory(MTDemanda::class)->create();
        $fakeMTDemanda = factory(MTDemanda::class)->make()->toArray();

        $updatedMTDemanda = $this->mTDemandaRepo->update($fakeMTDemanda, $mTDemanda->id);

        $this->assertModelData($fakeMTDemanda, $updatedMTDemanda->toArray());
        $dbMTDemanda = $this->mTDemandaRepo->find($mTDemanda->id);
        $this->assertModelData($fakeMTDemanda, $dbMTDemanda->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_m_t_demanda()
    {
        $mTDemanda = factory(MTDemanda::class)->create();

        $resp = $this->mTDemandaRepo->delete($mTDemanda->id);

        $this->assertTrue($resp);
        $this->assertNull(MTDemanda::find($mTDemanda->id), 'MTDemanda should not exist in DB');
    }
}
