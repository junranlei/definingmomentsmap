<?php
namespace frontend\models;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\modelsUser;
/**
* @property int $publicPermission
* @property string $public_email
*/
class UserSearch extends User
{
    

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'safeFields' => [['username', 'public_email', 'mapCount','histCount'], 'safe'],
            
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
        $query = User::find()->joinWith('profile')
        ->joinWith('map')
        ->joinWith('historicalfact')
        ->select(['user.id as id', 'username', 'profile.public_email as public_email',
         'COUNT(distinct map.id) AS mapCount','COUNT(distinct historicalFact.id) AS histCount'])->distinct()
        ->groupBy(['user.id']);
        //->orderBy('histCount desc,mapCount desc');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->setSort([
            'attributes' => [
                         
                'histCount' => [
                    'asc' => ['histCount' => SORT_ASC],
                    'desc' => ['histCount' => SORT_DESC],
                    //'label' => 'Hist Count',
                    'default' => SORT_DESC
                ],
                'mapCount' => [
                    'asc' => ['mapCount' => SORT_ASC],
                    'desc' => ['mapCount' => SORT_DESC],
                    //'label' => 'Map Count',
                    'default' => SORT_DESC
                ],
                'id',               
                'username',
                'public_email', 
                
            ],
            'defaultOrder' => ['histCount' => SORT_DESC,'mapCount' => SORT_DESC]
        ]);
    

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        /*if ($this->created_at !== null) {
            $date = strtotime($this->created_at);
            $query->andFilterWhere(['between', 'created_at', $date, $date + 3600 * 24]);
        }

        if ($this->last_login_at !== null) {
            $date = strtotime($this->last_login_at);
            $query->andFilterWhere(['between', 'last_login_at', $date, $date + 3600 * 24]);
        }*/

        $query
            ->andWhere(['blocked_at' => null])
            ->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'public_email', $this->public_email])
            ->andFilterHaving(['>=', 'histCount', $this->histCount])
            ->andFilterHaving(['>=', 'mapCount', $this->mapCount]);


        return $dataProvider;
    }

}