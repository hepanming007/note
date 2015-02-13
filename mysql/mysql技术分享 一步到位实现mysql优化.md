# MySQL的特点 #
- Not ORACLE， Not SQL Server， Not PostgreSQL
- Not Excel, Not Access
- NOT FILE STORAGE
- Not Calculator
- Not Seach Engin
- Not ...
- MySQL is MySQL

	    1. oltp数据库 事物一致性 永久存储 acid
	    2. 不是计算器
	    3. 不是搜索引擎 不要在mysql做很频繁的模糊检索 全文检索 不擅长 Sphinx、coreseek  
	    

# CPU的利用特点  top i 1  #
- 5.1 多核心支持较弱 
- 5.1 可利用4个核
- 5.5 可利用24个核
- 5.6 可利用64个核

        每个连接对应一个线程，每个并发query只能使用到一个核(
    	每个query只能用到一个核心 会产生等待 排队原理 尽可能让每个事务短小精悍 尽快提交)
# 内存利用特点 #
1. 类似ORACLE的SGA(全局内存块)、 PGA(每个session的内存块)模式，注意PGA不宜分配过大(内存不过用 操作系统会干掉某些内存大的进程)
1. 内存管理简单、有效。在高TPS、高并发环境下，可增加物理内存以减少物理IO，提高并发性能
1. 官方分支锁并发竞争比较严重， MariaDB、 Percona进行优化
1. 有类似ORACLE library cache的query cache，但效果不佳，建议关闭
1. 执行计划没有缓存（类似ORACLE的library cache）
1. 通常内存建议按热点数据总量的15%-20%来规划，专用单实例则可以分配物理内存的50~70%左右
1. 类似K-V简单数据，采用memcached、 Redis等NOSQL来缓存

# 对磁盘的利用特点 #
1. binlog、 [innodb]redo log、 [innodb]undo log主要顺序IO
1. datafile是随机IO和顺序IO都有
1. OLTP业务以随机IO为主，建议加大内存，尽量合并随机IO为顺序IO
1. OLAP业务以顺序IO为主，极大内存的同时增加硬盘数量提高顺序IO性能
1. MyISAM是堆组织表（ HOT）， InnoDB是索引组织表（ IOT）
1. InnoDB相比MyISAM更消耗磁盘空间

# 优化思路 #
1. 确认问题  监控和业务指标  业务系统【用户注册 用户登录 用户交易】 活动
1. 确认瓶颈  mysql这一层 表面现象 cpu非常高 索引使用不当导致的cpu利用率非常高 好几千 或者是宾并发太高 如果是内存发生swamp 内存分配太少 内存分配太多 如果是iowait太高的话 内存不足 io性能设备太差 索引使用不当 或者频繁读取大量数据  select * 这种类型  
1. 排序 group by 分组 通过表面现象确认瓶颈
1. 确认方案  如果并发太高 采用5.6以上 Percona 支持线程池 可以  如果是排序或者分组 索引的方式来解决 swap 适当调整类型 50-70% 尽可能覆盖热点数据
1. 制定方案
1. 测试方案
1. 实施方案
1. 回顾反馈 方案实施的效果跟踪 收集系统的状态 对比=====》 系统负载+业务指标  基准压力测试

