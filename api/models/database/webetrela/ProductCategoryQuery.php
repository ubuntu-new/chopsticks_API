<?php

namespace api\models\database\webetrela;

/**
 * This is the ActiveQuery class for [[ProductCategory]].
 *
 * @see ProductCategory
 */
class ProductCategoryQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return ProductCategory[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return ProductCategory|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
