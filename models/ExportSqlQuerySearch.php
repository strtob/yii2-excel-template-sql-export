<?php

namespace strtob\yii2ExcelTemplateSqlExport\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use \strtob\yii2ExcelTemplateSqlExport\models\ExportSqlQuery;

/**
 * strtob\yii2ExcelTemplateSqlExport\ExportSqlQuerySearch represents the model behind the search form about `\strtob\yii2ExcelTemplateSqlExport\ExportSqlQuery`.
 */
class ExportSqlQuerySearch extends ExportSqlQuery
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'tbl_export_id', 'order_by', 'created_by', 'updated_by', 'deleted_by', 'db_lock'], 'integer'],
            [['isActive', 'query', 'parameter', 'description', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = ExportSqlQuery::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'tbl_export_id' => $this->tbl_export_id,
            'order_by' => $this->order_by,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
            'deleted_at' => $this->deleted_at,
            'deleted_by' => $this->deleted_by,
            'db_lock' => $this->db_lock,
        ]);

        $query->andFilterWhere(['like', 'isActive', $this->isActive])
            ->andFilterWhere(['like', 'query', $this->query])
            ->andFilterWhere(['like', 'parameter', $this->parameter])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}