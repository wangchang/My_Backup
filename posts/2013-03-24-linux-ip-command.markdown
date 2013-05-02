IP命令可比ifconfig强大多了，掌握IP就基本掌握了网络方面的运维管理等。关于IP命令的详细学习，可以参考文末的参考文章，我这里只记录一下我觉得对我比较实用的。

> ip是iproute2软件包里面的一个强大的网络配置工具，它能够替代一些传统的网络管理工具。例如：ifconfig、route 等。

## 1 命令格式

     ip [OPTIONS] OBJECT [COMMAND [ARGUMENTS]]
    

OPTIONS：

*   -V 打印ip 的版本并退出
*   -s,-stats,-statistics 输出更为详尽的信息。
*   -o 对每行记录都使用单行输出，回行用字符代替。
*   -r 查询域名解析系统，用获得的主机名代替主机IP 地址。

<!--more-->

OBJECT：是你要管理或者获取信息的对象

*   link 网络设备
*   address 一个设备的协议（IP 或者IPV6）地址
*   neighbour ARP 或者NDISC 缓冲区条目
*   route 路由表条目
*   rule 路由策略数据库中的规则 *　maddress 多播地址
*   mroute 多播路由缓冲区条目
*   tunnel IP上的通道

ARGUMENTS 是命令的一些参数，它们倚赖于对象和命令。ip支持两种类型的参数：flag 和parameter 。flag由一个关键词组成；parameter 由一个关键词加一个数值组成。为了方便，每个命令都有一个可以忽略的默认参数。例如，参数dev 是ip link 命令的默认参数，因此ip link ls eth0 等于ip link ls dev eth0

## LINK

查看设备信息：

    root@kb310-node10:~#ip link show {dev NAME,up,}
    
    1: lo: <LOOPBACK,UP,LOWER_UP> mtu 16436 qdisc noqueue state UNKNOWN 
        link/loopback 00:00:00:00:00:00 brd 00:00:00:00:00:00
    2: eth0: <BROADCAST,MULTICAST,UP,LOWER_UP> mtu 1500 qdisc pfifo_fast state UP qlen 1000
        link/ether 20:cf:30:01:f2:99 brd ff:ff:ff:ff:ff:ff
    

qdisc (queuing discipline)显示这个网络接口使用的排队算法。noqueue 表示不对数据包进行排队；noop 表示这个网络接口出于黑洞模式，也就是所有进入本网络设备的数据会直接被丢弃。qlen 是网络接口传输队列的默认长度。

使用-statistics 选项，ip命令会打印出网络接口的统计信息：

    root@kb310-node10:~# ip -s link show dev eth0
    2: eth0: <BROADCAST,MULTICAST,UP,LOWER_UP> mtu 1500 qdisc pfifo_fast state UP qlen 1000
        link/ether 20:cf:30:01:f2:99 brd ff:ff:ff:ff:ff:ff
        RX: bytes  packets  errors  dropped overrun mcast   
        299273196425 420800569 0       51      0       0      
        TX: bytes  packets  errors  dropped carrier collsns 
        285997074106 420422755 0       0       0       0 
    

更改设备信息：

    ip link set dev eth0 {up/down,arp on/off,multicast on/off,name NAME,txqueuelen NUMBER,mtu NUMBER,address LLADDRESS(MAC地址),}
    

注意：ip不能修改PROMISC 或者ALLMULTI选项。这两个选项已经比较陈旧， 而且也不应该随便修改。

## ADDRESS

    ip address add 
    

这个得详细看看：

*   dev NAME 被操作的设备名 *local ADDRESS(default) 接口的地址，地址格式和协议有关。IPv4 地址使用. 进行分隔，而IPv6 地址使用冒号分隔。ADDRESS 可以跟着一个斜杠和表示掩码位数的十进制数字。
*   peer ADDRESS 点对点接口对端的地址。ADDRESS 也可以跟着一个斜杠和表示掩码位数的十进制数字。
*   broadcast ADDRESS 接口的广播地址。为了方便，可以使用+和-(注1) 代替广播地址。例如：
    
    ip addr add local 192.168.1.1/24 brd + dev eth0
    
    ip addr add local 192.168.1.1/28 brd - dev eth0

使用-，ip addr ls显示的是网络地址；使用+，ip addr ls显示的是广播地址。

*   scope SCOPE\_VALUE(注2) 设置地址的有效范围，它用于内核为数据包设置源地址。有效的范围在/etc/iproute2/tr\_scopes 文件列出，系统预先设定了一些范围值： global 这个地址全局有效。site 这个地址是局部连接，也就是只有目标 地址是这个设备地址时，才有效。site (只适用于IPv6) 地址在站点内部有效。host 地址在主机内部有效。
    
    ip address delete

用法和添加地址类似。

    ip address show
    

## NEIGHBOUR ARP管理

    ip neighbour show
    

## ROUTE,RO,R路由表管理

首先，路由类型：

*   unicast 这种类型的路由描述到目的地址的真实路径。 
*   unreachable 这些目的地址是不可达的。如果发过去的数据包都被丢弃并且收到ICMP 信息host unreachable，目的地址就会被标记为不可达。在这种情况下，本地发送者将返回EHOSTUNREAC H错误。
*   blackhole 这些目的地址不可达，而且发过去的数据包都被丢弃。在这种情况下，本地发送者将返回EINVAL 错误。 
*   local 目的地址被分配给本机。数据包通过回环被投递到本地。 
*   broadcast 目的地址是广播地址，数据包作为链路广播发送。 
*   throw 和策略规则(policy rule) 一块使用的控制路由。如果选择了这种路由，就会认为没有发现路由，在这个表中的查询就会被终止。没有找到策略路由就相当于在路由表中没有找到路由，数据包会被丢弃，并产生ICMP 信息net<br>*nat 特定的NAT 路由。目标地址属于哑地址（或者称为外部地址），在转发前需要进行地址转换。 
*   anycast 目标是anycast 地址，被分配给本机。这类地址和本地地址大同小异，不同的是这类地址不能用于任何数据包的源地址。 
*   multicast 使用多播路由。在普通的路由表中，这种路由并不存在。

涉及路由的操作：

    ip route add -- 添加新路由
    ip route change -- 修改路由
    ip route replace -- 替换已有的路由
    

*   to PREFIX 或者to TYPE：路由的目标前缀(prefix)。如果TYPE被忽略，ip命令就会使用默认的类型unicast。
*   dev NAME：输出设备的名字
*   via ADDRESS：指定下一跳路由器的地址。实际上，这个域的可靠性取决于路由类型。
*   protocol RTPROTO：本条路由得路由协议识别符。

## 参考文章：

<http://yemaosheng.com/?p=409>

<http://wenku.baidu.com/view/48053eeeaeaad1f346933fd3.html>