<?php

namespace frontend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\HistoricalFact;

/**
 * HistoricalfactSearch represents the model behind the search form of `frontend\models\HistoricalFact`.
 */
class HistoricalfactSearch extends HistoricalFact
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
    public function search($params, $status=1,$matchModel=null)
    {
        $query = HistoricalFact::find()->joinWith('features')
        ->groupBy(['historicalFact.id']);;

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
            'historicalFact.id' => $this->id,
            'historicalFact.date' => $this->date,
            'historicalFact.dateEnded' => $this->dateEnded,
            'historicalFact.timeCreated' => $this->timeCreated,
            'historicalFact.timeUpdated' => $this->timeUpdated,
            'mainMediaId' => $this->mainMediaId,
        ]);

        $query->andFilterWhere(['like', 'historicalFact.title', $this->title])
            ->andFilterWhere(['like', 'historicalFact.description', $this->description])
            ->andFilterWhere(['like', 'urls', $this->urls]);
        
        $query->andWhere('historicalFact.status='.$status); //only return enabled records or specify by status
        if($matchModel!=null&&$matchModel->title!=null){
            $query->andWhere("MATCH(historicalFact.title) AGAINST('".trim($matchModel->title)."')"); 
            $query->andWhere("historicalFact.id!=".$matchModel->id); 
        }
        return $dataProvider;
    }
}
