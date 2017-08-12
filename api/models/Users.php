<?php
/**
 * @link http://www.lrdouble.com/
 * @copyright Copyright (c) 2017 Double Software LLC
 * @license http://www.lrdouble.com/license/
 */
namespace api\models;

use common\models\GoldConfigObject;
use common\models\UsersGoldObject;
use common\models\UsersObject;

class Users extends UsersObject
{
    public $fishGold;
    public $diamond;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['game_id', 'gold','reg_time','status','vip_grade','grade'], 'integer'],
            [['nickname'], 'string', 'max' => 32],
            [['game_id'],'unique','on'=>'add'],
            [['game_id','nickname','gold','diamond','fishGold'],'required','on'=>'add'],

        ];
    }

    /**
     * 添加会员
     * @param $data
     * @return bool
     */
    public function add($data)
    {
        $this->scenario = 'add';
        if($this->load($data,'') && $this->validate())
        {

            $this->reg_time = time();
            $this->gem = $this->fishGold;
            $this->jewel = $this->diamond;
            $this->status   = 1;
            $this->gold_all = 0;
            return $this->save();
          /* {
                $datas = GoldConfigObject::find()->asArray()->all();

                foreach ($datas as $key=>$value)
                {
                    $agencyGold = new UsersGoldObject();
                    $agencyGold->users_id  = $this->id;
                    $agencyGold->gold_config = $value['name'];
                    $agencyGold->gold        = isset($data[$value['en_code']]) ? $data[$value['en_code']] : 0;
                    $agencyGold->sum_gold    = isset($data[$value['en_code']]) ? $data[$value['en_code']] : 0;
                    if($agencyGold->save() == false){
                        $message = $agencyGold->getFirstErrors();
                        $message = reset($message);
                        $this->addError('game_id',$message);
                        return false;
                    }
                }
                return true;
            }*/
        }
    }
}