<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class UpdatePersonelRoleLabel extends Migration
{
    public function up()
    {
        DB::table('role')
            ->where('key', 3)
            ->update(['role' => 'Personel']);
    }

    public function down()
    {
        // The corrected terminology is intentionally retained on rollback.
    }
}
