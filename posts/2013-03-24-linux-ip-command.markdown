IP����ɱ�ifconfigǿ����ˣ�����IP�ͻ������������緽�����ά����ȡ�����IP�������ϸѧϰ�����Բο���ĩ�Ĳο����£�������ֻ��¼һ���Ҿ��ö��ұȽ�ʵ�õġ�

> ip��iproute2����������һ��ǿ����������ù��ߣ����ܹ����һЩ��ͳ����������ߡ����磺ifconfig��route �ȡ�

## 1 �����ʽ

     ip [OPTIONS] OBJECT [COMMAND [ARGUMENTS]]
    

OPTIONS��

*   -V ��ӡip �İ汾���˳�
*   -s,-stats,-statistics �����Ϊ�꾡����Ϣ��
*   -o ��ÿ�м�¼��ʹ�õ���������������ַ����档
*   -r ��ѯ��������ϵͳ���û�õ���������������IP ��ַ��

<!--more-->

OBJECT������Ҫ������߻�ȡ��Ϣ�Ķ���

*   link �����豸
*   address һ���豸��Э�飨IP ����IPV6����ַ
*   neighbour ARP ����NDISC ��������Ŀ
*   route ·�ɱ���Ŀ
*   rule ·�ɲ������ݿ��еĹ��� *��maddress �ಥ��ַ
*   mroute �ಥ·�ɻ�������Ŀ
*   tunnel IP�ϵ�ͨ��

ARGUMENTS �������һЩ���������������ڶ�������ip֧���������͵Ĳ�����flag ��parameter ��flag��һ���ؼ�����ɣ�parameter ��һ���ؼ��ʼ�һ����ֵ��ɡ�Ϊ�˷��㣬ÿ�������һ�����Ժ��Ե�Ĭ�ϲ��������磬����dev ��ip link �����Ĭ�ϲ��������ip link ls eth0 ����ip link ls dev eth0

## LINK

�鿴�豸��Ϣ��

    root@kb310-node10:~#ip link show {dev NAME,up,}
    
    1: lo: <LOOPBACK,UP,LOWER_UP> mtu 16436 qdisc noqueue state UNKNOWN 
        link/loopback 00:00:00:00:00:00 brd 00:00:00:00:00:00
    2: eth0: <BROADCAST,MULTICAST,UP,LOWER_UP> mtu 1500 qdisc pfifo_fast state UP qlen 1000
        link/ether 20:cf:30:01:f2:99 brd ff:ff:ff:ff:ff:ff
    

qdisc (queuing discipline)��ʾ�������ӿ�ʹ�õ��Ŷ��㷨��noqueue ��ʾ�������ݰ������Ŷӣ�noop ��ʾ�������ӿڳ��ںڶ�ģʽ��Ҳ�������н��뱾�����豸�����ݻ�ֱ�ӱ�������qlen ������ӿڴ�����е�Ĭ�ϳ��ȡ�

ʹ��-statistics ѡ�ip������ӡ������ӿڵ�ͳ����Ϣ��

    root@kb310-node10:~# ip -s link show dev eth0
    2: eth0: <BROADCAST,MULTICAST,UP,LOWER_UP> mtu 1500 qdisc pfifo_fast state UP qlen 1000
        link/ether 20:cf:30:01:f2:99 brd ff:ff:ff:ff:ff:ff
        RX: bytes  packets  errors  dropped overrun mcast   
        299273196425 420800569 0       51      0       0      
        TX: bytes  packets  errors  dropped carrier collsns 
        285997074106 420422755 0       0       0       0 
    

�����豸��Ϣ��

    ip link set dev eth0 {up/down,arp on/off,multicast on/off,name NAME,txqueuelen NUMBER,mtu NUMBER,address LLADDRESS(MAC��ַ),}
    

ע�⣺ip�����޸�PROMISC ����ALLMULTIѡ�������ѡ���Ѿ��Ƚϳ¾ɣ� ����Ҳ��Ӧ������޸ġ�

## ADDRESS

    ip address add 
    

�������ϸ������

