<?php
class XhprofHelper{

   
    public static function go()
    {
        self::start();
        register_shutdown_function('Xhprof_Helper::end');
    }
    
    public static function  start()
    {
       // ob_start();
        //开启调试
        xhprof_enable();
        xhprof_enable(XHPROF_FLAGS_CPU + XHPROF_FLAGS_MEMORY);
    }

    public static function end()
    {
        //停止监测
        $xhprof_data = xhprof_disable();
        // display raw xhprof data for the profiler run
        $XHPROF_ROOT = XHPROF_DIR;
        include_once $XHPROF_ROOT . "/xhprof_lib/utils/xhprof_lib.php";
        include_once $XHPROF_ROOT . "/xhprof_lib/utils/xhprof_runs.php";
        // 保存统计数据，生成统计ID和source名称
        $xhprof_runs = new XHProfRuns_Default();
        $run_id = $xhprof_runs->save_run($xhprof_data, "xhprof_b2b"); // source名称是xhprof_foo

        $out = ob_get_contents();
        ob_end_clean();
        header("Xhprof:http://127.0.0.1/svn/xhprof/xhprof_html/index.php?run=" . $run_id . "&source=xhprof_b2b");
        echo $out;
        // 查看统计信息
        echo "<div style='margin: 50px auto; padding :10px; width: 92px; font-size: 16px; background: #ff0000;'>
        <a style='color:#ffff00;' href='http://127.0.0.1/xhprof/xhprof_html/index.php?run=" . $run_id . "&source=xhprof_b2b' target='_blank'>XHProf view</a></div>";
        return $run_id;
    }
}

/*******
define('XHPROF_DIR',dirname(dirname((__FILE__))).DIRECTORY_SEPARATOR.'xhprof');
require XHPROF_DIR.'xhprof.php';
XhprofHelper::start();
XhprofHelper::end();
XhporfHelper::go();
 *******/
