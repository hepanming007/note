<?php
/**
 * 分类信息格式化工具类
 */

class Category {
	protected $sonName;
	protected $parentName;

	/**
	 * 初始化工作
	 * @param string $son    子类标识的名称
	 * @param string $parent 父类标识的名称
	 */
	public function __construct($son = 'id', $parent = 'pid') {
		$this->sonName = $son;
		$this->parentName = $parent;
	}

	/**
	 * 根据传递的父类ID获取所有的子级分类
	 * 组合一维数组
	 * @param  [type]  $data 分类数组
	 * @param  integer $pid  父类id
	 * @param  integer $lev  父类所属层级
	 * @return [type]        格式化后的数组信息
	 */
	public function unlimitedForLevel($data, $pid = 0, $lev = 0) {
		$arr = array();

		foreach($data as $v) {
			if($v[$this->parentName] == $pid) {
				$v['level'] = $lev + 1;
				$arr[] = $v;
				$arr = array_merge($arr, self::unlimitedForLevel($data, $v[$this->sonName], $lev + 1));
			}
		}

		return $arr;
	}

	/**
	 * 根据传递的父类ID获取所有的子级分类
	 * 组合多维数组
	 * @param  [type]  $data 分类数组
	 * @param  integer $pid  父类id
	 * @return [type]        格式化后的数组信息
	 */
	public function unlimitedForLayer($data, $pid = 0) {
		$arr = array();

		foreach($data as $v) {
			if($v[$this->parentName] == $pid) {
				$v['children'] = self::unlimitedForLayer($data, $v[$this->sonName]);
				$arr[] = $v;
			}
		}

		return $arr;
	}

	/**
	 * 根据传递子类ID获取所有的父级分类
	 * @param  [type]  $data 分类数组
	 * @param  integer $id   子类id
	 * @return [type]        父类数组信息
	 */
	public function getParents($data, $id) {
		$arr = array();

		foreach($data as $v) {
			if($v[$this->sonName] == $id) {
				$arr[] = $v;
				$arr = array_merge(self::getParents($data, $v[$this->parentName]), $arr);
			}
		}

		return $arr;
	}

	/**
	 * 根据传递的父类ID获取所有的子级分类ID
	 * 注意返回值中不包括传递进来的父类ID
	 * @param  [type] $data 分类数组
	 * @param  [type] $pid  父类id
	 * @return [type]       子类id数组
	 */
	public function getChildsID($data, $pid) {
		$arr =array();

		foreach($data as $v) {
			if($v[$this->parentName] == $pid) {
				$arr[] = $v[$this->sonName];
				$arr = array_merge($arr, self::getChildsID($data, $v[$this->sonName]));
			}
		}

		return $arr;
	}

	/**
	 * 根据传递的子类ID获取所有的父类ID
	 * 注意返回值中不包括传递进来的子类ID
	 * @param  [type] $data 分类数组
	 * @param  [type] $id   子类id
	 * @return [type]       父类id数组
	 */
	public function getParentsID($data, $id) {
		$arr = array();

		foreach($data as $v) {
			if($v[$this->sonName] == $id) {
				$arr[] = $v[$this->parentName];
				$arr = array_merge($arr, self::getParentsID($data, $v[$this->parentName]));
			}
		}

		return $arr;
	}

    /**
     * 获取结构化的数组
     * @param $levelData 含有分级信息的数组
     * @return array
     */
    public function getTree($levelData)
    {
       // usort($leveData,array('Category','sortByLever'));
        $leveDataReform = $this->reformArr($levelData);
        $treeList = array();
        foreach($leveDataReform as $data){
                if($data['level']==1){//一级
                    $treeList[$data['id']]  = $data;
                }elseif($data['level']==2){//二级
                    $level2_parent_id[$data['id']] = $data['parent_id'];
                    $treeList[$data['parent_id']]['child'][$data['id']] = $data;
                }elseif($data['level']==3){//三级
                    if(isset($level2_parent_id[$data['parent_id']])){
                       $treeList[$level2_parent_id[$data['parent_id']]]['child'][$data['parent_id']]['child'][$data['id']] = $data;
                    }
                }


        }
        return $treeList;
    }


    function getDataTree($rows, $id='id',$pid = 'parent_id',$child = 'child',$root=0) {
        $tree = array(); // 树
        if(is_array($rows)){
            $array = array();
            foreach ($rows as $key=>$item){
                $array[$item[$id]] =& $rows[$key];
            }
            foreach($rows as $key=>$item){
                $parentId = $item[$pid];
                if($root == $parentId){
                    $tree[] =&$rows[$key];
                }else{
                    if(isset($array[$parentId])){
                        $parent =&$array[$parentId];
                        $parent[$child][]=&$rows[$key];
                    }
                }
            }
        }
        return $tree;
    }


