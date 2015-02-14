##Less简介
 动态样式语言，属于CSS预处理语言的一种，它使用类似CSS的语法，为CSS的赋予了动态语言的特性
 如变量、继承、运算、函数，更方便CSS编写和维护
 
### 编译工具：Koala
-  也可以使用Node.js或者浏览器端进行编译
-  声明文档头：@charset "utf-8"
-  将编写好的less文件拖到Koala中，进行编译，编译后生成css文件，然后将css文件再引入的HTML页面当中
-  编写还是在less文件中编写

### Less中的注释：
1. // 不会被编译的；
1. /* */ 会被编译的；

### Less声明变量 
其实就是个占位符
关键字@+变量名：值;。例：@test_color:red;
###混合
【重用】混合允许开发者仅仅通过包含类名将一个类当中的所有属性全部应用于另一个类，同时也可以像函数一样提供参数使用。
1.直接用.class名称 2.传参数 类似函数
		
	.border_02(@border_width){
	     border: solid yellow @border_width;
	}
  	 //默认值
	border_03(@border_width: 30px){
		 border: solid green @border_width;
    }