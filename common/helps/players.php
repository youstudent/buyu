<?php

namespace  common\helps;


class players
{
    /**
     * @param $id
     * @return mixed
     * 获取玩家房间号
     */
    public static function getRoom($id){
        $redis = self::getReids();
       if ($data =$redis->HGETALL('playerRoom')){
            if (array_key_exists($id,$data)){
                return $data[$id];
            }
                return '不在房间';
        }
        return '没有房间';
    }
    
    /**
     * @param $room
     * @return mixed
     * 获取玩家房间人数
     */
    public static function getRoomNmu($room){
        $redis = self::getReids();
       if ($data =$redis->HGETALL('roomPlayerNum')){
           if (array_key_exists($room,$data)){
               return $data[$room];
           }
           return '不在房间';
       }
        return '房间没有人';
    }
    
    /**
     * @return \Redis
     *  连接redis
     */
    public static function getReids(){
        $ip = "192.168.2.235";
        $port = 6379;
        $redis = new \Redis();
        $redis->pconnect($ip, $port, 1);
        return $redis;
    }
    
}