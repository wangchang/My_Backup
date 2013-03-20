<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ahm_files`;");
E_C("CREATE TABLE `ahm_files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `category` text NOT NULL,
  `file` varchar(255) NOT NULL,
  `password` varchar(40) NOT NULL,
  `download_count` int(11) NOT NULL,
  `access` enum('guest','member') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `show_counter` tinyint(1) NOT NULL,
  `quota` int(11) NOT NULL,
  `link_label` varchar(255) NOT NULL,
  `icon` varchar(256) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8");
E_D("replace into `ahm_files` values('1','Peer_XU:open-vswitch-gre-tunnel-configuration','','a:1:{i:0;s:11:\"openvswitch\";}','2013-1-15-open-vswitch-gre-tunnel-configuration.md','','6','guest','0','0','Download','Cloud Download On.png');");
E_D("replace into `ahm_files` values('2','KIMI YANG » Open vSwitch的GRE Tunnel配置','','a:1:{i:0;s:11:\"openvswitch\";}','KIMI YANG » Open vSwitch的GRE Tunnel配置.pdf','','2','guest','0','0','Download','Cloud Download On.png');");

require("../../inc/footer.php");
?>