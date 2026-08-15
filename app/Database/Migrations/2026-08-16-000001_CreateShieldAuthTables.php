<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateShieldAuthTables extends Migration
{
    public function up()
    {
        // -------------------------------------------------------------
        // 1. Ensure `users` table has `id_guru` column
        // -------------------------------------------------------------
        if ($this->db->tableExists('users') && !$this->db->fieldExists('id_guru', 'users')) {
            $this->forge->addColumn('users', [
                'id_guru' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'null'       => true,
                    'default'    => null,
                    'after'      => 'id',
                ],
            ]);
        }

        // -------------------------------------------------------------
        // 2. Table: auth_identities
        // -------------------------------------------------------------
        if (!$this->db->tableExists('auth_identities')) {
            $this->forge->addField([
                'id' => [
                    'type'           => 'INT',
                    'constraint'     => 11,
                    'unsigned'       => true,
                    'auto_increment' => true,
                ],
                'user_id' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'unsigned'   => true,
                ],
                'type' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 255,
                ],
                'name' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 255,
                    'null'       => true,
                ],
                'secret' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 255,
                ],
                'secret2' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 255,
                    'null'       => true,
                ],
                'expires' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
                'extra' => [
                    'type' => 'TEXT',
                    'null' => true,
                ],
                'force_reset' => [
                    'type'       => 'TINYINT',
                    'constraint' => 1,
                    'default'    => 0,
                ],
                'last_used_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
                'created_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
                'updated_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
            ]);
            $this->forge->addKey('id', true);
            $this->forge->addUniqueKey(['type', 'secret'], 'type_secret');
            $this->forge->addKey('user_id');
            $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
            $this->forge->createTable('auth_identities', true);
        }

        // -------------------------------------------------------------
        // 3. Table: auth_groups_users
        // -------------------------------------------------------------
        if (!$this->db->tableExists('auth_groups_users')) {
            $this->forge->addField([
                'id' => [
                    'type'           => 'INT',
                    'constraint'     => 11,
                    'unsigned'       => true,
                    'auto_increment' => true,
                ],
                'user_id' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'unsigned'   => true,
                ],
                'group' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 255,
                ],
                'created_at' => [
                    'type' => 'DATETIME',
                ],
            ]);
            $this->forge->addKey('id', true);
            $this->forge->addKey('user_id');
            $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
            $this->forge->createTable('auth_groups_users', true);
        }

        // -------------------------------------------------------------
        // 4. Table: auth_permissions_users
        // -------------------------------------------------------------
        if (!$this->db->tableExists('auth_permissions_users')) {
            $this->forge->addField([
                'id' => [
                    'type'           => 'INT',
                    'constraint'     => 11,
                    'unsigned'       => true,
                    'auto_increment' => true,
                ],
                'user_id' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'unsigned'   => true,
                ],
                'permission' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 255,
                ],
                'created_at' => [
                    'type' => 'DATETIME',
                ],
            ]);
            $this->forge->addKey('id', true);
            $this->forge->addKey('user_id');
            $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
            $this->forge->createTable('auth_permissions_users', true);
        }

        // -------------------------------------------------------------
        // 5. Table: auth_remember_tokens
        // -------------------------------------------------------------
        if (!$this->db->tableExists('auth_remember_tokens')) {
            $this->forge->addField([
                'id' => [
                    'type'           => 'INT',
                    'constraint'     => 11,
                    'unsigned'       => true,
                    'auto_increment' => true,
                ],
                'selector' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 255,
                ],
                'hashedValidator' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 255,
                ],
                'user_id' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'unsigned'   => true,
                ],
                'expires' => [
                    'type' => 'DATETIME',
                ],
                'created_at' => [
                    'type' => 'DATETIME',
                ],
                'updated_at' => [
                    'type' => 'DATETIME',
                ],
            ]);
            $this->forge->addKey('id', true);
            $this->forge->addUniqueKey('selector');
            $this->forge->addKey('user_id');
            $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
            $this->forge->createTable('auth_remember_tokens', true);
        }
    }

    public function down()
    {
        $this->forge->dropTable('auth_remember_tokens', true);
        $this->forge->dropTable('auth_permissions_users', true);
        $this->forge->dropTable('auth_groups_users', true);
        $this->forge->dropTable('auth_identities', true);

        if ($this->db->tableExists('users') && $this->db->fieldExists('id_guru', 'users')) {
            $this->forge->dropColumn('users', 'id_guru');
        }
    }
}
