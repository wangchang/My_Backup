<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `wp_commentmeta`;");
E_C("CREATE TABLE `wp_commentmeta` (
  `meta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `comment_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `meta_key` varchar(255) DEFAULT NULL,
  `meta_value` longtext,
  PRIMARY KEY (`meta_id`),
  KEY `comment_id` (`comment_id`),
  KEY `meta_key` (`meta_key`)
) ENGINE=InnoDB AUTO_INCREMENT=100 DEFAULT CHARSET=utf8");
E_D("replace into `wp_commentmeta` values('82','64','_wp_trash_meta_status','0');");
E_D("replace into `wp_commentmeta` values('83','65','_wp_trash_meta_status','0');");
E_D("replace into `wp_commentmeta` values('84','66','_wp_trash_meta_status','0');");
E_D("replace into `wp_commentmeta` values('85','66','_wp_trash_meta_time','1366944069');");
E_D("replace into `wp_commentmeta` values('86','67','_wp_trash_meta_status','0');");
E_D("replace into `wp_commentmeta` values('87','67','_wp_trash_meta_time','1366944071');");
E_D("replace into `wp_commentmeta` values('88','68','_wp_trash_meta_status','0');");
E_D("replace into `wp_commentmeta` values('89','68','_wp_trash_meta_time','1366944072');");
E_D("replace into `wp_commentmeta` values('90','69','_wp_trash_meta_status','0');");
E_D("replace into `wp_commentmeta` values('91','69','_wp_trash_meta_time','1366944073');");
E_D("replace into `wp_commentmeta` values('92','70','_wp_trash_meta_status','0');");
E_D("replace into `wp_commentmeta` values('93','70','_wp_trash_meta_time','1366944077');");
E_D("replace into `wp_commentmeta` values('94','71','_wp_trash_meta_status','0');");
E_D("replace into `wp_commentmeta` values('95','72','_wp_trash_meta_status','0');");
E_D("replace into `wp_commentmeta` values('96','73','_wp_trash_meta_status','0');");
E_D("replace into `wp_commentmeta` values('97','74','_wp_trash_meta_status','0');");
E_D("replace into `wp_commentmeta` values('98','75','_wp_trash_meta_status','0');");
E_D("replace into `wp_commentmeta` values('99','76','_wp_trash_meta_status','0');");

require("../../inc/footer.php");
?>