<?php

use App\Http\Controllers\MTCostoConformmidadController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::post('login', 'API\UserAPIController@login')->name('login');
Route::post('loginSa', 'API\UsuarioAPIController@login')->name('loginSa');
Route::post('change_pass', 'API\UserAPIController@changePass')->name('change_pass');

Route::middleware('ApiToken')->group(function () {
    //<editor-fold defaultstate="collapsed" desc="Ayuda">
    Route::resource('ayudas', 'API\AyudaAPIController')->except(['index', 'show']);
    Route::post('list_ayudas', 'API\AyudaAPIController@index')->name('list_ayudas');
    Route::post('show_ayudas', 'API\AyudaAPIController@show')->name('show_ayudas');
    //</editor-fold>

    //<editor-fold defaultstate="collapsed" desc="Persona">
    Route::resource('personas', 'API\PersonaAPIController')->except('index');
    Route::post('list_personas', 'API\PersonaAPIController@index')->name('list_personas');
    Route::post('persona_activo', 'API\PersonaAPIController@personaActivo')->name('persona_activo');
    Route::post('persona_nombre', 'API\PersonaAPIController@personaNombre')->name('persona_nombre');
    Route::post('persona_correo', 'API\PersonaAPIController@personaCorreo')->name('persona_correo');
    Route::post('persona_total', 'API\PersonaAPIController@personaTotal')->name('persona_total');
    //</editor-fold>

    //<editor-fold defaultstate="collapsed" desc="Roles">
    Route::resource('roles', 'API\RoleAPIController')->except('index');
    Route::post('list_roles', 'API\RoleAPIController@index')->name('list_roles');
    Route::post('role_activo', 'API\RoleAPIController@roleActivo')->name('role_activo');
    Route::post('role_nombre', 'API\RoleAPIController@roleNombre')->name('role_nombre');
    Route::post('role_get_permission', 'API\RoleAPIController@getAllPermission')->name('role_get_permission');
    //</editor-fold>

    //<editor-fold defaultstate="collapsed" desc="Permisos">
    Route::resource('permissions', 'PermissionAPIController');
    //</editor-fold>

    //<editor-fold defaultstate="collapsed" desc="Usuario">
    Route::resource('users', 'API\UserAPIController')->except('index');
    Route::post('list_users', 'API\UserAPIController@index')->name('list_users');
    Route::post('logout', 'API\UserAPIController@logout')->name('logout');
    Route::post('change_password', 'API\UserAPIController@changePassword')->name('change_password');
    Route::post('reset_password', 'API\UserAPIController@resetPassword')->name('reset_password');
    Route::post('usuarios_total', 'API\UserAPIController@usuarioTotal')->name('usuarios_total');
    Route::post('usuarios_nombre', 'API\UserAPIController@usuarioNombre')->name('usuarios_nombre');
    Route::post('usuarios_rol_total', 'API\UserAPIController@usuarioRolTotal')->name('usuarios_rol_total');
    Route::post('load_first_login', 'API\UserAPIController@loadFirstLogin')->name('load_first_login');
    //</editor-fold>

    //<editor-fold defaultstate="collapsed" desc="Usuarios">
    Route::resource('usuarios', 'API\UsuarioAPIController')->except('index');
    Route::post('lista_usuarios', 'API\UsuarioAPIController@index')->name('lista_usuarios');
    Route::post('logoutSa', 'API\UsuarioAPIController@logout')->name('logoutSa');
    //</editor-fold>

    //<editor-fold defaultstate="collapsed" desc="Trazas">
    Route::resource('trazas', 'API\TrazaAPIController')->except('index');
    Route::post('list_trazas', 'API\TrazaAPIController@index')->name('list_trazas');
    Route::post('traza_total', 'API\TrazaAPIController@trazaTotal')->name('traza_total');
    //</editor-fold>

    //<editor-fold defaultstate="collapsed" desc="Renglon">
    Route::resource('renglon', 'MTRenglonController');
    //</editor-fold>

    //<editor-fold defaultstate="collapsed" desc="Importacion">
    Route::resource('importacion', 'API\MTImportacionController')->except('index');
    Route::post('list_importaciones', 'API\MTImportacionController@index')->name('list_importaciones');
    Route::post('reporte_importacion_i_m', 'API\MTImportacionController@searchImportacionIndicadorMes')->name('reporte_importacion_i_m');
    Route::post('reporte_importacion_e_m', 'API\MTImportacionController@searchImportacionEmpresaMes')->name('reporte_importacion_e_m');
    Route::post('reporte_importaciones_all', 'API\MTImportacionController@getAllImportacion')->name('reporte_importaciones_all');
    Route::post('reporte_importaciones_compararplan', 'API\MTImportacionController@compararPlan')->name('reporte_importaciones_compararplan');
    Route::post('dashboard_info_imp', 'API\MTImportacionController@informacionDashboard')->name('dashboard_info_imp');
    //</editor-fold>

    ////<editor-fold defaultstate="collapsed" desc="Renglones">
    Route::resource('renglon', 'API\MTRenglonController')->except('index');
    Route::post('list_renglones', 'API\MTRenglonController@index')->name('list_renglones');
    //</editor-fold>

    //<editor-fold defaultstate="collapsed" desc="Indicador">
    Route::resource('indicador_importacion', 'API\MTIndicadorController')->except('index');
    Route::post('list_indicadores_importacion', 'API\MTIndicadorController@index')->name('list_indicadores_importacion');
    //</editor-fold>

    ////<editor-fold defaultstate="collapsed" desc="Entidad">
    Route::resource('entidad', 'API\MTEntidadController')->except('index');
    Route::post('list_entidades', 'API\MTEntidadController@index')->name('list_entidades');
    //</editor-fold>

    //<editor-fold defaultstate="collapsed" desc="Premios">
    Route::post('premio', 'API\MTPremioController@store');
    Route::post('list_premio', 'API\MTPremioController@index')->name('list_premio');
    Route::post('create_premio', 'API\MTPremioController@store')->name('create_premio');
    Route::put('/update_premio/{id}', 'API\MTPremioController@update');
    Route::post('/delete_premio/{id}', 'API\MTPremioController@destroy');
    Route::post('list_cantPremiosOsde', 'API\MTPremioController@cantPremiosOsde')->name('list_cantPremiosOsde');
    Route::post('list_cantPremiosCategoriaOsde', 'API\MTPremioController@cantPremiosCategoriaOsde')->name('list_cantPremiosCategoriaOsde');
    Route::post('porcientoPremiosEntidad', 'API\MTPremioController@porcientoPremiosEntidad')->name('porcientoPremiosEntidad');
    Route::post('dasboard_cantPremiosCat', 'API\MTPremioController@cantPremiosCAtegoriaDashboard')->name('dasboard_cantPremiosCat');
    Route::post('dasboard_cantPremios5years', 'API\MTPremioController@cantPremios5yearsDashboard')->name('dasboard_cantPremios5years');
    //</editor-fold>

    //<editor-fold defaultstate="collapsed" desc="Categoria-Premios">
    Route::resource('categoria_premio', 'API\MTCategoriaPremioController')->except('index');
    Route::post('list_categoria_premio', 'API\MTCategoriaPremioController@index')->name('list_categoria_premio');
    Route::post('get_categoria_premios', 'API\MTCategoriaPremioController@get');
    Route::post('create_categoria_premio', 'API\MTCategoriaPremioController@store')->name('create_categoria_premio');
    Route::put('/update_categoria_premio/{id}', 'API\MTCategoriaPremioController@update');
    Route::post('/delete_categoria_premio/{id}', 'API\MTCategoriaPremioController@destroy');
    //</editor-fold>

    //<editor-fold defaultstate="collapsed" desc="Compras">
    Route::post('list_compras', 'API\MTCompraController@index');
    Route::post('create_compras', 'API\MTCompraController@store');
    Route::get('/show_compras/{id}', 'API\MTCompraController@show');
    Route::put('/update_compras/{id}', 'API\MTCompraController@update');
    Route::post('/delete_compras/{id}', 'API\MTCompraController@destroy');
    Route::post('comparar_volumenrealsxmesxfamiliaproducto', 'API\MTCompraController@compararvolumenrealsxmesxfamiliaproducto');
    Route::post('comparar_precioxempresaxvolumenreal', 'API\MTCompraController@compararprecioxempresaxvolumenreal');
    Route::post('comparar_preciosxproductoxterritorio', 'API\MTCompraController@compararpreciosxproductoxterritorio');
    Route::post('list_volumeninventarioxterritorioxfamiliaproducto', 'API\MTCompraController@volumeninventarioxterritorioxfamiliadelproducto');
    Route::post('dashboard_cantcompras', 'API\MTCompraController@dashboardInfo');
    //</editor-fold>

    //<editor-fold defaultstate="collapsed" desc="Unidad-Medida">
    Route::post('list_unidad_medida', 'API\MTUnidadMedidaController@index');
    Route::post('get_unidad_medidas', 'API\MTUnidadMedidaController@get');
    Route::post('create_unidad_medida', 'API\MTUnidadMedidaController@store');
    Route::put('/update_unidad_medida/{id}', 'API\MTUnidadMedidaController@update');
    Route::post('/delete_unidad_medida/{id}', 'API\MTUnidadMedidaController@destroy');
    //</editor-fold>

    //<editor-fold defaultstate="collapsed" desc="Tipo-Proveedor">
    Route::post('list_tipo_proveedor', 'API\MTTipoProveedorController@index');
    Route::post('get_tipo_proveedores', 'API\MTTipoProveedorController@get');
    Route::post('create_tipo_proveedor', 'API\MTTipoProveedorController@store');
    Route::put('/update_tipo_proveedor/{id}', 'API\MTTipoProveedorController@update');
    Route::post('/delete_tipo_proveedor/{id}', 'API\MTTipoProveedorController@destroy');
    //</editor-fold>

    //<editor-fold defaultstate="collapsed" desc="Proveedor">
    Route::post('list_proveedor', 'API\MTProveedorController@index');
    Route::post('get_proveedores', 'API\MTProveedorController@get');
    Route::post('create_proveedor', 'API\MTProveedorController@store');
    Route::put('/update_proveedor/{id}', 'API\MTProveedorController@update');
    Route::post('/delete_proveedor/{id}', 'API\MTProveedorController@destroy');
    //</editor-fold>

    //<editor-fold defaultstate="collapsed" desc="Distribucion">
    Route::post('list_distribucion', 'MTDistribucionController@index');
    Route::post('get_distribuciones', 'MTDistribucionController@get');
    Route::post('create_distribucion', 'MTDistribucionController@store');
    Route::put('/update_distribucion/{id}', 'MTDistribucionController@update');
    Route::post('/delete_distribucion/{id}', 'MTDistribucionController@destroy');
    //</editor-fold>

    //<editor-fold defaultstate="collapsed" desc="Temperatura">
    Route::post('list_temperatura', 'MTTemperaturaController@index');
    Route::post('get_temperaturas', 'MTTemperaturaController@get');
    Route::post('create_temperatura', 'MTTemperaturaController@store');
    Route::put('/update_temperatura/{id}', 'MTTemperaturaController@update');
    Route::post('/delete_temperatura/{id}', 'MTTemperaturaController@destroy');
    //</editor-fold>

    //<editor-fold defaultstate="collapsed" desc="Tipo-Almacen">
    Route::post('list_tipo_almacen', 'MTTipoAlmacenController@index');
    Route::post('get_tipo_almacenes', 'MTTipoAlmacenController@get');
    Route::post('create_tipo_almacen', 'MTTipoAlmacenController@store');
    Route::put('/update_tipo_almacen/{id}', 'MTTipoAlmacenController@update');
    Route::post('/delete_tipo_almacen/{id}', 'MTTipoAlmacenController@destroy');
    //</editor-fold>

    //<editor-fold defaultstate="collapsed" desc="Actividad-Almacen">
    Route::post('list_actividad_almacen', 'MTActividadController@index');
    Route::post('get_actividad_almacenes', 'MTActividadController@get');
    Route::post('create_actividad_almacen', 'MTActividadController@store');
    Route::put('/update_actividad_almacen/{id}', 'MTActividadController@update');
    Route::post('/delete_actividad_almacen/{id}', 'MTActividadController@destroy');
    //</editor-fold>

    //<editor-fold defaultstate="collapsed" desc="Categoria-Almacen">
    Route::post('list_categoria_almacen', 'MTCategoriaController@index');
    Route::post('get_categoria_almacenes', 'MTCategoriaController@get');
    Route::post('create_categoria_almacen', 'MTCategoriaController@store');
    Route::put('/update_categoria_almacen/{id}', 'MTCategoriaController@update');
    Route::post('/delete_categoria_almacen/{id}', 'MTCategoriaController@destroy');
    //</editor-fold>

    //<editor-fold defaultstate="collapsed" desc="Almacen">
    Route::post('list_almacen', 'MTAlmacenController@index');
    Route::post('get_almacenes', 'MTAlmacenController@get');
    Route::post('create_almacen', 'MTAlmacenController@store');
    Route::put('/update_almacen/{id}', 'MTAlmacenController@update');
    Route::post('/delete_almacen/{id}', 'MTAlmacenController@destroy');
    Route::post('mostrar_ubicacion_geografica', 'MTAlmacenController@mostrar_ubicaciongeografica');
    Route::post('mostrar_almacenes_categoria', 'MTAlmacenController@mostrar_almacenescategoria');
    Route::post('mostrar_encargados_capacitados', 'MTAlmacenController@mostrar_encargadoscapacitados');
    Route::post('mostrar_almacenes_inversion_mantenimiento', 'MTAlmacenController@mostrar_almacenesinversionmantenimiento');
    Route::post('mostrar_almacenes_estadoconstructivo', 'MTAlmacenController@mostrar_almacenesestadoconstructivo');
    Route::post('dashboard_almacenescategoria', 'MTAlmacenController@almacenesXcategoria');
    //</editor-fold>

    //<editor-fold defaultstate="collapsed" desc="Encargado-Almacen">
    Route::post('list_encargado_almacen', 'MTEncargadoController@index');
    Route::post('create_encargado_almacen', 'MTEncargadoController@store');
    Route::put('/update_encargado_almacen/{id}', 'MTEncargadoController@update');
    Route::post('/delete_encargado_almacen/{id}', 'MTEncargadoController@destroy');
    //</editor-fold>

    //<editor-fold defaultstate="collapsed" desc="Disponibilidad Habitaciones">
    Route::resource('disponibilidad', 'API\MTDisponibilidadHabitacionesController')->except('index');
    Route::post('list_disponibilidad', 'API\MTDisponibilidadHabitacionesController@index')->name('list_disponibilidad');
    Route::post('list_porciento', 'API\MTDisponibilidadHabitacionesController@ListPorcientoEntidades')->name('list_porciento');
    Route::post('list_mayorIncidencia', 'API\MTDisponibilidadHabitacionesController@mayorIncidencia')->name('list_mayorIncidencia');
    Route::post('list_habNoDisp', 'API\MTDisponibilidadHabitacionesController@listHabitacionesNoDisponibles')->name('list_habNoDisp');
    Route::put('/update_disponibilidad/{id}', 'API\MTDisponibilidadHabitacionesController@update');

    //</editor-fold>

    //<editor-fold defaultstate="collapsed" desc="GestionMantenimiento">
  Route::post('add_anexo', 'API\MTGestionMantenimientoAPIController@createAnexo')->name('add_anexo');
  Route::post('get_anexos_xinstalacion', 'API\MTGestionMantenimientoAPIController@getAnexosxInstalacion')->name('get_anexos_xinstalacion');
  Route::delete('/delete_anexo/{id}', 'API\MTGestionMantenimientoAPIController@deleteAnexo')->name('delete_anexo');
  Route::post('get_anexos2_all', 'API\MTGestionMantenimientoAPIController@getAnexos2All')->name('get_anexos2_all');
  Route::post('get_anexos3_all', 'API\MTGestionMantenimientoAPIController@getAnexos3All')->name('get_anexos3_all');
  Route::post('get_anexos2indicador2_all', 'API\MTPlanMantenimientoAPIController@getAnexos2Indicador2')->name('get_anexos2indicador2_all');
  Route::post('get_anexos3indicador1_all', 'API\MTPresMantenimientoAPIController@getAnexos3Indicador1')->name('get_anexos3indicador1_all');
  Route::post('get_anexos3indicador23_all', 'API\MTGestionMantenimientoAPIController@getAnexos3Indicador23')->name('get_anexos3indicador23_all');
  Route::post('report_anexo_dos', 'API\MTPlanMantenimientoAPIController@reportPlanMtto')->name('report_anexo_dos');
  //</editor-fold>

    //<editor-fold defaultstate="collapsed" desc="Gestion de Mantenimiento">
    Route::resource('plan_mantenimientos', 'MTPlanMantenimientoAPIController');
    Route::resource('pres_mantenimientos', 'MTPresMantenimientoAPIController');
    //</editor-fold>

    ////<editor-fold defaultstate="collapsed" desc="Familia">
    Route::resource('familia', 'API\FamiliaAPIController')->except('index');
    Route::post('list_familias', 'API\FamiliaAPIController@index')->name('list_familias');
    Route::post('get_familias', 'API\FamiliaAPIController@getFamilias')->name('get_familias');
    //</editor-fold>

    //////<editor-fold defaultstate="collapsed" desc="osde">
    Route::resource('osde', 'API\MTOsdeAPIController')->except('index');
    Route::post('list_osdes', 'API\MTOsdeAPIController@index')->name('list_osdes');
    Route::post('get_osdes', 'API\MTOsdeAPIController@getOsdes')->name('get_osdes');
    //</editor-fold>

    //////<editor-fold defaultstate="collapsed" desc="municipio">
    Route::resource('municipio', 'API\MTMunicipioAPIController')->except('index');
    Route::post('list_municipios', 'API\MTMunicipioAPIController@index')->name('list_municipios');
    Route::post('get_municipios', 'API\MTMunicipioAPIController@getMunicipios')->name('get_municipios');
    //</editor-fold>

    //////<editor-fold defaultstate="collapsed" desc="provincia">
    Route::resource('provincia', 'API\MTProvinciaAPIController')->except('index');
    Route::post('list_provincias', 'API\MTProvinciaAPIController@index')->name('list_provincias');
    Route::post('get_provincias', 'API\MTProvinciaAPIController@getProvincias')->name('get_provincias');
    //</editor-fold>

    ////////<editor-fold defaultstate="collapsed" desc="tipos instalaciones">
    Route::resource('tipos_inst', 'API\MTTipoInstAPIController')->except('index');
    Route::post('list_tipos_inst', 'API\MTTipoInstAPIController@index')->name('list_tipos_inst');
    Route::post('get_tipo_inst', 'API\MTTipoInstAPIController@getTiposInst')->name('get_tipo_inst');
    //</editor-fold>

    ////////<editor-fold defaultstate="collapsed" desc="instalaciones">
    Route::resource('instalaciones', 'API\MTInstalacionAPIController')->except('index');
    Route::post('list_instalaciones', 'API\MTInstalacionAPIController@index')->name('list_instalaciones');
    Route::post('get_instalaciones', 'API\MTInstalacionAPIController@getInstalaciones')->name('get_instalaciones');
    //</editor-fold>

    ////////<editor-fold defaultstate="collapsed" desc="sectores">
    Route::resource('sector', 'API\MTSectorAPIController')->except('index');
    Route::post('list_sectores', 'API\MTSectorAPIController@index')->name('list_sectores');
    Route::post('get_sectores', 'API\MTSectorAPIController@getSectores')->name('get_sectores');
    //</editor-fold>

    ////////<editor-fold defaultstate="collapsed" desc="propuestaLider">
    Route::resource('propuesta', 'API\MTPropuestaLiderAPIController')->except('index');
    Route::post('list_propuestas', 'API\MTPropuestaLiderAPIController@index')->name('list_propuestas');
    Route::post('get_propuestas', 'API\MTPropuestaLiderAPIController@get_propuestas')->name('get_propuestas');
    Route::post('get_propuestas_estado', 'API\MTPropuestaLiderAPIController@get_propuestas_estado')->name('get_propuestas_estado');
    Route::post('lista_relaciones_propuesta', 'API\MTPropuestaLiderAPIController@lista_relaciones_propuesta')->name('lista_relaciones_propuesta');
    Route::post('dashboard_totalXestado_pl', 'API\MTPropuestaLiderAPIController@cantPropuestasXEstado')->name('dashboard_totalXestado_pl');
    Route::post('dashboard_totalXsector_pl', 'API\MTPropuestaLiderAPIController@cantPropuestasXSector')->name('dashboard_totalXsector_pl');
    //</editor-fold>

    ////////<editor-fold defaultstate="collapsed" desc="colectivoLider">
    Route::resource('colectivo', 'API\MTColectivoLiderAPIController')->except('index');
    Route::post('list_colectivos', 'API\MTColectivoLiderAPIController@index')->name('list_colectivos');
    Route::post('get_colectivos', 'API\MTColectivoLiderAPIController@get_colectivos')->name('get_colectivos');
    Route::post('get_colectivos_estado', 'API\MTColectivoLiderAPIController@get_colectivos_estado')->name('get_colectivos_estado');
    Route::post('lista_relaciones', 'API\MTColectivoLiderAPIController@lista_relaciones')->name('lista_relaciones');
    //</editor-fold>

    ////////<editor-fold defaultstate="collapsed" desc="demanda">
    Route::resource('demanda', 'API\MTDemandaAPIController')->except('index');
    Route::post('list_demandas', 'API\MTDemandaAPIController@index')->name('list_demandas');
    Route::post('get_demandas', 'API\MTDemandaAPIController@get_demandas')->name('get_demandas');
    Route::post('procesar_demanda', 'API\MTDemandaAPIController@procesarDemanda')->name('procesar_demanda');
    Route::post('demanda_instalacion_anno', 'API\MTDemandaAPIController@get_demandas_instalacion_anno')->name('demanda_instalacion_anno');
    Route::post('demanda_instalacion_ran_anno', 'API\MTDemandaAPIController@get_demandas_instalacion_ran_anno')->name('demanda_instalacion_ran_anno');
    Route::post('demanda_annos', 'API\MTDemandaAPIController@get_annos')->name('demanda_annos');
    Route::post('demanda_delete', 'API\MTDemandaAPIController@eliminarDemanda')->name('demanda_delete');
    Route::post('dashboard_demanda_aprobada', 'API\MTDemandaAPIController@dashboardInfo')->name('dashboard_demanda_aprobada');
    //</editor-fold>
    // ////////<editor-fold defaultstate="collapsed" desc="Mes">
    Route::post('get_meses', 'API\MTMesAPIController@get_meses')->name('get_meses');
    //</editor-fold>

    //////<editor-fold defaultstate="collapsed" desc="entidad">
    Route::resource('entidad', 'API\MTEntidadAPIController')->except('index');
    Route::post('list_entidades', 'API\MTEntidadAPIController@index')->name('list_entidades');
    Route::post('get_entidades', 'API\MTEntidadAPIController@getEntidades')->name('get_entidades');
    //</editor-fold>

    //<editor-fold defaultstate="collapsed" desc="Producto">
    Route::resource('producto', 'API\ProductoAPIController')->except('index');
    Route::post('list_productos', 'API\ProductoAPIController@index')->name('list_productos');
    Route::post('get_productos', 'API\ProductoAPIController@get');
    Route::post('list_productos_familia', 'API\ProductoAPIController@getProductoFamilia')->name('list_productos_familia');
    //</editor-fold>

    //<editor-fold defaultstate="collapsed" desc="Indicadores-Lineamientos">
    Route::resource('lineamientos', 'API\MTIndicadoresLineamientoController')->except('index');
    Route::post('listpg_lineamientos', 'API\MTIndicadoresLineamientoController@listPaginate')->name('listpg_lineamientos');
    Route::post('list_lineamientos', 'API\MTIndicadoresLineamientoController@index')->name('list_lineamientos');
    Route::post('report_countlicaval', 'API\MTIndicadoresLineamientoController@countLicAvales')->name('report_countlicaval');
    Route::post('dashboard_total_lic_estadoyear', 'API\MTIndicadoresLineamientoController@TotalLicenciasXestadoYear')->name('dashboard_total_lic_estadoyear');
    //</editor-fold>

    //<editor-fold defaultstate="collapsed" desc="LicSanitaria">
    Route::resource('lic_sanitaria', 'API\MTLicSanitariaController')->except('index');
    Route::post('getlic_sanitaria', 'API\MTLicSanitariaController@getLicencia')->name('getlic_sanitaria');
    //</editor-fold>

    //<editor-fold defaultstate="collapsed" desc="AvalCitma">
    Route::resource('avalcitma', 'API\MTAvalCitmaController')->except('index');
    Route::post('get_avalcitma', 'API\MTAvalCitmaController@getAval')->name('get_avalcitma');
    //</editor-fold>
    ////<editor-fold defaultstate="collapsed" desc="AvalApci">
    Route::resource('avalcitma', 'API\MTAvalApciController')->except('index');
    Route::post('get_avalapci', 'API\MTAvalApciController@getAval')->name('get_avalapci');
    //</editor-fold>

    //<editor-fold defaultstate="collapsed" desc="SistemaGestion">
    Route::get('get_operadoras', 'API\MTSistGestionOperadoraAPIController@getOperadoras')->name('get_operadoras');
    Route::post('add_operadora', 'API\MTSistGestionOperadoraAPIController@addOperadora')->name('add_operadora');
    Route::put('edit_operadora', 'API\MTSistGestionOperadoraAPIController@editOperadora')->name('edit_operadora');
    Route::delete('/delete_operadora/{id}', 'API\MTSistGestionOperadoraAPIController@deleteOperadora')->name('deleteOperadora');

    Route::get('get_estadosdemanda', 'API\MTSistGestionEstadosDemandaAPIController@getEstadosDemanda')->name('get_estadosdemanda');
    Route::post('add_estadodemanda', 'API\MTSistGestionEstadosDemandaAPIController@addEstadoDemanda')->name('add_estadodemanda');
    Route::put('edit_estadodemanda', 'API\MTSistGestionEstadosDemandaAPIController@editEstadoDemanda')->name('edit_estadodemanda');
    Route::delete('/delete_estadodemanda/{id}', 'API\MTSistGestionEstadosDemandaAPIController@deleteEstadoDemanda')->name('deleteEstadoDemanda');

    Route::get('get_tipossistgestion', 'API\MTSistGestionTiposSistGestionAPIController@getTiposSistGestion')->name('get_tipossistgestion');
    Route::post('add_tiposistgestion', 'API\MTSistGestionTiposSistGestionAPIController@addTiposSistGestion')->name('add_tiposistgestion');
    Route::put('edit_tiposistgestion', 'API\MTSistGestionTiposSistGestionAPIController@editTiposSistGestion')->name('edit_tiposistgestion');
    Route::delete('/delete_tiposistgestion/{id}', 'API\MTSistGestionTiposSistGestionAPIController@deleteTiposSistGestion')->name('deleteTiposSistGestion');

    Route::post('get_instalacionosde', 'API\MTInstalacionAPIController@getInstalacionOsde')->name('get_instalacionosde');
    Route::post('get_allregistros', 'API\MTSistGestionRegistrosAPIController@getAllRegistros')->name('get_allregistros');
    Route::post('get_registro', 'API\MTSistGestionRegistrosAPIController@getRegistro')->name('get_registro');
    Route::delete('/delete_register/{id}', 'API\MTSistGestionRegistrosAPIController@deleteRegister')->name('deleteRegister');
    Route::post('add_register', 'API\MTSistGestionRegistrosAPIController@addRegister')->name('add_register');
    Route::put('edit_register', 'API\MTSistGestionRegistrosAPIController@editRegister')->name('edit_register');

    //</editor-fold>
});

