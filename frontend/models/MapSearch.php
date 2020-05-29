<?php

namespace frontend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\Map;

/**
 * MapSearch represents the model behind the search form of `app\models\Map`.
 */
class MapSearch extends Map
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['right2Add','publicPermission'], 'integer'],
            [['title', 'description', 'timeCreated', 'timeUpdated','publicPermission'], 'safe'],
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
        $query = Map::find();

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
            'timeCreated' => $this->timeCreated,
            'timeUpdated' => $this->timeUpdated,
            'right2Add' => $this->right2Add,
            'publicPermission' => $this->publicPermission,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
