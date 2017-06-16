<?php
/**
 * User: harlen-angkemac
 * Date: 2017/6/15 - 下午9:35
 *
 */

namespace api\models;


use common\models\UserPayObject;

class UserPay extends UserPayObject
{
    public function clientPay($post)
    {
        if(!$this->load($post, '')){
            return null;
        }

        $user = Users::findOne(['game_id'=>$this->game_id]);
        if(!isset($user)){
            $this->addError('user_id', '玩家不存在');
        }
        $this->gold_config = '金币';

        if(!$user->payGold($this->gold_config, $this->gold)){
            $this->addError('gold', '充值失败');
            return false;
        }
        $this->agency_name = '客户端';
        $this->user_id = $user->id;
        $this->nickname = $user->nickname;
        $this->status = 1;
        $this->type= '充值';

        return $this->save() ? $this : false;
    }
}