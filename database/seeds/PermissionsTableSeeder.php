<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $options_menu = DB::table('event_options_menu')
            ->where('name', '=', 'Dashboard')
            ->first();

        DB::table('permissions')->insert([
            'name' => 'ver_dashboard',
            'visible_name' => 'Ver dashboard',
            'guard_name' => 'web',
            'option_menu_id' => $options_menu->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        //<editor-fold defaultstate="collapsed" desc="Dirección de Calidad">
        $options_menu = DB::table('event_options_menu')
            ->where('name', '=', 'Dirección de Calidad')
            ->first();

        DB::table('permissions')->insert([
            'name' => 'ver_dir_calidad',
            'visible_name' => 'Ver dirección de calidad',
            'guard_name' => 'web',
            'option_menu_id' => $options_menu->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        //Costo de Calidad
        $options_menu = DB::table('event_options_menu')
            ->where('name', '=', 'Dirección de Calidad->Costo de Calidad')
            ->first();

        DB::table('permissions')->insert([
            'name' => 'gestionar_costo_calidad',
            'visible_name' => 'Gestionar costo calidad',
            'guard_name' => 'web',
            'option_menu_id' => $options_menu->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('permissions')->insert([
            'name' => 'ver_costo_calidad',
            'visible_name' => 'Ver costo calidad',
            'guard_name' => 'web',
            'option_menu_id' => $options_menu->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        $options_menu = DB::table('event_options_menu')
            ->where('name', '=', 'Dirección de Calidad->Costo de Calidad->Conformidad y no conformidad')
            ->first();

       /* DB::table('permissions')->insert([
            'name' => 'gestionar_costo_calidad',
            'guard_name' => 'web',
            'option_menu_id' => $options_menu->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);*/
        $options_menu = DB::table('event_options_menu')
            ->where('name', '=', 'Dirección de Calidad->Costo de Calidad->Conformidad y no conformidad calidad')
            ->first();

       /* DB::table('permissions')->insert([
            'name' => 'gestionar_costo_calidad',
            'guard_name' => 'web',
            'option_menu_id' => $options_menu->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);*/
        $options_menu = DB::table('event_options_menu')
            ->where('name', '=', 'Dirección de Calidad->Costo de Calidad->Gestión de reportes')
            ->first();

       /* DB::table('permissions')->insert([
            'name' => 'gestionar_costo_calidad',
            'guard_name' => 'web',
            'option_menu_id' => $options_menu->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);*/
        $options_menu = DB::table('event_options_menu')
            ->where('name', '=', 'Dirección de Calidad->Costo de Calidad->Reportes')
            ->first();

        /*DB::table('permissions')->insert([
            'name' => 'ver_costo_calidad',
            'guard_name' => 'web',
            'option_menu_id' => $options_menu->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);*/
        $options_menu = DB::table('event_options_menu')
            ->where('name', '=', 'Dirección de Calidad->Costo de Calidad->Reportes->Costos calidad por entidades')
            ->first();

        /*DB::table('permissions')->insert([
            'name' => 'ver_costo_calidad',
            'guard_name' => 'web',
            'option_menu_id' => $options_menu->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);*/
        $options_menu = DB::table('event_options_menu')
            ->where('name', '=', 'Dirección de Calidad->Costo de Calidad->Reportes->Indicadores por entidades')
            ->first();

        /*DB::table('permissions')->insert([
            'name' => 'ver_costo_calidad',
            'guard_name' => 'web',
            'option_menu_id' => $options_menu->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);*/
        $options_menu = DB::table('event_options_menu')
            ->where('name', '=', 'Dirección de Calidad->Costo de Calidad->Reportes->Totales de costos Calidad')
            ->first();

        /*DB::table('permissions')->insert([
            'name' => 'ver_costo_calidad',
            'guard_name' => 'web',
            'option_menu_id' => $options_menu->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);*/
        $options_menu = DB::table('event_options_menu')
            ->where('name', '=', 'Dirección de Calidad->Costo de Calidad->Reportes->Costos por entidades')
            ->first();

        /*DB::table('permissions')->insert([
            'name' => 'ver_costo_calidad',
            'guard_name' => 'web',
            'option_menu_id' => $options_menu->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);*/
        // Turismo mas Higienico y Seguro
        $options_menu = DB::table('event_options_menu')
            ->where('name', '=', 'Dirección de Calidad->Turismo más higiénico y seguro')
            ->first();

        DB::table('permissions')->insert([
            'name' => 'gestionar_ths',
            'visible_name' => 'Gestionar turismo más higiénico y seguro',
            'guard_name' => 'web',
            'option_menu_id' => $options_menu->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('permissions')->insert([
            'name' => 'ver_ths',
            'visible_name' => 'Ver turismo más higiénico y seguro',
            'guard_name' => 'web',
            'option_menu_id' => $options_menu->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        $options_menu = DB::table('event_options_menu')
            ->where('name', '=', 'Dirección de Calidad->Turismo más higiénico y seguro->Turismo más higiénico y seguro')
            ->first();

       /* DB::table('permissions')->insert([
            'name' => 'gestionar_ths',
            'guard_name' => 'web',
            'option_menu_id' => $options_menu->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);*/
        $options_menu = DB::table('event_options_menu')
            ->where('name', '=', 'Dirección de Calidad->Turismo más higiénico y seguro->Expedientes del proceso T+HS')
            ->first();

        /*DB::table('permissions')->insert([
            'name' => 'gestionar_ths',
            'guard_name' => 'web',
            'option_menu_id' => $options_menu->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);*/
        $options_menu = DB::table('event_options_menu')
            ->where('name', '=', 'Dirección de Calidad->Turismo más higiénico y seguro->Seguimiento del proceso T+HS')
            ->first();

       /* DB::table('permissions')->insert([
            'name' => 'gestionar_ths',
            'guard_name' => 'web',
            'option_menu_id' => $options_menu->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);*/
        $options_menu = DB::table('event_options_menu')
            ->where('name', '=', 'Dirección de Calidad->Turismo más higiénico y seguro->Reportes')
            ->first();

        /*DB::table('permissions')->insert([
            'name' => 'ver_ths',
            'guard_name' => 'web',
            'option_menu_id' => $options_menu->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);*/
        $options_menu = DB::table('event_options_menu')
            ->where('name', '=', 'Dirección de Calidad->Turismo más higiénico y seguro->Reportes')
            ->first();

        /*DB::table('permissions')->insert([
            'name' => 'ver_ths',
            'guard_name' => 'web',
            'option_menu_id' => $options_menu->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);*/
        // Premios y Distinciones
        $options_menu = DB::table('event_options_menu')
            ->where('name', '=', 'Dirección de Calidad->Premios y Distinciones')
            ->first();

        DB::table('permissions')->insert([
            'name' => 'gestionar_premios',
            'visible_name' => 'Gestionar premio',
            'guard_name' => 'web',
            'option_menu_id' => $options_menu->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('permissions')->insert([
            'name' => 'ver_premios',
            'visible_name' => 'Ver premio',
            'guard_name' => 'web',
            'option_menu_id' => $options_menu->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        $options_menu = DB::table('event_options_menu')
            ->where('name', '=', 'Dirección de Calidad->Premios y Distinciones->Gestionar premios y distinciones')
            ->first();

        /*DB::table('permissions')->insert([
            'name' => 'gestionar_premios',
            'guard_name' => 'web',
            'option_menu_id' => $options_menu->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);*/
        $options_menu = DB::table('event_options_menu')
            ->where('name', '=', 'Dirección de Calidad->Premios y Distinciones->Reportes')
            ->first();

        /*DB::table('permissions')->insert([
            'name' => 'ver_premios',
            'guard_name' => 'web',
            'option_menu_id' => $options_menu->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);*/
        $options_menu = DB::table('event_options_menu')
            ->where('name', '=', 'Dirección de Calidad->Premios y Distinciones->Reportes->Cant. de premios por OSDE')
            ->first();

       /* DB::table('permissions')->insert([
            'name' => 'ver_premios',
            'guard_name' => 'web',
            'option_menu_id' => $options_menu->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);*/
        $options_menu = DB::table('event_options_menu')
            ->where('name', '=', 'Dirección de Calidad->Premios y Distinciones->Reportes->Cant. de premios por entidad')
            ->first();

        /*DB::table('permissions')->insert([
            'name' => 'ver_premios',
            'guard_name' => 'web',
            'option_menu_id' => $options_menu->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);*/
        $options_menu = DB::table('event_options_menu')
            ->where('name', '=', 'Dirección de Calidad->Premios y Distinciones->Reportes->% de instalaciones premiadas')
            ->first();

        /*DB::table('permissions')->insert([
            'name' => 'ver_premios',
            'guard_name' => 'web',
            'option_menu_id' => $options_menu->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);*/
        // Movimiento Líderes de Calidad
        $options_menu = DB::table('event_options_menu')
            ->where('name', '=', 'Dirección de Calidad->Movimiento Líderes de Calidad')
            ->first();

        DB::table('permissions')->insert([
            'name' => 'gestionar_lideres_calidad',
            'visible_name' => 'Gestionar líder calidad',
            'guard_name' => 'web',
            'option_menu_id' => $options_menu->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('permissions')->insert([
            'name' => 'ver_lideres_calidad',
            'visible_name' => 'Ver líder calidad',
            'guard_name' => 'web',
            'option_menu_id' => $options_menu->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        $options_menu = DB::table('event_options_menu')
            ->where('name', '=', 'Dirección de Calidad->Movimiento Líderes de Calidad->Gestionar colectivos líderes de la calidad')
            ->first();

       /* DB::table('permissions')->insert([
            'name' => 'gestionar_lideres_calidad',
            'guard_name' => 'web',
            'option_menu_id' => $options_menu->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);*/
        $options_menu = DB::table('event_options_menu')
            ->where('name', '=', 'Dirección de Calidad->Movimiento Líderes de Calidad->Gestionar propuestas de líderes de la calidad')
            ->first();

        /*DB::table('permissions')->insert([
            'name' => 'gestionar_lideres_calidad',
            'guard_name' => 'web',
            'option_menu_id' => $options_menu->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);*/
        $options_menu = DB::table('event_options_menu')
            ->where('name', '=', 'Dirección de Calidad->Movimiento Líderes de Calidad->Reportes')
            ->first();

        /*DB::table('permissions')->insert([
            'name' => 'ver_lideres_calidad',
            'guard_name' => 'web',
            'option_menu_id' => $options_menu->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);*/
        $options_menu = DB::table('event_options_menu')
            ->where('name', '=', 'Dirección de Calidad->Movimiento Líderes de Calidad->Reportes->Listado de colectivos por estados')
            ->first();

        /*DB::table('permissions')->insert([
            'name' => 'ver_lideres_calidad',
            'guard_name' => 'web',
            'option_menu_id' => $options_menu->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);*/
        $options_menu = DB::table('event_options_menu')
            ->where('name', '=', 'Dirección de Calidad->Movimiento Líderes de Calidad->Reportes->Listado de colectivos por filtros')
            ->first();

       /* DB::table('permissions')->insert([
            'name' => 'ver_lideres_calidad',
            'guard_name' => 'web',
            'option_menu_id' => $options_menu->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);*/
        $options_menu = DB::table('event_options_menu')
            ->where('name', '=', 'Dirección de Calidad->Movimiento Líderes de Calidad->Reportes->Listado de propuestas de líderes por estados')
            ->first();

        /*DB::table('permissions')->insert([
            'name' => 'ver_lideres_calidad',
            'guard_name' => 'web',
            'option_menu_id' => $options_menu->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);*/
        //Sistema de gestión de la calidad
        $options_menu = DB::table('event_options_menu')
            ->where('name', '=', 'Dirección de Calidad->Sistema de gestión de la calidad')
            ->first();

        DB::table('permissions')->insert([
            'name' => 'gestionar_sist_gestcalidad',
            'visible_name' => 'Gestionar sistema de gestión de la calidad',
            'guard_name' => 'web',
            'option_menu_id' => $options_menu->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('permissions')->insert([
            'name' => 'ver_sist_gestcalidad',
            'visible_name' => 'Ver sistema de gestión de la calidad',
            'guard_name' => 'web',
            'option_menu_id' => $options_menu->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        //Indicadores lineamientos
        $options_menu = DB::table('event_options_menu')
            ->where('name', '=', 'Dirección de Calidad->Indicadores lineamientos')
            ->first();

        DB::table('permissions')->insert([
            'name' => 'gestionar_indicadores_lineamientos',
            'visible_name' => 'Gestionar indicadores lineamiento',
            'guard_name' => 'web',
            'option_menu_id' => $options_menu->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('permissions')->insert([
            'name' => 'ver_indicadores_lineamientos',
            'visible_name' => 'Ver indicadores lineamiento',
            'guard_name' => 'web',
            'option_menu_id' => $options_menu->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        //</editor-fold>

        //<editor-fold defaultstate="collapsed" desc="Dirección de Logistica">
        $options_menu = DB::table('event_options_menu')
            ->where('name', '=', 'Dirección de Logistica')
            ->first();

        DB::table('permissions')->insert([
            'name' => 'ver_dir_logistica',
            'visible_name' => 'Ver dirección de logística',
            'guard_name' => 'web',
            'option_menu_id' => $options_menu->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        //Flujo material
        $options_menu = DB::table('event_options_menu')
            ->where('name', '=', 'Dirección de Logística->Flujo material')
            ->first();

        DB::table('permissions')->insert([
            'name' => 'gestionar_flujo_material',
            'visible_name' => 'Gestionar flujo material',
            'guard_name' => 'web',
            'option_menu_id' => $options_menu->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('permissions')->insert([
            'name' => 'ver_flujo_material',
            'visible_name' => 'Ver flujo material',
            'guard_name' => 'web',
            'option_menu_id' => $options_menu->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        $options_menu = DB::table('event_options_menu')
            ->where('name', '=', 'Dirección de Logística->Flujo material->Gestionar familias')
            ->first();

       /* DB::table('permissions')->insert([
            'name' => 'gestionar_flujo_material',
            'guard_name' => 'web',
            'option_menu_id' => $options_menu->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);*/
        $options_menu = DB::table('event_options_menu')
            ->where('name', '=', 'Dirección de Logística->Flujo material->Gestionar productos')
            ->first();

        /*DB::table('permissions')->insert([
            'name' => 'gestionar_flujo_material',
            'guard_name' => 'web',
            'option_menu_id' => $options_menu->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);*/
        $options_menu = DB::table('event_options_menu')
            ->where('name', '=', 'Dirección de Logística->Flujo material->Reportes')
            ->first();

        /*DB::table('permissions')->insert([
            'name' => 'ver_flujo_material',
            'guard_name' => 'web',
            'option_menu_id' => $options_menu->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);*/
        $options_menu = DB::table('event_options_menu')
            ->where('name', '=', 'Dirección de Logística->Flujo material->Listado productos por familia')
            ->first();

        /*DB::table('permissions')->insert([
            'name' => 'ver_flujo_material',
            'guard_name' => 'web',
            'option_menu_id' => $options_menu->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);*/
        //Flujo informativo
        $options_menu = DB::table('event_options_menu')
            ->where('name', '=', 'Dirección de Logística->Flujo informativo')
            ->first();

        DB::table('permissions')->insert([
            'name' => 'gestionar_flujo_informativo',
            'visible_name' => 'Gestionar flujo informativo',
            'guard_name' => 'web',
            'option_menu_id' => $options_menu->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('permissions')->insert([
            'name' => 'ver_flujo_informativo',
            'visible_name' => 'Ver flujo informativo',
            'guard_name' => 'web',
            'option_menu_id' => $options_menu->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        $options_menu = DB::table('event_options_menu')
            ->where('name', '=', 'Dirección de Logística->Flujo informativo->Gestionar compras')
            ->first();

        /*DB::table('permissions')->insert([
            'name' => 'gestionar_flujo_informativo',
            'guard_name' => 'web',
            'option_menu_id' => $options_menu->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);*/
        $options_menu = DB::table('event_options_menu')
            ->where('name', '=', 'Dirección de Logística->Flujo informativo->Gestionar proveedor')
            ->first();

        /*DB::table('permissions')->insert([
            'name' => 'gestionar_flujo_informativo',
            'guard_name' => 'web',
            'option_menu_id' => $options_menu->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);*/
        $options_menu = DB::table('event_options_menu')
            ->where('name', '=', 'Dirección de Logística->Flujo informativo->Nomencladores->Tipo proveedor')
            ->first();

        /*DB::table('permissions')->insert([
            'name' => 'gestionar_flujo_informativo',
            'guard_name' => 'web',
            'option_menu_id' => $options_menu->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);*/
        $options_menu = DB::table('event_options_menu')
            ->where('name', '=', 'Dirección de Logística->Flujo informativo->Nomencladores->Unidad de medida')
            ->first();

        /*DB::table('permissions')->insert([
            'name' => 'gestionar_flujo_informativo',
            'guard_name' => 'web',
            'option_menu_id' => $options_menu->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);*/
        //Importaciones
        $options_menu = DB::table('event_options_menu')
            ->where('name', '=', 'Dirección de Logística->Importaciones')
            ->first();

        DB::table('permissions')->insert([
            'name' => 'gestionar_importaciones',
            'visible_name' => 'Gestionar importación',
            'guard_name' => 'web',
            'option_menu_id' => $options_menu->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('permissions')->insert([
            'name' => 'ver_importaciones',
            'visible_name' => 'Ver importación',
            'guard_name' => 'web',
            'option_menu_id' => $options_menu->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        $options_menu = DB::table('event_options_menu')
            ->where('name', '=', 'Dirección de Logística->Importaciones->Gestionar importaciones')
            ->first();

        /*DB::table('permissions')->insert([
            'name' => 'gestionar_importaciones',
            'guard_name' => 'web',
            'option_menu_id' => $options_menu->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);*/
        $options_menu = DB::table('event_options_menu')
            ->where('name', '=', 'Dirección de Logística->Importaciones->Reportes')
            ->first();

       /* DB::table('permissions')->insert([
            'name' => 'ver_importaciones',
            'guard_name' => 'web',
            'option_menu_id' => $options_menu->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);*/
        $options_menu = DB::table('event_options_menu')
            ->where('name', '=', 'Dirección de Logística->Importaciones->Reportes->Plan anual de importaciones')
            ->first();

       /* DB::table('permissions')->insert([
            'name' => 'ver_importaciones',
            'guard_name' => 'web',
            'option_menu_id' => $options_menu->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);*/
        $options_menu = DB::table('event_options_menu')
            ->where('name', '=', 'Dirección de Logística->Importaciones->Reportes->Plan anual por indicador')
            ->first();

       /* DB::table('permissions')->insert([
            'name' => 'ver_importaciones',
            'guard_name' => 'web',
            'option_menu_id' => $options_menu->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);*/
        $options_menu = DB::table('event_options_menu')
            ->where('name', '=', 'Dirección de Logística->Importaciones->Reportes->Plan anual por entidad')
            ->first();

       /* DB::table('permissions')->insert([
            'name' => 'ver_importaciones',
            'guard_name' => 'web',
            'option_menu_id' => $options_menu->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);*/
        //Almacenes
        $options_menu = DB::table('event_options_menu')
            ->where('name', '=', 'Dirección de Logística->Almacenes')
            ->first();

        DB::table('permissions')->insert([
            'name' => 'gestionar_almacenes',
            'visible_name' => 'Gestionar almacén',
            'guard_name' => 'web',
            'option_menu_id' => $options_menu->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('permissions')->insert([
            'name' => 'ver_almacenes',
            'visible_name' => 'Ver almacén',
            'guard_name' => 'web',
            'option_menu_id' => $options_menu->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Dirección de Servicios Técnicos">
        $options_menu = DB::table('event_options_menu')
            ->where('name', '=', 'Dirección de Servicios Técnicos')
            ->first();

        DB::table('permissions')->insert([
            'name' => 'ver_dir_serviciostec',
            'visible_name' => 'Ver dirección de servicios técnicos',
            'guard_name' => 'web',
            'option_menu_id' => $options_menu->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        //Disponibilidad de habitaciones
        $options_menu = DB::table('event_options_menu')
            ->where('name', '=', 'Dirección de Servicios Técnicos->Disponibilidad de habitaciones')
            ->first();

        DB::table('permissions')->insert([
            'name' => 'gestionar_disp_habitaciones',
            'visible_name' => 'Gestionar disponibilidad de habitaciones',
            'guard_name' => 'web',
            'option_menu_id' => $options_menu->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('permissions')->insert([
            'name' => 'ver_disp_habitaciones',
            'visible_name' => 'Ver disponibilidad de habitaciones',
            'guard_name' => 'web',
            'option_menu_id' => $options_menu->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        $options_menu = DB::table('event_options_menu')
            ->where('name', '=', 'Dirección de Servicios Técnicos->Disponibilidad de habitaciones->Gestionar no disponibilidad')
            ->first();

       /* DB::table('permissions')->insert([
            'name' => 'gestionar_disp_habitaciones',
            'guard_name' => 'web',
            'option_menu_id' => $options_menu->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);*/
        /*DB::table('permissions')->insert([
            'name' => 'ver_disp_habitaciones',
            'guard_name' => 'web',
            'option_menu_id' => $options_menu->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);*/
        $options_menu = DB::table('event_options_menu')
            ->where('name', '=', 'Dirección de Servicios Técnicos->Disponibilidad de habitaciones->Reportes')
            ->first();

        /*DB::table('permissions')->insert([
            'name' => 'ver_disp_habitaciones',
            'guard_name' => 'web',
            'option_menu_id' => $options_menu->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);*/
        $options_menu = DB::table('event_options_menu')
            ->where('name', '=', 'Dirección de Servicios Técnicos->Disponibilidad de habitaciones->Reportes->Disponibilidad menor o igual al 95%')
            ->first();

        /*DB::table('permissions')->insert([
            'name' => 'ver_disp_habitaciones',
            'guard_name' => 'web',
            'option_menu_id' => $options_menu->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);*/
        $options_menu = DB::table('event_options_menu')
            ->where('name', '=', 'Dirección de Servicios Técnicos->Disponibilidad de habitaciones->Reportes->Habitaciones con mayor incidencia')
            ->first();

        /*DB::table('permissions')->insert([
            'name' => 'ver_disp_habitaciones',
            'guard_name' => 'web',
            'option_menu_id' => $options_menu->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);*/
        $options_menu = DB::table('event_options_menu')
            ->where('name', '=', 'Dirección de Servicios Técnicos->Disponibilidad de habitaciones->Reportes->Habitaciones no disponibles')
            ->first();

        /*DB::table('permissions')->insert([
            'name' => 'ver_disp_habitaciones',
            'guard_name' => 'web',
            'option_menu_id' => $options_menu->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);*/
        //Gestión de mantenimiento
        $options_menu = DB::table('event_options_menu')
            ->where('name', '=', 'Dirección de Servicios Técnicos->Gestión de mantenimiento')
            ->first();

        DB::table('permissions')->insert([
            'name' => 'gestion_mantenimiento',
            'visible_name' => 'Gestionar mantenimiento',
            'guard_name' => 'web',
            'option_menu_id' => $options_menu->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('permissions')->insert([
            'name' => 'ver_mantenimiento',
            'visible_name' => 'Ver mantenimiento',
            'guard_name' => 'web',
            'option_menu_id' => $options_menu->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        //Disponibilidad de los sistemas técnologicos
        $options_menu = DB::table('event_options_menu')
            ->where('name', '=', 'Dirección de Servicios Técnicos->Disponibilidad de los sistemas técnologicos')
            ->first();

        DB::table('permissions')->insert([
            'name' => 'gestion_sistemas_tec',
            'visible_name' => 'Gestionar sistemas tecnológicos',
            'guard_name' => 'web',
            'option_menu_id' => $options_menu->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('permissions')->insert([
            'name' => 'ver_sistemas_tec',
            'visible_name' => 'Ver sistemas tecnológicos',
            'guard_name' => 'web',
            'option_menu_id' => $options_menu->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        //</editor-fold>
        //Dirección de Transporte
        $options_menu = DB::table('event_options_menu')
            ->where('name', '=', 'Dirección de Transporte->Flujo informativo')
            ->first();

        DB::table('permissions')->insert([
            'name' => 'gestionar_dir_transporte_fi',
            'visible_name' => 'Gestionar dirección de transporte flujo informativo',
            'guard_name' => 'web',
            'option_menu_id' => $options_menu->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('permissions')->insert([
            'name' => 'eliminar_dir_transporte_fi',
            'visible_name' => 'Eliminar dirección de transporte flujo informativo',
            'guard_name' => 'web',
            'option_menu_id' => $options_menu->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('permissions')->insert([
            'name' => 'ver_dir_transporte_fi',
            'visible_name' => 'Ver dirección de transporte flujo informativo',
            'guard_name' => 'web',
            'option_menu_id' => $options_menu->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        //</editor-fold>

      //<editor-fold defaultstate="collapsed" desc="Dirección de Higiene y Epidemiología">
      $options_menu = DB::table('event_options_menu')
        ->where('name', '=', 'Dirección de Higiene y Epidemiología')
        ->first();

      DB::table('permissions')->insert([
        'name' => 'gestionar_dir_higienico_epidemiologico',
        'visible_name' => 'Gestionar dirección de higiene y epidemiología',
        'guard_name' => 'web',
        'option_menu_id' => $options_menu->id,
        'created_at' => now(),
        'updated_at' => now()
      ]);
      DB::table('permissions')->insert([
        'name' => 'eliminar_dir_higienico_epidemiologico',
        'visible_name' => 'Eliminar dirección de higiene y epidemiología',
        'guard_name' => 'web',
        'option_menu_id' => $options_menu->id,
        'created_at' => now(),
        'updated_at' => now()
      ]);
      DB::table('permissions')->insert([
        'name' => 'ver_dir_higienico_epidemiologico',
          'visible_name' => 'Ver dirección de higiene y epidemiología',
        'guard_name' => 'web',
        'option_menu_id' => $options_menu->id,
        'created_at' => now(),
        'updated_at' => now()
      ]);
      //</editor-fold>

        //Configuraciones
        $options_menu = DB::table('event_options_menu')
            ->where('name', '=', 'Configuraciones')
            ->first();

        DB::table('permissions')->insert([
            'name' => 'ver_configuraciones',
            'visible_name' => 'Ver configuración',
            'guard_name' => 'web',
            'option_menu_id' => $options_menu->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        //Seguridad
        $options_menu = DB::table('event_options_menu')
            ->where('name', '=', 'Configuraciones->Seguridad')
            ->first();

        DB::table('permissions')->insert([
            'name' => 'seguridad',
            'visible_name' => 'Seguridad',
            'guard_name' => 'web',
            'option_menu_id' => $options_menu->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        $options_menu = DB::table('event_options_menu')
            ->where('name', '=', 'Configuraciones->Seguridad->Gestionar roles')
            ->first();

        /*DB::table('permissions')->insert([
            'name' => 'seguridad',
            'guard_name' => 'web',
            'option_menu_id' => $options_menu->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);*/
        $options_menu = DB::table('event_options_menu')
            ->where('name', '=', 'Configuraciones->Seguridad->Gestionar personas')
            ->first();

       /* DB::table('permissions')->insert([
            'name' => 'seguridad',
            'guard_name' => 'web',
            'option_menu_id' => $options_menu->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);*/
        $options_menu = DB::table('event_options_menu')
            ->where('name', '=', 'Configuraciones->Seguridad->Listar usuarios SA')
            ->first();

       /* DB::table('permissions')->insert([
            'name' => 'seguridad',
            'guard_name' => 'web',
            'option_menu_id' => $options_menu->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);*/
        $options_menu = DB::table('event_options_menu')
            ->where('name', '=', 'Configuraciones->Seguridad->Ver trazas')
            ->first();

       /* DB::table('permissions')->insert([
            'name' => 'seguridad',
            'guard_name' => 'web',
            'option_menu_id' => $options_menu->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);*/
        $options_menu = DB::table('event_options_menu')
            ->where('name', '=', 'Configuraciones->Seguridad->ChangeContraseña')
            ->first();

        DB::table('permissions')->insert([
            'name' => 'seguridad_passw',
            'visible_name' => 'Seguridad contraseña',
            'guard_name' => 'web',
            'option_menu_id' => $options_menu->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        //Nomencladores
        $options_menu = DB::table('event_options_menu')
            ->where('name', '=', 'Configuraciones->Nomencladores')
            ->first();

        DB::table('permissions')->insert([
            'name' => 'nomencladores',
            'visible_name' => 'Nomenclador',
            'guard_name' => 'web',
            'option_menu_id' => $options_menu->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        $options_menu = DB::table('event_options_menu')
            ->where('name', '=', 'Configuraciones->Nomencladores->Gestionar renglones')
            ->first();

       /* DB::table('permissions')->insert([
            'name' => 'nomencladores',
            'guard_name' => 'web',
            'option_menu_id' => $options_menu->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);*/
        $options_menu = DB::table('event_options_menu')
            ->where('name', '=', 'Configuraciones->Nomencladores->Gestionar indicadores')
            ->first();

       /* DB::table('permissions')->insert([
            'name' => 'nomencladores',
            'guard_name' => 'web',
            'option_menu_id' => $options_menu->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);*/
        $options_menu = DB::table('event_options_menu')
            ->where('name', '=', 'Configuraciones->Nomencladores->Gestionar osde')
            ->first();

        /*DB::table('permissions')->insert([
            'name' => 'nomencladores',
            'guard_name' => 'web',
            'option_menu_id' => $options_menu->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);*/
        $options_menu = DB::table('event_options_menu')
            ->where('name', '=', 'Configuraciones->Nomencladores->Gestionar entidades')
            ->first();

        /*DB::table('permissions')->insert([
            'name' => 'nomencladores',
            'guard_name' => 'web',
            'option_menu_id' => $options_menu->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);*/
        $options_menu = DB::table('event_options_menu')
            ->where('name', '=', 'Configuraciones->Nomencladores->Gestionar provincias')
            ->first();

        /*DB::table('permissions')->insert([
            'name' => 'nomencladores',
            'guard_name' => 'web',
            'option_menu_id' => $options_menu->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);*/
        $options_menu = DB::table('event_options_menu')
            ->where('name', '=', 'Configuraciones->Nomencladores->Gestionar municipios')
            ->first();

        /*DB::table('permissions')->insert([
            'name' => 'nomencladores',
            'guard_name' => 'web',
            'option_menu_id' => $options_menu->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);*/
        $options_menu = DB::table('event_options_menu')
            ->where('name', '=', 'Configuraciones->Nomencladores->Gestionar tipos de instalaciones')
            ->first();

        /*DB::table('permissions')->insert([
            'name' => 'nomencladores',
            'guard_name' => 'web',
            'option_menu_id' => $options_menu->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);*/
        $options_menu = DB::table('event_options_menu')
            ->where('name', '=', 'Configuraciones->Nomencladores->Gestionar sectores')
            ->first();

        /*DB::table('permissions')->insert([
            'name' => 'nomencladores',
            'visible_name' => 'Nomenclador',
            'guard_name' => 'web',
            'option_menu_id' => $options_menu->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);*/
        $options_menu = DB::table('event_options_menu')
            ->where('name', '=', 'Ayuda->Ayuda')
            ->first();

        DB::table('permissions')->insert([
            'name' => 'ver_ayuda',
            'visible_name' => 'Ver ayuda',
            'guard_name' => 'web',
            'option_menu_id' => $options_menu->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('permissions');
    }
}
