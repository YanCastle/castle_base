<?php
/**
 * Created by PhpStorm.
 * User: 鄢鹏权
 * Date: 2017/9/26
 * Time: 0:00
 */

namespace Castle;


class Config
{
    protected static $Config=[];

    /**
     * 加载配置文件
     * @param $file
     * @param string $parse
     * @return array|bool|mixed
     */
    function load($file,$parse='php'){
        if(!file_exists($file)){
            //从模块内的配置文件开始查找，如果文件存在则替换，否则return[]
            $info = pathinfo($file);
            if($info['dirname']==='.'&&$file!=($info['dirname'].DIRECTORY_SEPARATOR.$info['basename'])){
                foreach ([APP_PATH.DIRECTORY_SEPARATOR.'Common/Config'] as $dir){
                    if(file_exists($dir.DIRECTORY_SEPARATOR.$info['basename'])){
                        $file=$dir.DIRECTORY_SEPARATOR.$info['basename'];
                    }
                }
            }
            if(!file_exists($file)){
                return [];
            }
        }
        $ext  = pathinfo($file,PATHINFO_EXTENSION);
        switch($ext){
            case 'php':
                return include $file;
            case 'ini':
                return parse_ini_file($file);
            case 'yaml':
                return yaml_parse_file($file);
            case 'xml':
                return (array)simplexml_load_file($file);
            case 'json':
                return json_decode(file_get_contents($file), true);
            default:
                if(function_exists($parse)){
                    return $parse($file);
                }else{
                    L(E('_NOT_SUPPORT_').':'.$ext);
                }
        }
    }

    /**
     * 获取配置信息
     * @param $key
     * @return mixed
     */
    function get($key){
        return self::$Config[$key];
    }

    /**
     * 设置配置信息
     * @param $key
     * @param null $value
     */
    function set($key,$value=null){
        if(is_array($key)){
            self::$Config=array_merge(self::$Config,$key);
        }else{
            self::$Config[$key]=$value;
        }
    }

    /**
     * 删除配置信息
     * @param $key
     */
    function del($key){
        unset(self::$Config[$key]);
    }
}