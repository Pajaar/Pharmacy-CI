<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RemoveAddressAndStatusFromCarts extends Migration
{
    public function up()
    {
        $this->forge->dropForeignKey('carts', 'carts_address_id_foreign');

        $this->forge->dropColumn('carts', ['address_id', 'status']);
    }

    public function down()
    {
        // Mengembalikan kolom jika di-rollback
        $fields = [
            'address_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'status' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
                'default'    => 'in_cart',
            ],
        ];
        $this->forge->addColumn('carts', $fields);
    }
}
