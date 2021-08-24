<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTenants extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tenants', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('telefone');
            $table->string('login');
            $table->string('senha');
            $table->tinyInteger('status');
            $table->timestamps();
        });

        Schema::create('planos', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->tinyInteger('status');
            $table->timestamps();
        });

        Schema::create('tenants_plano', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_tenant');
            $table->foreign('id_tenant')->references('id')->on('tenants');

            $table->unsignedBigInteger('id_plano');
            $table->foreign('id_plano')->references('id')->on('planos');

            $table->date('data_inicio')->nullable();
            $table->date('data_fim')->nullable();

            $table->tinyInteger('status');
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
        Schema::dropIfExists('tenants_plano');
        Schema::dropIfExists('planos');
        Schema::dropIfExists('tenants');
    }
}
