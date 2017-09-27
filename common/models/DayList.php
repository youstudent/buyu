<?php

namespace common\models;

use backend\models\Shop;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%day_list}}".
 *
 * @property string $id
 * @property string $name
 * @property string $url
 * @property string $type
 */
class DayList extends \yii\db\ActiveRecord
{
    public static $give;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%day_list}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'type_id','status'], 'string', 'max' => 20],
            [['url'], 'string', 'max' => 255],
            [['content','description'],'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'url' => 'Url',
            'type_id' => 'Type',
        ];
    }
    
    /**
     * 初始化赠送礼包配置
     */
    // 创建模型自动设置赠送礼品类型
    public function __construct(array $config = [])
    {
        //查询 道具列表中的数据
        $data  = Shop::find()->asArray()->all();
        //将道具数组格式化成  对应的数组
        $new_data = ArrayHelper::map($data,'id','name');
        //自定义 赠送类型
        $datas = ['gold'=>'金币','diamond'=>'钻石','fishGold'=>'鱼币'];
        //将数据合并 赋值给数组
        self::$give= ArrayHelper::merge($datas,$new_data);
        parent::__construct($config);
    }
}
