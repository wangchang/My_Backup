上一节说到了，iptables的一些基本概念以及工作流程，下面我们来看看iptables具体该如何使用。

本系列文章索引：

第一章：[流程介绍][1]

第二章：[如何使用][2]

第三章：深入理解

第四章：高级用法

## 1 规则语法：

### 1.1 查看规则

    iptables [-t 表名] <-L> [链名]
    

注：不指定表名的话默认查看filter表

### 1.2 增加(追加，append)、插入、替换规则

    iptables [-t 表名] <-A(追加) | -I(插入)> | -D(删除) | -R(替换)> <链名> [规则编号] [-i|o 进入或流出网卡名称] [-p 协议类型] [-s 源IP地址|源子网] [--sport 源端口号] [-d 目标ip地址|目标子网] [--dport 目标端口号] <-j 动作(accept|drop|nat等)> 
    

<!--more--> 注：不指定表名的话默认使用filter表

**`-I`插入规则如果不指明规则标号，则在第一条规则前插入（置顶）**

**`-R`替换规则一定要指明规则编号，替换后不会改变规则顺序。**

**`-p`可是是ssh、tcp、icmp等,如下几个针对协议的匹配都是OK的**

    -p icmp --icmp-type 类型
    -p --tcp-flags SYN,ACK,FIN,PSH,RST,URG
    

**关于`port`，以下都是可以的：**

    --sport 1000:3000 匹配源端口是 1000-3000 的数据包(含1000、3000)
    --dport :3000 匹配目标端口是 3000 以下的数据包(含 3000)
    --dport 1000: 匹配目标端口是 1000 以上的数据包(含 1000
    --sport 和 --dport 必须配合 -p 参数使用，指定是神马协议
    

**关于动作，有以下一些动作：**

*   ACCEPT 将封包放行，进行完此处理动作后，将不再比对其它规则，直接跳往下一个规则链（natostrouting）。

*   DROP 丢弃封包不予处理，进行完此处理动作后，将不再比对其它规则，直接中断过滤程序。

*   REJECT 拦阻该封包，并传送封包通知对方，可以传送的封包有几个选择：ICMP port-unreachable、ICMP echo-reply 或是 tcp-reset（这个封包会要求对方关闭联机），进行完此处理动作后，将不再比对其它规则，直接中断过滤程序。
    
    iptables -A FORWARD -p TCP --dport 22 -j REJECT --reject-with tcp-reset

*   SNAT 改写封包来源 IP 为某特定 IP 或 IP 范围，可以指定 port 对应的范围，进行完此处理动作后，将直接跳往下一个规则（mangleostrouting）。
    
    iptables -t nat -A POSTROUTING -p tcp-o eth0 -j SNAT --to-source 194.236.50.155-194.236.50.160:1024-32000

*   DNAT 改写封包目的地 IP 为某特定 IP 或 IP 范围，可以指定 port 对应的范围，进行完此处理动作后，将会直接跳往下一个规炼（filter:input 或 filter:forward）。
    
    iptables -t nat -A PREROUTING -p tcp -d 15.45.23.67 --dport 80 -j DNAT --to-destination 192.168.1.1-192.168.1.10:80-100

*   MASQUERADE 改写封包来源 IP 为防火墙 NIC IP，可以指定 port 对应的范围，进行完此处理动作后，直接跳往下一个规则（mangleostrouting）。这个功能与 SNAT 略有不同，当进行 IP 伪装时，不需指定要伪装成哪个 IP，IP 会从网卡直接读，当使用拨接连线时，IP 通常是由 ISP 公司的 DHCP 服务器指派的，这个时候 MASQUERADE 特别有用。
    
    iptables -t nat -A POSTROUTING -p TCP -j MASQUERADE --to-ports 1024-31000

*   REDIRECT 将封包重新导向到另一个端口（PNAT），进行完此处理动作后，将会继续比对其它规则。
    
    iptables -t nat -A PREROUTING -p tcp --dport 80 -j REDIRECT --to-ports 8080

*   MIRROR 镜射封包，也就是将来源 IP 与目的地 IP 对调后，将封包送回，进行完此处理动作后，将会中断过滤程序。

*   QUEUE 中断过滤程序，将封包放入队列，交给其它程序处理。透过自行开发的处理程序，可以进行其它应用，

*   MARK 将封包标上某个代号，以便提供作为后续过滤的条件判断依据，进行完此处理动作后，将会继续比对其它规则。
    
    iptables -t mangle -A PREROUTING -p tcp --dport 22 -j MARK --set-mark 2

*   RETURN结束在目前规则炼中的过滤程序，返回主规则炼继续过滤，如果把自订规则炼看成是一个子程序，那么这个动作，就相当提早结束子程序并返回到主程序中。

*   LOG 将封包相关讯息纪录在 /var/log 中，详细位置请查阅 /etc/syslog.conf 组态档，进行完此处理动作后，将会继续比对其规则。
    
    iptables -A INPUT -p tcp -j LOG --log-prefix "INPUT packets"

关于动作的使用，继续往下来:)！

