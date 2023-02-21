<?php

namespace app\modules\admin\controllers;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\comments\models\Comments;

/**
 * CommentsSearch represents the model behind the search form of `app\modules\comments\models\Comments`.
 */
class CommentsSearch extends Comments
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'author_id', 'created_at', 'updated_at', 'parent_comment', 'answered_comment'], 'integer'],
            [['body'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Comments::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'author_id' => $this->author_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'parent_comment' => $this->parent_comment,
            'answered_comment' => $this->answered_comment,
        ]);

        $query->andFilterWhere(['like', 'body', $this->body]);

        return $dataProvider;
    }
}
