<?php

use yii\db\Migration;
use console\migrations_data\Data;


/**
 * Class m210624_223817_insert_project
 */
class m210624_223817_insert_project extends Migration
{
    private string $securityHash = '69226f22702d07021eba7a6eff836ff';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->batchInsert('{{%cms_page_layout}}',
            ['body','name','description','cms_project_id', 'create_user_id'],
            [
                [Data::get('layouts/default'), 'default', 'Default layout', 0, 9]
            ],
        );

        $this->batchInsert('{{%cms_tree}}', ['cms_project_owner_id','create_user_id'],
            [
                [1,9]
            ],
        );

        $this->batchInsert('{{%cms_object_action}}', ['name','title'],
            [
                ['name' => 'page_create', 'title' => 'Create page'],
                ['name' => 'page_view', 'title' => 'View page'],
                ['name' => 'page_update', 'title' => 'Update page'],
                ['name' => 'page_delete', 'title' => 'Delete page']
            ],
        );

        $this->batchInsert('{{%cms_project}}', ['acronym','short_name', 'name', 'cms_project_status_id', 'create_user_id'],
            [
                ['FL', 'FreeLemur.COM', 'FreeLemur.COM', 2, 9]
            ],
        );

        $this->batchInsert('{{%cms_project_domain}}', ['cms_project_id','cms_tree_id', 'name', 'create_user_id'],
            [
                [1, 1, 'freelemur.com', 9],
                [1, 1, 'snu1743.freelemur.com', 9],
                [1, 1, 'snu1743-prod.freelemur.com', 9],
                [1, 1, 'snu1743-stage.freelemur.com', 9]
            ],
        );

        $this->batchInsert('{{%cms_project_tree_bind}}', ['cms_project_id','cms_tree_id', 'cms_project_tree_bind_status_id'],
            [
                [1, 1, 2]
            ],
        );

        $this->batchInsert('{{%cms_access_object_type}}', ['name', 'title'],
            [
                ['page', 'Page CMS']
            ],
        );

        $this->batchInsert('{{%cms_group_user_bind}}', ['cms_group_id','user_id'],
            [
                [1,9],
                [2,9],
                [3,9],
                [4,9],
            ],
        );

        $this->batchInsert('{{%cms_project_status}}', ['name','title'],
            [
                ['disabled', 'Disabled'],
                ['enable', 'Enable']
            ],
        );

        $this->batchInsert('{{%cms_project_tree_bind_status}}',
            ['name','title'],
            [
                ['disabled','Disabled'],
                ['enable','Enable'],
                ['blocked','Blocked'],
            ],
        );

        $this->batchInsert('{{%cms_group}}',
            ['cms_project_owner_id','name', 'title', 'description', 'create_user_id'],
            [
                [1,'ga','Global administrators ','Global users', 9],
                [1,'gu','Global users ','Global users', 9],
                [1,'admins','Administrators','Administrators', 9],
                [1,'users','Users','Users', 9]
            ],
        );

        $this->batchInsert('{{%cms_project_group_bind}}',
            ['cms_project_id','cms_group_id'],
            [
                [1,1],
                [1,2],
                [1,3],
                [1,4]
            ],
        );

        $this->batchInsert('{{%cms_page}}',
            ['path','name', 'title', 'hash_id', 'parent_id', 'owner_id', 'cms_tree_id', 'level', 'path_length', 'is_active'],
            [
                ['','FL', 'FL',  md5( '1' . $this->securityHash), 0, 9, 1, 0, 5, 1],
                ['hello','Hello', 'Hello',  md5('hello' . '1' . $this->securityHash), 1, 9, 1, 1, 5, 1],
                ['entities','Entities', 'Entities',  md5('entities' . '1' . $this->securityHash), 1, 9, 1, 1, 8, 1],
                ['entities/classes','Entities classes', 'Entities classes',  md5('entities/classes' . '1' . $this->securityHash), 3, 9, 1, 1, 16, 1],
                ['entities/config','Entities config', 'Entities config',  md5('entities/config' . '1' . $this->securityHash), 3, 9, 1, 1, 15, 1],
                ['entities/instances','Entities instances', 'Entities instances',  md5('entities/instances' . '1' . $this->securityHash), 3, 9, 1, 1, 18, 1]
            ],
        );

        $this->batchInsert('{{%cms_page_content}}',
            ['body','name','description','cms_project_id', 'create_user_id'],
            [
                [Data::get('pages_content/hello'), 'hello', 'Hello', 0, 9],
                [Data::get('pages_content/_entities'), 'Entities', 'Entities classes', 0, 9],
                [Data::get('pages_content/entities/classes'), 'Entities classes', 'Entities classes', 0, 9],
                [Data::get('pages_content/entities/config'), 'Entities config', 'Entities classes', 0, 9],
                [Data::get('pages_content/entities/instances'), 'Entities instances', 'Entities classes', 0, 9]
            ],
        );

        $this->batchInsert('{{%cms_page_layout_content_bind}}',
            ['cms_page_id','cms_page_layout_id', 'cms_page_content_id', 'version'],
            [
                [2,null,1,0],
                [3,null,2,0],
                [4,null,3,0],
                [5,null,4,0],
                [6,null,5,0]
            ],
        );

        $this->batchInsert('{{%cms_access_rule}}',
            ['cms_access_object_id','cms_access_object_type_id', 'cms_object_action_id', 'cms_group_id', 'user_id'],
            [
                [1,1,1,3,null], //Корень проекта FL, разрешение на создание страницы для группы admin(FL)
                [1,1,2,0,null], //Корень проекта FL, разрешение на просмотр страницы для всех пользователей
                [1,1,3,3,null], //Корень проекта FL, разрешение на изменение страницы для группы admin(FL)

                [2,1,1,3,null], //Страница hello проекта FL, разрешение на создание страницы для группы admin(FL)
                [2,1,2,0,null], //Страница hello проекта FL, разрешение на просмотр страницы для всех пользователей
                [2,1,3,3,null], //Страница hello проекта FL, разрешение на изменение страницы для группы admin(FL)

                [3,1,2,0,null], //Страница entities

                [4,1,1,3,null], //Страница entities/classes
                [4,1,2,0,null], //Страница entities/classes
                [4,1,3,3,null], //Страница entities/classes

                [5,1,1,3,null], //Страница entities/config
                [5,1,2,0,null], //Страница entities/config
                [5,1,3,3,null], //Страница entities/config

                [6,1,1,3,null], //Страница entities/instances
                [6,1,2,0,null], //Страница entities/instances
                [6,1,3,3,null], //Страница entities/instances
            ],
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

    }
}
