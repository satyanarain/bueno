<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMealSpiceLevelTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
   public function up()
  {
    Schema::create('meal_spice_level', function (Blueprint $table) {
      $table->increments('id');
      $table->string('name');
      $table->string('status');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::drop('meal_spice_level');
  }
}
