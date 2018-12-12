<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInvoiceTaxFieldsToKitchenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kitchens', function (Blueprint $table) {
            $table->double('value_added_tax');
            $table->double('merchandise');
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
            $table->dropColumn('value_added_tax');
            $table->dropColumn('merchandise');
        });
    }
}
