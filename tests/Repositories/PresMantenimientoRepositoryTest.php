<?php namespace Tests\Repositories;

use App\Models\MTPresMantenimiento;
use App\Repositories\PresMantenimientoRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class PresMantenimientoRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var PresMantenimientoRepository
     */
    protected $presMantenimientoRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->presMantenimientoRepo = \App::make(PresMantenimientoRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_pres_mantenimiento()
    {
        $presMantenimiento = factory(MTPresMantenimiento::class)->make()->toArray();

        $createdPresMantenimiento = $this->presMantenimientoRepo->create($presMantenimiento);

        $createdPresMantenimiento = $createdPresMantenimiento->toArray();
        $this->assertArrayHasKey('id', $createdPresMantenimiento);
        $this->assertNotNull($createdPresMantenimiento['id'], 'Created MTPresMantenimiento must have id specified');
        $this->assertNotNull(MTPresMantenimiento::find($createdPresMantenimiento['id']), 'MTPresMantenimiento with given id must be in DB');
        $this->assertModelData($presMantenimiento, $createdPresMantenimiento);
    }

    /**
     * @test read
     */
    public function test_read_pres_mantenimiento()
    {
        $presMantenimiento = factory(MTPresMantenimiento::class)->create();

        $dbPresMantenimiento = $this->presMantenimientoRepo->find($presMantenimiento->id);

        $dbPresMantenimiento = $dbPresMantenimiento->toArray();
        $this->assertModelData($presMantenimiento->toArray(), $dbPresMantenimiento);
    }

    /**
     * @test update
     */
    public function test_update_pres_mantenimiento()
    {
        $presMantenimiento = factory(MTPresMantenimiento::class)->create();
        $fakePresMantenimiento = factory(MTPresMantenimiento::class)->make()->toArray();

        $updatedPresMantenimiento = $this->presMantenimientoRepo->update($fakePresMantenimiento, $presMantenimiento->id);

        $this->assertModelData($fakePresMantenimiento, $updatedPresMantenimiento->toArray());
        $dbPresMantenimiento = $this->presMantenimientoRepo->find($presMantenimiento->id);
        $this->assertModelData($fakePresMantenimiento, $dbPresMantenimiento->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_pres_mantenimiento()
    {
        $presMantenimiento = factory(MTPresMantenimiento::class)->create();

        $resp = $this->presMantenimientoRepo->delete($presMantenimiento->id);

        $this->assertTrue($resp);
        $this->assertNull(MTPresMantenimiento::find($presMantenimiento->id), 'MTPresMantenimiento should not exist in DB');
    }
}
