<?php
global $caijiusers,$website;
$website = array(
	'huiyuangou'=>array('actType'=>2,'name'=>'会员购'), // 会员购
	//'qiang'=>array('actType'=>3,'name'=>'抢牛品','tcounts'=>count($pros->findAll('act_from=3 and '.$where))), // 抢牛品
	'jiukuaiyou'=>array('actType'=>4,'name'=>'九块邮'), // 九块邮
	'zhe800'=>array('actType'=>5,'name'=>'折800'), // 折800
	'mytehui'=>array('actType'=>6,'name'=>'VIP特惠'), // VIP特惠
	'tealife'=>array('actType'=>7,'name'=>'淘牛品'), // 淘牛品
	'taofen8'=>array('actType'=>8,'name'=>'淘粉吧'), // 外站  淘粉吧
	'vipgouyouhui'=>array('actType'=>9,'name'=>'VIP购优惠'), // VIP购优惠
	//'10mst'=>array('actType'=>10,'name'=>'秒杀通'), // 秒杀通
	'juanpi'=>array('actType'=>11,'name'=>'卷皮折扣'), // 卷皮折扣
	'legou'=>array('actType'=>12,'name'=>'乐购'), // 乐购
	'vipzxhd'=>array('actType'=>13,'name'=>'vip专享活动'), // vip专享活动
	'baidatuan'=>array('actType'=>14,'name'=>'百搭团'), // 百搭团
	'zhuanbao'=>array('actType'=>15,'name'=>'开心赚宝'), //开心赚宝
	//'tejiafengqiang'=>array('actType'=>16,'name'=>'特价疯抢'), // 特价疯抢
	'mao'=>array('actType'=>17,'name'=>'特价猫'), // 特价猫
	'mizheuz'=>array('actType'=>18,'name'=>'米折U站'), // 米折U站
	'ztbest'=>array('actType'=>19,'name'=>'中通优选'), // 中通优选
	//'mmrizhi'=>array('actType'=>20,'name'=>'美美购'), // 美美购
	'yuansu'=>array('actType'=>21,'name'=>'爆划算'), // 爆划算
	'none'=>null
);
$caijiusers = array(
	'xinxin'=>array('username'=>'xinxin','password'=>'xin123456'),
	//'xx0123'=>array('username'=>'xx0123','password'=>'xx0123','unick'=>'夜0019w5k/sdL2PXJXimFTsiluFi1udq9'),
	'cong'=>array('username'=>'cong','password'=>'cong123456','nick'=>'折扣吧'),
	'lijie'=>array('username'=>'lijie','password'=>'lijie1234','nick'=>''),
	'x0123'=>array('username'=>'x0123','password'=>'x0123','nick'=>'消灵','unick'=>'x0019wtl+cRC3vM7JYTUBIAXwQsISHmC'),
	'9kuaigou'=>array('username'=>'9kuaigou','password'=>'9kuaigou','nick'=>'九块购'),
	'sqsmb'=>array('username'=>'sqsmb','password'=>'sqsmb','nick'=>'U购'),
	'yuansu'=>array('username'=>'yuansu','password'=>'yuansu','nick'=>'爆划算'),
	'zhe800w'=>array('username'=>'zhe800w','password'=>'zhe800w','nick'=>'vip独家优惠'),
	'ifengqiang'=>array('username'=>'ifengqiang','password'=>'ifengqiang','nick'=>'爱疯抢'),
	'shiyonglianmeng'=>array('username'=>'shiyonglianmeng','password'=>'shiyonglianmeng'),
	'jumei'=>array('username'=>'jumei','password'=>'jumei','nick'=>'聚美优品'),
	'126789'=>array('username'=>'126789','password'=>'126789','nick'=>'126789'),
	'loveshe'=>array('username'=>'loveshe','password'=>'loveshe','nick'=>'大乐购'),
	'tongqu'=>array('username'=>'tongqu','password'=>'tongqu','nick'=>'vip优折')
);	
?>