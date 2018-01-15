<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Ifns]].
 *
 * @see Ifns
 */
class IfnsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return Ifns[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Ifns|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
