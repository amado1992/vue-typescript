<?php namespace Tests\Repositories;

use App\Models\MTMes;
use App\Repositories\MTMesRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class MTMesRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var MTMesRepository
     */
    protected $mTMesRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->mTMesRepo = \App::make(MTMesRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_m_t_mes()
    {
        $mTMes = factory(MTMes::class)->make()->toArray();

        $createdMTMes = $this->mTMesRepo->create($mTMes);

        $createdMTMes = $createdMTMes->toArray();
        $this->assertArrayHasKey('id', $createdMTMes);
        $this->assertNotNull($createdMTMes['id'], 'Created MTMes must have id specified');
        $this->assertNotNull(MTMes::find($createdMTMes['id']), 'MTMes with given id must be in DB');
        $this->assertModelData($mTMes, $createdMTMes);
    }

    /**
     * @test read
     */
    public function test_read_m_t_mes()
    {
        $mTMes = factory(MTMes::class)->create();

        $dbMTMes = $this->mTMesRepo->find($mTMes->id);

        $dbMTMes = $dbMTMes->toArray();
        $this->assertModelData($mTMes->toArray(), $dbMTMes);
    }

    /**
     * @test update
     */
    public function test_update_m_t_mes()
    {
        $mTMes = factory(MTMes::class)->create();
        $fakeMTMes = factory(MTMes::class)->make()->toArray();

        $updatedMTMes = $this->mTMesRepo->update($fakeMTMes, $mTMes->id);

        $this->assertModelData($fakeMTMes, $updatedMTMes->toArray());
        $dbMTMes = $this->mTMesRepo->find($mTMes->id);
        $this->assertModelData($fakeMTMes, $dbMTMes->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_m_t_mes()
    {
        $mTMes = factory(MTMes::class)->create();

        $resp = $this->mTMesRepo->delete($mTMes->id);

        $this->assertTrue($resp);
        $this->assertNull(MTMes::find($mTMes->id), 'MTMes should not exist in DB');
    }
}
