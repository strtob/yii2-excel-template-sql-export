<?php

namespace strtob\yii2ExcelTemplateSqlExport\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the base model class for table "tbl_export_sql_query".
 *
 * @property integer $id
 * @property integer $tbl_export_id
 * @property integer $isActive
 * @property string $sheet_name
 * @property string $query
 * @property string $parameter
 * @property string $description
 * @property string $sheet_name
 * @property integer $order_by
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property string $deleted_at
 * @property integer $deleted_by
 * @property integer $db_lock
 *
 * @property \app\export\Export\Export $tblExport
 */
class ExportSqlQuery extends \yii\db\ActiveRecord
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
            'tblExport'
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tbl_export_id', 'query'], 'required'],
            [['tbl_export_id', 'order_by', 'created_by', 'updated_by', 'deleted_by', 'db_lock'], 'integer'],
            [['isActive'], 'boolean'],
            [['isActive'], 'default', 'value' => 0],
            [['sheet_name','query', 'sheet_name','parameter', 'description'], 'string'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['db_lock'], 'default', 'value' => '0'],
            [['db_lock'], 'mootensai\components\OptimisticLockValidator']
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_export_sql_query';
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
            'sheet_name' => Yii::t('app', 'Name'),
            'isActive' => Yii::t('app', 'Is Active'),
            'query' => Yii::t('app', 'Query'),
            'sheet_name' => Yii::t('app', 'Sheet Name'),
            'parameter' => Yii::t('app', 'Parameter'),
            'description' => Yii::t('app', 'Description'),
            'order_by' => Yii::t('app', 'Order By'),
            'db_lock' => Yii::t('app', 'Db Lock'),
        ];
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
     * @return \strtob\yii2ExcelTemplateSqlExport\ExportSqlQueryQuery the active query used by this AR class.
     */
    public static function find()
    {
        $query = new \strtob\yii2ExcelTemplateSqlExport\models\ExportSqlQueryQuery(get_called_class());
        $query->where([
            'tbl_export_sql_query.deleted_by' => NULL,
        ]);

     

        return $query;
    }
}
