<?php

namespace strtob\yii2ExcelTemplateSqlExport\models;

/**
 * This is the ActiveQuery class for [[Export]].
 *
 * @see Export
 */
class ExportQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return Export[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Export|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
