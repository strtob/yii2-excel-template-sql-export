<?php

namespace strtob\yii2ExcelTemplateSqlExport\models;

/**
 * This is the ActiveQuery class for [[ExportSqlQuery]].
 *
 * @see ExportSqlQuery
 */
class ExportSqlQueryQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return ExportSqlQuery[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return ExportSqlQuery|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
