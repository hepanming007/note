<?php

/**
 *       
 *      dz里头的树
 *
 *      
 */
 
class Tree {

    /**
     * @var array  含有id pid name的数组
     */
    public $data = array();
    /**
     * @var array   子类数组
     */
    public $child = array(-1 => array());
    /**
     * @var array   层级数组
     */
    public $layer = array(-1 => -1);
    /**
     * @var array   父级数组
     */
    public $parent = array();
    /**
     * @var int
     */
    public $countid = 0;

    /**
     *
     */
    public function __construct() {
	}

    /**设置节点
     * @param $id     id
     * @param $parent 父id
     * @param $value  值
     */
    public function setNode($id, $parent, $value) {

		$parent = $parent ? $parent : 0;

		$this->data[$id] = $value;
		$this->child[$parent][] = $id;
		$this->parent[$id] = $parent;

		if (!isset($this->layer[$parent])) {
			$this->layer[$id] = 0;
		} else {
			$this->layer[$id] = $this->layer[$parent] + 1;
		}
	}

    /**
     * @param $tree
     * @param int $root
     */
    public function getList(&$tree, $root= 0) {
		foreach ($this->child[$root] as $key => $id) {
			$tree[] = $id;
			if (isset($this->child[$id]))
				$this->getList($tree, $id);
		}
	}

    /**
     * @param $id
     * @return mixed
     */
    public function getValue($id) {
		return $this->data[$id];
	}

    /**
     * @param $id
     */
    public function reSetLayer($id) {
		if ($this->parent[$id]) {
			$this->layer[$this->countid] = $this->layer[$this->countid] + 1;
			$this->reSetLayer($this->parent[$id]);
		}
	}

    /**
     * @param $id
     * @param bool $space
     * @return string
     */
    public function getLayer($id, $space = false) {
		$this->layer[$id] = 0;
		$this->countid = $id;
		$this->reSetLayer($id);
		return $space ? str_repeat($space, $this->layer[$id]) : $this->layer[$id];
	}

    /**
     * @param $id
     * @return mixed
     */
    public function getParent($id) {
		return $this->parent[$id];
	}

    /**
     * @param $id
     * @return mixed
     */
    public function getParents($id) {
		while ($this->parent[$id] != 0) {
			$id = $parent[$this->layer[$id]] = $this->parent[$id];
		}

		ksort($parent);
		reset($parent);

		return $parent;
	}

    /**
     * @param $id
     * @return mixed
     */
    public function getChild($id) {
		return $this->child[$id];
	}

    /**
     * 获取所有子类
     * @param int $id
     * @return array
     */
    public function getChilds($id = 0) {
		$child = array();
		$this->getList($child, $id);

		return $child;
	}

}
//列子

//setNode(目录ID,上级ID，目录名字);
/*
$Tree->setNode(1, 0, '目录1');
$Tree->setNode(2, 1, '目录2');
$Tree->setNode(3, 0, '目录3');
$Tree->setNode(4, 3, '目录3.1');
$Tree->setNode(5, 3, '目录3.2');
$Tree->setNode(6, 3, '目录3.3');
$Tree->setNode(7, 2, '目录2.1');
$Tree->setNode(8, 2, '目录2.2');
$Tree->setNode(9, 2, '目录2.3');
$Tree->setNode(10, 6, '目录3.3.1');
$Tree->setNode(11, 6, '目录3.3.2');
$Tree->setNode(12, 6, '目录3.3.3');
*/

require 'medoo.php';
$database = new medoo([
    'database_type' => 'mysql',
    'database_name' => 'test',
    'server' => 'localhost',
    'username' => 'root',
    'password' => '',
    'charset' => 'gbk'
]);
//new Tree(根目录的名字); 
//根目录的ID自动分配为0 
require './TimerHelper.php';
TimerHelper::start('1');
$classify =   $database->query("SELECT * FROM classify ORDER BY level ASC,orderid DESC,id asc LIMIT 100000")->fetchAll();
$Tree = new Tree();
foreach($classify as $class){
    $Tree->setNode($class['id'],$class['parent_id'],$class['name']);
}

/*******面包屑********/
$parent_ids = $Tree->getParents(3000);
array_push($parent_ids,3000);
foreach($parent_ids as $level=>$parent_id){
    echo $Tree->getValue($parent_id);
    if($level<3){
        echo ">>";
    }
}
echo PHP_EOL;

/******树形结构*******/
//getChilds(指定目录ID); 
//取得指定目录下级目录.如果没有指定目录就由根目录开始 
$category = $Tree->getChilds();
//遍历输出 
foreach ($category as $key=>$id) 
{
    $layer =     $Tree->getLayer($id, ' ');
    $treeValue = $Tree->getValue($id);
    if(substr_count($layer,' ')==1){
        $layer .= '++';
    }else if(substr_count($layer,' ')==2){
        $layer .= '++++';
}
	echo $layer.$treeValue."<br />\n";
}
TimerHelper::stop('1');
?>

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
