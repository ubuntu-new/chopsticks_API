<?php

namespace api\models\database\webetrela;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use api\models\database\webetrela\Orders;

/**
 * OrdersSearch represents the model behind the search form of `api\models\database\webetrela\Orders`.
 */
class OrdersSearch extends Orders
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'status', 'payment_method_id', 'delivery_method_id', 'is_discounted', 'driver_id', 'created_at', 'updated_at', 'created_by'], 'integer'],
            [['duration', 'order_data', 'promise_date', 'accept_date', 'finish_date', 'start_delivery', 'end_delivery', 'customer'], 'safe'],
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
        $query = Orders::find();

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
            'user_id' => $this->user_id,
            'status' => $this->status,
            'payment_method_id' => $this->payment_method_id,
            'delivery_method_id' => $this->delivery_method_id,
            'is_discounted' => $this->is_discounted,
            'finish_date' => $this->finish_date,
            'driver_id' => $this->driver_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
        ]);

        $query->andFilterWhere(['like', 'duration', $this->duration])
            ->andFilterWhere(['like', 'order_data', $this->order_data])
            ->andFilterWhere(['like', 'promise_date', $this->promise_date])
            ->andFilterWhere(['like', 'accept_date', $this->accept_date])
            ->andFilterWhere(['like', 'start_delivery', $this->start_delivery])
            ->andFilterWhere(['like', 'end_delivery', $this->end_delivery])
            ->andFilterWhere(['like', 'customer', $this->customer]);

        return $dataProvider;
    }
}
