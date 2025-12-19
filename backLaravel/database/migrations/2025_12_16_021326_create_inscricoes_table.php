<?php
// database/migrations/2024_01_01_000005_create_inscricoes_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInscricoesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inscricoes', function (Blueprint $table) {
            $table->id();
            
            // Foreign Keys
            $table->foreignId('evento_id')
                  ->constrained('eventos')
                  ->onDelete('cascade');
            
            $table->foreignId('participante_id')
                  ->constrained('participantes')
                  ->onDelete('cascade');
            
            $table->foreignId('ingresso_id')
                  ->nullable()
                  ->constrained('ingressos')
                  ->onDelete('set null');
            
            // Campos
            $table->string('codigo_inscricao', 50)->unique();
            $table->enum('status', ['pendente', 'confirmado', 'cancelado'])->default('pendente');
            $table->decimal('valor_pago', 10, 2)->default(0);
            
            $table->timestamps();
            
         
            
            // Constraint única: um participante não pode ter 2 inscrições no mesmo evento
            $table->unique(['evento_id', 'participante_id'], 'unique_evento_participante');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inscricoes');
    }
}