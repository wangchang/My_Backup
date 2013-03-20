> 本文主要是我学习IETF文档NVGRE: Network Virtualization using Generic Routing Encapsulation的一个笔记。版本是02.文档地址：<http://tools.ietf.org/html/draft-sridharan-virtualization-nvgre-02>，文末是文档的本地PDF下载。这个文档主要讲的事如何用GRE协议来实现网络虚拟化。相关的定义就是NVGRE协议。

## 1. Introduction

传统数据中心的规模很大，因为是在二层，所以在资源动态分配和容量上有局限，于是RSTP（Rapid Spanning Tree Protocol）协议能很好的解决冗余阻塞环路的问题，但是优惠造成网络容量的浪费以及网络超额，于是像TRILL协议等又被提出解决这个问题。

因为使用了VLAN技术来做广播隔离，网络的利用率其实很低。VLAN既作为流量控制的手段，也作为租户之间服务安全的保证，于是2层网络就被切割成许多小的子网，通常一个子网有一个VLAN ID，但是VLAN ID又有4K的限制，数量太少。于是下一代数据中心网络就要考虑到以下问题：

*   逻辑L2 L3网络的扩展 
*   当各种服务在DC中移动的时候要保护地址信息，或者L2段
*   提供广播隔离

于是，提出了一个NVGRE。

<!--more-->

## 3. Network Virtualization using GRE

网络虚拟化Network Virtualization包括：在实际的L2/L3网络中创建虚拟的L2/L3网络拓扑。那么虚拟拓扑之间的通信，就是通过把虚拟拓扑产生的以太帧封装的实际网络的IP中，（隧道技术），那么，有一些定义：