Route::post('dashboard_index', 'API\DashboardAPIController@index')->name('dashboard_index');


Route::resource('pres_mantenimientos', 'MTPresMantenimientoAPIController');

Route::resource('m_t_osdes', 'MTOsdeAPIController');


Route::resource('$_m_t_entidads', '$MTEntidadAPIController');

Route::resource('m_t_entidads', 'MTEntidadAPIController');


Route::resource('m_t_provincias', 'MTProvinciaAPIController');


Route::resource('m_t_municipios', 'MTMunicipioAPIController');


Route::resource('m_t_tipo_insts', 'MTTipoInstAPIController');


Route::resource('m_t_instalacions', 'MTInstalacionAPIController');


Route::resource('m_t_sectors', 'MTSectorAPIController');


Route::resource('m_t_colectivo_liders', 'MTColectivoLiderAPIController');


Route::resource('m_t_propuesta_liders', 'MTPropuestaLiderAPIController');



//ROUTE COST CONFORMITY
Route::get('/costos_conformidades', [MTCostoConformmidadController::class, 'index']); //GetAll
Route::post('/costo_conformidad', 'MTCostoConformmidadController@store')->name('store');
Route::get('/costos_conformidades/{id}', 'MTCostoConformmidadController@show')->name('show');
Route::put('/costos_conformidades/{id}', 'MTCostoConformmidadController@update')->name('update');
Route::delete('/costos_conformidades/{id}', 'MTCostoConformmidadController@destroy')->name('destroy');
Route::get('/costos_noconformidades', 'MTCostoConformmidadController@indexNoConformidad')->name('indexNoConformidad');; //GetAll

