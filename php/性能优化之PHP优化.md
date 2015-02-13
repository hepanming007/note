---
layout: post
title: 性能优化之PHP优化笔记
category：php
tags：php
description: 性能优化,PHP优化笔记
---
 
# 性能优化之PHP优化笔记
## 第1章 课程介绍
 1-1 PHP性能优化初探  
 
 php可能问题引发的性能问题

-  可能1：php语法使用不当
-  可能2: 使用php语言做了它不**擅长**做的
-  可能3：用php连接的服务不给力
-  可能4：php自身的短板   
-  可能5：其他问题
## 第2章 PHP性能问题简析
 2-1 性能问题解析  

- php语言级别的优化
- php周边性能优化
- php语言自身的分析和优化
## 第3章 PHP语言级的性能优化（一）

- 3-1 压力测试工具ab简介  
- 3-2 压力测试工具使用演示  

		 ab  -h 查看帮助
		./ab -n 请求数 -c 并发数 url
        每秒接受请求数   Requets per time
        一个请求要用耗时多久 time per request
        性能优化之前 ab压一下 前后对比 
- 3-3 多使用PHP自身能力  

		多用php内置变量 常量 函数
		可以用 time php -f xxx.php测试执行时间
- 3-4 PHP自身能力性能测试之代码准备  
- 3-5 PHP自身能力性能测试之代码测试  
- 3-6 PHP代码运行流程 

	     *.php
	     ====>scanner  zend引擎
	     Exper
	     ====>Parse
	     opcodes   缓存APC
	     ====>exec
	     output
    
- 3-7 PHP内置函数之间的性能测试  
- 3-8 PHP内置函数之间的性能测试之代码测试  

-第4章 PHP语言级的性能优化（二）

- 4-1 减少PHP魔法函数的使用  `尽可能少用`
- 4-2 禁用错误抑制符  @

		@符合的实际逻辑是在代码前和代码后 添加opcodes，忽略报错
        error_reporting
        vld 扩展可以查看opcodes 
- 4-3 错误抑制符的性能测试 
- 4-4 合理的使用内存和正则表达式 

	 	利用unset及时释放不使用的内存
- 4-5 避免在循环内做运算  
  
		  <?php
	        $str = 'hello world';
	        $strlength = strlen($str);
	        for($i=0；$i<$strlength;$i++)
			{
				// do something...
			}
- 4-6 减少计算密集型业务 
		
		大批量日志分析
		php语言特性决定了php不适合大数据量运算
 		php适合衔接webserver与后端服务与UI呈现 字符串 文本处理

    
- 4-7 务必使用带引号字符串做键值  

		否则会被当做常量 多了没必要的开销
		使用带引号字符串做键值 
## 第5章 PHP周边问题的性能优化
- 5-1 PHP周边问题的分析与阐述（一）  
		
		linux系统环境 硬盘（文件存储）内存 数据库 缓存 网络
- 5-2 PHP周边对PHP程序的影响分析  

		DB 
- 5-3 减少文件类的操作  

		读写内存 读写数据库（有缓存） 读写磁盘（文件）   读取网络数据(隐形因数 网络延迟)
- 5-4 减少PHP发起网络请求
  
		如何优化完了请求
		1.设置超时时间
			连接超时 200ms
			读超时  800ms 
	        写超时  500ms
		2.将串行请求并行化
		  curl_multi_*
          使用swoole扩展

- 5-5 压缩PHP输出的利与弊  
		压缩php接口输出
		
		如何使用压缩 使用gzip（利 利用数据输出，client获取数据更快 弊 产生额外的cpu开销）
		数据量大于10k才启用
- 5-6 PHP缓存复用

	什么情况下做内容的输出缓存

		多次请求 内容不变
	  
- 5-7 Smarty调优和重叠时间窗口思想 
		
		Smarty开启cache
	    重叠时间窗口
- 5-8 PHP旁路处理方案  
		
		由原有的串行话
		====》
		变为多了个旁路

## 第6章 PHP性能问题分析
- 6-1 借助xhprof工具分析PHP性能  
- 6-2 PHP性能分析工具扩展  

		ab
		vld opcode代码的工具	
##第7章 PHP性能瓶颈究极办法


- 7-1 PHP性能瓶颈究极办法
  
 		opcode 缓存 APC
		yac
		扩展实现：通过php扩展代替原PHP代码逻辑中的高频逻辑
		runtime：HHVM
##第8章 课程总结


- 8-1 课程总结  
		
		php语言自身
		php周边环境
		
 
注：本文为Pangee的视频笔记总结