<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Reestr;

/**
 * ReestrSearch represents the model behind the search form about `app\models\Reestr`.
 */
class ReestrSearch extends Reestr
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_group', 'period'], 'integer'],
            [['number_model', 'number_typical', 'name_violation', 'regulations', 'period_dop', 'description', 'date_create', 'date_edit', 'date_delete', 'log_change'], 'safe'],
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
        $query = Reestr::find();

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
            'id_group' => $this->id_group,
            'period' => $this->period,
            'date_create' => $this->date_create,
            'date_edit' => $this->date_edit,
            'date_delete' => $this->date_delete,
        ]);

        $query->andFilterWhere(['like', 'number_model', $this->number_model])
            ->andFilterWhere(['like', 'number_typical', $this->number_typical])
            ->andFilterWhere(['like', 'name_violation', $this->name_violation])            
            ->andFilterWhere(['like', 'regulations', $this->regulations])
            ->andFilterWhere(['like', 'period_dop', $this->period_dop])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'log_change', $this->log_change]);

        return $dataProvider;
    }
}
