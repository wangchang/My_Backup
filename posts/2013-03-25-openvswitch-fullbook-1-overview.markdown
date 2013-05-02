> OpenvSwitch��ȫʹ���ֲ�(һ)-����Overview
> 
> ������Ҫ�ο�[Overview of functionality and components][1]�Լ�[Frequently Asked Questions][2]�Լ�����Լ�����⡣

## 1 ʲô��OpenvSwitch

OpenvSwitch�����OVS��һ�����⽻���������Ҫ���������VM��������Ϊһ�����⽻������֧��Xen/XenServer, KVM, and VirtualBox�������⻯������

������ĳһ̨���������⻯�Ļ����У�һ�����⽻������vswitch����Ҫ���������ã����������VM֮����������Լ�ʵ��VM����������ͨ�š�

����OVS������Cд�ġ�Ŀǰ�����¹��ܣ�

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

## 2 OpenvSwitch�����

*   ovs-vswitchd���ػ�����ʵ�ֽ������ܣ���Linux�ں˼���ģ��һ��ʵ�ֻ������Ľ���flow-based switching��
*   ovsdb-server�������������ݿ������Ҫ����������OVS��������Ϣ�������ӿڰ����������ݣ�VLAN���ȵȡ�ovs-vswitchd��������ݿ��е�������Ϣ������
*   ovs-dpctl��һ�����ߣ��������ý������ں�ģ�飬���Կ���ת������
*   ovs-vsctl����Ҫ�ǻ�ȡ���߸���ovs-vswitchd��������Ϣ���˹��߲�����ʱ������ovsdb-server�е����ݿ⡣
*   ovs-appctl����Ҫ����OVS�ػ����̷�������ģ�һ���ò��ϡ�
*   ovsdbmonitor��GUI��������ʾovsdb-server��������Ϣ��
*   ovs-controller��һ���򵥵�OpenFlow������
*   ovs-ofctl����������OVS��ΪOpenFlow����������ʱ����������ݡ�

## 3 OpenvSwitch������vswitch

����������vswitch,����VMware vNetwork distributed switch�Լ�˼�Ƶ�Cisco Nexus 1000V��

VMware vNetwork distributed switch�Լ�˼�Ƶ�Cisco Nexus 1000V�������⽻�����ṩ����һ������ʽ�Ŀ��Ʒ�ʽ������OVS����һ��������vswitch����������ÿ��ʵ�����⻯����������ϣ����ṩԶ�̹���OVS�ṩ�����������⻯������Զ�̹����Э�飺һ����OpenFlow,ͨ��������������������Ϊ��һ����OVSDB management protocol��������¶sietch��port״̬��

 [1]: http://openvswitch.org/cgi-bin/gitweb.cgi?p=openvswitch;a=blob_plain;f=README;hb=HEAD
 [2]: http://openvswitch.org/cgi-bin/gitweb.cgi?p=openvswitch;a=blob_plain;f=FAQ;hb=HEAD