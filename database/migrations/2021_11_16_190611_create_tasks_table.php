<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('address');
            $table->string('title');
            $table->text('description');
            $table->string('start_date');
            $table->string('end_date');

            // Создаём внешний ключ для связи с таблицей пользователей
            $table->unsignedBigInteger('driver_id')->nullable();
            $table->foreign('driver_id')->on('users')->references('id'); 

            $table->timestamps();
            $table->softDeletes(); // "Мягкое удаление"
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tasks');
    }
}