//END

//ROUTE COST NO CONFORMITY
/*Route::get('/costos_noconformidades', 'MTCostoNoConformidadController@index')->name('index');;//GetAll
Route::post('/costo_noconformidad', 'MTCostoNoConformidadController@store')->name('store');
Route::get('/costos_noconformidades/{id}', 'MTCostoNoConformidadController@show')->name('show');
Route::put('/costos_noconformidades/{id}', 'MTCostoNoConformidadController@update')->name('update');
Route::delete('/costos_noconformidades/{id}', 'MTCostoNoConformidadController@destroy')->name('destroy');*/
//END

//ROUTE COST CALIDAD REPORT
Route::get('/costo_calidad_reports', 'MTCostoCalidadReportController@index')->name('index');; //GetAll
Route::post('/costo_calidad_report', 'MTCostoCalidadReportController@store')->name('store');
Route::get('/costo_calidad_report/{id}', 'MTCostoCalidadReportController@show')->name('show');
Route::put('/costo_calidad_report/{id}', 'MTCostoCalidadReportController@update')->name('update');
Route::delete('/costo_calidad_report/{id}', 'MTCostoCalidadReportController@destroy')->name('destroy');
Route::post('/dashboard_totalReport_ccr', 'MTCostoCalidadReportController@totalReportesXanno')->name('totalReportesXanno');
//END

