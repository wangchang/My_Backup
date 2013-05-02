> 浅出OpenStack源码系列
> 
> Quantum OpenvSwitch Plugin源码分析
> 
> Plugin和Agent的RPC机制（一）
> 
> 基于2013、03月的Quantum源代码，主要是Folsom版本。

这一节主要讲讲OVS Plugin和OVS agent之间如何进行交互，也就是RPC机制的过程。

首先，需要了解一点RPC的知识，主要是AMQP协议，这方面可以网上找一点文章看看。

## 1.Plugin/Agent的RPC交互背景

如果我下文中有一些术语如果不明白，那就需要先补充AMQP和RabbitMQ的知识了。

在Quantum Plugin的OpenvSwitch插件中，有一些操作需要Agent端通过执行`ovs-vsctl`来完成，这些操作包括：

*   port-update：更新一个port的信息
*   network-delete:删除网络，那么就需要删除网络上所有的port信息
*   tunnel:在GRE模式中，有时候需要更新GRE port的信息

而Plugin的作用就是告诉Agent应该做什么，他们是通过RPC机制来通信，简单来说，就是:`Plugin把一个具体操作发送到RabbitMQ的消息队列中，Agent从里面读取消息，并执行，当结果需要通知Plugin的时候，又向PLugin发送一个RPC消息，Plugin此时就主要是更改数据库相应的信息。

<!--more-->

同时，Agent还有一个作用，就是通过RPC通告plugin自己是否存活。也会把自己的设备信息发送给Plugin，或者告诉Plugin：本Agent已死，请在数据库删除我的信息吧，有事烧纸~

## 2.Plugin/Agent的RPC交互结构

以下会涉及到消息队列的知识，请一定先学习相关知识。

我们暂时不考虑L3-agent这种，就简单的学习OVS PLUGIN和AGENT的交互。

首先Plugin和Agent有两条RPC通道，注意，我说的逻辑上的哈：

通道1：Plugin向Agent发送port-update,network-delete,tunnel-update的消息，在消息队列上使用的Exchange分别名为：q-agent-notifier-network-delete\_fanout，q-agent-notifier-port-update\_fanout，q-agent-notifier-tunnel-update_fanout，交换类型fanout.

在通道1上，又来继续，因为OpenStack中有多个OVS AGENT，每个Agent会有一个ID，并且，每个AGENT在每个Exchange上都有一条消息队列。队列名字为：q-agent-notifier-tunnel-update\_fanout\_995ad5d516d1430fa7f8535337ed5b24这种，简单点说，就是一个Agent在通道1的过程中，会有3个相应的名字中包含自己ID号的队列与三个Exchange进行绑定。

通道2：Agent向Plugin发送通道1的结果或者自身的一个心跳信息（主要是设备打开关闭，设备info）等信息，在消息队列上Exchange名为：openstack，交换类型topic交换。

在OpenStack中，Quantum Server节点只有一个，加之这个交换机是topic交换，所以Plugin端与交换机openstack就只有一个队列，名字为：q-plugin

说白了，就是说Plugin-->Agent使用的Exchange是q-agent-notifier-\***|||||，Agent-->Plugin使用的Exchange是openstack。

下面是Quantum官方资料的一个图，我只截取相关部分，你应该能看懂了！暂时我们只学习我圈红的部分。

[<img src="http://blog.wachang.net/wp-content/uploads/2013/03/PluginAgent的交互1-1024x707.png" alt="PluginAgent的交互1" width="640" height="441" class="aligncenter size-large wp-image-228" />][1]

我对这个图进行了一些补充，如下是两个OVS AGENT的情况！

[<img src="http://blog.wachang.net/wp-content/uploads/2013/03/PluginAgent的交互2.png" alt="PluginAgent的交互2" width="570" height="621" class="aligncenter size-full wp-image-229" />][2]

## 3.源码概念理解

首先，再次强调，虽然OpenvSwitch Plugin单Plugin+多agent形式的，但是在实际中，Plugin会主动向Agent发送消息（更改port删除network，tunnel_update等等），Agent也会主动向Plugin发送信息（比如在新计算节点加入的时候需要报告OVS信息，以及OVS有改动后要通知Plugin更改数据库等），所以从消息队列的方向来说，Plugin和Agent既是生产者，也是消费者。在源代码中，会有这么几个概念：

*   manager：其实就是指Plugin或者Agent的实例。
*   dispatcher:在RPC中，当接收消息以后，需要定义一个函数来处理，这成为回调callback，dispatcher就是做这么一个调度工作,一个到达的消息可以被调度（dispatch）到一个函数或者一个类中。
*   create_consumer：用于向AMQP服务器指明接收哪个队列的消息，这个函数需要dispatcher作为参数（很明显，接收到消息以后就交给调度器处理嘛）
*   在Plugin的代码中：`Class AgentNotifierApi`这个类用于向Agent发送RPC消息
*   在Plugin的代码中:`Class OVSRpcCallbacks`作为Plugin接收RPC消息的目标，见上面dispatcher的描述。
*   在Agent代码中：`Class OVSPluginApi`这个类用于向Plugin发送RPC消息

下面再给出源代码中各个组件的一个流程图,对于Plugin和Agent端都是通用的，下一节中会从源码上分析本节内容！

[<img src="http://blog.wachang.net/wp-content/uploads/2013/03/RPC源码流程.jpg" alt="RPC源码流程" width="855" height="400" class="aligncenter size-full wp-image-235" />][3]

 [1]: http://blog.wachang.net/wp-content/uploads/2013/03/PluginAgent的交互1.png
 [2]: http://blog.wachang.net/wp-content/uploads/2013/03/PluginAgent的交互2.png
 [3]: http://blog.wachang.net/wp-content/uploads/2013/03/RPC源码流程.jpg