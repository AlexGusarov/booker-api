<?php

namespace app\controllers;

use Yii;
use app\models\Author;
use yii\rest\ActiveController;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

class AuthorController extends ActiveController
{
    public $modelClass = 'app\models\Author';

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        // Здесь может быть настройка поведения, например CORS или аутентификация
        return $behaviors;
    }

    public function actions()
    {
        $actions = parent::actions();
        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];

        unset($actions['update']);
        return $actions;
    }

    public function prepareDataProvider()
    {
        $query = Author::find();
        $name = Yii::$app->request->get('name');
        if ($name) {
            $query->andFilterWhere(['like', 'name', $name]);
        }
        return new ActiveDataProvider(['query' => $query]);
    }

    public function actionUpdate($id)
    {
        $author = Author::findOne($id);
        if (!$author) {
            throw new NotFoundHttpException("Author not found");
        }

        if ($author->load(Yii::$app->request->post(), '') && $author->save()) {
            return ['success' => true, 'message' => 'Автор успешно обновлен', 'data' => $author];
        } else {
            return ['success' => false, 'errors' => $author->getErrors()];
        }
    }
    public function actionDelete($id)
    {
        $author = Author::findOne($id);
        if (!$author) {
            throw new NotFoundHttpException("Author not found");
        }

        if ($author->delete()) {
            return ['success' => true, 'message' => 'Автор успешно удален'];
        } else {
            return ['success' => false, 'message' => 'Ошибка при удалении автора'];
        }
    }
}
