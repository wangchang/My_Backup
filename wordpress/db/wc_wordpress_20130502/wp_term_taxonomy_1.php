<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `wp_term_taxonomy`;");
E_C("CREATE TABLE `wp_term_taxonomy` (
  `term_taxonomy_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `term_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `taxonomy` varchar(32) NOT NULL DEFAULT '',
  `description` longtext NOT NULL,
  `parent` bigint(20) unsigned NOT NULL DEFAULT '0',
  `count` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`term_taxonomy_id`),
  UNIQUE KEY `term_id_taxonomy` (`term_id`,`taxonomy`),
  KEY `taxonomy` (`taxonomy`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8");
E_D("replace into `wp_term_taxonomy` values('1','1','category','','0','0');");
E_D("replace into `wp_term_taxonomy` values('2','2','category','','0','11');");
E_D("replace into `wp_term_taxonomy` values('3','3','post_tag','','0','0');");
E_D("replace into `wp_term_taxonomy` values('4','4','post_tag','','0','1');");
E_D("replace into `wp_term_taxonomy` values('5','5','post_tag','','0','2');");
E_D("replace into `wp_term_taxonomy` values('7','7','nav_menu','','0','5');");
E_D("replace into `wp_term_taxonomy` values('8','8','category','','0','11');");
E_D("replace into `wp_term_taxonomy` values('9','9','post_tag','','0','1');");
E_D("replace into `wp_term_taxonomy` values('10','10','post_tag','','0','1');");
E_D("replace into `wp_term_taxonomy` values('11','11','post_tag','','0','1');");
E_D("replace into `wp_term_taxonomy` values('12','12','post_tag','','0','3');");
E_D("replace into `wp_term_taxonomy` values('13','13','post_tag','','0','2');");
E_D("replace into `wp_term_taxonomy` values('14','14','post_tag','','0','2');");
E_D("replace into `wp_term_taxonomy` values('15','15','post_tag','','0','2');");
E_D("replace into `wp_term_taxonomy` values('16','16','post_tag','','0','1');");
E_D("replace into `wp_term_taxonomy` values('17','17','category','','0','3');");
E_D("replace into `wp_term_taxonomy` values('18','18','post_tag','','0','5');");
E_D("replace into `wp_term_taxonomy` values('19','19','post_tag','','0','2');");
E_D("replace into `wp_term_taxonomy` values('20','20','post_tag','','0','2');");
E_D("replace into `wp_term_taxonomy` values('21','21','post_tag','','0','8');");
E_D("replace into `wp_term_taxonomy` values('22','22','category','','0','4');");
E_D("replace into `wp_term_taxonomy` values('23','23','category','','0','12');");
E_D("replace into `wp_term_taxonomy` values('24','24','post_tag','','0','3');");
E_D("replace into `wp_term_taxonomy` values('25','25','post_tag','','0','1');");
E_D("replace into `wp_term_taxonomy` values('26','26','post_tag','','0','1');");
E_D("replace into `wp_term_taxonomy` values('27','27','category','','0','2');");
E_D("replace into `wp_term_taxonomy` values('28','28','category','','0','5');");
E_D("replace into `wp_term_taxonomy` values('29','29','post_tag','','0','3');");
E_D("replace into `wp_term_taxonomy` values('30','30','post_tag','','0','5');");
E_D("replace into `wp_term_taxonomy` values('31','31','post_tag','','0','2');");
E_D("replace into `wp_term_taxonomy` values('32','32','post_tag','','0','1');");
E_D("replace into `wp_term_taxonomy` values('33','33','post_tag','','0','4');");
E_D("replace into `wp_term_taxonomy` values('34','34','post_tag','','0','1');");
E_D("replace into `wp_term_taxonomy` values('35','35','post_tag','','0','3');");
E_D("replace into `wp_term_taxonomy` values('36','36','post_tag','','0','1');");
E_D("replace into `wp_term_taxonomy` values('37','37','category','','0','1');");
E_D("replace into `wp_term_taxonomy` values('38','38','category','','0','1');");
E_D("replace into `wp_term_taxonomy` values('39','39','post_tag','','0','1');");

require("../../inc/footer.php");
?>