*   dev NAME ���������豸�� *local ADDRESS(default) �ӿڵĵ�ַ����ַ��ʽ��Э���йء�IPv4 ��ַʹ��. ���зָ�����IPv6 ��ַʹ��ð�ŷָ���ADDRESS ���Ը���һ��б�ܺͱ�ʾ����λ����ʮ�������֡�
*   peer ADDRESS ��Ե�ӿڶԶ˵ĵ�ַ��ADDRESS Ҳ���Ը���һ��б�ܺͱ�ʾ����λ����ʮ�������֡�
*   broadcast ADDRESS �ӿڵĹ㲥��ַ��Ϊ�˷��㣬����ʹ��+��-(ע1) ����㲥��ַ�����磺
    
    ip addr add local 192.168.1.1/24 brd + dev eth0
    
    ip addr add local 192.168.1.1/28 brd - dev eth0

ʹ��-��ip addr ls��ʾ���������ַ��ʹ��+��ip addr ls��ʾ���ǹ㲥��ַ��

*   scope SCOPE\_VALUE(ע2) ���õ�ַ����Ч��Χ���������ں�Ϊ���ݰ�����Դ��ַ����Ч�ķ�Χ��/etc/iproute2/tr\_scopes �ļ��г���ϵͳԤ���趨��һЩ��Χֵ�� global �����ַȫ����Ч��site �����ַ�Ǿֲ����ӣ�Ҳ����ֻ��Ŀ�� ��ַ������豸��ַʱ������Ч��site (ֻ������IPv6) ��ַ��վ���ڲ���Ч��host ��ַ�������ڲ���Ч��
    
    ip address delete

�÷�����ӵ�ַ���ơ�

    ip address show
    

## NEIGHBOUR ARP����

    ip neighbour show
    

## ROUTE,RO,R·�ɱ����

���ȣ�·�����ͣ�

*   unicast �������͵�·��������Ŀ�ĵ�ַ����ʵ·���� 
*   unreachable ��ЩĿ�ĵ�ַ�ǲ��ɴ�ġ��������ȥ�����ݰ��������������յ�ICMP ��Ϣhost unreachable��Ŀ�ĵ�ַ�ͻᱻ���Ϊ���ɴ����������£����ط����߽�����EHOSTUNREAC H����
*   blackhole ��ЩĿ�ĵ�ַ���ɴ���ҷ���ȥ�����ݰ���������������������£����ط����߽�����EINVAL ���� 
*   local Ŀ�ĵ�ַ����������������ݰ�ͨ���ػ���Ͷ�ݵ����ء� 
*   broadcast Ŀ�ĵ�ַ�ǹ㲥��ַ�����ݰ���Ϊ��·�㲥���͡� 
*   throw �Ͳ��Թ���(policy rule) һ��ʹ�õĿ���·�ɡ����ѡ��������·�ɣ��ͻ���Ϊû�з���·�ɣ���������еĲ�ѯ�ͻᱻ��ֹ��û���ҵ�����·�ɾ��൱����·�ɱ���û���ҵ�·�ɣ����ݰ��ᱻ������������ICMP ��Ϣnet<br>*nat �ض���NAT ·�ɡ�Ŀ���ַ�����Ƶ�ַ�����߳�Ϊ�ⲿ��ַ������ת��ǰ��Ҫ���е�ַת���� 
*   anycast Ŀ����anycast ��ַ��������������������ַ�ͱ��ص�ַ��ͬС�죬��ͬ���������ַ���������κ����ݰ���Դ��ַ�� 
*   multicast ʹ�öಥ·�ɡ�����ͨ��·�ɱ��У�����·�ɲ������ڡ�

�漰·�ɵĲ�����

    ip route add -- �����·��
    ip route change -- �޸�·��
    ip route replace -- �滻���е�·��
    

*   to PREFIX ����to TYPE��·�ɵ�Ŀ��ǰ׺(prefix)�����TYPE�����ԣ�ip����ͻ�ʹ��Ĭ�ϵ�����unicast��
*   dev NAME������豸������
*   via ADDRESS��ָ����һ��·�����ĵ�ַ��ʵ���ϣ������Ŀɿ���ȡ����·�����͡�
*   protocol RTPROTO������·�ɵ�·��Э��ʶ�����

## �ο����£�

<http://yemaosheng.com/?p=409>

<http://wenku.baidu.com/view/48053eeeaeaad1f346933fd3.html>