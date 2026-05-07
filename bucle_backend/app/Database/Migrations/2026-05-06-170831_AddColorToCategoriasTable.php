<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddColorToCategoriasTable extends Migration
{
    public function up()
    {
        $this->forge->addColumn('categorias', [
            'color' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null'       => true,
                'after'      => 'emoji'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('categorias', 'color');
    }
}
