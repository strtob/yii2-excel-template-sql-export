<?php

use yii\db\Migration;

class m241017_204101_create_table_tbl_export extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            '{{%export}}',
            [
                'id' => $this->primaryKey(),
                'parent_id' => $this->integer(),
                'name' => $this->string()->notNull(),
                'description' => $this->text(),
                'tbl_template_file' => $this->integer(),
                'order_by' => $this->integer(),
                'created_at' => $this->dateTime(),
                'created_by' => $this->integer(),
                'updated_at' => $this->dateTime(),
                'updated_by' => $this->integer(),
                'deleted_at' => $this->dateTime(),
                'deleted_by' => $this->integer(),
                'db_lock' => $this->integer()->notNull()->defaultValue('0'),
            ],
            $tableOptions
        );

        $this->createIndex('tbl_template_file', '{{%export}}', ['tbl_template_file']);
        $this->createIndex('parent_id', '{{%export}}', ['parent_id']);
    }

    public function safeDown()
    {
        $this->dropTable('{{%export}}');
    }
}
