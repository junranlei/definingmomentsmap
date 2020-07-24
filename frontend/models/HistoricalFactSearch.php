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
     * @param int $matchModel matched model to return related models if $matchModel is not null (default null return all models), $related ==1 or return unrelated models if related==0
     * @param int $related if $matchModel is not null return related models or unrelated model according to $related value.
     * @param int $manual manual related or not
     * @return ActiveDataProvider
     */
    public function search($params, $status=1,$matchModel=null,$related=1,$manual=0)
    {
        if($matchModel!=null){
            if($related){
                if($manual){
                    $query = HistoricalFact::find()->joinWith('features')
                    ->innerJoin('historicalRelated',"(historicalFact.id=historicalRelated.histId2 and historicalRelated.histId1=".$matchModel->id.") or (historicalFact.id=historicalRelated.histId1 and historicalRelated.histId2=".$matchModel->id.")")
                    ->groupBy(['historicalFact.id']);
                }else{
                    $query = HistoricalFact::find()->joinWith('features')
                    //moved to where condition
                    //->innerJoin('historicalRelated',"MATCH(historicalFact.title) AGAINST('".trim($matchModel->title)."')")
                    ->groupBy(['historicalFact.id']);

                }
            }else{
                //if no related no manual or auto
                //if($manual){
                    $query = HistoricalFact::find()->joinWith('features')
                    ->leftJoin('historicalRelated',"((historicalFact.id=historicalRelated.histId2 and historicalRelated.histId1=".$matchModel->id.") or (historicalFact.id=historicalRelated.histId1 and historicalRelated.histId2=".$matchModel->id."))")
                    ->groupBy(['historicalFact.id']);
                //}else{
                    //$query = HistoricalFact::find()->joinWith('features')
                    //->innerJoin("!MATCH(historicalFact.title) AGAINST('".trim($matchModel->title)."')")
                    //->groupBy(['historicalFact.id']);

                //}
            }
        }else{
            $query = HistoricalFact::find()->joinWith('features')
            ->groupBy(['historicalFact.id']);
        }

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
            if($related){
                if(!$manual){
                    $query->andWhere("MATCH(historicalFact.title) AGAINST('".trim($matchModel->title)."')");
                }
                $query->andWhere("historicalFact.id!=".$matchModel->id); 
            }else{
                //exluded auto matched below
                //$query->andWhere("historicalRelated.histId1 is null and !MATCH(historicalFact.title) AGAINST('".trim($matchModel->title)."')"); 
                // not excluded auto matched 
                $query->andWhere("historicalRelated.histId1 is null"); 
                $query->andWhere("historicalFact.id!=".$matchModel->id); 

            }
        }
        return $dataProvider;
    }
}