//ROUTE COST CALIDAD CONFORMITY
Route::get('/costo_calidad_conformidades', 'MTCostoCalidadConformidadController@index')->name('index');; //GetAll
Route::post('/costo_calidad_conformidad', 'MTCostoCalidadConformidadController@store')->name('store');
Route::get('/costo_calidad_conformidad/{id}', 'MTCostoCalidadConformidadController@show')->name('show');
Route::put('/costo_calidad_conformidad/{id}', 'MTCostoCalidadConformidadController@update')->name('update');
Route::delete('/costo_calidad_conformidad/{id}', 'MTCostoCalidadConformidadController@destroy')->name('destroy');
Route::get('/costo_calidad_noconformidades', 'MTCostoCalidadConformidadController@indexNoConformidad')->name('indexNoConformidad');; //GetAll

//END
Route::resource('m_t_demandas', 'MTDemandaAPIController');

//ROUTE COST CALIDAD NO CONFORMITY
//Route::get('/costo_calidad_noconformidades', 'MTCostoCalidadNoConformidadController@index')->name('index');;//GetAll
//Route::post('/costo_calidad_noconformidad', 'MTCostoCalidadNoConformidadController@store')->name('store');
//Route::get('/costo_calidad_noconformidad/{id}', 'MTCostoCalidadNoConformidadController@show')->name('show');
//Route::put('/costo_calidad_noconformidad/{id}', 'MTCostoCalidadNoConformidadController@update')->name('update');
//Route::delete('/costo_calidad_noconformidad/{id}', 'MTCostoCalidadNoConformidadController@destroy')->name('destroy');
//END

//ROUTE COST QUALITY ALL REPORTS
Route::get('/entidades_all_costos', 'MTCostoCalidadConformidadController@entities_all_costs')->name('entities_all_costs'); //GetAll
Route::get('/entidades_all_indicadores', 'MTCostoCalidadConformidadController@entities_all_indicators')->name('entities_all_indicators'); //GetAll
Route::post('/entidades_all_costos_trimestre', 'MTCostoCalidadConformidadController@entities_all_costs_trimester')->name('entities_all_costs_trimester');
Route::post('/entidades_all_costos_total', 'MTCostoCalidadConformidadController@entity_all_costs_total')->name('entity_all_costs_total');

//END
//ROUTE COST INSTALACION
Route::get('/get_instalacion', 'MTInstalacionController@show')->name('show');
//END

//ROUTE PROCESO TURISMO MAS HIGIENICO Y SALUDABLE
Route::get('/procesos_turismo_mas_higienico_seguro', 'MTProcesoTurismoMasHigienicoSeguroController@index')->name('index'); //GetAll
Route::post('/proceso_turismo_mas_higienico_seguro', 'MTProcesoTurismoMasHigienicoSeguroController@store')->name('store');
Route::get('/proceso_turismo_mas_higienico_seguro/{id}', 'MTProcesoTurismoMasHigienicoSeguroController@show')->name('show');
Route::put('/proceso_turismo_mas_higienico_seguro/{id}', 'MTProcesoTurismoMasHigienicoSeguroController@update')->name('update');
Route::delete('/proceso_turismo_mas_higienico_seguro/{id}', 'MTProcesoTurismoMasHigienicoSeguroController@destroy')->name('destroy');

Route::post('/expediente', 'MTExpedienteElementoController@store')->name('store');
Route::get('/expedientes', 'MTExpedienteElementoController@index')->name('index');
Route::delete('/expediente/{id}', 'MTExpedienteElementoController@destroy')->name('destroy');

Route::get('/expedient_process/{id}', 'MTExpedienteElementoController@expedient_process')->name('expedient_process');
Route::get('/download_file/{id}', 'MTExpedienteElementoController@download_file')->name('download_file');
Route::get('/get_provincia/{id}', 'MTProcesoTurismoMasHigienicoSeguroController@get_provincia')->name('get_provincia');

Route::get('/all_table', 'MTProcesoTurismoMasHigienicoSeguroController@all_table')->name('all_table');


//Seguimiento
Route::get('/seguimientos', 'MTseguimientoTurismoMasHSController@index')->name('index'); //GetAll
Route::post('/seguimiento', 'MTseguimientoTurismoMasHSController@store')->name('store');
Route::get('/seguimiento/{id}', 'MTseguimientoTurismoMasHSController@show')->name('show');
Route::put('/seguimiento/{id}', 'MTseguimientoTurismoMasHSController@update')->name('update');
Route::delete('/seguimiento/{id}', 'MTseguimientoTurismoMasHSController@destroy')->name('destroy');

Route::get('/seguimientos_process/{id}', 'MTseguimientoTurismoMasHSController@seguimientos_process')->name('seguimientos_process');
//Fin seguimiento

//Tipo instalacion turismo mas HS
Route::get('/tipos_instalaciones', 'MTTipoInstTurismoMasHSController@index')->name('index'); //GetAll
Route::post('/tipo_instalacion', 'MTTipoInstTurismoMasHSController@store')->name('store');
Route::get('/tipo_instalacion/{id}', 'MTTipoInstTurismoMasHSController@show')->name('show');
Route::put('/tipo_instalacion/{id}', 'MTTipoInstTurismoMasHSController@update')->name('update');
Route::delete('/tipo_instalacion/{id}', 'MTTipoInstTurismoMasHSController@destroy')->name('destroy');

Route::get('/lists_tipos_instalaciones', 'MTTipoInstTurismoMasHSController@lists_tipos_instalaciones')->name('lists_tipos_instalaciones');
//Fin tipo instalacion turismo mas HS

//Valor
Route::get('/valores', 'MTValorController@index')->name('index'); //GetAll
Route::post('/valor', 'MTValorController@store')->name('store');
Route::get('/valor/{id}', 'MTValorController@show')->name('show');
Route::put('/valor/{id}', 'MTValorController@update')->name('update');
Route::delete('/valor/{id}', 'MTValorController@destroy')->name('destroy');
//Fin valor

//Alcance
Route::get('/alcancestmhs', 'MTAlcanceTurismoMasHSController@index')->name('index'); //GetAll
Route::post('/alcancetmhs', 'MTAlcanceTurismoMasHSController@store')->name('store');
Route::get('/alcancetmhs/{id}', 'MTAlcanceTurismoMasHSController@show')->name('show');
Route::put('/alcancetmhs/{id}', 'MTAlcanceTurismoMasHSController@update')->name('update');
Route::delete('/alcancetmhs/{id}', 'MTAlcanceTurismoMasHSController@destroy')->name('destroy');

Route::get('/alcances/{id}', 'MTAlcanceTurismoMasHSController@alcances')->name('alcances');
//Fin alcance

//Seguimiento alcance
Route::get('/seguimientos_alcance_tmhs', 'MTSeguimientoAlcanceTurismoMasHSController@index')->name('index'); //GetAll
Route::post('/seguimiento_alcance_tmhs', 'MTSeguimientoAlcanceTurismoMasHSController@store')->name('store');
Route::get('/seguimiento_alcance_tmhs/{id}', 'MTSeguimientoAlcanceTurismoMasHSController@show')->name('show');
Route::put('/seguimiento_alcance_tmhs/{id}', 'MTSeguimientoAlcanceTurismoMasHSController@update')->name('update');
Route::delete('/seguimiento_alcance_tmhs/{id}', 'MTSeguimientoAlcanceTurismoMasHSController@destroy')->name('destroy');

Route::get('/seguimientos_alcance/{id}', 'MTSeguimientoAlcanceTurismoMasHSController@seguimientos_alcance')->name('seguimientos_alcance');
//Fin seguimiento alcance

//OACE
Route::get('/oaces', 'MTOaceController@index')->name('index');
Route::post('/oace', 'MTOaceController@store')->name('store');
Route::get('/oace/{id}', 'MTOaceController@show')->name('show');
Route::put('/oace/{id}', 'MTOaceController@update')->name('update');
Route::delete('/oace/{id}', 'MTOaceController@destroy')->name('destroy');
//Fin de OACE

//Empresa
Route::get('/empresas', 'MTEmpresaController@index')->name('index');
Route::post('/empresa', 'MTEmpresaController@store')->name('store');
Route::get('/empresa/{id}', 'MTEmpresaController@show')->name('show');
Route::put('/empresa/{id}', 'MTEmpresaController@update')->name('update');
Route::delete('/empresa/{id}', 'MTEmpresaController@destroy')->name('destroy');
//Fin de empresa

//Idioma
Route::get('/idiomas', 'MTIdiomaController@index')->name('index');
Route::post('/idioma', 'MTIdiomaController@store')->name('store');
Route::get('/idioma/{id}', 'MTIdiomaController@show')->name('show');
Route::put('/idioma/{id}', 'MTIdiomaController@update')->name('update');
Route::delete('/idioma/{id}', 'MTIdiomaController@destroy')->name('destroy');
//Fin de idioma

//Ueb
Route::get('/uebs', 'MTUebController@index')->name('index');
Route::post('/ueb', 'MTUebController@store')->name('store');
Route::get('/ueb/{id}', 'MTUebController@show')->name('show');
Route::put('/ueb/{id}', 'MTUebController@update')->name('update');
Route::delete('/ueb/{id}', 'MTUebController@destroy')->name('destroy');
//Fin de ueb

