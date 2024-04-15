<?php

namespace app\controllers;

use Yii;
use app\models\Book;
use app\models\Author;
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

        $authorName = Yii::$app->request->post('author');
        if (!$authorName) {
            return ['success' => false, 'message' => 'Author is required'];
        }

        $author = Author::find()->where(['name' => $authorName])->one();
        if (!$author) {
            $author = new Author();
            $author->name = $authorName;
            if (!$author->save()) {
                return ['success' => false, 'errors' => $author->getErrors()];
            }
        }

        $book->author_id = $author->id;

        if ($book->save()) {
            return ['success' => true, 'book' => $book];
        } else {
            return ['success' => false, 'errors' => $book->getErrors()];
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
            $query->andFilterWhere(['in', 'languageModel.id', $languages]);
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
