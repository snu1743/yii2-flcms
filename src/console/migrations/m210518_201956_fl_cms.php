<?php

use yii\db\Migration;

/**
 * Class m210518_201956_fl_cms
 */
class m210518_201956_fl_cms extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%cms_project}}', [
            'id' => $this->primaryKey()->comment('ID проекта'),
            'acronym' =>  $this->string(10)->notNull()->comment('Сокращённое в акроним имя проекта'),
            'short_name' =>  $this->string(50)->notNull()->unique()->comment('Короткое имя проекта, для списков и тд.'),
            'name' =>  $this->string(255)->notNull()->unique()->comment('Полное имя проекта'),
            'cms_project_status_id' => $this->tinyInteger()->notNull()->defaultValue(2),
            'created_at' => $this->dateTime()->defaultExpression('NOW()'),
            'create_user_id' =>  $this->integer()->notNull(),
            'updated_at' => $this->dateTime(),
            'update_user_id' =>  $this->integer()
        ]);

        $this->createTable('{{%cms_project_status}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(50)->notNull(),
            'title' => $this->string(250)->notNull(),
        ]);

        $this->createTable('{{%cms_project_domain}}', [
            'id' => $this->primaryKey(),
            'cms_project_id' => $this->integer()->notNull(),
            'cms_tree_id' => $this->integer()->notNull(),
            'name' =>  $this->string(255)->notNull()->unique(),
            'created_at' => $this->dateTime()->defaultExpression('NOW()'),
            'create_user_id' =>  $this->integer()->notNull()
        ]);
//        $this->createIndex('idx-cms_project_domain', '{{%cms_project_domain}}', ['name']);

        $this->createTable('{{%cms_object_action}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(50)->notNull()->unique(),
            'title' => $this->string(250)->notNull()->unique()
        ]);

        $this->createTable('{{%cms_page}}', [
            'id' => $this->primaryKey(),
            'path' =>  $this->string(2048)->notNull(),
            'name' =>  $this->string(2048)->notNull(),
            'title' =>  $this->string(255),
            'hash_id' => $this->string(32)->notNull()->unique(),
            'parent_id' =>  $this->integer()->notNull(),
            'owner_id' =>  $this->integer()->notNull(),
            'cms_tree_id' =>  $this->integer()->notNull(),
//            'cms_page_type_id' =>  $this->integer()->notNull()->defaultValue(1),
            'level' => $this->integer(1024)->notNull(),
            'path_length' => $this->integer(2048)->notNull(),
            'is_active' => $this->tinyInteger()->defaultValue(0),
            'created_at' => $this->dateTime()->defaultExpression('NOW()'),
            'updated_at' => $this->dateTime()
        ]);

