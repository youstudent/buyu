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
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title','content','status','location'],'required'],
            [['manage_id', 'status', 'time'], 'integer'],
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
            $this->manage_id    = \Yii::$app->session->get('manageId');
            $this->manage_name  = \Yii::$app->session->get('manageName');
            $this->time         = time();
            return $this->save();
        }
    }
}