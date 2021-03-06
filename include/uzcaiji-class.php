<?php
/*
 * U站采集类，页面匹配商品iid及商品促销价,其余的值通过API获取 
*/

class UzCaiji{
	public $url;
	public $items;

	public function __construct(){
		$this->items = array();
	}
	/*
	 *  采集主体 
	 *  website:采集网址二级域名
	 *  page:默认采集一页,目前可以获取多页的站有 jiukuaiyou，juanpi,zhuanbao
	 *  mode:操作模式,默认为直接导入数据库,值 2 为输出json格式,值3为输出新品页数
	 */ 
	public function Caiji($website,$page=1,$mode=1){
		if($website){
			if($website=='huiyuangou'){ // 会员购
				$this->url = 'http://huiyuangou.uz.taobao.com/';
				$result = file_get_contents($this->url);
				
				// 匹配爆款
			    $hygptn = '/class="hyg_nav1"(.+?)nav1_check1(.+?)href="(.+?)[?,&,]id=(\d+)(.*?)"(.+?)<img(.+?)src="(.+?)"(.+?)>(\d+\.?\d+)<\/b><del>(.+?)nav1_check2/is';	
			    preg_match_all($hygptn,$result,$hygarr,PREG_SET_ORDER);
			    //print_r($hygarr);
				foreach($hygarr as $k => $v){
					$bk[] = array('iid'=>$v[4],'nprice'=>$v[10],'pic'=>$v[8]);
				}
				$huiyuangou['bk'] = $bk;
				// end - 匹配爆款结束
				
				// 匹配9.9
				$hygptn = '/class="nav4"(.+?)class="nav5"/is';
				preg_match_all($hygptn,$result,$hygarr,PREG_SET_ORDER);
				$hygptn = '/class="hyg_goods_wk"(.+?)class="hyg_goods"(.+?)class="hyg_goods_img"(.+?)<img(.+?)src="(.+?)"(.+?)>(\d+\.?\d+)<\/b><del>(.+?)href="(.+?)[?,&,]id=(\d+)(.*?)"/is';
				preg_match_all($hygptn,$hygarr[0][0],$hygarr1,PREG_SET_ORDER);
				//print_r($hygarr1);
				foreach($hygarr1 as $k => $v){
					$zq99[] = array('iid'=>$v[10],'nprice'=>$v[7],'pic'=>$v[5]);
				}
				$huiyuangou['zq99'] = $zq99;
				// end -9.9
				
				// 匹配一分钟抢购
				$hygptn = '/class="nav7"(.+?)name="nineteen"/is';
				preg_match_all($hygptn,$result,$hygarr,PREG_SET_ORDER);
				$hygptn = '/class="hyg_goods_wk"(.+?)class="hyg_goods"(.+?)class="hyg_goods_img"(.+?)<img(.+?)src="(.+?)"(.+?)>(\d+\.?\d+)<\/b><del>(.+?)href="(.+?)[?,&,]id=(\d+)(.*?)"/is';
				preg_match_all($hygptn,$hygarr[0][0],$hygarr1,PREG_SET_ORDER);
				//print_r($hygarr1);
				foreach($hygarr1 as $k => $v){
					$yfzqg[] = array('iid'=>$v[10],'nprice'=>$v[7],'pic'=>$v[5]);
				}
				$huiyuangou['yfzqg'] = $yfzqg;
				//var_dump($yfzqg);
				// end - 一分钟抢购
				
				// 惠品折清仓
				$hygptn = '/class="nav9"(.+?)name="hot"/is';
				preg_match_all($hygptn,$result,$hygarr,PREG_SET_ORDER);
				$hygptn = '/class="hyg_goods_wk"(.+?)class="hyg_goods"(.+?)class="hyg_goods_img"(.+?)<img(.+?)src="(.+?)"(.+?)>(\d+\.?\d+)<\/b><del>(.+?)href="(.+?)[?,&,]id=(\d+)(.*?)"/is';
				preg_match_all($hygptn,$hygarr[0][0],$hygarr1,PREG_SET_ORDER);
				//print_r($hygarr1);
				foreach($hygarr1 as $k => $v){
					$hpzqc[] = array('iid'=>$v[10],'nprice'=>$v[7],'pic'=>$v[5]);
				}
				$huiyuangou['hpzqc'] = $hpzqc;
				// end - 惠品折清仓
				
				// 限时抢购
				$hygptn = '/class="nav11"(.+?)name="brand"/is';
				preg_match_all($hygptn,$result,$hygarr,PREG_SET_ORDER);
				$hygptn = '/class="hyg_goods_wk"(.+?)class="hyg_goods"(.+?)class="hyg_goods_img"(.+?)<img(.+?)src="(.+?)"(.+?)>(\d+\.?\d+)<\/b><del>(.+?)href="(.+?)[?,&,]id=(\d+)(.*?)"/is';
				preg_match_all($hygptn,$hygarr[0][0],$hygarr1,PREG_SET_ORDER);
				foreach($hygarr1 as $k => $v){
					$xsqg[] = array('iid'=>$v[10],'nprice'=>$v[7],'pic'=>$v[5]);
				}
				$huiyuangou['xsqg'] = $xsqg;
				// end - 限时抢购 
				
				/* $hygptn = '/class="b5_hyg_index_nav3"(.+?)class="b5_hyg_index_tbannerbk"/is';
				preg_match_all($hygptn,$result,$hygarr,PREG_SET_ORDER);
				$jxspR = $hygarr[0][0];
				
				$hygarr = null;
				$hygptn = '/class="b5_hyg_index_nav4"(.+?)class="b5_hyg_index_tbannerbk"/is';
				preg_match_all($hygptn,$result,$hygarr,PREG_SET_ORDER);
				$tdzpR = $hygarr[0][0];
				
				$hygarr = null;
				// 精选商品
				$hygptn = '/class="index_nav3goodsbk(.+?)nav3goodsbk_pd(.+?)href="(.+?)[?,&,]id=(\d+)(.*?)"(.+?)<img(.+?)src="(.+?)"(.+?)class="index_nav3goodstit"(.+?)class="index_span6"(.+?)class="b5">(\d+\.?\d+)<\/b>/is';
				preg_match_all($hygptn,$jxspR,$hygarr,PREG_SET_ORDER);
				foreach($hygarr as $k => $v){
					$jxsp[] = array('iid'=>$v[4],'nprice'=>$v[12],'pic'=>$v[8]);
				}
				$huiyuangou['jxsp'] = $jxsp;
				// END - 精选商品
				
				$hygarr = null;
				// 淘点折品
				$hygptn = '/class="index_nav4goodsbk(.+?)href="(.+?)[?,&,]id=(\d+)(.*?)"(.+?)class="src_img"(.+?)<img(.+?)data-ks-lazyload="(.+?)"(.+?)class="test1"(.+?)<h2><b>(.+?)(\d+\.?\d+)<\/b>/is';
				preg_match_all($hygptn,$tdzpR,$hygarr,PREG_SET_ORDER);
				//print_r($hygarr);
				foreach($hygarr as $k => $v){
					$tdzp[] = array('iid'=>$v[3],'nprice'=>$v[12],'pic'=>$v[8]);
				}
				$huiyuangou['tdzp'] = $tdzp;
				// END - 淘点折品 */
				
				//var_dump($huiyuangou);
				$this->items = $huiyuangou;
				if($mode==2)
					echo json_encode($this->items);
			}elseif($website=='jiukuaiyou'){ // 九块邮	
				if($mode==3){ // 返回要采集的页数,九块邮新品为45个一页
					$this->url = 'http://jiukuaiyoucom.uz.taobao.com/';
					$result = file_get_contents($this->url);
					
					$ptn = '/<div class="head"(.+?)class="main"/is';
					preg_match_all($ptn,$result,$jiuarr,PREG_SET_ORDER);
					//print_r($jiuarr);
					
					$ptn = '/<div class="nav-show(.+?)今日新品(.+?)<\/li>/is';
					preg_match_all($ptn,$jiuarr[0][0],$jiuarr1,PREG_SET_ORDER);
					//print_r($jiuarr1);
					
					$ptn = '/(\d+)/is';
					preg_match_all($ptn,$jiuarr1[0][2],$jiuarr,PREG_SET_ORDER);
					if($jiuarr[0][1]){
						return $jiuarr[0][1];
					}else{
						return false;
					}
				}else{			
					$this->url = 'http://jiukuaiyoucom.uz.taobao.com/d/jiu?u=xinpin/'.$page;
					//echo $this->url;
					$result = file_get_contents($this->url);
					
					// 匹配商品内容
					$ptn = '/<div class="main"(.+?)class="page"/is';
					preg_match_all($ptn,$result,$jiuarr,PREG_SET_ORDER);
					//print_r($jiuarr[0][0]);
					
					// 匹配单个商品内容
					$ptn = '/class="goods-box(.+?)class="buy-content"(.+?)class="price"(.+?)<\/em>(\d+\.?\d+)<\/span>(.+?)class="oldprice"(.+?)class="btn"(.+?)href="(.+?)[?,]id=(\d+)(.+?)"(.+?)<\/li>/is';
					preg_match_all($ptn,$jiuarr[0][0],$jiuarr1,PREG_SET_ORDER);
					//print_r($jiuarr1);
					
					foreach($jiuarr1 as $k => $v){
						$jiuarr2[] = array('iid'=>$v[9],'nprice'=>$v[4]);
					}
					$jiukuaiyou['page'.$page] = $jiuarr2;	

					//var_dump($jiukuaiyou);
					$this->items = $jiukuaiyou;
					
					if($mode==2)
						echo json_encode($this->items);
				}
			}elseif($website=='mytehui'){ // VIP特惠
				$this->url = 'http://mytehui.uz.taobao.com/';
				$result = file_get_contents($this->url);
				// 匹配爆款
				$thptn = '/id="main"(.+?)class="tmcon"(.+?)class="ygtm"/is';
				preg_match_all($thptn,$result,$bkarr,PREG_SET_ORDER);
				$thptn = '/class="buylink"(.+?)<a(.+?)href="(.+?)[?,&,]id=(\d+)(.*?)"(.+?)class="buybtn"(.+?)class="cRed"(.+?)(\d+\.?\d+)(.+?)<\/strong>/is';
				preg_match_all($thptn,$bkarr[0][2],$bkarr1,PREG_SET_ORDER);
				//print_r($bkarr1);
				foreach($bkarr1 as $k => $v){
					$bk[] = array('iid'=>$v[4],'nprice'=>$v[9]);
				}
				$mytehui['bk'] = $bk;
				// end - 匹配爆款
				//var_dump($mytehui);
				
				// 匹配一分钟抢购
				$yfzptn = '/class="mainbox"(.+?)class="msblist(.+?)<\/ul>/is';
				preg_match_all($yfzptn,$result,$yfzarr,PREG_SET_ORDER);
				//echo $yfzarr[0][0];
				$yfzptn = '/<li>(.+?)class="msbinfo"(.+?)href="(.+?)[?,&,]id=(\d+)(.*?)"(.+?)class="vip_price"(.+?)(\d+\.?\d+)(.+?)<\/li>/is';
				preg_match_all($yfzptn,$yfzarr[0][0],$yfzarr1,PREG_SET_ORDER);
				//print_r($yfzarr1);
				foreach($yfzarr1 as $k => $v){
					$yfz[] = array('iid'=>$v[4],'nprice'=>$v[8]);
				} 
				$mytehui['yfz'] = $yfz;
				// 匹配一分钟抢购结束
				//var_dump($mytehui);
				
				// 匹配9.9包邮
				$tjbyptn = '/class="mainbox(.+?)class="ninebox"(.+?)class="nextnine"/is';
				preg_match_all($tjbyptn,$result,$tjbyarr,PREG_SET_ORDER);
				$tjbyptn = '/<li>(.+?)class="ninfo"(.+?)class="vip_price"(.+?)(\d+\.?\d+)(.+?)class="vip_buy"(.+?)href="(.+?)[?,&,]id=(\d+)(.*?)"(.+?)class="vipbuybtn"(.+?)<\/li>/is';
				preg_match_all($tjbyptn,$tjbyarr[0][2],$tjbyarr1,PREG_SET_ORDER);
				foreach($tjbyarr1 as $k => $v){
					$tjby[] = array('iid'=>$v[8],'nprice'=>$v[4]);
				}
				$mytehui['tjby'] = $tjby;
				// 匹配9.9包邮结束
				//var_dump($mytehui);
				
				// 匹配热销卖场
				$rxmcptn = '/class="mainbox"(.+?)class="msslist(.+?)<\/ul>/is';
				preg_match_all($rxmcptn,$result,$rxmcarr,PREG_SET_ORDER);
				$rxmcptn = '/<li>(.+?)class="msbinfo"(.+?)href="(.+?)[?,&,]id=(\d+)(.*?)"(.+?)<\/h3>(.+?)class="vip_price"(.+?)(\d+\.?\d+)<\/span>(.+?)<\/li>/is';
				preg_match_all($rxmcptn,$rxmcarr[0][2],$rxmcarr1,PREG_SET_ORDER);
				foreach($rxmcarr1 as $k => $v){
					$rxmcarr2[] = array('iid'=>$v[4],'nprice'=>$v[9]);
				}
				$mytehui['rxmc'] = $rxmcarr2; 
				//print_r($rxmcarr1);
				// 匹配热销卖场结束
				
				//var_dump($mytehui);
				$this->items = $mytehui;
				if($mode==2)
					echo json_encode($this->items);
			}elseif($website=='vipgouyouhui'){ // VIP购优惠
				$this->url = 'http://vipgouyouhui.uz.taobao.com/';
				$result = file_get_contents($this->url);
				$gyhptn = '/class="zhuanbao"(.+?)class="ad"/is';
				preg_match_all($gyhptn,$result,$gyharr,PREG_SET_ORDER);
				$zbR = $gyharr[0][0];
				
				$gyharr = null;
				// 性价速购
				$xjsgptn = '/class="shop"(.+?)class="ad"/is';
				preg_match_all($xjsgptn,$result,$xjsgarr,PREG_SET_ORDER);
				$xjsgResult = $xjsgarr[0][0];
				$xsqgResult = $xjsgarr[1][0];// 限时抢购
				//echo $xjsgResult;
				$xjsgptn = '/<li(.+?)原价(.+?)href="(.+?)[?,]id=(\d+)(.+?)"(.+?)<img(.+?)src="(.+?)"(.+?)<div(.+?)class="jiage"(.+?)>(\d+\.?\d+)<\/span(.+?)<\/li>/is';
				preg_match_all($xjsgptn,$xjsgResult,$xjsgarr1,PREG_SET_ORDER);
				//print_r($xjsgarr1);
				foreach($xjsgarr1 as $k => $v){
					$xjsg[] = array('iid'=>$v[4],'nprice'=>$v[12],'pic'=>$v[8]);
				}
				$vipgouyouhui['xjsg'] = $xjsg;
				// end - 性价速购
				
				// 限时抢购
				$xsqgptn = '/<li(.+?)原价(.+?)href="(.+?)[?,]id=(\d+)(.+?)"(.+?)<img(.+?)src="(.+?)"(.+?)<div(.+?)class="jiage"(.+?)>(\d+\.?\d+)<\/span(.+?)<\/li>/is';
				preg_match_all($xsqgptn,$xsqgResult,$xsqgarr,PREG_SET_ORDER);
				//print_r($xsqgarr);
				foreach($xsqgarr as $k => $v){
					$xsqg[] = array('iid'=>$v[4],'nprice'=>$v[12],'pic'=>$v[8]);
				}
				$vipgouyouhui['xsqg'] = $xsqg;
				// end- 限时速购
				
				// 赚宝
				$gyhptn = '/class="imgs"(.+?)<a(.+?)href="(.+?)[?,]id=(\d+)(.+?)"(.+?)<img(.+?)src="(.+?)"(.+?)￥(.+?)<b>(\d+\.?\d+)<\/b>/is';
				preg_match_all($gyhptn,$zbR,$gyharr,PREG_SET_ORDER);
				//print_r($gyharr);
				foreach($gyharr as $k => $v){
					$zb[] = array('iid'=>$v[4],'nprice'=>$v[11],'pic'=>$v[8]);
				}
				$vipgouyouhui['zb'] = $zb;
				// END - 赚宝
				
				
				// 爆款热卖
				$bkrmptn = '/class="shop2"(.+?)class="ad"/is';
				preg_match_all($bkrmptn,$result,$bkrmarr,PREG_SET_ORDER);
				$bkrmResult = $bkrmarr[0][0];
				//print_r($bkrmResult);
				$bkrmptn = '/<li(.+?)<img(.+?)src="(.+?)"(.+?)专享价(.+?)<b>(\d+\.?\d+)<\/b>(.+?)<a(.+?)href="(.+?)[?,]id=(\d+)(.+?)"(.+?)<img(.+?)<\/li>/is';
				preg_match_all($bkrmptn,$bkrmResult,$bkrmarr1,PREG_SET_ORDER);
				//print_r($bkrmarr1);
				foreach($bkrmarr1 as $k => $v){
					$bkrm[] = array('iid'=>$v[10],'nprice'=>$v[6],'pic'=>$v[3]);
				}
				$vipgouyouhui['bkrm'] = $bkrm;
				// end - 爆款热卖
			
				//var_dump($vipgouyouhui);
				$this->items = $vipgouyouhui;
				if($mode==2)
					echo json_encode($this->items);
				
			}elseif($website=='juanpi'){ // 卷皮折扣
				if($mode==3){ // 返回要采集的页数,九块邮新品为45个一页
					$this->url = 'http://juanpi.uz.taobao.com/';
					$result = file_get_contents($this->url);
						
					$ptn = '/<div class="head"(.+?)class="main"/is';
					preg_match_all($ptn,$result,$jiuarr,PREG_SET_ORDER);
					//print_r($jiuarr);
						
					$ptn = '/<div class="nav-show(.+?)今日新品(.+?)<\/li>/is';
					preg_match_all($ptn,$jiuarr[0][0],$jiuarr1,PREG_SET_ORDER);
					//print_r($jiuarr1);
						
					$ptn = '/(\d+)/is';
					preg_match_all($ptn,$jiuarr1[0][2],$jiuarr,PREG_SET_ORDER);
					if($jiuarr[0][1]){
						return $jiuarr[0][1];
					}else{
						return false;
					}
				}else{				
					$this->url = 'http://juanpi.uz.taobao.com/d/index?u=index/all/all/all/'.$page;
					$result = file_get_contents($this->url);
					//echo $result;
					// 匹配商品内容
					$ptn = '/<div class="main"(.+?)class="content"(.+?)class="page"/is';
					preg_match_all($ptn,$result,$jiuarr,PREG_SET_ORDER);
					//print_r($jiuarr[0][0]);
					
					// 匹配单个商品内容
					$ptn = '/<li(.+?)class="good-price"(.+?)class="price-current"(.+?)<\/em>(\d+\.?\d+)<\/span>(.+?)class="like-state"(.+?)key(.+?)(\d+)(.+?)<em(.+?)<\/li>/is';
					preg_match_all($ptn,$jiuarr[0][0],$jiuarr1,PREG_SET_ORDER);
					//print_r($jiuarr1);
					
					foreach($jiuarr1 as $k => $v){
						$jiuarr2[] = array('iid'=>$v[8],'nprice'=>$v[4]);
					}
					$jiukuaiyou['page'.$page] = $jiuarr2;	
						
					$this->items = $jiukuaiyou;
					//var_dump($this->items);
					if($mode==2)
						echo json_encode($this->items);
				}
			}elseif($website=='zhe800'){ // 折800
				// 采集9.9包邮 
				$this->url = 'http://zhe800.uz.taobao.com/d/99?zone_id=1';
				$result = file_get_contents($this->url);
				// 卖光的替换成空
				$result = preg_replace('/<div class="deal figure1 zt3">(.+?)<\/div>/is','',$result);
				// 没开始的也替换成空
				$result = preg_replace('/<div class="deal figure1 zt2">(.+?)<\/div>/is','',$result);
				$zhe8ptn = '/<div class="area">(.*)<\/div>/is';
				preg_match_all($zhe8ptn,$result,$zhe8arr,PREG_SET_ORDER);
				$zhe8ptn = '/<h4>(.+?)((\d+\.?\d+)|(\d+\.?))(.+?)href="(.+?)[?,&,]id=(\d+)(.*?)"(.+?)<\/h4>/is';
				preg_match_all($zhe8ptn,$zhe8arr[0][0],$zhe8arr1,PREG_SET_ORDER);
				//print_r($zhe8arr1);
				foreach($zhe8arr1 as $k => $v){
					$zhe9by[] = array('iid'=>$v[7],'nprice'=>$v[2]);
				}
				$zhe800arr['9by'] = $zhe9by;
				// 采集9.9包邮结束
				
				// 采集20封顶 
				$this->url = 'http://zhe800.uz.taobao.com/d/20feng?zone_id=2';
				$result = file_get_contents($this->url);
				$result = preg_replace('/<div class="deal figure1 zt3">(.+?)<\/div>/is','',$result);
				$zhe20ptn = '/<div class="area">(.*)<\/div>/is';
				preg_match_all($zhe20ptn,$result,$zhe20arr,PREG_SET_ORDER);
				$zhe20ptn = '/<h4>(.+?)((\d+\.?\d+)|(\d+\.?))(.+?)href="(.+?)[?,&,]id=(\d+)(.*?)"(.+?)<\/h4>/is';
				preg_match_all($zhe20ptn,$zhe20arr[0][0],$zhe20arr1,PREG_SET_ORDER);
				foreach($zhe20arr1 as $k => $v){
					$zhe20by[] = array('iid'=>$v[7],'nprice'=>$v[2]);
				}
				$zhe800arr['20feng'] = $zhe20by;
				// 采集20封顶结束
				$this->items = $zhe800arr;	
				if($mode==2)
					echo json_encode($this->items); 
			}elseif($website=='zhuanbao'){ // 开心赚宝
				//$this->url = 'http://zhuanbao.uz.taobao.com/zhuanbao.php?page='.$page;
				$this->url = 'http://zhuanbao.uz.taobao.com';
				$result = file_get_contents($this->url);
				//echo $result;
				$zbptn = '/class="zb_main"(.+?)class="list"(.+?)class="page_now "/is';
				preg_match_all($zbptn,$result,$zbarr,PREG_SET_ORDER);
				$zbptn = '/<li(.+?)class="lipage1(.+?)class="pic_area"(.+?)class="img"(.+?)href="(.+?)[?,&,]id=(\d+)(.*?)"(.+?)class="btn_area "(.+?)<\/em>(\d+\.?\d+)<\/span>(.+?)<\/li>/is';
				preg_match_all($zbptn,$zbarr[0][0],$zbarr1,PREG_SET_ORDER);
				//print_r($zbarr1);
				foreach($zbarr1 as $k => $v){
					$zball[] = array('iid'=>$v[6],'nprice'=>$v[10]);
				}
				$zhuanbao['zxzk'.$page] = $zball; 
				$this->items = $zhuanbao;
				//var_dump($this->items);
				if($mode==2)
					echo json_encode($this->items);
			}elseif($website=='10mst'){ // 秒杀通
				$this->url = 'http://10mst.uz.taobao.com/d/seckill?cat=all&by=new&page='.$page;
				$result = file_get_contents($this->url);
				//echo $result;
				$mstptn = '/<div class="lx-item-list">(.+?)<div class="lx-page-area">/is';
				preg_match_all($mstptn,$result,$mstarr,PREG_SET_ORDER);				
				$mstptn = '/<div class="lx-item-list-price">(.+?)class="send"(.+?)<em>(.+?)<\/span>(.+?)class="lx-item-btn-buy"(.+?)href="(.+?)[?,&,]id=(\d+)(.*?)"(.+?)<\/li>/is';				
				preg_match_all($mstptn,$mstarr[0][0],$mstarr1,PREG_SET_ORDER);
				foreach($mstarr1 as $k => $v){
					$mstall[] = array('iid'=>$v[7],'nprice'=>preg_replace('/<\/em>/','',$v[3]));
				}
				$mst['new'.$page] = $mstall;
				//var_dump($mst);
				$this->items = $mst;
				//var_dump($this->items);
				if($mode==2)
					echo json_encode($this->items);
			}elseif($website=='qiang'){ // 抢牛品
				$this->url = 'http://201314.uz.taobao.com/';
				$result = file_get_contents($this->url);
				$qiangptn = '/<div class="homeBody">(.+?)<div class="home_links">/is';				
				preg_match_all($qiangptn,$result,$qiangarr,PREG_SET_ORDER);
				$qiangptn = '/<div class="goodsItem">(.+?)class="price"(.+?)<\/em>(.+?)<\/span>(.+?)href="(.+?)[?,&,]id=(\d+)(.*?)"(.+?)<\/h5>/is';
				preg_match_all($qiangptn,$qiangarr[0][0],$qiangarr1,PREG_SET_ORDER);
				foreach($qiangarr1 as $k => $v){
					$qiangall[] = array('iid'=>$v[6],'nprice'=>$v[3]);
				}
				$qiang['all'] = $qiangall;
				$this->items = $qiang;
				//var_dump($this->items);
				if($mode==2)
					echo json_encode($this->items);
			}elseif($website=='tealife'){ // 淘牛品
				$this->url = 'http://tealife.uz.taobao.com/';
				$result = file_get_contents($this->url);
				// 精品推荐
				$teaptn = '/class="goods_item"(.+?)class="goods_img"(.+?)<img(.+?)data-ks-lazyload="(.+?)"(.+?)class="goods_name"(.+?)href="(.+?)[?,&,]id=(\d+)(.*?)"(.+?)class="price_sales"(.+?)class="promo_price(.+?)class="integer">(\d+)<\/em>/is';
				preg_match_all($teaptn,$result,$teaarr,PREG_SET_ORDER);
				//print_r($teaarr);
				foreach($teaarr as $k => $v){
					$bk[] = array('iid'=>$v[8],'nprice'=>$v[13],'pic'=>$v[4]);
				}
				$tealife['bk'] = $bk;
				//var_dump($bk);
				// end - 精品推荐
				
				//var_dump($tealife);
				$this->items = $tealife;
				//var_dump($this->items);
				if($mode==2)
					echo json_encode($this->items);
			}elseif($website=='taofen8'){ // 外站  淘粉吧
				$this->url = 'http://www.taofen8.com/';
				$result = file_get_contents($this->url);
				$tf8ptn = '/<div class="tf8_sp">(.+?)<div class="go_guangyh">/is';
				preg_match_all($tf8ptn,$result,$tf8arr,PREG_SET_ORDER);
				//print_r($tf8arr);
				$tf8ptn = '/<li(.+?)itemid(.+?)(\d+)(.+?)pic(.+?)"(.+?)"(.+?)c(.+?)price(.+?)(\d+\.?\d+)(.+?)>/is';
				preg_match_all($tf8ptn,$tf8arr[0][0],$tf8arr1,PREG_SET_ORDER);
				//print_r($tf8arr1);
				foreach($tf8arr1 as $k => $v){
					$tf8zx[] = array('iid'=>$v[3],'nprice'=>$v[10],'pic'=>$v[6]);
				}
				$tf8['tf8zx'] = $tf8zx;
				//var_dump($tf8);
				$this->items = $tf8;
				if($mode==2)
					echo json_encode($this->items);
			}elseif($website=='legou'){ // 乐购
				// 普通页面采集
				$this->url = 'http://legou.uz.taobao.com/';
				$result = file_get_contents($this->url);
				$lgptn = '/<div class="recpro_list">(.+?)class="oneminute"(.+?)<ul>(.+?)<\/ul>(.+?)class="go_more"/is';
				preg_match_all($lgptn,$result,$lgarr,PREG_SET_ORDER);
				$lgptn = '/<li>(.+?)class="pro_buy"(.+?)class="price"(.+?)<\/b>(\d+\.?\d+)<\/span>(.+?)href="(.+?)[?,&,]id=(\d+)(.*?)"(.+?)<\/li>/is';
				preg_match_all($lgptn,$lgarr[0][3],$lgarr1,PREG_SET_ORDER);
				foreach($lgarr1 as $k => $v){
					$lgfq[] = array('iid'=>$v[7],'nprice'=>$v[4]);
				} 
				// 接口采集
				/* $this->url = 'http://legou.uz.taobao.com/view/front/legouout.php';
				$result = file_get_contents($this->url);
				$lgptn = '/class="taeapp"(.+?)>(.+?)<\/div>(.+?)id="footer"/is';
				preg_match_all($lgptn,$result,$lgarr,PREG_SET_ORDER);
				$lgptn = '/<div class="item">(.+?)class="iid">(\d+)<\/span>(.+?)class="nprice">(\d+\.?\d+)<\/span>(.+?)class="volume">(\d+)<\/span>(.+?)<br\/>/is';
				preg_match_all($lgptn,$lgarr[0][0],$lgarr1,PREG_SET_ORDER);
				foreach($lgarr1 as $k => $v){
					$lgfq[] = array('iid'=>$v[2],'nprice'=>$v[4],'volume'=>$v[6]);
				}  */
				$legou['lgfq'] = $lgfq;
				$this->items = $legou;
				//var_dump($this->items);
				if($mode==2)
					echo json_encode($this->items); 
				//echo json_encode($this->items);
			}elseif($website=='vipzxhd'){
				$this->url = 'http://jiejie.uz.taobao.com';
				$result = file_get_contents($this->url);
				$zxhdptn = '/class="jiu_bd(.+?)style="width:1024px;/is';
				preg_match_all($zxhdptn,$result,$zxhdarr,PREG_SET_ORDER);
				$xlqg = $zxhdarr[0][0]; // 限量抢购
				$vippptm = $zxhdarr[1][0]; // vip品牌特卖
				$vipshtj = $zxhdarr[2][0]; // vip实惠推荐
				
				$zxhdarr = null;
				$zxhdptn = '/style="width:1024px;(.+?)class="jiu_top1/is';
				preg_match_all($zxhdptn,$result,$zxhdarr,PREG_SET_ORDER);
				//print_r($zxhdarr);
				$vipbkrm = $zxhdarr[1][0]; // 爆款热卖
				$youpintehui = $zxhdarr[2][0]; //优品特惠
				$tmalljp = $zxhdarr[3][0]; // 天猫精品
				
				$zxhdarr = null;
				// 限量抢购
				$zxhdptn = '/<li>(.+?)class="tao(.+?)href="(.+?)[?,&,]id=(\d+)(.*?)"(.+?)class="vipprice"(.+?)<strong>(\d+\.?\d+)<\/strong>(.+?)<\/li>/is';
				preg_match_all($zxhdptn,$xlqg,$zxhdarr,PREG_SET_ORDER);
				//print_r($zxhdarr);
				foreach($zxhdarr as $k => $v){
					$xlqgarr[] = array('iid'=>$v[4],'nprice'=>$v[8]);
				}
				$vipzxhd['xlqg'] = $xlqgarr;
				// end - 限量抢购
				
				$zxhdarr = null;
				// vip品牌特卖
				$zxhdptn = '/<li>(.+?)href="(.+?)[?,&,]id=(\d+)(.*?)"(.+?)class="viptj"(.+?)class="vipprice"(.+?)<strong>(\d+\.?\d+)<\/strong>(.+?)class="vippp"(.+?)<\/li>/is';
				preg_match_all($zxhdptn,$vippptm,$zxhdarr,PREG_SET_ORDER);
				//print_r($zxhdarr);
				foreach($zxhdarr as $k => $v){
					$vippptmarr[] = array('iid'=>$v[3],'nprice'=>$v[8]);
				}				 
				$vipzxhd['vippptm'] = $vippptmarr; 
				// end - vip品牌特卖
				
				$zxhdarr = null;
				// vip实惠推荐
				$zxhdptn = '/<li>(.+?)class="lansesubl"(.+?)href="(.+?)[?,&,]id=(\d+)(.*?)"(.+?)class="vipprice"(.+?)<strong>(\d+\.?\d+)<\/strong>(.+?)class="lansesubr"(.+?)<\/li>/is';
				preg_match_all($zxhdptn,$vipshtj,$zxhdarr,PREG_SET_ORDER);
				//print_r($zxhdarr);
				foreach($zxhdarr as $k => $v){
					$vipshtjarr[] = array('iid'=>$v[4],'nprice'=>$v[8]);
				}
				$vipzxhd['vipshtj'] = $vipshtjarr;
				// end - vip实惠推荐
				
				$zxhdarr = null;
				// vip爆款热卖
				$zxhdptn = '/class="goods_item"(.+?)class="goods_img"(.+?)href="(.+?)[?,&,]id=(\d+)(.*?)"(.+?)src="(.+?)"(.+?)class="promo_price(.+?)class="integer">(\d+\.?\d+)<\/em>/is';
				preg_match_all($zxhdptn,$vipbkrm,$zxhdarr,PREG_SET_ORDER);
				//print_r($zxhdarr);
				foreach($zxhdarr as $k => $v){
					$vipbkrmarr[] = array('iid'=>$v[4],'nprice'=>$v[10],'pic'=>$v[7]);
				}  
				$vipzxhd['vipbkrm'] = $vipbkrmarr; 
				//var_dump($vipbkrmarr); 
				// end - vip爆款热卖
				
				$zxhdarr = null;
				// 优品特惠
				$zxhdptn = '/class="goods_item"(.+?)class="goods_img"(.+?)href="(.+?)[?,&,]id=(\d+)(.*?)"(.+?)src="(.+?)"(.+?)class="promo_price(.+?)class="integer">(\d+\.?\d+)<\/em>/is';
				preg_match_all($zxhdptn,$youpintehui,$zxhdarr,PREG_SET_ORDER);
				//print_r($zxhdarr);
				foreach($zxhdarr as $k => $v){
					$ypth[] = array('iid'=>$v[4],'nprice'=>$v[10],'pic'=>$v[7]);
				}
				$vipzxhd['ypth'] = $ypth;
				//var_dump($ypth);
				// end - 优品特惠
				
				$zxhdarr = null;
				// 天猫精品
				$zxhdptn = '/class="goods_item"(.+?)class="goods_img"(.+?)href="(.+?)[?,&,]id=(\d+)(.*?)"(.+?)src="(.+?)"(.+?)class="promo_price(.+?)class="integer">(\d+\.?\d+)<\/em>/is';
				preg_match_all($zxhdptn,$tmalljp,$zxhdarr,PREG_SET_ORDER);
				//print_r($zxhdarr);
				foreach($zxhdarr as $k => $v){
					$tmjp[] = array('iid'=>$v[4],'nprice'=>$v[10],'pic'=>$v[7]);
				}
				$vipzxhd['tmjp'] = $tmjp;
				//var_dump($tmjp);
				// end - 天猫精品
				
				//var_dump($vipzxhd);
				$this->items = $vipzxhd;
				if($mode==2)
					echo json_encode($this->items);
				//echo json_encode($this->items);
			}elseif($website=='baidatuan'){
				$this->url = 'http://baidatuan.uz.taobao.com/';
				$result = file_get_contents($this->url);
				$bdtptn = '/class="show_items(.+?)<\/ul>/is';
				preg_match_all($bdtptn,$result,$bdtdarr,PREG_SET_ORDER);
				
				$bdtptn = '/class="buy-action(.+?)href="(.+?)[?,&,]id=(\d+)(.*?)"(.+?)class="price(.+?)<\/em>(\d+\.?\d+)<\/span>/is';
				preg_match_all($bdtptn,$bdtdarr[0][0],$bdtdarr1,PREG_SET_ORDER);
				
				foreach($bdtdarr1 as $k => $v){
					$bdthot[] = array('iid'=>$v[3],'nprice'=>$v[7]);
				}
				$bdthot = array_chunk($bdthot,18);
				$bdthot = $bdthot[0];
				$bdt['hot'] = $bdthot;
				//$baidatuan['zs'] = array_chunk($bemdt,18)[0];
				//var_dump($bdt);
				$this->items = $bdt;
				if($mode==2)
					echo json_encode($this->items);
			}elseif($website=='tejiafengqiang'){
				$this->url = 'http://jianshi.uz.taobao.com/d/index?page='.$page;
				$result = file_get_contents($this->url);
				$tjfqptn = '/class="container(.+?)class="recpro_list"(.+?)class="pagination"/is';
				preg_match_all($tjfqptn,$result,$tjfqarr,PREG_SET_ORDER);
				$tjfqptn = '/<li>(.+?)href="(.+?)[?,&,]id=(\d+)(.*?)"(.+?)class="price_list_sale"(.+?)<em>(\d+\.?\d+)<\/em>(.+?)<\/li>/is';
				preg_match_all($tjfqptn,$tjfqarr[0][2],$tjfqarr1,PREG_SET_ORDER);
				//print_r($tjfqarr1);
				foreach($tjfqarr1 as $k => $v){
					$tjfqall[] = array('iid'=>$v[3],'nprice'=>$v[7]);
				}
				$tjfq['page'.$page] = $tjfqall;
				//var_dump($tjfq); 
				$this->items = $tjfq;
				if($mode==2)
					echo json_encode($this->items);
			}elseif($website=='mao'){
				$this->url = 'http://ju.tejiamao.com/page/maou.html';
				$result = file_get_contents($this->url);
				$maoptn = '/id="tejia">(\d+\.?\d+)<\/td>(.+?)id="iid">(\d+)<\/td>/is';
				preg_match_all($maoptn,$result,$maoarr,PREG_SET_ORDER);
				foreach($maoarr as $k => $v){
					$mao[] = array('iid'=>$v[3],'nprice'=>$v[1]);
				}
				$tejiamao['mao'] = $mao;
				$this->items = $tejiamao;
				if($mode==2)
					echo json_encode($this->items);
			}elseif($website=='mizheuz'){
				$this->url = 'http://lanmama.uz.taobao.com';
				$result = file_get_contents($this->url);
				
				// 米折九块九
				$mizheuzptn = '/class="tuan-choice(.+?)span-19"(.+?)class="span-5/is';
				preg_match_all($mizheuzptn,$result,$mizheuzarr,PREG_SET_ORDER);
				
				$mizhe99 = $mizheuzarr[0][0];
				$mizheuzarr = null;
				
				$mizheuzptn = '/<li>(.+?)class="chioce-detail(.+?)class="buy-info"(.+?)class="big">(.+?)<\/span>(.+?)class="go-btn(.+?)href="(.+?)[?,&,]id=(\d+)(.*?)"(.+?)<\/li>/is';
				preg_match_all($mizheuzptn,$mizhe99,$mizheuzarr,PREG_SET_ORDER);
				
				//print_r($mizheuzarr);
				
				foreach($mizheuzarr as $k => $v){
					$v[4] = preg_replace('/<\/em>/i','',$v[4]);
					$v[4] = preg_replace('/<em>/i','',$v[4]); 
					$mizhe9[] = array('iid'=>$v[8],'nprice'=>$v[4]);
				}
				$mizhe['mizhe9'] = $mizhe9;
				// END - 米折九块九
				
				// 米折OTHER
				$mizheuzptn = '/class="tuan-list"(.+?)class="pagination/is';
				preg_match_all($mizheuzptn,$result,$mizheuzarr,PREG_SET_ORDER);
				
				$mizheother = $mizheuzarr[0][0];
				$mizheuzarr = null;
				//echo $mizheother;
				$mizheuzptn = '/<li(.+?)status-ongoing(.+?)class="big">(\d+)<\/em>(.+?)(\.?\d+)<\/em>(.+?)<\/span>(.+?)class="go-btn(.+?)href="(.+?)[?,&,]id=(\d+)(.*?)"(.+?)class="tags"(.+?)<\/li>/is';
				preg_match_all($mizheuzptn,$mizheother,$mizheuzarr,PREG_SET_ORDER);
				foreach($mizheuzarr as $k => $v){
					$mizheo[] = array('iid'=>$v[10],'nprice'=>$v[3].$v[5]);
				}
				$mizhe['mizheo'] = $mizheo;
				//print_r($mizheuzarr);
				// END - 米折OTHER
				
				$this->items = $mizhe;
				if($mode==2)
					echo json_encode($this->items);
				
			}elseif($website=='ztbest'){
				$this->url = 'http://ztbest.uz.taobao.com';
				$result = file_get_contents($this->url);
				$ztbestptn = '/class="taeapp_aw2"(.+?)class="taeapp_aw"/is';
				preg_match_all($ztbestptn,$result,$ztbestarr,PREG_SET_ORDER);
				$jryxR =  $ztbestarr[0][1]; // 今日优选
				$pzgR = $ztbestarr[1][1]; // 品质购
				$fkmsR = $ztbestarr[2][1]; // 疯狂秒杀
				
				$ztbestarr = null;
				// 今日优选
				$ztbestptn = '/class="taeapp_box1"(.+?)class="taeapp_box_pic"(.+?)href="(.+?)[?,&,]id=(\d+)(.*?)"(.+?)<img(.+?)src="(.+?)"(.+?)class="taeapp_box_price"(.+?)<strong>(\d+\.?\d+)<\/strong>/is';
				preg_match_all($ztbestptn,$jryxR,$ztbestarr,PREG_SET_ORDER);
				foreach($ztbestarr as $k => $v){
					$jryx[] = array('iid'=>$v[4],'nprice'=>$v[11],'pic'=>$v[8]);
				}
				$ztbest['jryx'] = $jryx;
				// END - 今日优选
				
				$ztbestarr = null;
				// 品质购
				$ztbestptn = '/class="taeapp_box1"(.+?)class="taeapp_box_pic"(.+?)href="(.+?)[?,&,]id=(\d+)(.*?)"(.+?)<img(.+?)src="(.+?)"(.+?)class="taeapp_box_price"(.+?)<strong>(\d+\.?\d+)<\/strong>/is';
				preg_match_all($ztbestptn,$pzgR,$ztbestarr,PREG_SET_ORDER);
				foreach($ztbestarr as $k => $v){
					$pzg[] = array('iid'=>$v[4],'nprice'=>$v[11],'pic'=>$v[8]);
				}
				$ztbest['pzg'] = $pzg;
				// END - 品质购
				
				$ztbestarr = null;
				// 疯狂秒杀
				$ztbestptn = '/class="taeapp_box_pic"(.+?)href="(.+?)[?,&,]id=(\d+)(.*?)"(.+?)<img(.+?)src="(.+?)_200x200.jpg"(.+?)class="taeapp_box_price"(.+?)<span>￥(\d+\.?\d+)<\/span>/is';
				preg_match_all($ztbestptn,$fkmsR,$ztbestarr,PREG_SET_ORDER);
				foreach($ztbestarr as $k => $v){
					$fkms[] = array('iid'=>$v[3],'nprice'=>$v[10],'pic'=>$v[7].'_310x310.jpg');
				}
				$ztbest['fkms'] = $fkms;
				// END - 疯狂秒杀
				
				//var_dump($ztbest);
				$this->items = $ztbest; 
				if($mode==2)
					echo json_encode($this->items);
			}elseif($website=='mmrizhi'){
				$this->url = 'http://mmrizhi.uz.taobao.com/view/front/list.php?page='.$page;
				$result = file_get_contents($this->url);
				$mmrzptn = '/class="container(.+?)class="recpro_list"(.+?)class="block70"/is';
				preg_match_all($mmrzptn,$result,$mmrzarr,PREG_SET_ORDER);
				$pageR = $mmrzarr[0][2];
				
				$mmrzarr = null;
				$mmrzptn = '/class="proList"(.+?)class="p"(.+?)class="pic_c"(.+?)<a(.+?)class="edit"(.+?)<\/div>(.+?)href="(.+?)[?,&,]id=(\d+)(.*?)"(.+?)<img(.+?)data-ks-lazyload="(.+?)"(.+?)class="price_list_sale"(.+?)<em>(\d+\.?\d+)<\/em>/is';
				preg_match_all($mmrzptn,$pageR,$mmrzarr,PREG_SET_ORDER);
				//print_r($mmrzarr);
				foreach($mmrzarr as $k => $v){
					$allsp[] = array('iid'=>$v[8],'nprice'=>$v[15],'pic'=>$v[12]);
				}
				$mmrizhi['page'.$page] = $allsp; 
				//var_dump($mmrizhi);
				$this->items = $mmrizhi;
				if($mode==2)
					echo json_encode($this->items);
			}elseif($website=='yuansu'){
				$this->url = 'http://yuansu.uz.taobao.com';
				$result = file_get_contents($this->url);
				$yuansuptn = '/class="lff-con(.+?)first"(.+?)class="floor-item(.+?)class="clear"/is';
				preg_match_all($yuansuptn,$result,$yuansuarr,PREG_SET_ORDER);
				$yfzqgR = $yuansuarr[0][3];
				$bkjpR = $yuansuarr[1][3];
				
				$yuansuarr = null;
				// 一分钟抢光
				$yuansuptn = '/class="img"(.+?)<a(.+?)href="(.+?)[?,&,]id=(\d+)(.*?)"(.+?)<img(.+?)data-ks-lazyload="(.+?)"(.+?)class="price"(.+?)<\/font>(\d+\.?\d+)(.+?)<\/div>/is';
				preg_match_all($yuansuptn,$yfzqgR,$yuansuarr,PREG_SET_ORDER);
				//print_r($yuansuarr);
				foreach($yuansuarr as $k => $v){
					$yfzqg[] = array('iid'=>$v[4],'nprice'=>$v[11],'pic'=>preg_replace('/_210x210.jpg/i','_310x310.jpg',$v[8]));
				}
				$yuansu['yfzqg'] = $yfzqg;
				// END - 一分钟抢光
				
				$yuansuarr = null;
				// 爆款精品
				$yuansuptn = '/class="img"(.+?)<a(.+?)href="(.+?)[?,&,]id=(\d+)(.*?)"(.+?)<img(.+?)data-ks-lazyload="(.+?)"(.+?)class="price"(.+?)<\/font>(\d+\.?\d+)(.+?)<\/div>/is';
				preg_match_all($yuansuptn,$bkjpR,$yuansuarr,PREG_SET_ORDER);
				//print_r($yuansuarr);
				foreach($yuansuarr as $k => $v){
					$bkjp[] = array('iid'=>$v[4],'nprice'=>$v[11],'pic'=>preg_replace('/_210x210.jpg/i','_310x310.jpg',$v[8]));
				}
				$yuansu['bkjp'] = $bkjp; 
				// END - 爆款精品
				
				//var_dump($yuansu);
				$this->items = $yuansu;
				if($mode==2)
					echo json_encode($this->items);
			}
			
		}
	}
	
	/*
	 * 输出采集好的数据数组
	*/
	public function getitems(){
		return $this->items;
	}
	

}
	
?>
