<?php

namespace frontend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\HistoricalFact;

/**
 * HistoricalfactSearch represents the model behind the search form of `frontend\models\HistoricalFact`.
 */
class HistoricalmapSearch extends HistoricalFact
{
    public $keyword;
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'date'=>'Start Date',
            'dateEnded'=>'End Date'
        ];
    }
    public function rules()
    {
        return [
            [['mainMediaId'], 'integer'],
            [['keyword' ,'title', 'description', 'date', 'dateEnded', 'timeCreated', 'urls'], 'safe'],
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
    public function search($params, $status=1)
    {
        $query = HistoricalFact::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        //if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            //return $dataProvider;
        //}

        // grid filtering conditions
       /* $query->andFilterWhere([
            'id' => $this->id,
            'date' => $this->date,
            'dateEnded' => $this->dateEnded,
            'timeCreated' => $this->timeCreated,
            'mainMediaId' => $this->mainMediaId,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'urls', $this->urls]);*/

        if($this->keyword!=null && trim($this->keyword)!='')
            $query->andWhere('title like "%' . $this->keyword . '%" or description like "%' . $this->keyword . '%"');
        

        if($this->date!=null && trim($this->date)!='')
            $query->andWhere('date >="'.$this->date.'"');
        if($this->dateEnded!=null && trim($this->dateEnded)!='')
            $query->andWhere('dateEnded <="'.$this->dateEnded.'"');

        $query->andWhere('status='.$status); //only return enabled records 

        return $dataProvider;
    }
}
