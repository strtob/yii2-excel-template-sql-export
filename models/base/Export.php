<?php

namespace strtob\yii2ExcelTemplateSqlExport\models\base;


use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use strtob\yii2Filemanager\models\File;

/**
 * This is the base model class for table "tbl_export".
 *
 * @property integer $id
 * @property integer $parent_id
 * @property string $name
 * @property string $description
 * @property integer $template_tbl_file_id
 * @property integer $presentation_tbl_file_id
 * @property integer $order_by
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property string $deleted_at
 * @property integer $deleted_by
 * @property integer $db_lock
 *
 * @property \app\file\File\File $templateTblFile
 * @property \app\file\File\File $presentationTblFile
 * @property \app\export\ExportHasMandate\ExportHasMandate[] $exportHasMandates
 * @property \app\export\ExportSqlQuery\ExportSqlQuery[] $exportSqlQueries
 */
class Export extends \yii\db\ActiveRecord
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
            'templateTblFile',
            'presentationTblFile',
            'exportHasMandates',
            'exportSqlQueries'
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id', 'template_tbl_file_id', 'presentation_tbl_file_id', 'order_by', 'created_by', 'updated_by', 'deleted_by', 'db_lock'], 'integer'],
            [['name'], 'required'],
            [['description'], 'string'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['db_lock'], 'default', 'value' => '0'],
            [['db_lock'], 'mootensai\components\OptimisticLockValidator']
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_export';
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
            'parent_id' => Yii::t('app', 'Parent'),
            'name' => Yii::t('app', 'Name'),
            'description' => Yii::t('app', 'Description'),
            'template_tbl_file_id' => Yii::t('app', 'Excel Template File'),
            'presentation_tbl_file_id' => Yii::t('app', 'Powerpoint Presentation File'),
            'order_by' => Yii::t('app', 'Order By'),
            'db_lock' => Yii::t('app', 'Db Lock'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTemplateTblFile()
    {
        return $this->hasOne(File::className(), ['id' => 'template_tbl_file_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPresentationTblFile()
    {
        return $this->hasOne(File::className(), ['id' => 'presentation_tbl_file_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExportHasMandates()
    {
        return $this->hasMany(ExportHasMandate::className(), ['tbl_export_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExportSqlQueries()
    {
        return $this->hasMany(ExportSqlQuery::className(), ['tbl_export_id' => 'id']);
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


            'mandate' => [
                'class' => \app\components\behaviors\MandateBehavior::class,
                'mandateAttribute' => 'tbl_mandate_id',
            ],
            'ip' => [
                'class' => \app\components\behaviors\IpBehavior::class,
                'createIpAttribute' => 'created_ip',
                'updateIpAttribute' => 'updated_ip',
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
     * @return \app\models\ExportQuery the active query used by this AR class.
     */
    public static function find()
    {
        $query = new \app\models\ExportQuery(get_called_class());
        $query->where([
            'tbl_export.deleted_by' => NULL,
        ]);
     
        return $query;
    }
}