    /**
     * @param array $arr
     * @param string $field
     * @return array
     */
    public  function reformArr($arr = array(array('id'=>1, 'other'=>''),), $field = 'id'){
        $new_arr = array();
        if (!is_array($arr)) {
            return $new_arr;
        }
        foreach ($arr as $av) {
            if (!is_array($av)) {
                break;
            }
            if (!array_key_exists($field, $av)) {
                break;
            }
            if (!isset($new_arr[$av[$field]])) {
                $new_arr[$av[$field]] = $av;
            }
        }
        return $new_arr;
    }

    /**
     * @param $a
     * @param $b
     * @return int
     */
    public function sortByOrderID($a,$b)
    {

        if($a['order_id']==$b['order_id'])
        {
            return 0;
        }else{
            return $a['order_id']>$b['order_id']?1:-1;
        }
    }


    /**
     * @param $a
     * @param $b
     * @return int
     */
    public function sortByLever($a,$b)
    {
        if($a['level']==$b['level'])
        {
            return 0;
        }else{
            return $a['level']>$b['level']?1:-1;
        }
    }

}



require 'medoo.php';

$database = new medoo([
    'database_type' => 'mysql',
    'database_name' => 'test',
    'server' => 'localhost',
    'username' => 'root',
    'password' => '',
    'charset' => 'gbk'
]);
require './TimerHelper.php';
TimerHelper::start('1');
$classify =   $database->query("SELECT * FROM classify ORDER BY level ASC,orderid DESC,id asc LIMIT 100000")->fetchAll();

echo "<pre>";
$cate = new Category('id','parent_id');
//print_r($cate->getTree($classify));
print_r($cate->getDataTree($classify));
TimerHelper::stop('1');
exit();
/*
$tree_data  = [
    ['id'=>1,'parent_id'=>0,'name'=>'01','order_id'=>0],
    ['id'=>2,'parent_id'=>0,'name'=>'02','order_id'=>0],
    ['id'=>3,'parent_id'=>0,'name'=>'03','order_id'=>0],
    ['id'=>4,'parent_id'=>1,'name'=>'14','order_id'=>0],
    ['id'=>5,'parent_id'=>1,'name'=>'15','order_id'=>0],
    ['id'=>6,'parent_id'=>1,'name'=>'16','order_id'=>0],
    ['id'=>7,'parent_id'=>2,'name'=>'27','order_id'=>0],
    ['id'=>8,'parent_id'=>2,'name'=>'28','order_id'=>0],
    ['id'=>9,'parent_id'=>2,'name'=>'29','order_id'=>0],
    ['id'=>10,'parent_id'=>4,'name'=>'104','order_id'=>100],
    ['id'=>11,'parent_id'=>4,'name'=>'114','order_id'=>10000],
    ['id'=>12,'parent_id'=>4,'name'=>'124','order_id'=>10000],
    ['id'=>13,'parent_id'=>11,'name'=>'104','order_id'=>12],
    ['id'=>14,'parent_id'=>11,'name'=>'114','order_id'=>11],
    ['id'=>15,'parent_id'=>11,'name'=>'124','order_id'=>10],
];
*/


echo "<pre>";
//print_r($cate->unlimitedForLevel($tree_data,0,0));


foreach($tree_data as $key=>$class){
        echo $class['name']."<br/>";
        if(isset($class['child']) && !empty($class['child'])){
            foreach($class['child'] as $child1_class){
                echo "&nbsp;".$child1_class['name']."<br/>";
                if(isset($child1_class['child']) && !empty($child1_class['child'])){
                    foreach($child1_class['child'] as $child2_class){
                        echo "&nbsp;&nbsp;&nbsp".$child2_class['name']."<br/>";
                    }

                }
            }
        }
}



/*****

CREATE TABLE `classify` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`name` varchar(50) NOT NULL COMMENT '产品名称',
`admin_id` int(10) unsigned NOT NULL COMMENT '管理员id',
`parent_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '父级id',
`level` tinyint(4) unsigned NOT NULL DEFAULT '1' COMMENT '第几级',
`orderid` smallint(4) unsigned NOT NULL DEFAULT '999' COMMENT '排序',
`flag` tinyint(4) unsigned NOT NULL DEFAULT '1' COMMENT '是否隐藏',
`addtime` int(10) unsigned DEFAULT NULL,
PRIMARY KEY (`id`),
KEY `parent_id` (`parent_id`),
KEY `flag` (`flag`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=gbk;

 *****/

//耗时: 0.041002035 s	内存消耗: 2.523727417 MB