//        $this->createTable('{{%cms_page_type}}', [
//            'id' => $this->primaryKey(),
//            'name' => $this->string(50)->notNull()->unique(),
//            'title' => $this->string(250)->notNull()->unique()
//        ]);

        $this->createTable('{{%cms_page_layout}}', [
            'id' => $this->primaryKey(),
            'body' => $this->text(),
            'name' => $this->string(50)->notNull(),
            'description' => $this->string(1024),
            'cms_project_id' =>  $this->integer()->notNull(),
            'created_at' => $this->dateTime()->defaultExpression('NOW()'),
            'create_user_id' =>  $this->integer()->notNull(),
            'updated_at' => $this->dateTime(),
            'update_user_id' =>  $this->integer()
        ]);

        $this->createTable('{{%cms_page_content}}', [
            'id' => $this->primaryKey(),
            'body' => $this->text(),
            'name' => $this->string(50)->notNull(),
            'description' => $this->string(1024),
            'cms_project_id' =>  $this->integer()->notNull(),
            'created_at' => $this->dateTime()->defaultExpression('NOW()'),
            'create_user_id' =>  $this->integer()->notNull(),
            'updated_at' => $this->dateTime(),
            'update_user_id' =>  $this->integer()
        ]);

        $this->createTable('{{%cms_page_layout_content_bind}}', [
            'cms_page_id' =>  $this->integer(),
            'cms_page_layout_id' =>  $this->integer(),
            'cms_page_content_id' =>  $this->integer(),
            'version' =>  $this->integer()->notNull()->defaultValue(0)
        ]);

        $this->createIndex('idx-cms_page-path-cms_tree_id', '{{%cms_page}}', ['path', 'cms_tree_id'], true);

        $this->createIndex('idx-cms_page_layout-name-cms_project_id', '{{%cms_page_layout}}', ['name', 'cms_project_id'], true);

        $this->createIndex('idx-cms_page_content-name-cms_project_id', '{{%cms_page_content}}', ['name', 'cms_project_id'], true);

        $this->createIndex('idx-cms_page_layout_content_bind', '{{%cms_page_layout_content_bind}}', ['cms_page_id', 'cms_page_layout_id', 'cms_page_content_id'], true);
        $this->createIndex('idx-cms_page_layout_content_bind_version', '{{%cms_page_layout_content_bind}}', ['cms_page_id', 'version'], true);



        $this->createTable('{{%cms_access_rule}}', [
            'cms_access_object_id' =>  $this->integer(),
            'cms_access_object_type_id' =>  $this->integer(),
            'cms_object_action_id' =>  $this->integer(),
            'cms_group_id' =>  $this->integer(),
            'user_id' =>  $this->integer()
        ]);

        $this->createTable('{{%cms_access_object_type}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(50)->notNull(),
            'title' => $this->string(250)->notNull(),
        ]);

        $this->createIndex('idx-cms_access_rule', '{{%cms_access_rule}}', ['cms_access_object_id', 'cms_access_object_type_id', 'cms_object_action_id', 'cms_group_id', 'user_id'], true);

        $this->createTable('{{%cms_tree}}', [
            'id' => $this->primaryKey(),
            'cms_project_owner_id' => $this->integer()->notNull(),
            'created_at' => $this->dateTime()->defaultExpression('NOW()'),
            'create_user_id' =>  $this->integer()->notNull(),
        ]);

        $this->createTable('{{%cms_project_tree_bind}}', [
            'id' => $this->primaryKey(),
            'cms_project_id' => $this->integer()->notNull(),
            'cms_tree_id' => $this->integer()->notNull(),
            'cms_project_tree_bind_status_id' => $this->tinyInteger()->notNull()->defaultValue(1),
            'created_at' => $this->dateTime()->defaultExpression('NOW()'),
            'update_at' => $this->dateTime(),
        ]);

        $this->createTable('{{%cms_project_tree_bind_status}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(50)->notNull(),
            'title' => $this->string(250)->notNull(),
        ]);

        $this->createTable('{{%cms_group}}', [
            'id' => $this->primaryKey(),
            'cms_project_owner_id' => $this->integer()->notNull(),
            'name' => $this->string(50)->notNull(),
            'title' => $this->string(250)->notNull(),
            'description' => $this->string(10240),
            'created_at' => $this->dateTime()->defaultExpression('NOW()'),
            'create_user_id' =>  $this->integer()->notNull(),
            'updated_at' => $this->dateTime(),
            'update_user_id' =>  $this->integer()
        ]);

        $this->createTable('{{%cms_group_user_bind}}', [
            'id' => $this->primaryKey(),
            'cms_group_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'created_at' => $this->dateTime()->defaultExpression('NOW()')
        ]);

        $this->createTable('{{%cms_project_group_bind}}', [
            'id' => $this->primaryKey(),
            'cms_project_id' => $this->integer()->notNull(),
            'cms_group_id' => $this->integer()->notNull(),
            'created_at' => $this->dateTime()->defaultExpression('NOW()')
        ]);


        $this->addForeignKey('fk-cms_page-cms_tree', '{{%cms_page}}', 'cms_tree_id', '{{%cms_tree}}', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-cms_page-cms_tree', '{{%cms_page}}');
        $this->dropTable('{{%cms_page}}');
        $this->dropTable('{{%cms_page_content}}');
        $this->dropTable('{{%cms_page_layout}}');
        $this->dropTable('{{%cms_page_layout_content_bind}}');
        $this->dropTable('{{%cms_object_action}}');
        $this->dropTable('{{%cms_access_rule}}');
        $this->dropTable('{{%cms_access_object_type}}');
        $this->dropTable('{{%cms_project}}');
        $this->dropTable('{{%cms_project_domain}}');
        $this->dropTable('{{%cms_tree}}');
        $this->dropTable('{{%cms_project_tree_bind}}');
        $this->dropTable('{{%cms_group_user_bind}}');
        $this->dropTable('{{%cms_group}}');
        $this->dropTable('{{%cms_project_group_bind}}');
        $this->dropTable('{{%cms_project_status}}');
        $this->dropTable('{{%cms_project_tree_bind_status}}');
    }
}
