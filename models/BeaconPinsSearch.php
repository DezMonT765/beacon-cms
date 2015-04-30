<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\BeaconPins;

/**
 * BeaconPinsSearch represents the model behind the search form about `app\models\BeaconPins`.
 */
class BeaconPinsSearch extends BeaconPins
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'x', 'y', 'canvas_width', 'canvas_height'], 'integer'],
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
    public function search($params)
    {
        $query = BeaconPins::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'x' => $this->x,
            'y' => $this->y,
            'canvas_width' => $this->canvas_width,
            'canvas_height' => $this->canvas_height,
        ]);

        return $dataProvider;
    }
}
