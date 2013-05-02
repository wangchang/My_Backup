> OpenvSwitch完全使用手册(一)-总览Overview
> 
> 本文主要参考[Overview of functionality and components][1]以及[Frequently Asked Questions][2]以及结合自己的理解。

## 1 什么是OpenvSwitch

OpenvSwitch，简称OVS是一个虚拟交换软件，主要用于虚拟机VM环境，作为一个虚拟交换机，支持Xen/XenServer, KVM, and VirtualBox多种虚拟化技术。

在这种某一台机器的虚拟化的环境中，一个虚拟交换机（vswitch）主要有两个作用：传递虚拟机VM之间的流量，以及实现VM和外界网络的通信。

整个OVS代码用C写的。目前有以下功能：

*   Standard 802.1Q VLAN model with trunk and access ports
*   NIC bonding with or without LACP on upstream switch
*   NetFlow, sFlow(R), and mirroring for increased visibility
*   QoS (Quality of Service) configuration, plus policing
*   GRE, GRE over IPSEC, VXLAN, and LISP tunneling
*   802.1ag connectivity fault management
*   OpenFlow 1.0 plus numerous extensions
*   Transactional configuration database with C and Python bindings
*   High-performance forwarding using a Linux kernel module

<!--more-->

## 2 OpenvSwitch的组成

*   ovs-vswitchd：守护程序，实现交换功能，和Linux内核兼容模块一起，实现基于流的交换flow-based switching。
*   ovsdb-server：轻量级的数据库服务，主要保存了整个OVS的配置信息，包括接口啊，交换内容，VLAN啊等等。ovs-vswitchd会根据数据库中的配置信息工作。
*   ovs-dpctl：一个工具，用来配置交换机内核模块，可以控制转发规则。
*   ovs-vsctl：主要是获取或者更改ovs-vswitchd的配置信息，此工具操作的时候会更新ovsdb-server中的数据库。
*   ovs-appctl：主要是向OVS守护进程发送命令的，一般用不上。
*   ovsdbmonitor：GUI工具来显示ovsdb-server中数据信息。
*   ovs-controller：一个简单的OpenFlow控制器
*   ovs-ofctl：用来控制OVS作为OpenFlow交换机工作时候的流表内容。

## 3 OpenvSwitch和其他vswitch

这里其他的vswitch,包括VMware vNetwork distributed switch以及思科的Cisco Nexus 1000V。

VMware vNetwork distributed switch以及思科的Cisco Nexus 1000V这种虚拟交换机提供的是一个集中式的控制方式，。而OVS则是一个独立的vswitch，他运行在每个实现虚拟化的物理机器上，并提供远程管理。OVS提供了两种在虚拟化环境中远程管理的协议：一个是OpenFlow,通过流表来管理交换机的行为，一个是OVSDB management protocol，用来暴露sietch的port状态。

 [1]: http://openvswitch.org/cgi-bin/gitweb.cgi?p=openvswitch;a=blob_plain;f=README;hb=HEAD
 [2]: http://openvswitch.org/cgi-bin/gitweb.cgi?p=openvswitch;a=blob_plain;f=FAQ;hb=HEAD