//Categoria docente o cientifica
Route::get('/categorias_docente_cientifica', 'MTCategoriaDocenteCientificaController@index')->name('index');
Route::post('/categoria_docente_cientifica', 'MTCategoriaDocenteCientificaController@store')->name('store');
Route::get('/categoria_docente_cientifica/{id}', 'MTCategoriaDocenteCientificaController@show')->name('show');
Route::put('/categoria_docente_cientifica/{id}', 'MTCategoriaDocenteCientificaController@update')->name('update');
Route::delete('/categoria_docente_cientifica/{id}', 'MTCategoriaDocenteCientificaController@destroy')->name('destroy');
//Fin de categoria docente o cientifica

//Ficha experto
Route::get('/ficha_expertos', 'MTFichaExpertoController@index')->name('index');
Route::post('/ficha_experto', 'MTFichaExpertoController@store')->name('store');
Route::get('/ficha_experto/{id}', 'MTFichaExpertoController@show')->name('show');
Route::put('/ficha_experto/{id}', 'MTFichaExpertoController@update')->name('update');
Route::delete('/ficha_experto/{id}', 'MTFichaExpertoController@destroy')->name('destroy');

Route::get('/empresas_exp/{id}', 'MTFichaExpertoController@empresas')->name('empresas');
Route::get('/uebs_exp/{id}', 'MTFichaExpertoController@uebs')->name('uebs');
//Fin ficha experto
Route::get('/municipios', 'MTFichaExpertoController@municipios')->name('municipios');

//Experto idioma
Route::get('/idiomas_expertos', 'MTExpertoIdiomaController@index')->name('index');
Route::post('/idioma_experto', 'MTExpertoIdiomaController@store')->name('store');
Route::get('/idioma_experto/{id}', 'MTExpertoIdiomaController@show')->name('show');
Route::put('/idioma_experto/{id}', 'MTExpertoIdiomaController@update')->name('update');
Route::delete('/idioma_experto/{id}', 'MTExpertoIdiomaController@destroy')->name('destroy');
//Fin experto idioma
//Experto idioma habilidad evaluacion
Route::get('/exps_idiomas_habs_evals', 'MTExpertoIdiomaHabilidadController@index')->name('index');
Route::post('/exp_idioma_hab_eval', 'MTExpertoIdiomaHabilidadController@store')->name('store');
Route::get('/exp_idioma_hab_eval/{id}', 'MTExpertoIdiomaHabilidadController@show')->name('show');
Route::put('/exp_idioma_hab_eval/{id}', 'MTExpertoIdiomaHabilidadController@update')->name('update');
Route::delete('/exp_idioma_hab_eval/{id}', 'MTExpertoIdiomaHabilidadController@destroy')->name('destroy');
//Fin experto idioma habilidad evaluacion


//Habilidad-evaluacion
Route::get('/habilidades', 'MTHabilidadController@index')->name('index');
Route::get('/evaluaciones', 'MTEvaluacionController@index')->name('index');
//Fin de habilidad-evaluacion

Route::get('/expertos/{id}', 'MTExpertoIdiomaHabilidadController@idiomaHabilidadEvalByExperto')->name('idiomaHabilidadEvalByExperto');

//Grupo evaluador
Route::get('/grupoevaluadores', 'MTGrupoEvaluadorController@index')->name('index');
Route::post('/grupoevaluador', 'MTGrupoEvaluadorController@store')->name('store');
Route::get('/grupoevaluador/{id}', 'MTGrupoEvaluadorController@show')->name('show');
Route::put('/grupoevaluador/{id}', 'MTGrupoEvaluadorController@update')->name('update');
Route::delete('/grupoevaluador/{id}', 'MTGrupoEvaluadorController@destroy')->name('destroy');
Route::get('/evaluadores/{id}', 'MTGrupoEvaluadorController@grupo_evaluador_proceso')->name('grupo_evaluador_proceso');
//Fin grupo evaluador

//Dictamen
Route::get('/dictamen_tmhs_all', 'MTDictamenController@index')->name('index');
Route::post('/dictamen_tmhs', 'MTDictamenController@store')->name('store');
Route::get('/dictamen_tmhs/{id}', 'MTDictamenController@show')->name('show');
Route::put('/dictamen_tmhs/{id}', 'MTDictamenController@update')->name('update');
Route::delete('/dictamen_tmhs/{id}', 'MTDictamenController@destroy')->name('destroy');

Route::post('/dictamen_evaluacion', 'MTDictamenController@dictamen_tmhs')->name('dictamen_tmhs');
Route::post('/dictamen_seguimiento', 'MTDictamenController@dictamen_seguimiento')->name('dictamen_seguimiento');
Route::post('/dictamen_renovacion', 'MTDictamenController@dictamen_renovacion')->name('dictamen_renovacion');
//Fin de dictamen

Route::get('/entidades_tmhs', 'MTEntidadTMHSController@index')->name('index');

//Renovacion
//Route::get('/uebs', 'MTRenovacionController@index')->name('index');
Route::post('/renew_process', 'MTRenovacionController@store')->name('store');
Route::get('/renew_process/{id}', 'MTRenovacionController@show')->name('show');
Route::put('/renew_process/{id}', 'MTRenovacionController@update')->name('update');
Route::delete('/renew_process/{id}', 'MTRenovacionController@destroy')->name('destroy');

Route::get('/renew_process_tmhs/{id}', 'MTRenovacionController@renew_process')->name('renew_process');
//Fin de renovacion

Route::get('/tipos_evals', 'MTTipoEvalController@index')->name('index');

//Reporte
Route::get('/report_experts/{id}', 'ReportTMHSController@report_experts')->name('report_experts');
Route::get('/report_register_base/{id}', 'ReportTMHSController@report_register_base')->name('report_register_base');
//Fin reporte

//FIN PROCESO TURISMO MAS HIGIENICO Y SALUDABLE

//SYSTEM
Route::get('/sistemas', 'MTSistemaController@index')->name('index'); //GetAll
Route::post('/sistema', 'MTSistemaController@store')->name('store');
Route::get('/sistema/{id}', 'MTSistemaController@show')->name('show');
Route::put('/sistema/{id}', 'MTSistemaController@update')->name('update');
Route::delete('/sistema/{id}', 'MTSistemaController@destroy')->name('destroy');
//END SYSTEM

//EQUIPO
Route::get('/equipos', 'MTEquipoController@index')->name('index'); //GetAll
Route::post('/equipo', 'MTEquipoController@store')->name('store');
Route::get('/equipo/{id}', 'MTEquipoController@show')->name('show');
Route::put('/equipo/{id}', 'MTEquipoController@update')->name('update');
Route::delete('/equipo/{id}', 'MTEquipoController@destroy')->name('destroy');
//FIN EQUIPO

//SISTEMA TECNOLOGICO
Route::get('/sistemas_tecnologicos', 'MTSistemaTecnologicoController@index')->name('index'); //GetAll
Route::post('/sistema_tecnologico', 'MTSistemaTecnologicoController@store')->name('store');
Route::get('/sistema_tecnologico/{id}', 'MTSistemaTecnologicoController@show')->name('show');
Route::put('/sistema_tecnologico/{id}', 'MTSistemaTecnologicoController@update')->name('update');
Route::delete('/sistema_tecnologico/{id}', 'MTSistemaTecnologicoController@destroy')->name('destroy');
Route::post('/dasboard_sismantenimiento_stec', 'MTSistemaTecnologicoController@totalSistemasMantenimiento')->name('destroy');

//Reports
Route::get('/coefdisptec_menor_igual_sesenta', 'MTReporteSistemaTecnologicoController@coefdisptec_menor_igual_sesenta')->name('coefdisptec_menor_igual_sesenta');
Route::get('/coefdisptec_menor_igual_noventa_y_cinco', 'MTReporteSistemaTecnologicoController@coefdisptec_menor_igual_noventa_y_cinco')->name('coefdisptec_menor_igual_noventa_y_cinco');
Route::get('/no_contrado_en_mantenimiento', 'MTReporteSistemaTecnologicoController@no_contrado_en_mantenimiento')->name('no_contrado_en_mantenimiento');
Route::post('/porciento_sistemas_en_crisis', 'MTReporteSistemaTecnologicoController@porciento_sistemas_en_crisis')->name('porciento_sistemas_en_crisis');
Route::post('/coefdisptec_general', 'MTReporteSistemaTecnologicoController@coefdisptec_general')->name('coefdisptec_general');
Route::post('/no_contrado_en_mantenimiento_general', 'MTReporteSistemaTecnologicoController@no_contrado_en_mantenimiento_general')->name('no_contrado_en_mantenimiento_general');


Route::get('/lists_instalaciones', 'MTSistemaTecnologicoController@instalaciones')->name('instalaciones');
Route::get('/lists_osdes', 'MTSistemaTecnologicoController@osdes')->name('osdes');
//End report

//FIN SISTEMA TECNOLOGICO

//Grupo electrogeno
Route::get('/tipos_ge', 'MTTipoGEController@index')->name('index'); //GetAll
Route::post('/tipo_ge', 'MTTipoGEController@store')->name('store');
Route::get('/tipo_ge/{id}', 'MTTipoGEController@show')->name('show');
Route::put('/tipo_ge/{id}', 'MTTipoGEController@update')->name('update');
Route::delete('/tipo_ge/{id}', 'MTTipoGEController@destroy')->name('destroy');

Route::get('/estados_ge', 'MTEstadoGEController@index')->name('index'); //GetAll
Route::post('/estado_ge', 'MTEstadoGEController@store')->name('store');
Route::get('/estado_ge/{id}', 'MTEstadoGEController@show')->name('show');
Route::put('/estado_ge/{id}', 'MTEstadoGEController@update')->name('update');

Route::get('/estadostecnicos_ge', 'MTEstadoTecnicoGEController@index')->name('index'); //GetAll
Route::post('/estadotecnico_ge', 'MTEstadoTecnicoGEController@store')->name('store');
Route::get('/estadotecnico_ge/{id}', 'MTEstadoTecnicoGEController@show')->name('show');
Route::put('/estadotecnico_ge/{id}', 'MTEstadoTecnicoGEController@update')->name('update');

