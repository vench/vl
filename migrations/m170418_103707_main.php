<?php

use yii\db\Migration;

class m170418_103707_main extends Migration {

    public function safeUp() {

        $this->createTable('user', [
            'id' => 'pk',
            'username' => 'varchar(32)',
            'password' => 'varchar(255)',
            'authKey' => 'varchar(128)',
            'accessToken' => 'varchar(128)',
        ]);

        $users = [
            '100' => [
                'id' => '100',
                'username' => 'admin',
                'password' => \app\models\User::passwordHash( 'admin' ),
                'authKey' => 'test100key',
                'accessToken' => '100-token',
            ],
            '101' => [
                'id' => '101',
                'username' => 'demo',
                'password' => \app\models\User::passwordHash( 'demo' ),
                'authKey' => 'test101key',
                'accessToken' => '101-token',
            ],
        ];

        foreach ($users as $user) {
            $this->insert('user', $user);
        }


        $this->createTable('image', [
            'id' => 'pk',
            'width' => 'int',
            'height' => 'int',
            'user_id' => 'int',
            'data' => 'text',
        ]);

        if ($this->getDb()->getDriverName() == 'mysql') {
            $this->addForeignKey('FK_image_user', 'image', 'user_id', 'user', 'id');
        }
    }

    public function safeDown() {
        if ($this->getDb()->getDriverName() == 'mysql') {
            $this->dropForeignKey('FK_image_user', 'image');
        }
        $this->dropTable('user');
        $this->dropTable('image');
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m170418_103707_main cannot be reverted.\n";

      return false;
      }
     */
}
