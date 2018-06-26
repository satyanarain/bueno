<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddKitchenAddressColumnToKitchenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kitchens', function (Blueprint $table) {
            $table->string('kitchen_id');
            $table->string('unit_number');
            $table->string('address_line1');
            $table->string('address_line2');
            $table->string('sub_area');
            $table->string('longitude');
            $table->string('latitude');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kitchens', function (Blueprint $table) {
            $table->dropColumn('kitchen_id');
            $table->dropColumn('unit_number');
            $table->dropColumn('address_line1');
            $table->dropColumn('address_line2');
            $table->dropColumn('sub_area');
            $table->dropColumn('longitude');
            $table->dropColumn('latitude');
        });
    }
}