Route::get('/estadosincidencias_ge', 'MTEstadoIncidenciaGEController@index')->name('index'); //GetAll
Route::post('/estadoincidencia_ge', 'MTEstadoIncidenciaGEController@store')->name('store');
Route::get('/estadoincidencia_ge/{id}', 'MTEstadoIncidenciaGEController@show')->name('show');
Route::put('/estadoincidencia_ge/{id}', 'MTEstadoIncidenciaGEController@update')->name('update');
//Fin

// Direccion de Transporte
//<editor-fold defaultstate="collapsed" desc="Dir. Transporte - Flujo informativo">
Route::resource('mediotransporte_fi', 'API\MTMedioTransporteController')->except('index');
Route::post('listpg_mediotransporte', 'API\MTMedioTransporteController@index')->name('listpg_mediotransporte');
Route::post('list_mediotransporte', 'API\MTMedioTransporteController@listMedioTransporte')->name('list_mediotransporte');
Route::post('reporte_CDT', 'API\MTMedioTransporteController@reporteCDT')->name('reporte_CDT');
Route::post('reporte_parque_total', 'API\MTMedioTransporteController@reporteParqueTotal')->name('reporte_parque_total');
Route::post('list_tipo_flota_instalacion', 'API\MTMedioTransporteController@listaTipoFlotaInstalacion')->name('list_tipo_flota_instalacion');
Route::post('/reporte_CDT/pdf', 'API\MTMedioTransporteController@exportarReporteCDT')->name('exportarPDF');
Route::post('reporte_fichamt', 'API\MTMedioTransporteController@fichaMediosTransporte')->name('reporte_fichamt');
Route::post('/reporte_fichamt/pdf', 'API\MTMedioTransporteController@exportarReporteFichaMT')->name('reporte_fichamt_pdf');
Route::post('mediotransporte_dashboard', 'API\MTMedioTransporteController@coeficienteDisposicionTec_dashboard')->name('dashboard');
Route::post('mediotransporte_dashboard_CDTT', 'API\MTMedioTransporteController@coeficienteDisposicionTecTOTAL_dashboard')->name('dashboardCDTT');
//</editor-fold>
//<editor-fold defaultstate="collapsed" desc="Plan de recape">
Route::resource('planrecape_fi', 'API\MTPlanRecapeController')->except('index');
Route::post('list_planrecape_fi_paginate', 'API\MTPlanRecapeController@index')->name('list_planrecape_fi_paginate');
Route::post('list_planrecape_fi', 'API\MTPlanRecapeController@listadoPlanRecape')->name('list_planrecape_fi');
Route::resource('cumplimiento_planrecape', 'API\MTCumplimientoPlanRecapeController')->except('index');
Route::post('listcumplimiento_planrecape', 'API\MTCumplimientoPlanRecapeController@index')->name('list_cumplimiento_planrecape');
Route::post('reporte_cumplimientopr', 'API\MTCumplimientoPlanRecapeController@reporteCumplimientoDelPlan')->name('reporte_cumplimientopr');
Route::post('cumplimientopr_dashboard', 'API\MTPlanRecapeController@cumplimientoPlanRecapeInst_dashboard')->name('cumplimientopr_dashboard');
Route::post('cumplimientoprtotal_dashboard', 'API\MTPlanRecapeController@cumplimientoPlanRecapeTotal_dashboard')->name('cumplimientoprtotal_dashboard');
//</editor-fold>

//<editor-fold defaultstate="collapsed" desc="Tipo de flota">
Route::post('list_tipo_flota', 'MTTipoFlotaController@index');
Route::post('get_tipo_flotas', 'MTTipoFlotaController@get');
Route::post('create_tipo_flota', 'MTTipoFlotaController@store');
Route::put('/update_tipo_flota/{id}', 'MTTipoFlotaController@update');
Route::post('/delete_tipo_flota/{id}', 'MTTipoFlotaController@destroy');
//</editor-fold>

//<editor-fold defaultstate="collapsed" desc="Tipo de embarcaciones y medios nauticos">
Route::post('list_tipo_emnauticos', 'MTTipoEMNauticosController@index');
Route::post('get_tipo_emnauticos', 'MTTipoEMNauticosController@get');
Route::post('create_tipo_emnauticos', 'MTTipoEMNauticosController@store');
Route::put('/update_tipo_emnauticos/{id}', 'MTTipoEMNauticosController@update');
Route::post('/delete_tipo_emnauticos/{id}', 'MTTipoEMNauticosController@destroy');
//</editor-fold>

//<editor-fold defaultstate="collapsed" desc="Tipo de vehiculos administrativos y de servicios">
Route::post('list_tipo_vaservicios', 'MTTipoVAServiciosController@index');
Route::post('get_tipo_vaservicios', 'MTTipoVAServiciosController@get');
Route::post('create_tipo_vaservicios', 'MTTipoVAServiciosController@store');
Route::put('/update_tipo_vaservicios/{id}', 'MTTipoVAServiciosController@update');
Route::post('/delete_tipo_vaservicios/{id}', 'MTTipoVAServiciosController@destroy');
//</editor-fold>

//<editor-fold defaultstate="collapsed" desc="Tipo de vehiculos especializados">
Route::post('list_tipo_vespecializados', 'MTTipoVEspecializadosController@index');
Route::post('get_tipo_vespecializados', 'MTTipoVEspecializadosController@get');
Route::post('create_tipo_vespecializados', 'MTTipoVEspecializadosController@store');
Route::put('/update_tipo_vespecializados/{id}', 'MTTipoVEspecializadosController@update');
Route::post('/delete_tipo_vespecializados/{id}', 'MTTipoVEspecializadosController@destroy');
//</editor-fold>

//<editor-fold defaultstate="collapsed" desc="Tipo de vehiculos turisticos">
Route::post('list_tipo_vturisticos', 'MTTipoVTuristicosController@index');
Route::post('get_tipo_vturisticos', 'MTTipoVTuristicosController@get');
Route::post('create_tipo_vturisticos', 'MTTipoVTuristicosController@store');
Route::put('/update_tipo_vturisticos/{id}', 'MTTipoVTuristicosController@update');
Route::post('/delete_tipo_vturisticos/{id}', 'MTTipoVTuristicosController@destroy');
//</editor-fold>

//<editor-fold defaultstate="collapsed" desc="Clasificacion de accidentes">
Route::post('list_clasificacion_accidente', 'MTClasificacionAccidenteController@index');
Route::post('get_clasificacion_accidente', 'MTClasificacionAccidenteController@get');
Route::post('create_clasificacion_accidente', 'MTClasificacionAccidenteController@store');
Route::put('/update_clasificacion_accidente/{id}', 'MTClasificacionAccidenteController@update');
Route::post('/delete_clasificacion_accidente/{id}', 'MTClasificacionAccidenteController@destroy');
//</editor-fold>

//<editor-fold defaultstate="collapsed" desc="Causas de accidentes">
Route::post('list_causa_accidente', 'MTCausaAccidenteController@index');
Route::post('get_causa_accidente', 'MTCausaAccidenteController@get');
Route::post('create_causa_accidente', 'MTCausaAccidenteController@store');
Route::put('/update_causa_accidente/{id}', 'MTCausaAccidenteController@update');
Route::post('/delete_causa_accidente/{id}', 'MTCausaAccidenteController@destroy');
//</editor-fold>

//<editor-fold defaultstate="collapsed" desc="Victimas de accidentes">
Route::post('list_victima_accidente', 'MTVictimaAccidenteController@index');
Route::post('get_victima_accidente', 'MTVictimaAccidenteController@get');
Route::post('create_victima_accidente', 'MTVictimaAccidenteController@store');
Route::put('/update_victima_accidente/{id}', 'MTVictimaAccidenteController@update');
Route::post('/delete_victima_accidente/{id}', 'MTVictimaAccidenteController@destroy');
//</editor-fold>

//<editor-fold defaultstate="collapsed" desc="Estado tecnico de medio de transportes">
Route::post('list_etmtransporte', 'MTEstadoTMTransporteController@index');
Route::post('get_etmtransporte', 'MTEstadoTMTransporteController@get');
Route::post('create_etmtransporte', 'MTEstadoTMTransporteController@store');
Route::put('/update_etmtransporte/{id}', 'MTEstadoTMTransporteController@update');
Route::post('/delete_etmtransporte/{id}', 'MTEstadoTMTransporteController@destroy');
//</editor-fold>

//<editor-fold defaultstate="collapsed" desc="Tipo de combustible de medio de transportes">
Route::post('list_tcmtransporte', 'MTTipoCMTransporteController@index');
Route::post('get_tcmtransporte', 'MTTipoCMTransporteController@get');
Route::post('create_tcmtransporte', 'MTTipoCMTransporteController@store');
Route::put('/update_tcmtransporte/{id}', 'MTTipoCMTransporteController@update');
Route::post('/delete_tcmtransporte/{id}', 'MTTipoCMTransporteController@destroy');
//</editor-fold>

//<editor-fold defaultstate="collapsed" desc="Situacion actual de medio de transportes">
Route::post('list_samtransporte', 'MTSituacionAMTransporteController@index');
Route::post('get_samtransporte', 'MTSituacionAMTransporteController@get');
Route::post('create_samtransporte', 'MTSituacionAMTransporteController@store');
Route::put('/update_samtransporte/{id}', 'MTSituacionAMTransporteController@update');
Route::post('/delete_samtransporte/{id}', 'MTSituacionAMTransporteController@destroy');
//</editor-fold>

//<editor-fold defaultstate="collapsed" desc="Motivo de paralisis de medio de transportes">
Route::post('list_mpmtransporte', 'MTMotivoPMTransporteController@index');
Route::post('get_mpmtransporte', 'MTMotivoPMTransporteController@get');
Route::post('create_mpmtransporte', 'MTMotivoPMTransporteController@store');
Route::put('/update_mpmtransporte/{id}', 'MTMotivoPMTransporteController@update');
Route::post('/delete_mpmtransporte/{id}', 'MTMotivoPMTransporteController@destroy');
//</editor-fold>

//<editor-fold defaultstate="collapsed" desc="Clase de medio de transportes">
Route::post('list_cmtransporte', 'MTClaseMTransporteController@index');
Route::post('get_cmtransporte', 'MTClaseMTransporteController@get');
Route::post('create_cmtransporte', 'MTClaseMTransporteController@store');
Route::put('/update_cmtransporte/{id}', 'MTClaseMTransporteController@update');
Route::post('/delete_cmtransporte/{id}', 'MTClaseMTransporteController@destroy');
//</editor-fold>

