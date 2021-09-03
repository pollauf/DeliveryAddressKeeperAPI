<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Base extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_tenant');
            $table->foreign('id_tenant')->references('id')->on('tenants');

            $table->string('nome');
            $table->string('login');
            $table->string('senha');
            $table->string('token');

            $table->tinyInteger('status');
            $table->timestamps();
        });

        Schema::create('clientes_delivery', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_tenant');
            $table->foreign('id_tenant')->references('id')->on('tenants');

            $table->string('nome');
            $table->string('celular', 25);
            $table->string('endereco');
            $table->string('numero', 10);
            $table->string('complemento');
            $table->string('bairro', 100);
            $table->string('cidade', 100);
            $table->string('estado', 50);

            $table->tinyInteger('origem');

            $table->tinyInteger('status');
            $table->timestamps();
        });

        Schema::create('notif_cad_cli_delivery', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_tenant');
            $table->foreign('id_tenant')->references('id')->on('tenants');

            $table->unsignedBigInteger('id_cliente_delivery');
            $table->foreign('id_cliente_delivery')->references('id')->on('clientes_delivery');

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
        Schema::dropIfExists('notif_cad_cli_delivery');
        Schema::dropIfExists('clientes_delivery');
        Schema::dropIfExists('usuarios');
    }
}
