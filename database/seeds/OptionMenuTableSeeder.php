<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OptionMenuTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('event_options_menu')->insert([
            'name' => 'Dashboard',
            'created_at' => now(),
            'updated_at' => now()
        ]);
// Dirección de Calidad
        DB::table('event_options_menu')->insert([
            'name' => 'Dirección de Calidad',
            'created_at' => now(),
            'updated_at' => now()
        ]);
// Costo de Calidad
        DB::table('event_options_menu')->insert([
            'name' => 'Dirección de Calidad->Costo de Calidad',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('event_options_menu')->insert([
            'name' => 'Dirección de Calidad->Costo de Calidad->Conformidad y no conformidad',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('event_options_menu')->insert([
            'name' => 'Dirección de Calidad->Costo de Calidad->Conformidad y no conformidad calidad',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('event_options_menu')->insert([
            'name' => 'Dirección de Calidad->Costo de Calidad->Gestión de reportes',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('event_options_menu')->insert([
            'name' => 'Dirección de Calidad->Costo de Calidad->Reportes',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('event_options_menu')->insert([
            'name' => 'Dirección de Calidad->Costo de Calidad->Reportes->Costos calidad por entidades',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('event_options_menu')->insert([
            'name' => 'Dirección de Calidad->Costo de Calidad->Reportes->Indicadores por entidades',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('event_options_menu')->insert([
            'name' => 'Dirección de Calidad->Costo de Calidad->Reportes->Totales de costos Calidad',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('event_options_menu')->insert([
            'name' => 'Dirección de Calidad->Costo de Calidad->Reportes->Costos por entidades',
            'created_at' => now(),
            'updated_at' => now()
        ]);
// Turismo mas Higienico y Seguro
        DB::table('event_options_menu')->insert([
            'name' => 'Dirección de Calidad->Turismo más higiénico y seguro',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('event_options_menu')->insert([
            'name' => 'Dirección de Calidad->Turismo más higiénico y seguro->Turismo más higiénico y seguro',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('event_options_menu')->insert([
            'name' => 'Dirección de Calidad->Turismo más higiénico y seguro->Expedientes del proceso T+HS',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('event_options_menu')->insert([
            'name' => 'Dirección de Calidad->Turismo más higiénico y seguro->Seguimiento del proceso T+HS',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('event_options_menu')->insert([
            'name' => 'Dirección de Calidad->Turismo más higiénico y seguro->Reportes',
            'created_at' => now(),
            'updated_at' => now()
        ]);
// Premios y Distinciones
        DB::table('event_options_menu')->insert([
            'name' => 'Dirección de Calidad->Premios y Distinciones',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('event_options_menu')->insert([
            'name' => 'Dirección de Calidad->Premios y Distinciones->Gestionar premios y distinciones',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('event_options_menu')->insert([
            'name' => 'Dirección de Calidad->Premios y Distinciones->Reportes',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('event_options_menu')->insert([
            'name' => 'Dirección de Calidad->Premios y Distinciones->Reportes->Cant. de premios por OSDE',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('event_options_menu')->insert([
            'name' => 'Dirección de Calidad->Premios y Distinciones->Reportes->Cant. de premios por entidad',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('event_options_menu')->insert([
            'name' => 'Dirección de Calidad->Premios y Distinciones->Reportes->% de instalaciones premiadas',
            'created_at' => now(),
            'updated_at' => now()
        ]);
// Movimiento Líderes de Calidad
        DB::table('event_options_menu')->insert([
            'name' => 'Dirección de Calidad->Movimiento Líderes de Calidad',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('event_options_menu')->insert([
            'name' => 'Dirección de Calidad->Movimiento Líderes de Calidad->Gestionar colectivos líderes de la calidad',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('event_options_menu')->insert([
            'name' => 'Dirección de Calidad->Movimiento Líderes de Calidad->Gestionar propuestas de líderes de la calidad',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('event_options_menu')->insert([
            'name' => 'Dirección de Calidad->Movimiento Líderes de Calidad->Reportes',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('event_options_menu')->insert([
            'name' => 'Dirección de Calidad->Movimiento Líderes de Calidad->Reportes->Listado de colectivos por estados',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('event_options_menu')->insert([
            'name' => 'Dirección de Calidad->Movimiento Líderes de Calidad->Reportes->Listado de colectivos por filtros',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('event_options_menu')->insert([
            'name' => 'Dirección de Calidad->Movimiento Líderes de Calidad->Reportes->Listado de propuestas de líderes por estados',
            'created_at' => now(),
            'updated_at' => now()
        ]);
//Sistema de gestión de la calidad
DB::table('event_options_menu')->insert([
    'name' => 'Dirección de Calidad->Sistema de gestión de la calidad',
    'created_at' => now(),
    'updated_at' => now()
]);
//Indicadores lineamientos
DB::table('event_options_menu')->insert([
    'name' => 'Dirección de Calidad->Indicadores lineamientos',
    'created_at' => now(),
    'updated_at' => now()
]);


// Dirección de Logística
        DB::table('event_options_menu')->insert([
            'name' => 'Dirección de Logística',
            'created_at' => now(),
            'updated_at' => now()
        ]);
//Flujo material
        DB::table('event_options_menu')->insert([
            'name' => 'Dirección de Logística->Flujo material',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('event_options_menu')->insert([
            'name' => 'Dirección de Logística->Flujo material->Gestionar familias',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('event_options_menu')->insert([
            'name' => 'Dirección de Logística->Flujo material->Gestionar productos',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('event_options_menu')->insert([
            'name' => 'Dirección de Logística->Flujo material->Reportes',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('event_options_menu')->insert([
            'name' => 'Dirección de Logística->Flujo material->Listado productos por familia',
            'created_at' => now(),
            'updated_at' => now()
        ]);
//Flujo informativo
        DB::table('event_options_menu')->insert([
            'name' => 'Dirección de Logística->Flujo informativo',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('event_options_menu')->insert([
            'name' => 'Dirección de Logística->Flujo informativo->Gestionar compras',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('event_options_menu')->insert([
            'name' => 'Dirección de Logística->Flujo informativo->Gestionar proveedor',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('event_options_menu')->insert([
            'name' => 'Dirección de Logística->Flujo informativo->Nomencladores->Tipo proveedor',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('event_options_menu')->insert([
            'name' => 'Dirección de Logística->Flujo informativo->Nomencladores->Unidad de medida',
            'created_at' => now(),
            'updated_at' => now()
        ]);
//Importaciones
        DB::table('event_options_menu')->insert([
            'name' => 'Dirección de Logística->Importaciones',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('event_options_menu')->insert([
            'name' => 'Dirección de Logística->Importaciones->Gestionar importaciones',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('event_options_menu')->insert([
            'name' => 'Dirección de Logística->Importaciones->Reportes',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('event_options_menu')->insert([
            'name' => 'Dirección de Logística->Importaciones->Reportes->Plan anual de importaciones',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('event_options_menu')->insert([
            'name' => 'Dirección de Logística->Importaciones->Reportes->Plan anual por indicador',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('event_options_menu')->insert([
            'name' => 'Dirección de Logística->Importaciones->Reportes->Plan anual por entidad',
            'created_at' => now(),
            'updated_at' => now()
        ]);
//Almacenes
DB::table('event_options_menu')->insert([
    'name' => 'Dirección de Logística->Almacenes',
    'created_at' => now(),
    'updated_at' => now()
]);
//Dirección de Servicios Técnicos
        DB::table('event_options_menu')->insert([
            'name' => 'Dirección de Servicios Técnicos',
            'created_at' => now(),
            'updated_at' => now()
        ]);
//Disponibilidad de habitaciones
        DB::table('event_options_menu')->insert([
            'name' => 'Dirección de Servicios Técnicos->Disponibilidad de habitaciones',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('event_options_menu')->insert([
            'name' => 'Dirección de Servicios Técnicos->Disponibilidad de habitaciones->Gestionar no disponibilidad',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('event_options_menu')->insert([
            'name' => 'Dirección de Servicios Técnicos->Disponibilidad de habitaciones->Reportes',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('event_options_menu')->insert([
            'name' => 'Dirección de Servicios Técnicos->Disponibilidad de habitaciones->Reportes->Disponibilidad menor o igual al 95%',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('event_options_menu')->insert([
            'name' => 'Dirección de Servicios Técnicos->Disponibilidad de habitaciones->Reportes->Habitaciones con mayor incidencia',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('event_options_menu')->insert([
            'name' => 'Dirección de Servicios Técnicos->Disponibilidad de habitaciones->Reportes->Habitaciones no disponibles',
            'created_at' => now(),
            'updated_at' => now()
        ]);
//Gestión de mantenimiento
DB::table('event_options_menu')->insert([
    'name' => 'Dirección de Servicios Técnicos->Gestión de mantenimiento',
    'created_at' => now(),
    'updated_at' => now()
]);
//Disponibilidad de los sistemas técnologicos
DB::table('event_options_menu')->insert([
    'name' => 'Dirección de Servicios Técnicos->Disponibilidad de los sistemas técnologicos',
    'created_at' => now(),
    'updated_at' => now()
]);
//Dir. de Transporte
DB::table('event_options_menu')->insert([
    'name' => 'Dirección de Transporte->Flujo informativo',
    'created_at' => now(),
    'updated_at' => now()
]);
//Dir. de Higiene y Epidemiología
DB::table('event_options_menu')->insert([
    'name' => 'Dirección de Higiene y Epidemiología',
    'created_at' => now(),
    'updated_at' => now()
]);
//Configuraciones
        DB::table('event_options_menu')->insert([
            'name' => 'Configuraciones',
            'created_at' => now(),
            'updated_at' => now()
        ]);
//Seguridad
        DB::table('event_options_menu')->insert([
            'name' => 'Configuraciones->Seguridad',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('event_options_menu')->insert([
            'name' => 'Configuraciones->Seguridad->Gestionar roles',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('event_options_menu')->insert([
            'name' => 'Configuraciones->Seguridad->Gestionar personas',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('event_options_menu')->insert([
            'name' => 'Configuraciones->Seguridad->Listar usuarios SA',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('event_options_menu')->insert([
            'name' => 'Configuraciones->Seguridad->Ver trazas',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('event_options_menu')->insert([
            'name' => 'Configuraciones->Seguridad->ChangeContraseña',
            'created_at' => now(),
            'updated_at' => now()
        ]);
//Nomencladores
        DB::table('event_options_menu')->insert([
            'name' => 'Configuraciones->Nomencladores',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('event_options_menu')->insert([
            'name' => 'Configuraciones->Nomencladores->Gestionar renglones',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('event_options_menu')->insert([
            'name' => 'Configuraciones->Nomencladores->Gestionar indicadores',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('event_options_menu')->insert([
            'name' => 'Configuraciones->Nomencladores->Gestionar osde',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('event_options_menu')->insert([
            'name' => 'Configuraciones->Nomencladores->Gestionar entidades',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('event_options_menu')->insert([
            'name' => 'Configuraciones->Nomencladores->Gestionar provincias',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('event_options_menu')->insert([
            'name' => 'Configuraciones->Nomencladores->Gestionar municipios',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('event_options_menu')->insert([
            'name' => 'Configuraciones->Nomencladores->Gestionar tipos de instalaciones',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('event_options_menu')->insert([
            'name' => 'Configuraciones->Nomencladores->Gestionar sectores',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('event_options_menu')->insert([
            'name' => 'Ayuda->Ayuda',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('event_options_menu');
    }
}
