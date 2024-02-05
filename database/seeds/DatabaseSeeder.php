<?php

use App\Models\MTFamiliaProducto;
use App\Models\MTUnidadMedida;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   *
   * @return void
   */
  public function run()
  {
    $this->call(OptionMenuTableSeeder::class);
    $this->call(PermissionsTableSeeder::class);
    $this->call(RolesTableSeeder::class);
    $this->call(AccionsTableSeeder::class);
    $this->call(MTPersonaSeeder::class);
    $this->call(UserSaTableSeeder::class);
    $this->call(MTCategoriaPremioSeeder::class);
    $this->call(MTCausaSeeder::class);
    $this->call(MTClasificacionSeeder::class);
    $this->call(MTProvinciaSeeder::class);
    $this->call(MTMunicipioSeeder::class);
    $this->call(MTOaceSeeder::class);
    $this->call(MTOsdeSeeder::class);
    $this->call(MTEntidadSeeder::class);
    $this->call(MTTipoInstalacionSeeder::class);
    $this->call(MTCategoriaInstalacionSeeder::class);
    $this->call(MTCadenaHoteleraSeeder::class);
    $this->call(MTInstalacionSeeder::class);
    $this->call(UsersTableSeeder::class);
    $this->call(MTDisponibilidadHabitacionesSeeder::class);
    $this->call(SectorTableSeeder::class);
    $this->call(MTMesSeeder::class);
    $this->call(CostoConformidadSeeder::class);
    $this->call(MTCostoNoConformidadSeeder::class);
    $this->call(MTAvalApciSeeder::class);
    $this->call(MTAvalCitmaSeeder::class);
    $this->call(MTLicSanitariaSeeder::class);
    $this->call(MTRenglon_importacionesSeeder::class);
    $this->call(MTIndicador_importacionesSeeder::class);
    $this->call(MTTipoProveedorSeeder::class);
    $this->call(MTActividadSeeder::class);
    $this->call(MTDistribucionSeeder::class);
    $this->call(MTTipoAlmacenSeeder::class);
    $this->call(MTTemperaturaSeeder::class);
    $this->call(MTCategoriaAlmacenSeeder::class);
    $this->call(MTTipoFlotaSeeder::class);
    $this->call(MTTipoEMNauticosSeeder::class);
    $this->call(MTTipoVAServiciosSeeder::class);
    $this->call(MTTipoVEspecializadosSeeder::class);
    $this->call(MTTipoVTuristicosSeeder::class);
    $this->call(MTClasificacionAccidentesSeeder::class);
    $this->call(MTCausaAccidentesSeeder::class);
    $this->call(MTVictimaAccidentesSeeder::class);
    $this->call(MTEstadoTMTransportesSeeder::class);
    $this->call(MTTipoCMTransportesSeeder::class);
    $this->call(MTSituacionAMTransportesSeeder::class);
    $this->call(MTMotivoPMTransportesSeeder::class);
    $this->call(MTClaseMTransportesSeeder::class);
    $this->call(MTUbicacionMEMNauticosTransportesSeeder::class);
    $this->call(MTDeteccionSeeder::class);
    $this->call(MTTipoFocoSeeder::class);
    $this->call(MTTipoMuestraSeeder::class);
    $this->call(MTPatogenoIdentificadoSeeder::class);
    $this->call(MTClasificacionEventoSeeder::class);
    $this->call(MTDetectadoPorSeeder::class);
    $this->call(MTTipoCasoSeeder::class);
    $this->call(MTOrigenCasoSeeder::class);
    $this->call(MTAgenteCausalCasoSeeder::class);
    $this->call(MTTipoBroteSeeder::class);
    $this->call(MTOrigenBroteSeeder::class);
    $this->call(MTAgenteCausalBroteSeeder::class);
    $this->call(MTVehiculoTransmisionSeeder::class);
    $this->call(MTMecanismoTransmisionSeeder::class);
    //    $this->call(MTUnidad_de_medidaSeeder::class);
    //    $this->call(MTImportacionesSeeder::class);
    //    $this->call(MTFamiliaProducto_flujoMaterialSeeder::class);
    //    $this->call(MTProducto_flujoMaterialSeeder::class);
    //factory(\App\Models\MTSistema::class, 10)->create();
    $this->call(SistemaSeeder::class);
    $this->call(MTValorSeeder::class);
    $this->call(MTHabilidadSeeder::class);
    $this->call(MTEvaluacionSeeder::class);
    $this->call(MTEntidadTMHSSeeder::class);
    $this->call(MTTipoEvalSeeder::class);
    $this->call(MTTipoGESeeder::class);
  }
}
