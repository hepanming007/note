<?php

/**
 * 时间和内存消耗检测工具
 * Class TimerHelper
 */
class TimerHelper
{
    /**
     * 时间
     * @var array $_timer Collection of timers
     */
    private static $_timer = array();

    /**
     * 内存
     * @var array
     */
    private static $_mem = array();

    /**
     * 开始检测
     * start - Start a timer
     *
     * @param string $id The id of the timer to start
     */
    public static function start($id)
    {
        if (isset(self::$_timer[$id]))
            throw new Exception("Timer already set: $id");

        self::startTime($id);
        self::startMem($id);
    }


    /**
     * 开始时间
     * @param $id
     */
    public static function startTime($id)
    {
        self::$_timer[$id] = self::microtime();
    }

    /**开始内存检测
     * @param $id
     */
    public static function startMem($id)
    {
        self::$_mem[$id] = memory_get_usage();
    }

    /**结束时间
     * @param $id
     */
    public static function endTime($id)
    {
        $totalTime = self::microtime() - self::$_timer[$id];
        $totalTimeStr = sprintf('耗时: %.9f s', $totalTime);
        echo "<hr/>";
        echo $totalTimeStr;

    }

    /**结束内存检测
     * @param $id
     */
    public static function endMem($id)
    {
        $endMem = memory_get_usage();
        $memUsed = $endMem - self::$_mem[$id];
        $memUsedStr = sprintf('内存消耗: %01.9f MB', $memUsed / 1024 / 1024);
        echo "\t";
        echo $memUsedStr;
        echo "<hr/>";
    }


    /**
     * 结束检测
     * stop - Stop a timer
     *
     * @param string $id The id of the timer to stop
     */
    public static function stop($id)
    {
       self::endTime($id);
       self::endMem($id);
    }


    /**
     * get microtime float
     */
    public static function microtime()
    {
        list($usec, $sec) = explode(" ", microtime());
        return ((float)$usec + (float)$sec);
    }
}
/*
TimerHelper::start('test');
for ($i = 0; $i < 10000000; $i++) {
    echo ".";
    if ($i % 100 == 0) {
        echo "<br/>";
    }
}
echo TimerHelper::stop('test');

*/
