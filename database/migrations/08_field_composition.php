<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::create('field_composition', function(Blueprint $table) {
			$table->id();
			$table->foreignId('field')->constrained('fields');
			$table->foreignId('license_area')->constrained('license_areas');	
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::dropIfExists('field_composition');
    }
};
