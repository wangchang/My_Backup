> OpenvSwitch完全使用手册(二)-概念及工作流程2
> 
> 这一部分我以一个简单的例子，说明在虚拟化环境中OpenvSwitch的典型工作流程。

前面已经说到，OVS主要是用来在虚拟化环境中。虚拟机之间一个虚拟机和外网之间的通信所用，如下是一个典型的结构图：

那么，通常情况下的工作流程如下：

[<img src="http://blog.wachang.net/wp-content/uploads/2013/03/vm-ovs-workflow.png" alt="vm-ovs-workflow" width="752" height="416" class="aligncenter size-full wp-image-217" />][1]

*   1 VM实例instance产生一个数据包并发送至实例内的虚拟网络接口VNIC，图中就是instance中的eth0.
*   2 这个数据包会传送到物理节点上的VNIC接口，如图就是vnet接口。
*   3 数据包从vnet NIC出来，到达桥（虚拟交换机）br100上.
*   4 数据包经过交换机的处理，从物理节点上的物理接口发出，如图中物理节点上的eth0.
*   5 数据包从eth0出去的时候，是按照物理节点上的路由以及默认网关操作的，这个时候该数据包其实已经不受你的控制了。

 [1]: http://blog.wachang.net/wp-content/uploads/2013/03/vm-ovs-workflow.png