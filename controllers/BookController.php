<?php

namespace app\controllers;

use Yii;
use app\models\Book;
use yii\rest\ActiveController;
use yii\data\ActiveDataProvider;

class BookController extends ActiveController
{
    public $modelClass = 'app\models\Book';

    public function behaviors()
    {
        $behaviors = parent::behaviors();

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
        $query = Book::find();

        // Фильтрация по названию и описанию
        $title = Yii::$app->request->get('title');
        $description = Yii::$app->request->get('description');
        if ($title) {
            $query->andFilterWhere(['like', 'title', $title]);
        }
        if ($description) {
            $query->andFilterWhere(['like', 'description', $description]);
        }

        // Мультиселект фильтрация по автору, языку, жанру
        $authors = Yii::$app->request->get('authors');
        $languages = Yii::$app->request->get('languages');
        $genres = Yii::$app->request->get('genres');
        if ($authors) {
            $query->andFilterWhere(['in', 'author_id', $authors]);
        }
        if ($languages) {
            $query->andFilterWhere(['in', 'language', $languages]);
        }
        if ($genres) {
            $query->andFilterWhere(['in', 'genre', $genres]);
        }

        // Фильтрация по числу страниц
        $page_count_min = Yii::$app->request->get('page_count_min');
        $page_count_max = Yii::$app->request->get('page_count_max');
        if ($page_count_min !== null) {
            $query->andWhere(['>=', 'page_count', $page_count_min]);
        }
        if ($page_count_max !== null) {
            $query->andWhere(['<=', 'page_count', $page_count_max]);
        }

        return new ActiveDataProvider([
            'query' => $query,
        ]);
    }

}
