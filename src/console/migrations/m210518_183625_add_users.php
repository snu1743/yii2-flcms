<?php

use yii\db\Migration;
use common\models\User;

/**
 * Class m210518_183625_add_users
 */
class m210518_183625_add_users extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $users = [
            ['username'=>'admin@freelemur.com', 'pass'=>'Qxjeni7543w', 'auth_key' => '5f3aa1d6ee6696c53464c5563abb1cc2']
        ];
        foreach ($users as $userData) {
            $user = new User();
            $user->setPassword($userData['pass']);
            $user->username = $userData['username'];
            $user->email = $userData['username'];
            $user->status = 1;
            $user->auth_key = $userData['auth_key'];
            $user->save(false);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        return true;
    }
}
