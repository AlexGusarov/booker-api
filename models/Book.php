<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "books".
 *
 * @property int $id
 * @property string $title
 * @property int $author_id
 * @property string|null $description
 * @property int|null $page_count
 * @property string|null $language
 * @property string|null $genre
 *
 * @property Author $author
 */
class Book extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'books';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'author_id', 'language', 'genre'], 'required'],
            [['author_id', 'page_count'], 'integer'],
            [['description'], 'string'],
            [['title', 'language', 'genre'], 'string', 'max' => 255],
            [['author_id'], 'exist', 'skipOnError' => true, 'targetClass' => Author::class, 'targetAttribute' => ['author_id' => 'id']],
        ];
    }

    public function fields()
    {
        $fields = parent::fields();

        unset($fields['author_id']);

        $fields['author'] = function ($model) {
            return $model->author->name;
        };

        return $fields;
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'author_id' => 'Author ID',
            'description' => 'Description',
            'page_count' => 'Page Count',
            'language' => 'Language',
            'genre' => 'Genre',
        ];
    }

    /**
     * Gets query for [[Author]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(Author::class, ['id' => 'author_id']);
    }

    public function getLanguageModel()
    {
        return $this->hasOne(Language::class, ['id' => 'language']);
    }
}
