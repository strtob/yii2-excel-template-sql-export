<?php

use yii\db\Migration;

class m241017_204103_create_table_tbl_export_sql_query extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            '{{%export_sql_query}}',
            [
                'id' => $this->primaryKey(),
                'tbl_export_id' => $this->integer()->notNull(),
                'isActive' => $this->boolean()->defaultValue('1'),
                'sheet_name' => $this->string(),
                'query' => $this->text()->notNull(),
                'parameter' => $this->text()->comment('-- A serialized JSON string to define parameter names and types'),
                'description' => $this->text(),
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

        $this->createIndex('tbl_export_id', '{{%export_sql_query}}', ['tbl_export_id']);
    }

    public function safeDown()
    {
        $this->dropTable('{{%export_sql_query}}');
    }
}
