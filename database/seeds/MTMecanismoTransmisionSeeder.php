<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class MTMecanismoTransmisionSeeder extends Seeder
{
  public function run()
  {
    DB::table('mtmecanismo_transmisions')->insert([
      'codigo' => 'HRY1',
      'mecanismo_transmision' => 'Directo',
      'created_at' => Carbon::now()
    ]);
    DB::table('mtmecanismo_transmisions')->insert([
      'codigo' => 'LBA2',
      'mecanismo_transmision' => 'Indirecto',
      'created_at' => Carbon::now()
    ]);
  }

  public function down()
  {
    Schema::dropIfExists('mtmecanismo_transmisions');
  }

}
