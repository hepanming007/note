1.如何在页面调用自定义函数
/e/class/userfun.php
添加已经user_开通的函数
1.……………………………………………………………………………………………………………………………………
function user_helllo($param){
   echo "hello world".$param;
    global $empire;
    $result = $empire->fetch1("select * from phome_ecms_article LIMIT 10");
    var_dump($result);
}
2.…………………………………………………………………………………………………………………………………………
模板管理/标签/管理标签
标签名 hello测试
标签符号 hello
函数名 user_helllo
标签格式：[hello]参数[/hello]
[hello]参数[/hello]
3.………………………………………………………………………………………………………………………………………………
[hello]hello[/hello]