# 确认瓶颈   cpu io 内存的瓶颈
## 系统级别 ##
1. top  整个系统当前的状态  cpu 内存 快速定位
1. vmstat
1. sar  -u cpu -d io -r 内存
1. iotop 看哪个进程消耗io最高
1. dstat
1. oprofile 神器 
## mysql级别 ##
1. 慢查询  优先处理发生频次最多的慢查询 最有可能导致业务高峰期出现 让你瓶颈一直存在 引发雪崩的效应  
1. slow log
1. show global status  链接数 超时连接设置短一点  buffer的命中率 慢查询数量 tps  负载很高跑不上去
1. show processlist   连接数  少数活跃的 大量不活跃的 
1. show engine innodb status
1. pt-ioprofile

	    连接数 活跃和不活跃的 设置interactive_timeout wait_timeout
	    的timeout值 减少不活跃的连接
	    tps每秒事物数\qps每秒请求数\DML_Active 每秒的dml数量
	    tps =(handler_commit_d+hander_rollback_d)/uptime_d
	    qps = (Question_d2-Question_d1)/uptime_d
	    DML_Active = (com_select+com_insert_id+com_update_id+com_delete)/uptime_d
	    各种buffer\cache的命中率
	    innodb_buffer_pool_wait_free
	    wait_free
	    wait
	    innodb_row_lock_current_wait
	    innodb_row_lock_time_avg
	    innodb_row_lock_wait
	    slow_queries
	    table_locks+immediate
	    Table_locks_waited
		pt-ioprofile  直接查看innodb内部哪些文件被频繁的读写 
		可以定位瓶颈所在 比如想看当前哪些表是活跃的 
		show processlist  
		show engine innodb status 锁 事物 等待


# 优化 #
## 硬件优化 – BIOS设置优化 ##
1. System Profile（系统配置）选择Performance Per Watt Optimized(DAPC)，发挥最大功耗
1. 性能
1. Memory Frequency（内存频率）选择Maximum Performance（最佳性能）
1. C1E， 允许在处理器处于闲置状态时启用或禁用处理器切换至最低性能状态，建议关闭
1. （默认启用）
1. C States（ C状态）， 允许启用或禁用处理器在所有可用电源状态下运行，建议关闭（默
1. 认启用）
1. 硬件优化 – IO子系统优化
1. 阵列卡配备CACHE及BBU模块， 提高IOPS
1. 设置写策略为WB，或者FORCE WB，禁用WT策略
1. 关闭预读，没必要预读，那点宝贵的CACHE用来做写缓存
1. 阵列级别使用RAID 1+0， 而不是RAID 5
1. 关闭物理磁盘cache策略，防止丢数据
1. 使用高转速硬盘，不使用低转速盘
1. 使用SSD或者PCIe-SSD盘

## 系统优化 ##
1.  vm.swappiness
1. /sys/block/sdX/queue/scheduler
1. 文件系统首选xfs，其次ext4， zfs也很不错，但在linux下不是那么可靠
## 配置优化 – 全局参数 ##
1.  interactive_timeout[断开一个活跃连接的时间]、 wait_timeout[断开一个不活跃链接的时间]  值设置一样 建议300以内
1.  open_files_limit 如果不够用导致 too many open files 内核级别的也要修改 ulimit
1.  max_connections  适当的调小 优化业务
1.  thread_pool 官方版本不支持 
## 内存相关参数 ##
	mysql使用总内存=gloabl_buffers+thread_buffers
	global buffer(全局内存分配总和)
	= innodb_buffer_pool_size
	+innodb_additional_mem_pool_size
	+innodb_log_buffer_size
	+key_buffer_size
	+query_cache_size
	+table_open_cache
	+table_definition_cache
	+thread_cache_size
	
	All thread buffer(会话/线程级内存分配)
	max threads *(
	read_buffer_size
	+read_rnd_buffer_size
	+sort_buffer_size
	+join_buffer_size
	+binlog_cache_size
	+tmp_talbe_size   最大不超过100m
	+thread_stack
	+net_buffer_length
	+bulk_insert_buffer_size)

## 配置优化 – InnoDB相关 ##
-  innodb_buffer_pool_size  缓存大量的脏数据 缓存事物的信息 缓存锁的信息 最重要的内存参数 50%-70% ips太小 
1.导致问题tps很低 大量的锁2.可能会table full 3.锁不够用
-  innodb_data_file_path  建议至少设置成1个G 
-  innodb_flush_log_at_trx_commit
-  innodb_log_buffer_size & innodb_log_file_size
-  transaction_isolation  事物隔离机制
## 配置优化 – 其他 ##
- general_log  通常不打开  有需要在打开
- log_bin      一定要打开
- sync_binlog
- long_query_time
- log_slow_query

