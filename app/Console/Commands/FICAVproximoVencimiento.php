<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\VehiculoFICAVporVencer;
use App\Repositories\UserRepository;
use App\Repositories\MTMedioTransporteRepository;
use App\Models\MTMedioTransporte as ModelsMTMedioTransporte;

class FICAVproximoVencimiento extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dirTransporte:checkVencimientoFicav';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comprueba la vigencia del FICAV y notifica próximo vencimiento por email';

    private $medioTransporteRepository;
    private $medioTransporteModel;
    private $userRepository;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(MTMedioTransporteRepository $medioTransporteRepository, ModelsMTMedioTransporte $medioTransporteModel, UserRepository $userRepository)
    {
        parent::__construct();
        $this->medioTransporteRepository = $medioTransporteRepository;
        $this->medioTransporteModel = $medioTransporteModel;
        $this->userRepository = $userRepository;
    }

    /**
     * Listado de vehículos con el FICAV próximo a vecerse.
     */
    public function handle()
    {
        try {
            $vehiculos = $this->medioTransporteRepository->listaVehiculosProximoVencimientoFICAV();
            foreach ($vehiculos as $key => $value) {
                if ($value->status === 'vencido') {
                    $emails = $this->userRepository->listUserEmailByPermission('gestionar_dir_transporte_fi', $value->instalacion_id);
                    Mail::to($emails)->send(new VehiculoFICAVporVencer($value));
                }
            }
            return $vehiculos;
        } catch (\Exception $exception) {
            return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->medioTransporteModel->model]), $exception->getMessage(), '500');
        }
    }
}
