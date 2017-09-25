<?php
/**
 * Created by PhpStorm.
 * User: 鄢鹏权
 * Date: 2017/9/26
 * Time: 0:13
 */

namespace Castle;

/**
 * php 文件压缩
 * Class Compress
 * @package Castle
 */
class Compress
{
    /**
     * 目录级别的PHP压缩算法
     * @param $from_path
     * @param $to_path
     * @return bool
     */
    function compress($from_path,$to_path){
        $from_path=realpath($from_path);
        $to_path=realpath($to_path);
        if(!is_dir($to_path)){
            mkdir($to_path,0777,true);
        }
        if(is_dir($from_path)&&is_dir($to_path)&&is_writable($to_path)){
            return $this->recursion_compress($from_path,$to_path);
        }else{
            return false;
        }
    }

    private function recursion_compress($path,$to){
        if(!is_dir($path)){return false;}
        if(!is_dir($to)){
            @mkdir($to);
        }
        foreach(scandir($path) as $src){
            if(!in_array($src,['.','..'])){
                $rpath = $path.DIRECTORY_SEPARATOR.$src;
                $rto=$to.DIRECTORY_SEPARATOR.$src;
                if(is_dir($rpath)){
                    $this->recursion_compress($rpath,$rto);
                    continue;
                }elseif(substr($src,-3)=='php'){
                    file_put_contents($rto,php_strip_whitespace($rpath));
                }else{
                    copy($rpath,$rto);
                }
            }
        }
        return true;
    }
}