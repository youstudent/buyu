<?php
/**
 * @link http://www.lrdouble.com/
 * @copyright Copyright (c) 2017 Double Software LLC
 * @license http://www.lrdouble.com/license/
 */
namespace common\services;

class Request
{
    /**
     * @param string $url
     * @param string $param
     * @return array|bool
     */
    static function request_post($url = '', $param = '') {
//        return ['code'=>1,'message'=>"游戏服务器相应失败"];

        if (empty($url) || empty($param)) {
            return false;
        }
        
//        'game_id'=>$model->game_id,'gold'=>$this->pay_gold_num,'gold_config'=>GoldConfigObject::getNumCodeByName($this->pay_gold_config);
        /*$datas['uid']      = $param['game_id'];
        $datas['depositType']   = $param['gold_config'];
        $datas['gold']          = $param['gold'];*/
        $postUrl = $url;
        $curlPost = http_build_query($param);
        $ch = curl_init();//初始化curl
        curl_setopt($ch, CURLOPT_URL,$postUrl);//抓取指定网页
        curl_setopt($ch, CURLOPT_HEADER, 0);//设置header
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
        $data = curl_exec($ch);//运行curl
        curl_close($ch);
        $data = json_decode($data);
        if($data->code == 1)
        {
            return ['code'=>1,$data->data];
        }
        return ['code'=>0,'message'=>$data->message];
    }
    
    /**
     * @param string $url
     * @param $raw
     * @return array|bool
     */
    static function request_post_raw($url = '', $raw) {
//        return ['code'=>1,'message'=>"游戏服务器相应失败"];
        if (empty($url) || empty($raw)) {
            return false;
        }
        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $raw
        ));
        $data = curl_exec($ch);//运行curl
        curl_close($ch);
        $data = json_decode($data);
        //var_dump($data);EXIT;
        if($data->code == 1)
        {
            return ['code'=>1,$data->data];
        }
        return ['code'=>0,'message'=>$data->message];
    }
    
    
    /**
     * @param string $url
     * @param $raw
     * @return array|bool
     */
    static function request_post_raws($url = '', $raw) {
//        return ['code'=>1,'message'=>"游戏服务器相应失败"];
        if (empty($url) || empty($raw)) {
            return false;
        }
        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $raw
        ));
        $data = curl_exec($ch);//运行curl
        curl_close($ch);
        $data = json_decode($data);
        if($data->code == 1)
        {
            return ['code'=>1,'id'=>$data->id];
        }
        return ['code'=>0,'message'=>$data->message];
    }
    /**
     * get调用接口
     * @param string $url
     * @return mixed
     */
    static function request_get($url = '')
    {
        $GetRetData = json_decode(file_get_contents($url));
        if($GetRetData->ok){
            $data['code'] = 1;
            return $data;
        }else{
            $data['code'] = 0;
            $data['message'] = '未知错误！';
            return $data;
        }
    }

}