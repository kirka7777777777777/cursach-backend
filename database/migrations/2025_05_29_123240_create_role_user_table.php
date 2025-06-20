<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoleUserTable extends Migration
{
    public function up()
    {
        Schema::create('role_user', function (Blueprint $table) {
            $table->id(); // Это создаст поле id с типом BIGINT(20) UNSIGNED
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('role_id'); // Убедитесь, что это поле имеет правильный тип данных
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('role_user');
    }
}

