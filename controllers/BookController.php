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
        $book->load(Yii::$app->request->post(), '');

        $authorId = Yii::$app->request->post('author_id');
        $languageId = Yii::$app->request->post('language_id');  // Получение language_id из запроса

        if (!$authorId) {
            return ['success' => false, 'message' => 'Author ID is required'];
        }

        $author = Author::findOne($authorId);
        if (!$author) {
            return ['success' => false, 'message' => 'Author not found'];
        }

        $language = Language::findOne($languageId);  // Проверка существования языка
        if (!$language) {
            return ['success' => false, 'message' => 'Language not found'];
        }

        $book->author_id = $author->id;
        $book->language_id = $language->id;

        if (!$book->save()) {
            Yii::error("Ошибка при сохранении книги: " . json_encode($book->getErrors()), __METHOD__);
            return ['success' => false, 'errors' => $book->getErrors()];
        }

        return ['success' => true, 'message' => 'Книга успешно создана'];
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
