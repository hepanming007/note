//根据时间年份+博客id%40定位该写入分表位置
$memberid = 15;
$tableid = $memberid%40;
$tablename = ' tbBlogArticle'.date('Ym').$tableid;
