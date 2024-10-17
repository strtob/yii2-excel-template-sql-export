<?php

namespace strtob\yii2ExcelTemplateSqlExport\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the base model class for table "tbl_export_has_mandate".
 *
 * @property integer $id
 * @property integer $tbl_export_id
 * @property integer $tbl_mandate_id
 * @property string $valid_from
 * @property string $valid_until
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property string $deleted_at
 * @property integer $deleted_by
 * @property integer $db_lock
 *
 * @property \app\mandate\Mandate\Mandate $tblMandate
 * @property \app\export\Export\Export $tblExport
 */
class ExportHasMandate extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;


    private $_rt_softdelete;
    private $_rt_softrestore;

    public function __construct()
    {
        parent::__construct();
        $this->_rt_softdelete = [
            'deleted_by' => \Yii::$app->user->id,
            'deleted_at' => date('Y-m-d H:i:s'),
        ];
        $this->_rt_softrestore = [
            'deleted_by' =>
            0,
            'deleted_at' =>
            date('Y-m-d H:i:s'),
        ];
    }

    /**
     * This function helps \mootensai\relation\RelationTrait runs faster
     * @return array relation names of this model
     */
    public function relationNames()
    {
        return [
            'tblMandate',
            'tblExport'
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tbl_export_id'], 'required'],
            [['tbl_export_id', 'tbl_mandate_id', 'created_by', 'updated_by', 'deleted_by', 'db_lock'], 'integer'],
            [['valid_from', 'valid_until', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['db_lock'], 'default', 'value' => '0'],
            [['db_lock'], 'mootensai\components\OptimisticLockValidator']
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_export_has_mandate';
    }

    /**
     *
     * @return string
     * overwrite function optimisticLock
     * return string name of field are used to stored optimistic lock
     *
     */
    public function optimisticLock()
    {
        return 'db_lock';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'tbl_export_id' => Yii::t('app', 'Export'),
            'tbl_mandate_id' => Yii::t('app', 'Mandate'),
            'valid_from' => Yii::t('app', 'Valid From'),
            'valid_until' => Yii::t('app', 'Valid Until'),
            'db_lock' => Yii::t('app', 'Db Lock'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblMandate()
    {
        return $this->hasOne(\app\models\Mandate::className(), ['id' => 'tbl_mandate_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblExport()
    {
        return $this->hasOne(\strtob\yii2ExcelTemplateSqlExport\models\Export::className(), ['id' => 'tbl_export_id']);
    }

    /**
     * @inheritdoc
     * @return array mixed
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => \yii\behaviors\TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => new \yii\db\Expression('NOW()'),
            ],
            'blameable' => [
                'class' => \yii\behaviors\BlameableBehavior::class,
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => 'updated_by',
            ],
            
        ];
    }

    /**
     * The following code shows how to apply a default condition for all queries:
     *
     * ```php
     * class Customer extends ActiveRecord
     * {
     *   public static function find()
     * {
     *   return parent::find()->where(['deleted' => false]);
     * }
     * }
     *
     * // Use andWhere()/orWhere() to apply the default condition
     * // SELECT FROM customer WHERE `deleted`=:deleted AND age>30
     * $customers = Customer::find()->andWhere('age>30')->all();
     *
     * // Use where() to ignore the default condition
     * // SELECT FROM customer WHERE age>30
     * $customers = Customer::find()->where('age>30')->all();
     * ```
     */

    /**
     * @inheritdoc
     * @return \strtob\yii2ExcelTemplateSqlExport\ExportHasMandateQuery the active query used by this AR class.
     */
    public static function find()
    {
        $query = new \strtob\yii2ExcelTemplateSqlExport\models\ExportHasMandateQuery(get_called_class());
        $query->where([
            'tbl_export_has_mandate.deleted_by' => NULL,
        ]);

        // Check if the user has the 'mandates_see_all' permission
        if (!\Yii::$app->user->can('mandates_see_all')) {
            // If the user doesn't have the permission, apply the filter by tbl_mandate_id
            $query->andWhere([
                'tbl_mandate_id' => \Yii::$app->user->identity->tbl_mandate_id,
            ]);
        }

        return $query;
    }
}
