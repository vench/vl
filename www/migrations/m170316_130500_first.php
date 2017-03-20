<?php

use yii\db\Migration;

class m170316_130500_first extends Migration
{
    public function up()
    {
        $this->createTable('{{%user}}', [
            'id'    => 'pk',
            'name'  => 'varchar(128)',
            'password' => 'varchar(32)',
            'phone' => 'varchar(32)',
            'email' => 'varchar(64)',
            'role_id' => 'integer not null',
            'remoteToken'   => 'varchar(32)',
            'is_active'     => 'boolean default false',     
            
        ], 'engine=InnoDB, DEFAULT CHARSET=utf8');
        
        $this->createTable('{{%role}}', [
            'id'    => 'pk',
            'title'  => 'varchar(128)', 
        ], 'engine=InnoDB, DEFAULT CHARSET=utf8');
         
        $this->addForeignKey('FK_user_role', '{{%user}}', 'role_id', '{{%role}}', 'id', 'CASCADE', 'CASCADE');
        
        $this->insert('{{%role}}', [
            'title' => 'Aдминистратор',
        ]);
        $adminRoleId = $this->db->getLastInsertID();
        
        $this->insert('{{%role}}', [
            'title' => 'Пользователь',
        ]);
         
        $this->insert('{{%user}}', [
            'name'      => 'admin',
            'phone'     => '9110000000',
            'email'     => 'admin@test.ru',
            'role_id'   => $adminRoleId,
            'password'  => app\models\User::createHash('admin'),
            'is_active' => 1
        ]);
    }

    public function down()
    {
        
        $this->dropForeignKey('FK_user_role', '{{%user}}');
        $this->dropTable('{{%user}}');
        $this->dropTable('{{%role}}');
        
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
