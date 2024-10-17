<?php

namespace strtob\yii2ExcelTemplateSqlExport\models;

/**
 * This is the ActiveQuery class for [[ExportHasMandate]].
 *
 * @see ExportHasMandate
 */
class ExportHasMandateQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return ExportHasMandate[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return ExportHasMandate|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
