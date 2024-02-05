<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Repositories\MTKmRecorridosRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Mail;
use App\Mail\NotificarAsignacionKmRecorridos;
use App\Models\MTKmRecorridos;

class ComprobarKmRecorridos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dirTransporte:kmRecorridos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comprueba si existen km recorridos en el mes actual y envia un email.';

    private $userRepository;
    private $mtKmRecorridos;
    private $mtKmRecorridosRepository;



    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(MTKmRecorridosRepository $mtKmRecorridosRepository, UserRepository $userRepository, MTKmRecorridos $mtKmRecorridos)
    {
        parent::__construct();
        $this->mtKmRecorridos = $mtKmRecorridos;
        $this->userRepository = $userRepository;
        $this->mtKmRecorridosRepository = $mtKmRecorridosRepository;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $response = $this->mtKmRecorridosRepository->comprobarExistenciaKmRecorridos();
            foreach ($response as $key => $value) {
                if ($value->status === 'vencido') {
                    $emails = $this->userRepository->listUserEmailByPermission('gestionar_dir_transporte_fi', $value->instalacion_id);
                    Mail::to($emails)->send(new NotificarAsignacionKmRecorridos($value));
                }
            }
            return $response;
        } catch (\Exception $exception) {
            return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->mtKmRecorridos->model]), $exception->getMessage(), '500');
        }
    }
}
