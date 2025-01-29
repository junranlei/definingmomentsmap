<?php

namespace frontend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\FlagNote;

/**
 * FlagnoteSearch represents the model behind the search form of `frontend\models\FlagNote`.
 */
class FlagnoteSearch extends FlagNote
{
    public $username;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'flagId', 'userId'], 'integer'],
            [['note','username'], 'safe'],
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
        $query = FlagNote::find()->joinWith('user');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['attributes' => ['id', 'username', 'userId', 'note']]
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
            'flagId' => $this->flagId,
            'userId' => $this->userId,

        ]);
        $query->andFilterWhere(['like', 'user.username', $this->username]);

        $query->andFilterWhere(['like', 'note', $this->note]);

        return $dataProvider;
    }
}