//<editor-fold defaultstate="collapsed" desc="Ubicacion del motor en una embarcacion o medio nautico">
Route::post('list_umemnautico', 'MTUbicacionMEMNauticoController@index');
Route::post('get_umemnautico', 'MTUbicacionMEMNauticoController@get');
Route::post('create_umemnautico', 'MTUbicacionMEMNauticoController@store');
Route::put('/update_umemnautico/{id}', 'MTUbicacionMEMNauticoController@update');
Route::post('/delete_umemnautico/{id}', 'MTUbicacionMEMNauticoController@destroy');
//</editor-fold>

//<editor-fold defaultstate="collapsed" desc="Vehiculo ajeno">
Route::post('list_vajeno', 'MTVehiculoAjenoController@index');
Route::post('get_vajeno', 'MTVehiculoAjenoController@get');
Route::post('create_vajeno', 'MTVehiculoAjenoController@store');
Route::put('/update_vajeno/{id}', 'MTVehiculoAjenoController@update');
Route::post('/delete_vajeno/{id}', 'MTVehiculoAjenoController@destroy');
//</editor-fold>

//<editor-fold defaultstate="collapsed" desc="Kilómetros recorridos">
Route::post('list_km_recorridos', 'MTKmRecorridosController@index');
Route::get('/show_km_recorridos/{id}', 'MTKmRecorridosController@show');
Route::post('create_km_recorridos', 'MTKmRecorridosController@store');
Route::put('/update_km_recorridos/{id}', 'MTKmRecorridosController@update');
//</editor-fold>

//<editor-fold defaultstate="collapsed" desc="Gestionar accidentes">
Route::post('list_gaccidente', 'MTGestionarAccidenteController@index');
Route::post('get_gaccidente', 'MTGestionarAccidenteController@get');
Route::get('/show_gaccidente/{id}', 'MTGestionarAccidenteController@show');
Route::post('create_gaccidente', 'MTGestionarAccidenteController@store');
Route::put('/update_gaccidente/{id}', 'MTGestionarAccidenteController@update');
Route::post('/delete_gaccidente/{id}', 'MTGestionarAccidenteController@destroy');
Route::post('/reporte/vehiculoconmasaccidentes', 'MTGestionarAccidenteController@vehiculoconmasaccidentes');
Route::post('/reporte/horarioconmasaccidentes', 'MTGestionarAccidenteController@horarioconmasaccidentes');
Route::post('/reporte/accidentalidad', 'MTGestionarAccidenteController@reporteaccidentalidad');
Route::post('/reporte/accidentalidad/pdf', 'MTGestionarAccidenteController@exportarreporteaccidentalidad');
Route::post('accidente_dashboard', 'MTGestionarAccidenteController@accidentalidad_dashboard');


//</editor-fold>

//<editor-fold defaultstate="collapsed" desc="Historico vehiculo">
Route::post('historico_vehiculo', 'API\MTHistoricoVehiculoController@listHistoricoVehiculo')->name('historico_vehiculo');
Route::post('historico_accidente', 'API\MTHistoricoVehiculoController@historicoAccidentes')->name('historico_accidente');
//</editor-fold>

//<editor-fold defaultstate="collapsed" desc="Detección">
Route::post('list_deteccion', 'MTDeteccionController@index');
Route::post('get_deteccion', 'MTDeteccionController@get');
Route::post('create_deteccion', 'MTDeteccionController@store');
Route::put('/update_deteccion/{id}', 'MTDeteccionController@update');
Route::post('/delete_deteccion/{id}', 'MTDeteccionController@destroy');
//</editor-fold>

//<editor-fold defaultstate="collapsed" desc="Clasificacion del evento">
Route::post('list_clasificacion_evento', 'MTClasificacionEventoController@index');
//Route::post('get_clasificacion_evento', 'MTClasificacionEventoController@get');
Route::post('create_clasificacion_evento', 'MTClasificacionEventoController@store');
Route::put('/update_clasificacion_evento/{id}', 'MTClasificacionEventoController@update');
Route::post('/delete_clasificacion_evento/{id}', 'MTClasificacionEventoController@destroy');
//</editor-fold>

//<editor-fold defaultstate="collapsed" desc="Tipo de foco">
Route::post('list_tipo_foco', 'MTTipoFocoController@index');
//Route::post('get_tipo_foco', 'MTTipoFocoController@get');
Route::post('create_tipo_foco', 'MTTipoFocoController@store');
Route::put('/update_tipo_foco/{id}', 'MTTipoFocoController@update');
Route::post('/delete_tipo_foco/{id}', 'MTTipoFocoController@destroy');
//</editor-fold>

//<editor-fold defaultstate="collapsed" desc="Tipo de muestra">
Route::post('list_tipo_muestra', 'MTTipoMuestraController@index');
//Route::post('get_tipo_muestra', 'MTTipoMuestraController@get');
Route::post('create_tipo_muestra', 'MTTipoMuestraController@store');
Route::put('/update_tipo_muestra/{id}', 'MTTipoMuestraController@update');
Route::post('/delete_tipo_muestra/{id}', 'MTTipoMuestraController@destroy');
//</editor-fold>

//<editor-fold defaultstate="collapsed" desc="Patógeno identificado">
Route::post('list_patogeno_identificado', 'MTPatogenoIdentificadoController@index');
//Route::post('get_patogeno_identificado', 'MTPatogenoIdentificadoController@get');
Route::post('create_patogeno_identificado', 'MTPatogenoIdentificadoController@store');
Route::put('/update_patogeno_identificado/{id}', 'MTPatogenoIdentificadoController@update');
Route::post('/delete_patogeno_identificado/{id}', 'MTPatogenoIdentificadoController@destroy');
//</editor-fold>

//<editor-fold defaultstate="collapsed" desc="Detectado por">
Route::post('list_detectadopor', 'API\MTDetectadoPorController@index');
Route::post('get_detectadopor', 'API\MTDetectadoPorController@get');
Route::post('create_detectadopor', 'API\MTDetectadoPorController@store');
Route::put('/update_detectadopor/{id}', 'API\MTDetectadoPorController@update');
Route::post('/delete_detectadopor/{id}', 'API\MTDetectadoPorController@destroy');
//</editor-fold>

//<editor-fold defaultstate="collapsed" desc="Tipo de caso">
Route::post('list_tipocaso', 'API\MTTipoCasoController@index');
Route::post('get_tipocaso', 'API\MTTipoCasoController@get');
Route::post('create_tipocaso', 'API\MTTipoCasoController@store');
Route::put('/update_tipocaso/{id}', 'API\MTTipoCasoController@update');
Route::post('/delete_tipocaso/{id}', 'API\MTTipoCasoController@destroy');
//</editor-fold>

//<editor-fold defaultstate="collapsed" desc="Origen de caso">
Route::post('list_origencaso', 'API\MTOrigenCasoController@index');
Route::post('get_origencaso', 'API\MTOrigenCasoController@get');
Route::post('create_origencaso', 'API\MTOrigenCasoController@store');
Route::put('/update_origencaso/{id}', 'API\MTOrigenCasoController@update');
Route::post('/delete_origencaso/{id}', 'API\MTOrigenCasoController@destroy');
//</editor-fold>

//<editor-fold defaultstate="collapsed" desc="Agente causal de caso">
Route::post('list_agentecausalcaso', 'API\MTAgenteCausalCasosController@index');
Route::post('get_agentecausalcaso', 'API\MTAgenteCausalCasosController@get');
Route::post('create_agentecausalcaso', 'API\MTAgenteCausalCasosController@store');
Route::put('/update_agentecausalcaso/{id}', 'API\MTAgenteCausalCasosController@update');
Route::post('/delete_agentecausalcaso/{id}', 'API\MTAgenteCausalCasosController@destroy');
//</editor-fold>

//<editor-fold defaultstate="collapsed" desc="Origen de brote">
Route::post('list_origenbrote', 'API\MTOrigenBroteController@index');
Route::post('get_origenbrote', 'API\MTOrigenBroteController@get');
Route::post('create_origenbrote', 'API\MTOrigenBroteController@store');
Route::put('/update_origenbrote/{id}', 'API\MTOrigenBroteController@update');
Route::post('/delete_origenbrote/{id}', 'API\MTOrigenBroteController@destroy');
//</editor-fold>

//<editor-fold defaultstate="collapsed" desc="Tipo de brote">
Route::post('list_tipobrote', 'API\MTTipoBroteController@index');
Route::post('get_tipobrote', 'API\MTTipoBroteController@get');
Route::post('create_tipobrote', 'API\MTTipoBroteController@store');
Route::put('/update_tipobrote/{id}', 'API\MTTipoBroteController@update');
Route::post('/delete_tipobrote/{id}', 'API\MTTipoBroteController@destroy');
//</editor-fold>

//<editor-fold defaultstate="collapsed" desc="Agente causal de brote">
Route::post('list_agentecausalbrote', 'API\MTAgenteCausalBrotesController@index');
Route::post('get_agentecausalbrote', 'API\MTAgenteCausalBrotesController@get');
Route::post('create_agentecausalbrote', 'API\MTAgenteCausalBrotesController@store');
Route::put('/update_agentecausalbrote/{id}', 'API\MTAgenteCausalBrotesController@update');
Route::post('/delete_agentecausalbrote/{id}', 'API\MTAgenteCausalBrotesController@destroy');
//</editor-fold>

//<editor-fold defaultstate="collapsed" desc="Vehiculo transmision">
Route::post('list_vehiculotransmision', 'API\MTVehiculoTransmisionController@index');
Route::post('get_vehiculotransmision', 'API\MTVehiculoTransmisionController@get');
Route::post('create_vehiculotransmision', 'API\MTVehiculoTransmisionController@store');
Route::put('/update_vehiculotransmision/{id}', 'API\MTVehiculoTransmisionController@update');
Route::post('/delete_vehiculotransmision/{id}', 'API\MTVehiculoTransmisionController@destroy');
//</editor-fold>

