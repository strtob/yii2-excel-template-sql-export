<?php

use yii\db\Migration;

class m241017_204104_create_foreign_keys extends Migration
{
    public function safeUp()
    {
        $this->addForeignKey(
            'tbl_export_ibfk_1',
            '{{%export}}',
            ['tbl_template_file'],
            '{{%file}}',
            ['id'],
            'RESTRICT',
            'RESTRICT'
        );
        $this->addForeignKey(
            'tbl_export_has_mandate_ibfk_1',
            '{{%export_has_mandate}}',
            ['tbl_mandate_id'],
            '{{%mandate}}',
            ['id'],
            'RESTRICT',
            'RESTRICT'
        );
        $this->addForeignKey(
            'tbl_export_has_mandate_ibfk_2',
            '{{%export_has_mandate}}',
            ['tbl_export_id'],
            '{{%export}}',
            ['id'],
            'RESTRICT',
            'RESTRICT'
        );
        $this->addForeignKey(
            'tbl_export_sql_query_ibfk_1',
            '{{%export_sql_query}}',
            ['tbl_export_id'],
            '{{%export}}',
            ['id'],
            'RESTRICT',
            'RESTRICT'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('tbl_export_sql_query_ibfk_1', '{{%export_sql_query}}');
        $this->dropForeignKey('tbl_export_has_mandate_ibfk_2', '{{%export_has_mandate}}');
        $this->dropForeignKey('tbl_export_has_mandate_ibfk_1', '{{%export_has_mandate}}');
        $this->dropForeignKey('tbl_export_ibfk_1', '{{%export}}');
    }
}
