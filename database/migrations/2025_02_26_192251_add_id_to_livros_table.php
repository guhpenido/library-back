<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIdToLivrosTable extends Migration
{
    public function up()
    {
        Schema::table('livros', function (Blueprint $table) {
            // Verifica se a coluna 'id' já existe
            if (!Schema::hasColumn('livros', 'id')) {
                $table->bigIncrements('id')->primary(); // Adiciona o 'id' como chave primária
            }
        });
    }

    public function down()
    {
        Schema::table('livros', function (Blueprint $table) {
            // Remove a coluna 'id', caso exista
            if (Schema::hasColumn('livros', 'id')) {
                $table->dropColumn('id');
            }
        });
    }
}