然后还有一点，一般我们都会考虑到，当数据包没有规则匹配是应该肿么办，是的，这个时候还有一个默认规则，通常叫做默认策略，`当数据包不被任何规则匹配时，会采用默认规则`

    iptables [-t 表名] <-P> <链名> <动作>
    

这里动作就不加-j了，因为-j表示匹配到以后执行的动作。

然后，我们再继续一点，当规则很多的时候，我们不可能一条一条的删除呗，所以还有以下：

    iptables [-t 表名] [链名] <-F|Z>
    

还是一样，不指定表名默认操作filter表，这里Z表示计数器和流量归0，现在我们暂时不管他。关于删除，还得多说一点：

*   1 `-F 仅仅是清空链中规则，并不影响 -P 设置的默认规则`

*   2 -P(默认策略)设置了 DROP 后，使用 -F 一定要小心，不然你网络就断了。

*   3 如果不写链名，默认清空某表里所有链里的所有规则

iptables的规则在重启后就会失效，所以还需要保存和载入：

    iptables-save > /xx/iptables.save
    iptables-restore < /xx/iptables.save
    

## 2 iptables的应用

iptables的动作设置，其实就属于iptables的应用了。我们来看看：

### 2.1 增加安全性

    iptables -P INPUT DROP
    iptables -p OUTPUT DROP
    

体会一下默认策略的作用，你就懂了。

### 2.2 NAT

做SNAT，允许内部的机器访问外部网络，这需要在出去的时候操作POSTROUTING链，SNAT 支持转换为单 IP，也支持转换到 IP 地址池。

    -j SNAT --to IP[-IP][:端口-端口]
    iptables -t nat -A POSTROUTING -s 192.168.0.0/24 -o eht0 -j SNAT --to 1.1.1.1
    iptables -t nat -A POSTROUTING -s 192.168.0.0/24 -o eth0 -j SNAT --to 1.1.1.1-1.1.1.1
    

做DNAT，主要是使外部的流量能够访问到内部的网络，类似DMZ功能，这需要在数据包进入的时候操作PREROUTING链目的地址转换，DNAT 支持转换为单 IP，也支持转换到 IP 地址池

    -j DNAT --to IP[-IP][:端口-端口]
    iptables -t nat -A PREROUTING -i ppp0 -p tcp --dport 80 -j DNAT --to 192.168.0.1:80
    iptables -t nat -A PREROUTING -i ppp0 -p tcp --dport 80 -j DNAT --to 192.168.0.1-192.168.0.10
    

### 2.3 MASQUERADE

地址转换，动态源地址转换(动态 IP 的情况下使用)，主要还是NAT上使用

    iptables -t nat -A POSTROUTING -s 192.168.0.0/24 -o eth0 -j MASQUERADE
    

将源地址是 192.168.0.0/24 的数据包进行地址伪装,这是一个SNAT。

### 2.4 防火墙

防火墙嘛，主要就是过滤，当然是用到filter表了。

    iptables -A INPUT -p tcp --dport 22 -j ACCEPT
    iptables -A OUTPUT -p udp --sport 22 -j ACCEPT
    

### 2.5 转发

如果机器具有网关的作用，那么就一定要能转发数据包，可以通过FPRWARD控制分组转发到LAN上的哪些地方，比如一个机器，两个网卡，eth0接外部网络，eth1接内部网络，并且网关作用，就要允许eth1上转发

    iptables -A FORWARD -i eth1 -j ACCEPT
    iptables -A FORWARD -o eth1 -j ACCEPT
    

经过以上学习，常用的iptables的功能都差不多了，其实看到这里，也就可以勒，如果你还想玩的炫一点，那就接着看下一篇。

## 3 参考资料

<http://man.chinaunix.net/network/iptables-tutorial-cn-1.1.19.html>

<http://www.lampbo.org/linux-xuexi/linux-advance/iptables-options.html>

<http://tech.anquan365.com/application/other/201201/167224.html>

推荐<http://www.cnblogs.com/diyunpeng/archive/2012/05/10/2493749.html>

推荐<http://blog.163.com/xychenbaihu@yeah/blog/static/132229655201212705752493/>

一个实战经验，我没看：<http://blog.sina.com.cn/s/blog_67a6c6f10100k3yx.html>

 [1]: http://blog.wachang.net/2013/03/iptables-usage-ref-1/
 [2]: http://blog.wachang.net/2013/03/iptables-useage-ref-2/

