<?php

namespace app\controllers;

use Yii;
use app\models\Author;
use yii\rest\ActiveController;
use yii\data\ActiveDataProvider;

class AuthorController extends ActiveController
{
    public $modelClass = 'app\models\Author';

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        return $behaviors;
    }

    public function actions()
    {
        $actions = parent::actions();

        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];

        return $actions;
    }

    public function prepareDataProvider()
    {
        $query = Author::find();

        $name = Yii::$app->request->get('name');
        if ($name) {
            $query->andFilterWhere(['like', 'name', $name]);
        }

        return new ActiveDataProvider([
            'query' => $query,
        ]);
    }
}
