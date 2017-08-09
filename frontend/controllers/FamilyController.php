<?php

namespace frontend\controllers;

use common\models\AddFamily;

class FamilyController extends \yii\web\Controller
{
    
    /**
     *  家族申请列表
     * @return string
     */
    public function actionList()
    {
        $model = new AddFamily();
        $data = $model->getList(\Yii::$app->request->get());
        return $this->render('list',$data);
    }

}
