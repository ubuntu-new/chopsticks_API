<?php

namespace api\models\database\webetrela;

/**
 * This is the ActiveQuery class for [[Ingredients]].
 *
 * @see Ingredients
 */
class IngredientsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Ingredients[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Ingredients|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
