<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `wp_comments`;");
E_C("CREATE TABLE `wp_comments` (
  `comment_ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `comment_post_ID` bigint(20) unsigned NOT NULL DEFAULT '0',
  `comment_author` tinytext NOT NULL,
  `comment_author_email` varchar(100) NOT NULL DEFAULT '',
  `comment_author_url` varchar(200) NOT NULL DEFAULT '',
  `comment_author_IP` varchar(100) NOT NULL DEFAULT '',
  `comment_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `comment_date_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `comment_content` text NOT NULL,
  `comment_karma` int(11) NOT NULL DEFAULT '0',
  `comment_approved` varchar(20) NOT NULL DEFAULT '1',
  `comment_agent` varchar(255) NOT NULL DEFAULT '',
  `comment_type` varchar(20) NOT NULL DEFAULT '',
  `comment_parent` bigint(20) unsigned NOT NULL DEFAULT '0',
  `user_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`comment_ID`),
  KEY `comment_post_ID` (`comment_post_ID`),
  KEY `comment_approved_date_gmt` (`comment_approved`,`comment_date_gmt`),
  KEY `comment_date_gmt` (`comment_date_gmt`),
  KEY `comment_parent` (`comment_parent`)
) ENGINE=InnoDB AUTO_INCREMENT=77 DEFAULT CHARSET=utf8");
E_D("replace into `wp_comments` values('2','33','ati9550128m','wangchang365@outlook.com','','120.94.192.33','2013-03-03 15:06:29','2013-03-03 07:06:29','<p>可以评论么？</p>\n','0','1','Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.1 (KHTML, like Gecko) Chrome/21.0.1180.89 Safari/537.1','','0','1');");
E_D("replace into `wp_comments` values('16','262','Quantum 基础知识及服务启动 | Wang Chang&#039;s Blog','','http://blog.wachang.net/2013/04/quantum-code-base/','222.197.180.135','2013-04-01 16:42:51','2013-04-01 08:42:51','[...] 关于这一部分，如果不是很懂的话，请先阅读本系列文章基础部分，关于配置文件cfg的部分，请阅读Quantum OpenvSwitch Plugin&amp;Agent读取配置文件,原理都是一样的。 [...]\n','0','1','The Incutio XML-RPC PHP Library -- WordPress/3.5.1','pingback','0','0');");
E_D("replace into `wp_comments` values('30','303','Quantum WSGI服务基础 | Wang Chang&#039;s Blog','','http://blog.wachang.net/2013/04/quantum-wsgi-base/','222.197.180.135','2013-04-07 14:01:23','2013-04-07 06:01:23','[...] 首先，我将Quantum WSGI中代码概念及如何处理API中涉及的概念在代码上做一个总结。 [...]\n','0','1','The Incutio XML-RPC PHP Library -- WordPress/3.5.1','pingback','0','0');");
E_D("replace into `wp_comments` values('33','93','Todd','yourmail@gmail.com','http://groups.tianya.cn/tribe/showArticle.jsp?groupId=551332&amp;articleId=266950558dc367c343e1c71bf07db5d3','222.94.139.247','2013-04-09 05:15:40','2013-04-08 21:15:40','十分感谢！\n','0','1','Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0; T312461)','','0','0');");
E_D("replace into `wp_comments` values('64','281','affordable windows vps','filomena.welsh@gmx.net','http://Www.Tedram.com/','142.54.178.211','2013-04-23 18:22:00','2013-04-23 10:22:00','Hi, I do think this is &#1072;n excellent web &#1109;ite. I stumbledup&#959;n it ;) I m&#1072;y re&nu;isit once again \n&#1109;in&#1089;e I b&#959;ok m&#1072;rked it. Money &#1072;nd freedom is the g&#1075;eatest way to change, may y&#1086;u be r&#1110;ch &#1072;nd c&omicron;ntinue to help other \n&#1088;eople.\n\nmy web-site <a href=\"http://Www.Tedram.com/\" rel=\"nofollow\">affordable windows vps</a>\n','0','spam','Mozilla/5.0 (Windows NT 6.1; WOW64; rv:5.0) Gecko/20100101 Firefox/5.0','','0','0');");
E_D("replace into `wp_comments` values('65','297','优淘积分','www163bbc@163.com','http://www.uuutao.com/','122.143.3.66','2013-04-23 23:27:02','2013-04-23 15:27:02','免费放送100个优淘注册码，先到先得。\n并大量收购优淘积分，1万积分0.8元。\n五级积分提成制度，赚取积分超级简单！\nQQ：25101441\n','0','spam','Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.1 (KHTML, like Gecko) Chrome/14.0.802.30 Safari/535.1 SE 2.X MetaSr 1.0','','0','0');");
E_D("replace into `wp_comments` values('66','297','fire safes','S3HhwgKcNla@gmail.com','http://www.e-cannonsafes.com/about.html','54.224.4.47','2013-04-25 00:05:42','2013-04-24 16:05:42','you could have a terrific blog right here! would you wish to make some invite posts on my weblog?\n','0','trash','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_6_8) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30','','0','0');");
E_D("replace into `wp_comments` values('67','138','Boutique Angleterre','axhxwuun@gmail.com','http://maillots2013.wordpress.com/','202.105.60.238','2013-04-25 04:10:12','2013-04-24 20:10:12','I would like this!!! Boutique Angleterre http://maillots2013.wordpress.com/\n','0','trash','Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)','','0','0');");
E_D("replace into `wp_comments` values('68','54','Inwatstut','ekselmoksel@hotmail.com','http://archive.org/details/rahimburch','94.27.82.152','2013-04-25 04:30:28','2013-04-24 20:30:28','Zyprexa Long Acting Injection  Seroquel Time Tardive Dyskinesia . Ultracet Acetaminophen Narcotic Doryx Long Term Female  <a href=\"http://archive.org/details/urielreilly\" rel=\"nofollow\">Prices Buy Vytorin</a> Cephalexin 250 Mg For Sinus Infection Yasmin Pill Positives Breakthrough Bleeding Effexor Starting Dosage Selective Serotonin Reuptake Inhibitors Prilosec Gout H Pylori . Diet Reviews Acai Berry Pills Nitrofurantoin 100mg Ingredients Missing A Amount Of Lexapro <a href=\"http://archive.org/details/rahimburch\" rel=\"nofollow\">List Soma%27s Online No Prescription</a>. No Rx Buying Symmetrel Avelox Treatment For Cellulitis Urine Testing Oxycodone Coffee Skin Allergies .\n','0','trash','Opera/9.80 (Windows NT 6.1; WOW64) Presto/2.12.388 Version/12.10','','0','0');");
E_D("replace into `wp_comments` values('69','138','maillot psg 2013','teukyyxlj@gmail.com','http://www.maillotpsg2013.info/','202.105.89.157','2013-04-25 16:16:09','2013-04-25 08:16:09','I loved your post.Thanks Again. Really Great. maillot psg 2013 http://www.maillotpsg2013.info/\n','0','trash','Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; TencentTraveler)','','0','0');");
E_D("replace into `wp_comments` values('70','112','inifuffepsy','gdfgdfgdg@mail.ua','http://www.maps.google.com','178.137.163.241','2013-04-26 05:26:34','2013-04-25 21:26:34','http://www.maps.google.com/ -  http://www.wikipedia.org/ - wiki \n<a href=\"http://mail.ru/\" / rel=\"nofollow\">mail</a>\n','0','trash','Opera/9.80 (Windows NT 5.1; MRA 6.0 (build 5998)) Presto/2.12.388 Version/12.11','','0','0');");
E_D("replace into `wp_comments` values('71','295','payday loans uk','','http://www.paydayloansbargains.co.uk','198.245.63.133','2013-04-27 04:28:46','2013-04-26 20:28:46','<strong>payday loans uk...</strong>\n\nI have read not a single post on your weblog. You are a massive lad...\n','0','spam','Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1) )','trackback','0','0');");
E_D("replace into `wp_comments` values('72','81','Mike','sbdh47tf@hotmail.com','http://www.6shpFpANPwYnffbs9P5rsRN67oJWDZuQ.com','188.143.234.127','2013-04-27 17:02:09','2013-04-27 09:02:09','T3ap4l http://www.6shpFpANPwYnffbs9P5rsRN67oJWDZuQ.com\n','0','spam','Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)','','0','0');");
E_D("replace into `wp_comments` values('73','303','simplifier','wersanmir@gmail.com','http://www.grantee.info','190.181.26.245','2013-04-27 19:54:40','2013-04-27 11:54:40','Fantastic site. A lot of useful info here. I''m sending it to some friends ans also sharing in delicious. And obviously, thanks for your sweat! simplifier http://www.grantee.info\n','0','spam','Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1) )','','0','0');");
E_D("replace into `wp_comments` values('74','348','酷赚网','webmaster@koozhuan.com','http://www.koozhuan.com/','119.187.95.141','2013-04-28 16:56:43','2013-04-28 08:56:43','酷赚网，全免费，介绍一人提成一分，一分钱就付款，八级下线，轻松月赚三千。酷赚网，必须干：http://www.koozhuan.com/\n癸巳年(蛇)三月十九 2013-4-28\n','0','spam','Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.1 (KHTML, like Gecko) Chrome/14.0.802.30 Safari/535.1 SE 2.X MetaSr 1.0','','0','0');");
E_D("replace into `wp_comments` values('75','303','michael kors purses','discountflash@hotmail.com','http://www.michaelmichaelkorsoutletonline.com','59.58.189.117','2013-04-28 17:31:20','2013-04-28 09:31:20','If you considerthe sheer quantity of new glasses released every single year there  are only a handful ofsunglasses that attain iconic status and the Michael Kors Bayswater bag falls into that classification. Introduced in 2003 the Bayswater\n<a href=\"http://www.michaelmichaelkorsoutletonline.com\" rel=\"nofollow\">Michael Kors Outlet</a> discount sale online boutinque. including <a href=\"http://www.michaelmichaelkorsoutletonline.com/hamilton-c-265.html\" rel=\"nofollow\">Michael Kors Hamilton Bags</a>\n Tough?\n','0','spam','Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)','','0','0');");
E_D("replace into `wp_comments` values('76','10','john','sbdh47tf@hotmail.com','http://www.6shpFpANPwYnffbs9P5rsRN67oJWDZuQ.com','188.143.234.127','2013-04-28 19:42:36','2013-04-28 11:42:36','mP2d4e http://www.6shpFpANPwYnffbs9P5rsRN67oJWDZuQ.com\n','0','spam','Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)','','0','0');");

require("../../inc/footer.php");
?>