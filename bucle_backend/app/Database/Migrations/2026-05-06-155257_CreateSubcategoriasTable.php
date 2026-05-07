<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSubcategoriasTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'categoria_id' => [
                'type'       => 'INT',
                'unsigned'   => true,
            ],
            'nombre' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'descripcion' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'datos_extra' => [
                'type' => 'JSONB',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('categoria_id', 'categorias', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('subcategorias');
    }

    public function down()
    {
        $this->forge->dropTable('subcategorias');
    }
}
