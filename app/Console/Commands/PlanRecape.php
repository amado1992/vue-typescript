<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Mail\PlanRecapeVencido;
use App\Models\MTPlanRecape as ModelMTPlanRecape;
use App\Repositories\MTPlanRecapeRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Mail;

class PlanRecape extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dirTransporte:checkPlanRecape';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verifica que el plan de recape anual esté vigente y notifica por email.';

    private $planRecapeModel;
    private $planrecapeRepository;
    private $userRepository;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(ModelMTPlanRecape $planRecapeModel, MTPlanRecapeRepository $planrecapeRepository, UserRepository $userRepository)
    {
        parent::__construct();
        $this->planRecapeModel = $planRecapeModel;
        $this->planrecapeRepository = $planrecapeRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * Execute the console command.
     *
     */
    public function handle()
    {
        try {
            $response = $this->planrecapeRepository->comprobarExistenciaPlanRecape();
            if ($response['status'] === 'vencido') {
                $emails = $this->userRepository->listUserEmailByPermission('gestionar_dir_transporte_fi', $response['instlacion_id']);
                Mail::to($emails)->send(new PlanRecapeVencido());
            }
            return $response;
        } catch (\Exception $exception) {
            return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->planRecapeModel->model]), $exception->getMessage(), '500');
        }
    }
}
