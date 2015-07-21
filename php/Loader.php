<?php

/**
 * 自动载入
 * Class Loader
 */
class Loader{
    /**
     *载入助手
     * @param $classname
     */
    public  static function loadHelper($classname){
        $file = './helper/'.$classname.'.php';
        if(is_file($file)){
            require $file;
        }
    }


    /**
     * 载入类库
     * @param $classname
     */
    public static function loadlibrary($classname){
        $file = './library/'.$classname.'.php';
        if(is_file($file)){
            require $file;
        }
    }

    
}

spl_autoload_register(array('Loader','loadHelper'));//类
