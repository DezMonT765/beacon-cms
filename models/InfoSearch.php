<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Info;
use yii\db\ActiveQuery;

/**
 * InfoSearch represents the model behind the search form about `app\models\Info`.
 */
class InfoSearch extends Info
{
    const ITEMS_PER_PAGE = 10;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'client_id'], 'integer'],
            [['key', 'value','clientEmail'], 'safe'],
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
        $query = Info::find();

        $dataProvider = new ActiveDataProvider([
                                                   'query' => $query,
                                                   'pagination' => [
                                                       'pageSize' => self::ITEMS_PER_PAGE,
                                                   ],
                                                   'sort' => [

                                                       'attributes'=>[
                                                           'key' => [
                                                               'asc'=>['message'=>SORT_ASC],
                                                               'desc'=>['message'=>SORT_DESC],
                                                           ],
                                                           'value' => [
                                                               'asc'=>['value'=>SORT_ASC],
                                                               'desc'=>['value'=>SORT_DESC],
                                                           ],
                                                           'clientEmail' => [
                                                               'asc'=>[ClientUsers::tableName().'.email'=>SORT_ASC],
                                                               'desc'=>[ClientUsers::tableName().'.email'=>SORT_DESC],
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



        $query->joinWith(['client'=>function(ActiveQuery $query) {
            $query->andFilterWhere(['like',ClientUsers::tableName().'.email', $this->clientEmail]);
        }]);

        $query->andFilterWhere(['like', 'key', $this->key])
            ->andFilterWhere(['like', 'value', $this->value]);

        return $dataProvider;
    }
}
