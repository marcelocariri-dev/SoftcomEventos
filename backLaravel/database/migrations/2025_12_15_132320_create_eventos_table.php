<?php
// database/migrations/2024_01_01_000003_create_eventos_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eventos', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->text('descricao');
            $table->dateTime('data_inicio');
            $table->dateTime('data_fim');
            $table->foreignId('local_id')->constrained('locais')->onDelete('restrict');
            $table->integer('capacidade_maxima')->unsigned();
            $table->decimal('valor_padrao', 10, 2)->nullable()->default(0);
            $table->string('imagem')->nullable();
            $table->enum('status', ['rascunho', 'publicado', 'cancelado', 'finalizado'])->default('rascunho');
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict');
            $table->boolean('ativo')->default(true);
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
        Schema::dropIfExists('eventos');
    }
}