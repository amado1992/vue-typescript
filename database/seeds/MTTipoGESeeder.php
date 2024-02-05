<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class MTTipoGESeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $lists = [
            ['nombre' => 'Carrozado Sincronizado (CS)', 'codigo' => 'CS', 'created_at' => Carbon::now()],
            ['nombre' => 'Carrozado Automático (CA', 'codigo' => 'CA', 'created_at' => Carbon::now()],
            ['nombre' => 'Carrozado Manual (CM)', 'codigo' => 'CM', 'created_at' => Carbon::now()],
            ['nombre' => 'Descubierto Automático (DA)', 'codigo' => 'DA', 'created_at' => Carbon::now()],
            ['nombre' => 'Descubierto Manual (DM)', 'codigo' => 'DM', 'created_at' => Carbon::now()]];


        DB::table('mttiposge')->insert($lists);
    }
}
