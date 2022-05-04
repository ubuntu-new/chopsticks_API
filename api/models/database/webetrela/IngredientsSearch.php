<?php

namespace api\models\database\webetrela;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use api\models\database\webetrela\Ingredients;

/**
 * IngredientsSearch represents the model behind the search form of `api\models\database\webetrela\Ingredients`.
 */
class IngredientsSearch extends Ingredients
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'weight', 'isPremium', 'base', 'product_category_id'], 'integer'],
            [['name', 'name_ge', 'name_ru', 'class_name', 'status', 'price','url'], 'safe'],
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
        $query = Ingredients::find();

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
            'weight' => $this->weight,
            'isPremium' => $this->isPremium,
            'base' => $this->base,
            'product_category_id' => $this->product_category_id,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'name_ge', $this->name_ge])
            ->andFilterWhere(['like', 'name_ru', $this->name_ru])
            ->andFilterWhere(['like', 'url', $this->url])
            ->andFilterWhere(['like', 'class_name', $this->class_name])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'price', $this->price]);

        return $dataProvider;
    }
}
