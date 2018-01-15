<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Reestr]].
 *
 * @see Reestr
 */
class ReestrQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return Reestr[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Reestr|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
