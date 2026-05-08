<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddColorToSubcategoriasTable extends Migration
{
    public function up()
    {
        $this->forge->addColumn('subcategorias', [
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
        $this->forge->dropColumn('subcategorias', 'color');
    }
}
