<?php

namespace strtob\yii2ExcelTemplateSqlExport\models;

use Yii;
use strtob\yii2Traits\ValidityTrait;
use \strtob\yii2ExcelTemplateSqlExport\models\base\ExportHasMandate as BaseExportHasMandate;

/**
 * This is the model class for table "tbl_export_has_mandate".
 */
class ExportHasMandate extends BaseExportHasMandate
{
    use ValidityTrait;
	
}
