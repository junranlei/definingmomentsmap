<?php

namespace frontend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\Historicalfact;

/**
 * HistoricalfactSearch represents the model behind the search form of `frontend\models\Historicalfact`.
 */
class HistoricalmapSearch extends Historicalfact
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
    public function search($params)
    {
        $query = Historicalfact::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);



        if($this->keyword!=null && trim($this->keyword)!='')
            $query->andWhere('title like "%' . $this->keyword . '%" or description like "%' . $this->keyword . '%"');
        

        if($this->date!=null && trim($this->date)!='')
            $query->andWhere('date >="'.$this->date.'"');
        if($this->dateEnded!=null && trim($this->dateEnded)!='')
            $query->andWhere('dateEnded <="'.$this->dateEnded.'"');

        return $dataProvider;
    }
}
