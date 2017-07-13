<?php
/**
 * @link http://www.lrdouble.com/
 * @copyright Copyright (c) 2017 Double Software LLC
 * @license http://www.lrdouble.com/license/
 */
namespace backend\models;

use common\models\NoticeObject;

class Notice extends NoticeObject
{
    public static $get_type=['0'=>'暂无奖励','1'=>'金币',2=>'钻石'];
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title','content','status','location'],'required'],
            [['manage_id', 'status', 'time','number','type'], 'integer'],
            [['content'], 'string'],
            [['manage_name'], 'string', 'max' => 32],
            [['title'], 'string', 'max' => 64],
            [['notes', 'location'], 'string', 'max' => 255],
        ];
    }

    /**
     * 添加一个通知
     * @param array $data
     * @return bool
     */
    public function add($data = [])
    {
        if($this->load($data) && $this->validate())
        {
            if ($this->type == 1 || $this->type == 2) {
                if (empty($this->number)) {
                    $this->addError('message', '请选择赠送的数量!!');
                    return false;
                }
            }
            if ($this->number && $this->type==0){
                $this->addError('message', '请选择赠送类型!!');
                return false;
            }
            $this->manage_id    = \Yii::$app->session->get('manageId');
            $this->manage_name  = \Yii::$app->session->get('manageName');
            $this->time         = time();
            return $this->save();
        }
    }
    
    
    
    public function edit($data = []){
        if($this->load($data) && $this->validate())
        {
            if ($this->type == 1 || $this->type == 2) {
                if (empty($this->number)) {
                    $this->addError('message', '请选择赠送的数量!!');
                    return false;
                }
            }
            if ($this->number && $this->type==0){
                $this->addError('message', '请选择赠送类型!!');
                return false;
            }
            return $this->save();
        }
    }
}