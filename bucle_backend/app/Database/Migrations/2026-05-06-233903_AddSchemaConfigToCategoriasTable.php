<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSchemaConfigToCategoriasTable extends Migration
{
    public function up()
    {
        $fields = [
            'schema_config' => [
                'type' => 'JSON',
                'null' => true,
                'after' => 'color'
            ],
        ];
        $this->forge->addColumn('categorias', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('categorias', 'schema_config');
    }
}
