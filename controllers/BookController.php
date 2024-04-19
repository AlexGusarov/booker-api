<?php

namespace app\controllers;

use Yii;
use app\models\Book;
use app\models\Author;
use app\models\Language;
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

        unset($actions['create']);

        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];
        return $actions;
    }

    public function actionCreate()
    {
        $book = new Book();
        if ($book->load(Yii::$app->request->post(), '') && $book->validate()) {
            $language = Language::findOne(Yii::$app->request->post('language_id'));
            if (!$language) {
                return ['success' => false, 'message' => 'Language not found'];
            }
            $book->language_id = $language->id;
            if ($book->save()) {
                return ['success' => true, 'message' => 'Книга успешно создана', 'data' => $book];
            } else {
                return ['success' => false, 'errors' => $book->getErrors()];
            }
        } else {
            return ['success' => false, 'errors' => $book->getErrors()];
        }
    }
    public function actionGenres()
    {
        try {
            $genres = Book::find()
                ->select(['genre'])
                ->distinct(true)
                ->orderBy('genre')
                ->asArray()
                ->all();

            return $this->asJson($genres);
        } catch (\Throwable $e) {
            Yii::error("Ошибка при получении жанров: " . $e->getMessage(), __METHOD__);
            return $this->asJson([
                'success' => false,
                'message' => 'Произошла ошибка при загрузке жанров'
            ]);
        }
    }



    public function prepareDataProvider()
    {
        $query = Book::find()->joinWith('languageModel');

        $title = Yii::$app->request->get('title');
        $description = Yii::$app->request->get('description');
        if ($title) {
            $query->andFilterWhere(['like', 'title', $title]);
        }
        if ($description) {
            $query->andFilterWhere(['like', 'description', $description]);
        }

        $authors = Yii::$app->request->get('authors');
        $languages = Yii::$app->request->get('languages');
        $genres = Yii::$app->request->get('genres');
        if ($authors) {
            $query->andFilterWhere(['in', 'author_id', $authors]);
        }

        if ($languages) {
            $query->andFilterWhere(['in', 'language_id', $languages]);
        }

        if ($genres) {
            $query->andFilterWhere(['in', 'genre', $genres]);
        }

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
