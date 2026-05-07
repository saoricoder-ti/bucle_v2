<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddEmojiToSubcategoriasTable extends Migration
{
    public function up()
    {
        $this->forge->addColumn('subcategorias', [
            'emoji' => [
                'type'       => 'VARCHAR',
                'constraint' => '10',
                'null'       => true,
                'after'      => 'nombre'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('subcategorias', 'emoji');
    }
}
