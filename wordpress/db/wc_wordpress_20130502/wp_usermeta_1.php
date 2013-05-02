<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `wp_usermeta`;");
E_C("CREATE TABLE `wp_usermeta` (
  `umeta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `meta_key` varchar(255) DEFAULT NULL,
  `meta_value` longtext,
  PRIMARY KEY (`umeta_id`),
  KEY `user_id` (`user_id`),
  KEY `meta_key` (`meta_key`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8");
E_D("replace into `wp_usermeta` values('1','1','first_name','');");
E_D("replace into `wp_usermeta` values('2','1','last_name','');");
E_D("replace into `wp_usermeta` values('3','1','nickname','Wang Chang');");
E_D("replace into `wp_usermeta` values('4','1','description','');");
E_D("replace into `wp_usermeta` values('5','1','rich_editing','true');");
E_D("replace into `wp_usermeta` values('6','1','comment_shortcuts','false');");
E_D("replace into `wp_usermeta` values('7','1','admin_color','fresh');");
E_D("replace into `wp_usermeta` values('8','1','use_ssl','0');");
E_D("replace into `wp_usermeta` values('9','1','show_admin_bar_front','true');");
E_D("replace into `wp_usermeta` values('10','1','wp_capabilities','a:1:{s:13:\"administrator\";b:1;}');");
E_D("replace into `wp_usermeta` values('11','1','wp_user_level','10');");
E_D("replace into `wp_usermeta` values('12','1','dismissed_wp_pointers','wp330_toolbar,wp330_saving_widgets,wp340_choose_image_from_library,wp340_customize_current_theme_link,wp350_media');");
E_D("replace into `wp_usermeta` values('13','1','show_welcome_panel','1');");
E_D("replace into `wp_usermeta` values('14','1','wp_dashboard_quick_press_last_post_id','353');");
E_D("replace into `wp_usermeta` values('15','1','managenav-menuscolumnshidden','a:4:{i:0;s:11:\"link-target\";i:1;s:11:\"css-classes\";i:2;s:3:\"xfn\";i:3;s:11:\"description\";}');");
E_D("replace into `wp_usermeta` values('16','1','metaboxhidden_nav-menus','a:2:{i:0;s:8:\"add-post\";i:1;s:12:\"add-post_tag\";}');");
E_D("replace into `wp_usermeta` values('17','1','closedpostboxes_post','a:1:{i:0;s:9:\"formatdiv\";}');");
E_D("replace into `wp_usermeta` values('18','1','metaboxhidden_post','a:6:{i:0;s:11:\"postexcerpt\";i:1;s:13:\"trackbacksdiv\";i:2;s:10:\"postcustom\";i:3;s:16:\"commentstatusdiv\";i:4;s:7:\"slugdiv\";i:5;s:9:\"authordiv\";}');");
E_D("replace into `wp_usermeta` values('19','1','wp_user-settings','libraryContent=browse&align=center&imgsize=large&wplink=1');");
E_D("replace into `wp_usermeta` values('20','1','wp_user-settings-time','1364201254');");
E_D("replace into `wp_usermeta` values('21','1','nav_menu_recently_edited','7');");
E_D("replace into `wp_usermeta` values('22','1','closedpostboxes_dashboard','a:1:{i:0;s:25:\"dashboard_recent_comments\";}');");
E_D("replace into `wp_usermeta` values('23','1','metaboxhidden_dashboard','a:0:{}');");
E_D("replace into `wp_usermeta` values('24','1','closedpostboxes_nav-menus','a:0:{}');");
E_D("replace into `wp_usermeta` values('25','1','aim','');");
E_D("replace into `wp_usermeta` values('26','1','yim','');");
E_D("replace into `wp_usermeta` values('27','1','jabber','');");
E_D("replace into `wp_usermeta` values('28','1','googleplus','');");

require("../../inc/footer.php");
?>