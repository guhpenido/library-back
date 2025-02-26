<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTimestampsToLivrosTable extends Migration
{
    public function up()
    {
        Schema::table('livros', function (Blueprint $table) {
            // Verifica se as colunas já existem antes de adicioná-las
            if (!Schema::hasColumn('livros', 'created_at')) {
                $table->timestamp('created_at')->nullable();
            }
            if (!Schema::hasColumn('livros', 'updated_at')) {
                $table->timestamp('updated_at')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('livros', function (Blueprint $table) {
            // Remove as colunas, caso existam
            $table->dropColumn(['created_at', 'updated_at']);
        });
    }
}
