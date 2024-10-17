<?php

use yii\db\Migration;

class m241017_204102_create_table_tbl_export_has_mandate extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            '{{%export_has_mandate}}',
            [
                'id' => $this->primaryKey(),
                'tbl_export_id' => $this->integer()->notNull(),
                'tbl_mandate_id' => $this->integer(),
                'canExcelExport' => $this->boolean()->defaultValue('1'),
                'canPdfExport' => $this->boolean()->defaultValue('0'),
                'canZipExport' => $this->boolean()->defaultValue('0'),
                'valid_from' => $this->dateTime(),
                'valid_until' => $this->dateTime(),
                'created_at' => $this->dateTime(),
                'created_by' => $this->integer(),
                'updated_at' => $this->dateTime(),
                'updated_by' => $this->integer(),
                'deleted_at' => $this->dateTime(),
                'deleted_by' => $this->integer(),
                'db_lock' => $this->integer()->defaultValue('0'),
            ],
            $tableOptions
        );

        $this->createIndex('tbl_export_id', '{{%export_has_mandate}}', ['tbl_export_id']);
        $this->createIndex('tbl_mandate_id', '{{%export_has_mandate}}', ['tbl_mandate_id']);
    }

    public function safeDown()
    {
        $this->dropTable('{{%export_has_mandate}}');
    }
}
