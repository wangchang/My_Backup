主要是自己的学习笔记以及网上资料的参考，按照自己的口味总结，以后稍微参考一下就能熟练上手，本系列暂定写4章：

第一章：[流程介绍][1]

第二章：如何使用

第三章：深入理解

第四章：高级用法

## 1 几句话介绍iptables

Linux内核中有一个联网子系统netfilter，提供了有状态和无状态的分组过滤，同时提供了NAT和IP伪装服务。**这个系统可以通过工具iptables来进行控制**。

<!--more-->

## 2 规则，链，表

既然是用来处理过滤数据包神马的，那么最基本的肯定就是规则了，一个规则就定义了对一个满足某些条件的数据包应该肿么做，这是最基本的了。我们又看，对于数据包，最终处理它的是规则，那么处理的时机又可以是进入系统的时候或者出系统的时候，所以我们又提出链，一个链包含了一些规则。最后，那么多的规则，又不好管理，于是又来了一个表，iptables以表的形式来管理规则，而且表这个东西又和应用挂钩，比如与NAT有关的链，规则我就放到一个NAT表中。于是乎，概念就解释完了，下面列出iptables里面的链和表：

iptables里面：

**规则：**有很多个，你自己定义。

**链：**包含了一些规则，主要有以下的链（主要是根据处理时机划分的）

*   INPUT链：包含处理入站数据包的一些规则
*   OUTPUT：包含处理出站数据包的一些规则
*   FORWARD：包含处理转发数据包的一些规则，转发数据包：就是不进入本机应用程序，而是从本机转发出去的包。
*   POSTROUTING链：在进行路由选择后处理数据包
*   PREROUTING链：在进行路由选择前处理数据包，无论数据包是否进入代理服务器内部，还是直接转发，都要先进行这条链的匹配。

**默认的表：**

*   raw表：debug测试，确认是否对该数据包进行状态跟踪
*   mangle表：为数据包设置标记,主要是改变包的TOS,TTL,MARK属性，一般不操作。包含了PREROUTING,POSTROUTING,FORWARD,INPUT,OUTPUT链。
*   nat表：主要做SNAT,DNAT，MASQUERADE的，包含INPUT OUTPUT PREROUTING POSTROUTING四条链
*   filer表：对数据包进行过滤，准许什么数据包通过accept，不许什么数据包通过drop，并且这个规则表也是默认的

最后补充一点，iptables中，表是唯一的，也就是不存在两个NAT表，而链则不是唯一的，比如NAT表和MANGLE表都有一个INPUT链，他们的INPUT链中的规则是不一样的。而链中的具体规则，也不是唯一的，INPUT链和OUTPUT链中都可以存在一个一样的规则。

## 3 数据包流程

总结一句话概括就是 当数据包到达防火墙时，如果MAC地址符合，就会由内核里相应的驱动程序接收，当数据包经过某个表的某个链时，iptables对比数据包和设定的规则，进行处理，决定是发送给本地的程序，还是转发给其他机子，还是其他的什么。

我们来细细说：

首先看下面这张图：

[<img src="http://blog.wachang.net/wp-content/uploads/2013/03/iptables-workflow-1024x528.jpg" alt="iptables-workflow" width="1024" height="528" class="aligncenter size-large wp-image-113" />][2]

然后我们来加深理解：

**对于收到的数据包，表是有优先级的哦**

raw->mangle->nat(转换)->filter(过滤),每一步的处理根据上图中的优先级顺序来的哦。

规则链间的匹配顺序

　　入站数据：PREROUTING、INPUT

　　出站数据：OUTPUT、POSTROUTING

　　转发数据：PREROUTING、FORWARD、POSTROUTING

按顺序依次进行检查，找到相匹配的规则即停止(LOG策略会有例外)

检测下看懂木有，以下是三个图，请对比上面的流程图理解，会恍然大悟的。

**1 以本地为目标（就是我们自己的机子了）的包**

[<img src="http://blog.wachang.net/wp-content/uploads/2013/03/iptables-example-1-1024x192.jpg" alt="iptables-example-1" width="1024" height="192" class="aligncenter size-large wp-image-114" />][3]

**2 以本地为源的包**

[<img src="http://blog.wachang.net/wp-content/uploads/2013/03/iptables-example-2-1024x179.jpg" alt="iptables-example-2" width="1024" height="179" class="aligncenter size-large wp-image-115" />][4]

**3 被转发的包**

[<img src="http://blog.wachang.net/wp-content/uploads/2013/03/iptables-example-3-1024x226.jpg" alt="iptables-example-3" width="1024" height="226" class="aligncenter size-large wp-image-116" />][5]

最后再来一张差不多的图。

[<img src="http://blog.wachang.net/wp-content/uploads/2013/03/tables_traverse.jpg" alt="tables_traverse" width="525" height="585" class="aligncenter size-full wp-image-117" />][6]

在第一个路由判断处，不是发往本地的包，我们会发送它穿过 FORWARD链。若包的目的地是本地监听的IP地址，我们就会发送这个包穿过INPUT链，最后到达本地。

值得注意的是，在做NAT的过程中，发往本机的包的目的地址可能会在PREROUTING链里被改变。这个操作发生在第一次路由之前，所以在地址被改变之后，才能对包进行路由。注意，所有的包都会经过上图中的某 一条路径。如果你把一个包DNAT回它原来的网络，这个包会继续走完相应路径上剩下的链，直到它被发送回原来的网络。 这一节就到这里，了解了数据包的流程，下一节我们就讲讲怎么使用iptables了。

## 4 参考文章

<http://linux.ccidnet.com/art/737/20060705/596545_1.html>

<http://man.chinaunix.net/network/iptables-tutorial-cn-1.1.19.html>

<http://linux.ccidnet.com/pub/html/tech/iptables/index.htm>

 [1]: http://blog.wachang.net/2013/03/iptables-usage-ref-1/
 [2]: http://blog.wachang.net/wp-content/uploads/2013/03/iptables-workflow.jpg
 [3]: http://blog.wachang.net/wp-content/uploads/2013/03/iptables-example-1.jpg
 [4]: http://blog.wachang.net/wp-content/uploads/2013/03/iptables-example-2.jpg
 [5]: http://blog.wachang.net/wp-content/uploads/2013/03/iptables-example-3.jpg
 [6]: http://blog.wachang.net/wp-content/uploads/2013/03/tables_traverse.jpg

