<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `wp_terms`;");
E_C("CREATE TABLE `wp_terms` (
  `term_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL DEFAULT '',
  `slug` varchar(200) NOT NULL DEFAULT '',
  `term_group` bigint(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`term_id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8");
E_D("replace into `wp_terms` values('1','未分类','uncategorized','0');");
E_D("replace into `wp_terms` values('2','运维管理','%e8%bf%90%e7%bb%b4%e7%ae%a1%e7%90%86','0');");
E_D("replace into `wp_terms` values('3','letodms.ldap','letodms-ldap','0');");
E_D("replace into `wp_terms` values('4','letodms','letodms','0');");
E_D("replace into `wp_terms` values('5','ldap','ldap','0');");
E_D("replace into `wp_terms` values('7','导航菜单','%e5%af%bc%e8%88%aa%e8%8f%9c%e5%8d%95','0');");
E_D("replace into `wp_terms` values('8','技术学习','%e6%8a%80%e6%9c%af%e5%ad%a6%e4%b9%a0','0');");
E_D("replace into `wp_terms` values('9','amqp','amqp','0');");
E_D("replace into `wp_terms` values('10','rabbitmq','rabbitmq','0');");
E_D("replace into `wp_terms` values('11','cgi','cgi','0');");
E_D("replace into `wp_terms` values('12','php','php','0');");
E_D("replace into `wp_terms` values('13','fastcgi','fastcgi','0');");
E_D("replace into `wp_terms` values('14','php-cgi','php-cgi','0');");
E_D("replace into `wp_terms` values('15','php-fpm','php-fpm','0');");
E_D("replace into `wp_terms` values('16','openvpn','openvpn','0');");
E_D("replace into `wp_terms` values('17','协议学习','%e5%8d%8f%e8%ae%ae%e5%ad%a6%e4%b9%a0','0');");
E_D("replace into `wp_terms` values('18','GRE','gre','0');");
E_D("replace into `wp_terms` values('19','tunnel','tunnel','0');");
E_D("replace into `wp_terms` values('20','iptables','iptables','0');");
E_D("replace into `wp_terms` values('21','openvswitch','openvswitch','0');");
E_D("replace into `wp_terms` values('22','OpenFlow','openflow','0');");
E_D("replace into `wp_terms` values('23','OpenStack','openstack','0');");
E_D("replace into `wp_terms` values('24','openstack','openstack-2','0');");
E_D("replace into `wp_terms` values('25','hadoop','hadoop','0');");
E_D("replace into `wp_terms` values('26','NVGRE','nvgre','0');");
E_D("replace into `wp_terms` values('27','Web开发','web%e5%bc%80%e5%8f%91','0');");
E_D("replace into `wp_terms` values('28','Python','python','0');");
E_D("replace into `wp_terms` values('29','wsgi','wsgi','0');");
E_D("replace into `wp_terms` values('30','python','python-2','0');");
E_D("replace into `wp_terms` values('31','wordpress','wordpress','0');");
E_D("replace into `wp_terms` values('32','ip','ip','0');");
E_D("replace into `wp_terms` values('33','quantum','quantum','0');");
E_D("replace into `wp_terms` values('34','sphinx','sphinx','0');");
E_D("replace into `wp_terms` values('35','paste.deploy','paste-deploy','0');");
E_D("replace into `wp_terms` values('36','dns','dns','0');");
E_D("replace into `wp_terms` values('37','Coding','coding','0');");
E_D("replace into `wp_terms` values('38','Life','life','0');");
E_D("replace into `wp_terms` values('39','wine','wine','0');");

require("../../inc/footer.php");
?>