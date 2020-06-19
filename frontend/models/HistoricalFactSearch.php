<?php

namespace frontend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\Historicalfact;

/**
 * HistoricalfactSearch represents the model behind the search form of `frontend\models\Historicalfact`.
 */
class HistoricalfactSearch extends Historicalfact
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mainMediaId'], 'integer'],
            [['title', 'description', 'date', 'dateEnded', 'timeCreated', 'timeUpdated', 'urls'], 'safe'],
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
     * @param int $status default 1 enabled
     * @return ActiveDataProvider
     */
    public function search($params, $status=1)
    {
        $query = Historicalfact::find();

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
            'date' => $this->date,
            'dateEnded' => $this->dateEnded,
            'timeCreated' => $this->timeCreated,
            'timeUpdated' => $this->timeUpdated,
            'mainMediaId' => $this->mainMediaId,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'urls', $this->urls]);
        
        $query->andWhere('status='.$status); //only return enabled records or specify by status

        return $dataProvider;
    }
}
