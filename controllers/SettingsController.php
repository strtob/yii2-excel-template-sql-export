<?php

namespace strtob\yii2ExcelTemplateSqlExport\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use strtob\yii2ExcelTemplateSqlExport\models\Export;
use strtob\yii2ExcelTemplateSqlExport\models\ExportQuery;
use strtob\yii2ExcelTemplateSqlExport\models\ExportSqlQuery;
use strtob\yii2ExcelTemplateSqlExport\models\ExportHasMandate;
use strtob\yii2ExcelTemplateSqlExport\models\ExportSqlQuerySearch;
use strtob\yii2ExcelTemplateSqlExport\models\ExportHasMandateSearch;



class SettingsController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['GET'],
                ],
            ],
        ];
    }

    /**
     * Lists all Export models.
     * @return mixed
     */
    public function actionIndex()
    {



        $searchModel = new \strtob\yii2ExcelTemplateSqlExport\models\ExportSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Export model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $providerExportHasMandate = new \yii\data\ArrayDataProvider([
            'allModels' => $model->exportHasMandates,
        ]);
        $providerExportQuery = new \yii\data\ArrayDataProvider([
            'allModels' => $model->exportQueries,
        ]);
        return $this->render('view', [
            'model' => $this->findModel($id),
            'providerExportHasMandate' => $providerExportHasMandate,
            'providerExportQuery' => $providerExportQuery,
        ]);
    }

    /**
     * Creates a new Export model.
     * If creation is successful, the browser will be redirected to the 'update' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Export();

        if (
            $model->loadAll(Yii::$app->request->post())
            && $model->saveAll()
        ) {
            return $this->redirect(['update', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'dataProviders' => null,
                'searchModels' => null,
            ]);
        }
    }

    /**
     * Updates an existing Export model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        // find model
        $model = $this->findModel($id);

        // save model if save is ajax request
        $request = Yii::$app->request;

        if ($request->isAjax && !$request->isPjax) {

            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            if (
                $model->load($request->post()) &&
                $model->save()
            ) {
                $dbIds = \strtob\yii2helpers\ArHelper::collectDbLocks($model);
                return \strtob\yii2helpers\HtmlResponse::messageSuccess(null, null, null, ['db_locks' => $dbIds]);
            } else
                return \strtob\yii2helpers\HtmlResponse::messageError(
                    null,
                    null,
                    $model,
                    self::class . __METHOD__
                );
        } else {

            // search models & dataprovider inclusive related
            $searchModels = [];
            $dataProviders = [];

            // Relation ExportHasMandate
            $searchModels['ExportHasMandate'] = new ExportHasMandateSearch();
            $dataProviders['ExportHasMandate'] = $searchModels['ExportHasMandate']->search(Yii::$app->request->queryParams);
            $dataProviders['ExportHasMandate']->query->andWhere(['=', 'tbl_export_id', $id]);
            // Relation ExportQuery
            $searchModels['ExportQuery'] = new ExportSqlQuerySearch();
            $dataProviders['ExportQuery'] = $searchModels['ExportQuery']->search(Yii::$app->request->queryParams);
            $dataProviders['ExportQuery']->query->andWhere(['=', 'tbl_export_id', $id]);

            return $this->render('update', [
                'model' => $model,
                'dataProviders' => $dataProviders,
                'searchModels' => $searchModels,
            ]);
        }
    }

    /**
     * Deletes an existing Export model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $model = $this->findModel($id);

        if ($model->deleteWithRelated())
            return \strtob\yii2helpers\HtmlResponse::messageSuccess();
        else
            return \strtob\yii2helpers\HtmlResponse::messageError(
                null,
                null,
                $model,
                self::class . __METHOD__
            );
    }


    /**
     * Finds the Export model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Export the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {

        if (($model = Export::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


    /**
     * AjaxAction to manage related
     * for ExportHasMandate
     * @author Tobias Streckel <ts@re-soft.de>
     *
     * @return mixed
     */
    public function actionUpdateExportHasMandate($id)
    {
        if (Yii::$app->request->isAjax) {

            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            $model = ExportHasMandate::findOne($id);

            if (
                $model->loadAll(Yii::$app->request->post())
                &&
                $model->saveAll()
            ) {

                if ($model->save())
                    return \strtob\yii2helpers\HtmlResponse::messageSuccess(null, null, null, ['model' => $model]);
                else
                    return \strtob\yii2helpers\HtmlResponse::messageError(
                        null,
                        null,
                        $model,
                        self::class . __METHOD__
                    );
            } else {
                return $this->renderAjax('exporthasmandate/update', [
                    'model' => $model,
                ]);
            }
        } else
            throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Creates a new Export model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @author Tobias Streckel <ts@re-soft.de>
     * @return mixed
     */
    public function actionCreateExportHasMandate($id = null)
    {

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $model = new ExportHasMandate();
        // assign major id to related id
        if (!is_null($id))
            $model->tbl_export_id = $id;


        if ($model->loadAll(Yii::$app->request->post())) {
            if ($model->save())
                return \strtob\yii2helpers\HtmlResponse::messageSuccess();
            else
                return \strtob\yii2helpers\HtmlResponse::messageError(
                    null,
                    null,
                    $model,
                    self::class . __METHOD__
                );
        } else {
            return $this->renderAjax('exporthasmandate/create', [
                'model' => $model,
            ]);
        }
    }


    /**
     * Delete Export model.
     * @author Tobias Streckel <ts@re-soft.de>
     * @return mixed
     */
    public function actionDeleteExportHasMandate($id)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if (is_null($model =
            ExportHasMandate::findOne($id)))
            return \strtob\yii2helpers\HtmlResponse::messageSuccess();

        if ($model->delete())
            return \strtob\yii2helpers\HtmlResponse::messageSuccess();
        else
            return \strtob\yii2helpers\HtmlResponse::messageError(
                null,
                null,
                $model,
                self::class . __METHOD__
            );
    }



    /**
     * AjaxAction to manage related
     * for ExportQuery
     * @author Tobias Streckel <ts@re-soft.de>
     *
     * @return mixed
     */
    public function actionUpdateExportQuery($id)
    {
        if (Yii::$app->request->isAjax) {

            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            $model = ExportSqlQuery::findOne($id);

            if (
                $model->loadAll(Yii::$app->request->post())
                &&
                $model->saveAll()
            ) {

                if ($model->save())
                    return \strtob\yii2helpers\HtmlResponse::messageSuccess(null, null, null, ['model' => $model]);
                else
                    return \strtob\yii2helpers\HtmlResponse::messageError(
                        null,
                        null,
                        $model,
                        self::class . __METHOD__
                    );
            } else {
                return $this->renderAjax('exportquery/update', [
                    'model' => $model,
                ]);
            }
        } else
            throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Creates a new Export model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @author Tobias Streckel <ts@re-soft.de>
     * @return mixed
     */
    public function actionCreateExportQuery($id = null)
    {

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $model = new ExportSqlQuery();
        // assign major id to related id
        if (!is_null($id))
            $model->tbl_export_id = $id;


        if ($model->loadAll(Yii::$app->request->post())) {
            if ($model->save())
                return \strtob\yii2helpers\HtmlResponse::messageSuccess();
            else
                return \strtob\yii2helpers\HtmlResponse::messageError(
                    null,
                    null,
                    $model,
                    self::class . __METHOD__
                );
        } else {
            return $this->renderAjax('exportquery/create', [
                'model' => $model,
            ]);
        }
    }


    /**
     * Delete Export model.
     * @author Tobias Streckel <ts@re-soft.de>
     * @return mixed
     */
    public function actionDeleteExportQuery($id)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if (is_null($model =  ExportQuery::findOne($id)))
            return \strtob\yii2helpers\HtmlResponse::messageSuccess();

        if ($model->delete())
            return \strtob\yii2helpers\HtmlResponse::messageSuccess();
        else
            return \strtob\yii2helpers\HtmlResponse::messageError(
                null,
                null,
                $model,
                self::class . __METHOD__
            );
    }
}
