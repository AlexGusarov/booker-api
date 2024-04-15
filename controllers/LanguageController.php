<?php

namespace app\controllers;

use Yii;
use app\models\Language;
use yii\rest\ActiveController;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

class LanguageController extends ActiveController
{
    public $modelClass = 'app\models\Language';

    public function actions()
    {
        $actions = parent::actions();

        return $actions;
    }

    public function actionCreate()
    {
        $model = new Language();
        $model->load(Yii::$app->getRequest()->getBodyParams(), '');
        if ($model->save()) {
            return $model;
        } else {
            return ['errors' => $model->errors];
        }
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->load(Yii::$app->getRequest()->getBodyParams(), '');
        if ($model->save()) {
            return $model;
        } else {
            return ['errors' => $model->errors];
        }
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if ($model->delete()) {
            return ['success' => true];
        } else {
            return ['errors' => $model->errors];
        }
    }

    protected function findModel($id)
    {
        if (($model = Language::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested language does not exist.');
        }
    }
}
