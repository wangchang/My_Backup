<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `wp_term_relationships`;");
E_C("CREATE TABLE `wp_term_relationships` (
  `object_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `term_taxonomy_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `term_order` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`object_id`,`term_taxonomy_id`),
  KEY `term_taxonomy_id` (`term_taxonomy_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8");
E_D("replace into `wp_term_relationships` values('1','1','0');");
E_D("replace into `wp_term_relationships` values('7','1','0');");
E_D("replace into `wp_term_relationships` values('18','1','0');");
E_D("replace into `wp_term_relationships` values('21','1','0');");
E_D("replace into `wp_term_relationships` values('25','1','0');");
E_D("replace into `wp_term_relationships` values('30','1','0');");
E_D("replace into `wp_term_relationships` values('33','2','0');");
E_D("replace into `wp_term_relationships` values('33','4','0');");
E_D("replace into `wp_term_relationships` values('33','5','0');");
E_D("replace into `wp_term_relationships` values('42','1','0');");
E_D("replace into `wp_term_relationships` values('47','1','0');");
E_D("replace into `wp_term_relationships` values('54','8','0');");
E_D("replace into `wp_term_relationships` values('54','9','0');");
E_D("replace into `wp_term_relationships` values('54','10','0');");
E_D("replace into `wp_term_relationships` values('76','2','0');");
E_D("replace into `wp_term_relationships` values('76','11','0');");
E_D("replace into `wp_term_relationships` values('76','12','0');");
E_D("replace into `wp_term_relationships` values('76','13','0');");
E_D("replace into `wp_term_relationships` values('76','14','0');");
E_D("replace into `wp_term_relationships` values('76','15','0');");
E_D("replace into `wp_term_relationships` values('78','2','0');");
E_D("replace into `wp_term_relationships` values('78','12','0');");
E_D("replace into `wp_term_relationships` values('81','2','0');");
E_D("replace into `wp_term_relationships` values('81','12','0');");
E_D("replace into `wp_term_relationships` values('81','13','0');");
E_D("replace into `wp_term_relationships` values('81','14','0');");
E_D("replace into `wp_term_relationships` values('81','15','0');");
E_D("replace into `wp_term_relationships` values('90','2','0');");
E_D("replace into `wp_term_relationships` values('90','5','0');");
E_D("replace into `wp_term_relationships` values('90','16','0');");
E_D("replace into `wp_term_relationships` values('93','17','0');");
E_D("replace into `wp_term_relationships` values('93','18','0');");
E_D("replace into `wp_term_relationships` values('93','19','0');");
E_D("replace into `wp_term_relationships` values('105','1','0');");
E_D("replace into `wp_term_relationships` values('109','1','0');");
E_D("replace into `wp_term_relationships` values('110','1','0');");
E_D("replace into `wp_term_relationships` values('112','2','0');");
E_D("replace into `wp_term_relationships` values('112','8','0');");
E_D("replace into `wp_term_relationships` values('112','20','0');");
E_D("replace into `wp_term_relationships` values('121','7','0');");
E_D("replace into `wp_term_relationships` values('122','7','0');");
E_D("replace into `wp_term_relationships` values('129','1','0');");
E_D("replace into `wp_term_relationships` values('133','2','0');");
E_D("replace into `wp_term_relationships` values('133','8','0');");
E_D("replace into `wp_term_relationships` values('133','20','0');");
E_D("replace into `wp_term_relationships` values('136','7','0');");
E_D("replace into `wp_term_relationships` values('138','17','0');");
E_D("replace into `wp_term_relationships` values('138','18','0');");
E_D("replace into `wp_term_relationships` values('138','21','0');");
E_D("replace into `wp_term_relationships` values('142','1','0');");
E_D("replace into `wp_term_relationships` values('143','1','0');");
E_D("replace into `wp_term_relationships` values('145','1','0');");
E_D("replace into `wp_term_relationships` values('147','1','0');");
E_D("replace into `wp_term_relationships` values('151','8','0');");
E_D("replace into `wp_term_relationships` values('151','18','0');");
E_D("replace into `wp_term_relationships` values('151','19','0');");
E_D("replace into `wp_term_relationships` values('159','23','0');");
E_D("replace into `wp_term_relationships` values('159','24','0');");
E_D("replace into `wp_term_relationships` values('159','25','0');");
E_D("replace into `wp_term_relationships` values('161','1','0');");
E_D("replace into `wp_term_relationships` values('165','8','0');");
E_D("replace into `wp_term_relationships` values('165','18','0');");
E_D("replace into `wp_term_relationships` values('165','21','0');");
E_D("replace into `wp_term_relationships` values('165','22','0');");
E_D("replace into `wp_term_relationships` values('172','17','0');");
E_D("replace into `wp_term_relationships` values('172','18','0');");
E_D("replace into `wp_term_relationships` values('172','26','0');");
E_D("replace into `wp_term_relationships` values('181','8','0');");
E_D("replace into `wp_term_relationships` values('181','27','0');");
E_D("replace into `wp_term_relationships` values('181','28','0');");
E_D("replace into `wp_term_relationships` values('181','29','0');");
E_D("replace into `wp_term_relationships` values('181','30','0');");
E_D("replace into `wp_term_relationships` values('184','8','0');");
E_D("replace into `wp_term_relationships` values('184','27','0');");
E_D("replace into `wp_term_relationships` values('184','28','0');");
E_D("replace into `wp_term_relationships` values('184','29','0');");
E_D("replace into `wp_term_relationships` values('184','30','0');");

require("../../inc/footer.php");
?>