//<editor-fold defaultstate="collapsed" desc="Mecanismo transmision">
Route::post('list_mecanismotransmision', 'API\MTMecanismoTransmisionController@index');
Route::post('get_mecanismotransmision', 'API\MTMecanismoTransmisionController@get');
Route::post('create_mecanismotransmision', 'API\MTMecanismoTransmisionController@store');
Route::put('/update_mecanismotransmision/{id}', 'API\MTMecanismoTransmisionController@update');
Route::post('/delete_mecanismotransmision/{id}', 'API\MTMecanismoTransmisionController@destroy');
//</editor-fold>

//<editor-fold defaultstate="collapsed" desc="Gestionar Evento Higienio-Epidemiologico">
Route::resource('eventohe', 'API\MTEventoHigienicoEpidemiologicoController')->except('index');
Route::post('list_eventohe', 'API\MTEventoHigienicoEpidemiologicoController@index')->name('list_eventohe');
Route::post('/eventohe/resumenPDF', 'API\MTEventoHigienicoEpidemiologicoController@exportarResumenPDF')->name('resumenPDFehe');
Route::post('/eventohe/uploadplanaccion', 'API\MTEventoHigienicoEpidemiologicoController@uploadPlanAccion')->name('uploadplanaccion');
Route::post('/eventohe/listplanaccion', 'API\MTEventoHigienicoEpidemiologicoController@listarPlanAccion')->name('listplanaccion');
Route::post('/eventohe/downloadplanaccion', 'API\MTEventoHigienicoEpidemiologicoController@downloadPlanAccion')->name('downloadplanaccion');
Route::delete('/eventohe/deleteplanaccion/{id}', 'API\MTEventoHigienicoEpidemiologicoController@deletePlanAccion')->name('deleteplanaccion');
Route::post('/eventohe/uploadresumen', 'API\MTEventoHigienicoEpidemiologicoController@uploadDocResumen')->name('uploadresumen');
Route::post('/eventohe/downloadresumen', 'API\MTEventoHigienicoEpidemiologicoController@downloadDocResumen')->name('downloadresumen');
Route::delete('/eventohe/deleteresumen/{id}', 'API\MTEventoHigienicoEpidemiologicoController@deleteDocResumen')->name('deleteresumen');
Route::post('/eventohe/listresumen', 'API\MTEventoHigienicoEpidemiologicoController@listarDocResumen')->name('listresumen');
//</editor-fold>

// Nomencldores Generales

//<editor-fold defaultstate="collapsed" desc="OACE">
Route::post('list_oace', 'API\MTOaceController@index');
Route::post('get_oace', 'API\MTOaceController@get');
Route::post('create_oace', 'API\MTOaceController@store');
Route::put('/update_oace/{id}', 'API\MTOaceController@update');
Route::post('/delete_oace/{id}', 'API\MTOaceController@destroy');
//</editor-fold>

//<editor-fold defaultstate="collapsed" desc="Empresa">
Route::post('list_empresa', 'API\MTEmpresaController@index');
Route::post('get_empresa', 'API\MTEmpresaController@get');
Route::post('get_empresa_importadora', 'API\MTEmpresaController@getImportadora');
Route::post('create_empresa', 'API\MTEmpresaController@store');
Route::put('/update_empresa/{id}', 'API\MTEmpresaController@update');
Route::post('/delete_empresa/{id}', 'API\MTEmpresaController@destroy');
//</editor-fold>

//<editor-fold defaultstate="collapsed" desc="Fintur">
Route::post('list_fintur', 'API\MTFinturController@index');
Route::post('get_fintur', 'API\MTFinturController@get');
Route::post('create_fintur', 'API\MTFinturController@store');
Route::put('/update_fintur/{id}', 'API\MTFinturController@update');
Route::post('/delete_fintur/{id}', 'API\MTFinturController@destroy');
//</editor-fold>

//<editor-fold defaultstate="collapsed" desc="Unidad Administrativa">
Route::post('list_unidad_administrativa', 'API\MTUnidadAdministrativaController@index');
Route::post('get_unidad_administrativa', 'API\MTUnidadAdministrativaController@get');
Route::post('create_unidad_administrativa', 'API\MTUnidadAdministrativaController@store');
Route::put('/update_unidad_administrativa/{id}', 'API\MTUnidadAdministrativaController@update');
Route::post('/delete_unidad_administrativa/{id}', 'API\MTUnidadAdministrativaController@destroy');
//</editor-fold>

//<editor-fold defaultstate="collapsed" desc="Escuela Ramal">
Route::post('list_escuela_ramal', 'API\MTEscuelaRamalController@index');
Route::post('get_escuela_ramal', 'API\MTEscuelaRamalController@get');
Route::post('create_escuela_ramal', 'API\MTEscuelaRamalController@store');
Route::put('/update_escuela_ramal/{id}', 'API\MTEscuelaRamalController@update');
Route::post('/delete_escuela_ramal/{id}', 'API\MTEscuelaRamalController@destroy');
//</editor-fold>

//<editor-fold defaultstate="collapsed" desc="Oficina Nacional de Información Turística">
Route::post('list_onit', 'API\MTOnitController@index');
Route::post('get_onit', 'API\MTOnitController@get');
Route::post('create_onit', 'API\MTOnitController@store');
Route::put('/update_onit/{id}', 'API\MTOnitController@update');
Route::post('/delete_onit/{id}', 'API\MTOnitController@destroy');
//</editor-fold>

//<editor-fold defaultstate="collapsed" desc="Unidad Técnica de Inversiones del Turismo">
Route::post('list_utit', 'API\MTUtitController@index');
Route::post('get_utit', 'API\MTUtitController@get');
Route::post('create_utit', 'API\MTUtitController@store');
Route::put('/update_utit/{id}', 'API\MTUtitController@update');
Route::post('/delete_utit/{id}', 'API\MTUtitController@destroy');
//</editor-fold>

//<editor-fold defaultstate="collapsed" desc="Delegación Territorial">
Route::post('list_delegacionterritorial', 'API\MTDelegacionTerritorialController@index');
Route::post('get_delegacionterritorial', 'API\MTDelegacionTerritorialController@get');
Route::post('create_delegacionterritorial', 'API\MTDelegacionTerritorialController@store');
Route::put('/update_delegacionterritorial/{id}', 'API\MTDelegacionTerritorialController@update');
Route::post('/delete_delegacionterritorial/{id}', 'API\MTDelegacionTerritorialController@destroy');
//</editor-fold>

//<editor-fold defaultstate="collapsed" desc="Categoría Instalación">
Route::post('list_categoriainstalacion', 'API\MTCategoriaInstalacionController@index');
Route::post('get_categoriainstalacion', 'API\MTCategoriaInstalacionController@get');
Route::post('create_categoriainstalacion', 'API\MTCategoriaInstalacionController@store');
Route::put('/update_categoriainstalacion/{id}', 'API\MTCategoriaInstalacionController@update');
Route::post('/delete_categoriainstalacion/{id}', 'API\MTCategoriaInstalacionController@destroy');
//</editor-fold>

//<editor-fold defaultstate="collapsed" desc="UEB">
Route::post('list_ueb', 'API\MTUebController@index');
Route::post('get_ueb', 'API\MTUebController@get');
Route::post('create_ueb', 'API\MTUebController@store');
Route::put('/update_ueb/{id}', 'API\MTUebController@update');
Route::post('/delete_ueb/{id}', 'API\MTUebController@destroy');
//</editor-fold>

//<editor-fold defaultstate="collapsed" desc="Sucursal">
Route::post('list_sucursal', 'API\MTSucursalController@index');
Route::post('get_sucursal', 'API\MTSucursalController@get');
Route::post('create_sucursal', 'API\MTSucursalController@store');
Route::put('/update_sucursal/{id}', 'API\MTSucursalController@update');
Route::post('/delete_sucursal/{id}', 'API\MTSucursalController@destroy');
//</editor-fold>

//<editor-fold defaultstate="collapsed" desc="Escuela Capacitación">
Route::post('list_escuelacapacitacion', 'API\MTEscuelaCapacitacionController@index');
Route::post('get_escuelacapacitacion', 'API\MTEscuelaCapacitacionController@get');
Route::post('create_escuelacapacitacion', 'API\MTEscuelaCapacitacionController@store');
Route::put('/update_escuelacapacitacion/{id}', 'API\MTEscuelaCapacitacionController@update');
Route::post('/delete_escuelacapacitacion/{id}', 'API\MTEscuelaCapacitacionController@destroy');
//</editor-fold>

//<editor-fold defaultstate="collapsed" desc="Infotur">
Route::post('list_infotur', 'API\MTInfoturController@index');
Route::post('get_infotur', 'API\MTInfoturController@get');
Route::post('create_infotur', 'API\MTInfoturController@store');
Route::put('/update_infotur/{id}', 'API\MTInfoturController@update');
Route::post('/delete_infotur/{id}', 'API\MTInfoturController@destroy');
//</editor-fold>

//<editor-fold defaultstate="collapsed" desc="Oficina de Empleo">
Route::post('list_oficinaempleo', 'API\MTOficinaEmpleoController@index');
Route::post('get_oficinaempleo', 'API\MTOficinaEmpleoController@get');
Route::post('create_oficinaempleo', 'API\MTOficinaEmpleoController@store');
Route::put('/update_oficinaempleo/{id}', 'API\MTOficinaEmpleoController@update');
Route::post('/delete_oficinaempleo/{id}', 'API\MTOficinaEmpleoController@destroy');
//</editor-fold>

//<editor-fold defaultstate="collapsed" desc="Cadena Hotelera">
Route::post('list_cadenahotelera', 'API\MTCadenaHoteleraController@index');
Route::post('get_cadenahotelera', 'API\MTCadenaHoteleraController@get');
Route::post('create_cadenahotelera', 'API\MTCadenaHoteleraController@store');
Route::put('/update_cadenahotelera/{id}', 'API\MTCadenaHoteleraController@update');
Route::post('/delete_cadenahotelera/{id}', 'API\MTCadenaHoteleraController@destroy');
//</editor-fold>
