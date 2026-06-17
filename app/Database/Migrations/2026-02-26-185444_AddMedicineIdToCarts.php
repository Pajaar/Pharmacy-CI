<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddMedicineIdToCarts extends Migration
{
    public function up()
    {
        $fields = [
            'medicine_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true, 
                'null'       => false,
                'after'      => 'user_id',
            ],
            'quantity' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 1,
                'null'       => false,
                'after'      => 'medicine_id',
            ],
        ];

        $this->forge->addColumn('carts', $fields);

        $modifyFields = [
            'address_id' => [
                'name'       => 'address_id',
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'status' => [
                'name'       => 'status',
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true, 
                'default'    => 'in_cart',
            ]
        ];
        
        // Memodifikasi kolom yang sudah ada agar tidak error saat insert keranjang baru
        $this->forge->modifyColumn('carts', $modifyFields);
    }

    public function down()
    {
        $this->forge->dropColumn('carts', ['medicine_id', 'quantity']);
    }
}
