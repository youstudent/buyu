<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%users_gold_config}}".
 *
 * @property string $name
 * @property integer $type
 *
 * @property UsersGoldObject[] $usersGolds
 */
class GoldConfigObject extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%gold_config}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['type'], 'integer'],
            [['name'], 'string', 'max' => 32],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Name',
            'type' => 'type',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsersGolds()
    {
        return $this->hasMany(UsersGoldObject::className(), ['gold_config' => 'name']);
    }

    /**
     * 根据状态码获取name
     * @param $code
     * @return mixed
     */
    public static  function getNameByCode($code)
    {
        if(is_numeric($code)){
            $data =self::find()->where(['num_code'=>$code])->select(['name'])->one();
            return $data['name'];
        }else{
            $data =self::find()->where(['num_code'=>$code])->select(['name'])->one();
            return $data['en_code'];
        }
    }

    /**
     * 获取code
     * @param $name
     * @return mixed
     */
    public static function getNumCodeByName($name)
    {
        $data =self::find()->where(['name'=>$name])->select(['num_code'])->one();
        return $data['num_code'];
    }
}