# 设计优化 – 先入为主 #
## 默认使用InnoDB引擎，可适用99%以上业务场景 ##
- 并发 – 没人愿意所有的请求都被串行的执行完成
- 数据一致性 – 交易类业务要求数据高一致性，确保数据完整性
- Crash Recovery – 故障自动修复，修复相比MyISAM速度更快
- 更高存取效率 – 行锁减低锁粒度，更高内存利用率提高数据、索引存取效率

## 设计优化 – Schema设计 ##
-  不管InnoDB与否，都设计自增列主键
-  日期时间、 IPV4适用INT UNSIGNED存储
-  性别、是否等枚举类型，使用ENUM/TINYINT，而非CHAR/VARCHAR
-  杜绝TEXT/BLOB，可以做垂直拆分，或者转成MyISAM表
- USERNAME： VARCHAR(255) VS VARCHAR(30) vs CHAR(30)
-  所有字段显式定义NOT NULL
- 基数（ Cardinality ）很低的字段不创建索引（ MySQL还不支持 bitmap 索引）
- 采用第三方系统实现text/blob全文检索
- 常用排序（ ORDER BY）、分组（ GROUP BY）、取唯一（ DISTINCT）字段上创建索引
- 多使用联合索引，少用单独索引
-  字符型列需要索引时，创建前缀索引  列如username 30 可以创建18位的前缀索引

## 设计优化 – 无法使用索引的场景 ##
- 通过索引扫描的记录数超过30%，变成全表扫描
- 联合索引中，第一个索引列使用范围查询 > < >= <= BETWEEN SEELCT * FROM table where key_part1>? and key_part2 =?
-  联合索引中，第一个查询条件不是最左索引列     SEELCT * FROM table where key_part2>? and key_part3 =?
-  模糊查询条件列最左以通配符 % 开始
-  内存表(HEAP 表)使用HASH索引时，使用范围检索或者ORDER BY  只能做等或者不等的检索
- 两个独立索引，其中一个用于检索，一个用于排序
- 表关联字段类型不一样（也包括长度不一样） 会导致类型的隐式转化
- 索引字段条件上使用函数  where key +？=？ where fun(xxx)

## 设计优化 – 常见杀手级SQL ##
-  SELECT * vs SELECT col1, col2  减少磁盘io 减少网络
-  ORDER BY RAND()
-  LIMIT huge_num, offset
-  SELECT COUNT(*) on InnoDB table
-  WHERE func(key_col) = ?
-  WHERE key_part2 =? AND key_part3 =?
-  WHERE key_part1 > ? AND key_part2 =?
-  SELECT … WHERE key_col + ? = ?

		SELECT a.x ...
		FROM a
		ORDER BY a.y LIMIT 11910298, 20;
		采用子查询进行优化 =>
		SELECT a.x ...
		FROM a
		WHERE a.pkid >(SELECT pkid FROM a WHERE pkid >= 11910298 ORDER BY a.y) LIMIT 20;

## 设计优化 - 架构设计 ##
- 减少物理I/O，让MySQL闲下来  前端各种cache
- 转变随机I/O为顺序I/O  本地队列 最后合并写入
- 减小活跃数据
- 分库分表
- OLTP、 OLAP分离

## 优化工具 ##
- pt-ioprofile
- mysqldumpslow
- pt-query-digest + Box Anemometer/Query-Digest-UI

## 常见优化误区 ##
- 分配内存越多越好，可能导致OS Swap
- session级内存分配过大，导致OOM
- 索引越多越好，可能导致更多IO
- Qcache设置过大，实际效果差
- 认为MyISAM的只读效率远高于InnoDB
- 人云亦云，不自己动手实践
- 过度优化，反而带来成本的上升