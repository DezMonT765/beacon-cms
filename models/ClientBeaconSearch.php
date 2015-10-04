<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ClientBeacons;
use yii\db\ActiveQuery;

/**
 * ClientBeaconSearch represents the model behind the search form about `app\models\ClientBeacons`.
 */
class ClientBeaconSearch extends ClientBeacons
{
    const ITEMS_PER_PAGE = 10;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'client_id', 'beacon_id'], 'integer'],
            [['beaconTitle'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
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
    public function search($params = [])
    {
        $query = ClientBeacons::find();

        $dataProvider = new ActiveDataProvider([
                                                   'query' => $query,
                                                   'pagination' => [
                                                       'pageSize' => self::ITEMS_PER_PAGE,
                                                   ],
                                                   'sort' => [

                                                       'attributes'=>[
                                                           'beaconTitle' => [
                                                               'asc'=>[Beacons::tableName().'.title'=>SORT_ASC],
                                                               'desc'=>[Beacons::tableName().'.title'=>SORT_DESC],
                                                           ],
                                                           'created' => [
                                                               'asc'=>['created'=>SORT_ASC],
                                                               'desc'=>['created'=>SORT_DESC],
                                                           ],
                                                           'updated' => [
                                                               'asc'=>['updated'=>SORT_ASC],
                                                               'desc'=>['updated'=>SORT_DESC],
                                                           ],

                                                       ]
                                                   ]
                                               ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $query->joinWith('beacon',function (ActiveQuery $query) {
            $query->andFilterWhere('title',$this->beaconTitle);
        });

        $query->andFilterWhere([
            'id' => $this->id,
            'client_id' => $this->client_id,
            'beacon_id' => $this->beacon_id,
        ]);

        return $dataProvider;
    }
}
