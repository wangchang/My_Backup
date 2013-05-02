> OpenvSwitch完全使用手册(二)-概念及工作流程1

## 1 vswitch、Bridge、Datapath

在网络中，交换机和桥都是同一个概念，OVS实现了一个虚拟机的以太交换机，换句话说，OVS也就是实现了一个以太桥。那么，在OVS中，给一个交换机，或者说一个桥，用了一个专业的名词，叫做DataPath！

要了解，OVS如何工作，首先需要知道桥的概念。

网桥也叫做桥接器，连接两个局域网的设备，网桥工作在数据链路层，将两个LAN连接，根据MAC地址来转发帧，可以看成一个“低层的路由器”（路由器工作在网络层，根据IP地质进行转发）。

<!--more-->

### 1.1 网桥的工作原理

网桥处理包遵循以下几条规则：

*   在一个接口上接收到的包不会再往那个接口上发送此包。
*   每个接收到的包都要学习其源MAC地址。
*   如果数据包是多播或者广播包（通过2层MAC地址确定）则要向接收端口以外的所有端口转发，如果上层协议感兴趣，则还会递交上层处理。
*   如果数据包的地址不能再CAM表中找到，则向接收端口以外的其他端口转发。
*   如果CAM表中能找到，则转发给相应端口，如果发送和接收都是统一端口，则不发送。

注意，网桥是以`混杂模式工作`的。关于网桥更多，请查阅相关资料。

## 2 OVS中的bridge

上面，说到，一个桥就是一个交换机。在OVS中，

    ovs-vsctl add-br brname(br-int)
    
    root@Compute2:~# ifconfig
          br-int    Link encap:Ethernet  HWaddr 1a:09:56:ea:0b:49  
          inet6 addr: fe80::1809:56ff:feea:b49/64 Scope:Link
          UP BROADCAST RUNNING MULTICAST  MTU:1500  Metric:1
          RX packets:1584 errors:0 dropped:0 overruns:0 frame:0
          TX packets:6 errors:0 dropped:0 overruns:0 carrier:0
          collisions:0 txqueuelen:0 
          RX bytes:316502 (316.5 KB)  TX bytes:468 (468.0 B)
    

当我们创建了一个交换机（网桥）以后，此时网络功能不受影响，但是会产生一个虚拟网卡，名字就是brname，之所以会产生一个虚拟网卡，是为了实现接下来的网桥（交换机）功能。有了这个交换机以后，我还需要为这个交换机增加端口(port)，一个端口，就是一个物理网卡，当网卡加入到这个交换机之后，其工作方式就和普通交换机的一个端口的工作方式类似了。

    ovs-vsctl add-port brname port
    

**这里要特别注意，网卡加入网桥以后，要按照网桥的工作标准工作，那么加入的一个端口就必须是以混杂模式工作，工作在链路层，处理2层的帧，所以这个port就不需要配置IP了。（你没见过哪个交换的端口有IP的吧）**

那么接下来你可能会问，通常的交换机不都是有一个管理接口，可以telnet到交换机上进行配置吧，那么在OVS中创建的虚拟交换机有木有这种呢，有的！上面既然创建交换机brname的时候产生了一个虚拟网口brname,那么，你给这个虚拟网卡配置了IP以后，就相当于给交换机的管理接口配置了IP，此时一个正常的虚拟交换机就搞定了。

    ip address add 192.168.1.1/24 dev brname
    

最后，我们来看看一个br的具体信息：

    root@Compute2:~# ovs-vsctl show
    bc12c8d2-6900-42dd-9c1c-30e8ecb99a1b
    Bridge "br0"
        Port "eth0"
            Interface "eth0"
        Port "br0"
            Interface "br0"
                type: internal
    ovs_version: "1.4.0+build0"
    

首先，这里显示了一个名为br0的桥（交换机），这个交换机有两个接口,一个是eth0，一个是br0，上面说到，创建桥的时候会创建一个和桥名字一样的接口，并自动作为该桥的一个端口，那么这个虚拟接口的作用，一方面是可以作为交换机的管理端口，另一方面也是基于这个虚拟接口，实现了桥的功能。

## 3 参考资料：

<http://openvswitch.org/cgi-bin/gitweb.cgi?p=openvswitch;a=blob_plain;f=FAQ;hb=HEAD>