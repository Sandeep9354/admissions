<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('admissions', function (Blueprint $table) {
            // Rename column safely
            $table->renameColumn('class_applied', 'class');
        });
    }

    public function down()
    {
        Schema::table('admissions', function (Blueprint $table) {
            // Rollback rename
            $table->renameColumn('class', 'class_applied');
        });
    }
};
