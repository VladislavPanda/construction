<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColumnForemanIdToProjectForemenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('project_foremen', function (Blueprint $table) {
            $table->dropForeign(['foreman_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('project_foremen', function (Blueprint $table) {
            $table->unsignedBigInteger('foreman_id')->nullable();
            $table->foreign('foreman_id')->on('users')->references('id');
        });
    }
}
