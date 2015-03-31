<?php

namespace app\models;

use app\commands\RbacController;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * BeaconsSearch represents the model behind the search form about `app\models\Beacons`.
 */
class BeaconsSearch extends Beacons
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'minor', 'major'], 'integer'],
            [['title', 'description', 'picture', 'place', 'uuid'], 'safe'],
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
        $query = Beacons::find();
        if(!Yii::$app->user->can(RbacController::superAdmin))
        {
            $user = Users::getLogged(true);
            $query->joinWith([
                                 'groups' => function($query) use ($user)
                                 {
                                     $query->joinWith([
                                                          'users'=>function($query) use ($user)
                                                        {
                                                            $query->andFilterWhere(['users.id'=>$user->id]);
                                                        }
                                                      ]);
                                 }
                             ]);
        }



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
            'minor' => $this->minor,
            'major' => $this->major,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'uuid', $this->uuid]);

        return $dataProvider;
    }
}
