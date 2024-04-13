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
        // Например, добавить аутентификацию здесь
        return $behaviors;
    }

    public function actions()
    {
        $actions = parent::actions();

        // Настройка 'prepareDataProvider' для 'index' действия, если нужна кастомная фильтрация
        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];

        return $actions;
    }

    public function prepareDataProvider()
    {
        $query = Author::find();

        // Пример фильтрации по имени автора
        $name = Yii::$app->request->get('name');
        if ($name) {
            $query->andFilterWhere(['like', 'name', $name]);
        }

        return new ActiveDataProvider([
            'query' => $query,
        ]);
    }
}
