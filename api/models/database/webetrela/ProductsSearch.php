<?php

namespace api\models\database\webetrela;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use api\models\database\webetrela\Products;

/**
 * ProductsSearch represents the model behind the search form of `api\models\database\webetrela\Products`.
 */
class ProductsSearch extends Products
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'w_id', 'category_id', 'weight', 'is_special', 'created_at', 'status'], 'integer'],
            [['category_name', 'name', 'price', 'class_name', 'web', 'nutritional', 'description', 'is_sticks', 'price_sale', 'gallery', 'is_promo', 'name_ge', 'name_ru', 'description_ge', 'description_ru'], 'safe'],
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
        $query = Products::find();

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
            'w_id' => $this->w_id,
            'category_id' => $this->category_id,
            'weight' => $this->weight,
            'is_special' => $this->is_special,
            'created_at' => $this->created_at,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'category_name', $this->category_name])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'price', $this->price])
            ->andFilterWhere(['like', 'class_name', $this->class_name])
            ->andFilterWhere(['like', 'web', $this->web])
            ->andFilterWhere(['like', 'nutritional', $this->nutritional])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'is_sticks', $this->is_sticks])
            ->andFilterWhere(['like', 'price_sale', $this->price_sale])
            ->andFilterWhere(['like', 'gallery', $this->gallery])
            ->andFilterWhere(['like', 'is_promo', $this->is_promo])
            ->andFilterWhere(['like', 'name_ge', $this->name_ge])
            ->andFilterWhere(['like', 'name_ru', $this->name_ru])
            ->andFilterWhere(['like', 'description_ge', $this->description_ge])
            ->andFilterWhere(['like', 'description_ru', $this->description_ru]);

        return $dataProvider;
    }
}