没一个虚拟L2网络有一个24bit的标示符identifier，叫做Virtual Subnet Identifier (VSID，24bit足够1600万个虚拟subnet同时处在一个管理与上。一个VSID可以认为是一个广播域，有点类似VLAN ID。这个VSID在隧道封装中是可以作为头部信息的。

GRE这个协议是IETF RFC 2784提出的，特点就是`可以把任意协议封装到IP上`。而NVGRE补充了GRE协议，使得在每个包中药携带一个VSID的信息。

### 3.1. NVGRE Endpoint

NVGRE Endpoint就是在虚拟网络和实际网络之间出入的端点，任意屋里服务器或者网络设备都可以作为NVGRE Endpoint。但是在实际中常见的是`作为hypervisor`的一部分。endpoint主要功能有以下：

*   封装以太帧到GRE设备/从GRE设备解封出以太帧
*   可以作为一个虚拟拓扑的网关

为了封装以太帧，endpoint需要知道帧中目的地址的位置信息，这个地址可以通过管理层分配，本文档假设位置信息，包括VSID，在NVGRE endpoint是存在的。

### 3.2. NVGRE frame format

RFC 2784 RFC 2890中GRE头部的定义被用来在 NVGRE endpoints间通信。 NVGRE 扩展了头部信息，加入了VSID，GRE中二层包封装的格式如下，注意，从上往下看，就是整个数据的从外到内看：

首先，在网络中实际传输的MAC帧的格式 Outer Ethernet Header:

    +-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+
    |                (Outer) Destination MAC Address                |
    +-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+
    |(Outer)Destination MAC Address |  (Outer)Source MAC Address    |
    +-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+
    |                  (Outer) Source MAC Address                   |
    +-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+
    |Optional Ethertype=C-Tag 802.1Q| Outer VLAN Tag Information    |
    +-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+
    

其次，剥掉上面一层，则是网络中传输的IP报文的格式：

Outer IPv4 Header:

    +-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+
    |Version|  IHL  |Type of Service|          Total Length         |
    +-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+
    |         Identification        |Flags|      Fragment Offset    |
    +-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+
    |  Time to Live | Protocol 0x2F |         Header Checksum       |
    +-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+
    |                      (Outer) Source Address                   |
    +-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+
    |                  (Outer) Destination Address                  |
    +-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+
    

剥掉上面一层，则是NVGRE Header：

    +-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+
    |0| |1|0| Reserved0       | Ver |   Protocol Type 0x6558        |
    +-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+
    |               Virtual Subnet ID (VSID)        |   Reserved    |
    +-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+
    

剥掉GRE头信息，则是相应的虚拟拓扑的信息，也就是原始需要通信端点的信息 Inner Ethernet Header：

    +-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+
    |                (Inner) Destination MAC Address                |
    +-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+
    |(Inner)Destination MAC Address |  (Inner)Source MAC Address    |
    +-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+
    |                  (Inner) Source MAC Address                   |
    +-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+
    |Optional Ethertype=C-Tag 802.1Q| PCP |0| VID set to 0          |
    +-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+
    |       Ethertype 0x0800        |
    +-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+
    

最后，才是原始的IP层信息 Inner IPv4 Header:

    +-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+
    |Version|  IHL  |Type of Service|          Total Length         |
    +-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+
    |         Identification        |Flags|      Fragment Offset    |
    +-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+
    |  Time to Live |    Protocol   |         Header Checksum       |
    +-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+
    |                       Source Address                          |
    +-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+
    |                    Destination Address                        |
    +-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+
    |                    Options                    |    Padding    |
    +-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+
    |                      Original IP Payload                      |
    |                                                               |
    |                                                               |
    +-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+
    

有一个解释，这个我好好翻译下：

*   The outer Ethernet header：源地址是NVGRE endpoint（一个物理设备或者虚拟的设备）的MAC地址，目的地址是吓一跳指定节点，也是一个NVGRE endpoint节点的MAC地址。VLAN可以让这个帧跨越子网的。
*   The outer IP header:就是上一层的IP层地址信息。
*   C (Checksum Present)和S (Sequence Number Present)必须为0
*   The K bit (Key Present) 32bit，NVGRE用这个key来携带VSID.

具体来说32bit的key值，钱24bit用来携带VSID，后8bit用来携带一个FlowID，这个可以用来在VS中进一步划分流。

*   GRE头信息中，协议类型0x6558，(transparent Ethernet bridging)

*   在GRE中不仅仅能封装IP层，其他层都可以，NVGRE也是一样。

## 4 广播和多播

传统来说，像GRE这种点到点的tunnel是不能支持多播和广播的，NVGRE提出一个思想，就是为每一个subnet，或者说VSID所表示的虚拟网络指定一个广播和多播地址，从而解决这个问题。

### 4.2. Unicast Traffic 这段话很重要

NVGRE endpoint在GRE中封装2层报文，source PA（packet address）是这个endpoint的地址，而destination PA则是相应的对端endpoint地址。一个endpoint可以有多个地址，就有一个策略来选择是使用哪个地址，经过GRE封装以后，`The encapsulated GRE packet is bridged and routed normally by the physical network to the destination.`以及`On the destination the NVGRE endpoint decapsulates the GRE packet to recover the original Layer-2 frame.`

### 4.3. IP Fragmentation

在IP中，长的IP报文是有一个分段的，那么NVGRE这种是IP报文进行了一次封装，就最好不要分段，再一下草案中可能会提出解决分段的机制。

### 4.4. Address/Policy Management & Routing

### 4.5. Cross-subnet, Cross-premise Communication

主要是一个VPN gateway，建立一个site-to-site的隧道，注意不是端到端的。从而使得交叉的subnets之间可以通信。如下图：

[<img src="http://blog.wachang.net/wp-content/uploads/2013/03/nvgre-cross-subnets.jpg" alt="nvgre-cross-subnets" width="633" height="747" class="aligncenter size-full wp-image-178" />][1]

 [1]: http://blog.wachang.net/wp-content/uploads/2013/03/nvgre-cross-subnets.jpg

