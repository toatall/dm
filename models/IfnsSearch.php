<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Ifns;

/**
 * IfnsSearch represents the model behind the search form about `app\models\Ifns`.
 */
class IfnsSearch extends Ifns
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code_no', 'name_no', 'date_create', 'date_edit', 'log_change'], 'safe'],
            [['disable_no'], 'integer'],
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
        $query = Ifns::find();

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
            'disable_no' => $this->disable_no,
            'date_create' => $this->date_create,
            'date_edit' => $this->date_edit,
        ]);

        $query->andFilterWhere(['like', 'code_no', $this->code_no])
            ->andFilterWhere(['like', 'name_no', $this->name_no])
            ->andFilterWhere(['like', 'log_change', $this->log_change]);

        return $dataProvider;
    }
}
