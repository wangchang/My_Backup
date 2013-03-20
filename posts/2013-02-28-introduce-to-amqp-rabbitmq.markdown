> 1本文主要是翻译加总结，通过资料的查询，对AMQP有一定了解; 2 RabbitMQ使用的就是AMQP 0-9-1模型，所以本文其实也是在讲RabbitMQ中的一些原理; 3 文末会列出原文地址

## 1 What is AMQP?

AMQP，即Advanced Message Queuing Protocol，高级消息队列协议，一个网络应用层协议的开放标准，为面向消息的中间件设计。

说白了，这就是一个消费者生产者模型，生产者生产出一个消息，发送到一个队列Queue，消费者(也叫做worker)从队列里面取出这个消息。但是AMQP中，这个消息一般来说是一个任务，生产者消费者一般不在同一台机器上，所以，更好的解释就是，一个程序发送一个任务消息给一个队列，然后消费者程序从队列中拿出这个任务信息，进行执行，可能还需要返回结果。

<!--more-->

而RabbitMQ，就是实现了这么一个模型的软件，独立的开源实现，服务器端用Erlang语言编写，支持多种客户端，如：Python、 Ruby、.NET、Java、JMS、C、PHP、 ActionScript、XMPP、STOMP等，支持AJAX。RabbitMQ就是一个服务器，实现了队列的管理，一个程序连接这个服务器，把任务发送给服务器（也即是进入了队列），消费者连接服务器，从队列中取出任务消息，然后执行。

中英文对照： Messaging broker:消息协商器,它在TCP/IP等端口监听AMQ消息，其实就是AMQP的实现，比如RabbitMQ等。 producers：应用程序，产生消息，并publish到消息队列中，下文简称P。 consumers：应用程序，接收消息，然后进行处理，下文简称C。 AMQP client：AMQP客户端，指与AMQP broker连接的P或者C。

## 2 AMQP协议

### 2.1 协议概述

从整体来看，AMQP协议可划分为三层： [<img src="http://blog.wachang.net/wp-content/uploads/2013/02/amqp-protocol-256x300.png" alt="amqp-protocol" width="256" height="300" class="alignnone size-medium wp-image-55" />][1] AMQP定义了合适的服务器端域模型，用于规范服务器的行为(AMQP服务器端可称为broker)。在这里Model层决定这些基本域模型所产生的行为，这种行为在AMQP中用”command”表示，在后文中会着重来分析这些域模型。Session层定义客户端与broker之间的通信(通信双方都是一个peer，可互称做partner)，为command的可靠传输提供保障。Transport层专注于数据传送，并与Session保持交互，接受上层的数据，组装成二进制流，传送到receiver后再解析数据，交付给Session层。Session层需要Transport层完成网络异常情况的汇报，顺序传送command等工作。

### 2.2 协议模型

AMQP broker主要功能是消息的路由(Routing)和缓存(Buffering)，如下图： [<img src="http://blog.wachang.net/wp-content/uploads/2013/02/amqp-protocol-2.png" alt="amqp-protocol-2" width="571" height="239" class="alignnone size-full wp-image-59" />][1] Exchange接收消息生产者(Producer)发送的消息根据不同的路由算法将消息发送往Message queue。Message queue会在消息不能被正常消费时缓存这些消息，具体的缓存策略由实现者决定，当message queue与消息消费者(Message consumer)之间的连接通畅时，Message queue有将消息转发到consumer的责任。

Message是当前模型中所操纵的基本单位,基本结构有两部分: Header和Body(playload),Header是由Producer添加上的各种属性的集合，这些属性有控制Message是否可被缓存，接收的queue是哪个，优先级是多少等。Body是真正需要传送的数据，它是对Broker不可见的二进制数据流，在传输过程中不应该受到影响。

一个broker中会存在多个Message queue，Exchange通过binding知道要把消息发送到哪个Message queue,在创建Message queue后需要确定它来接收并保存哪个Exchange路由的结果。Binding是用来关联Exchange与Message queue的域模型,通过关键字bindinds_key。

在与多个Message queue关联后，Exchange中就会存在一个路由表，这个表中存储着每个Message queue所需要消息的限制条件。Exchange就会检查它接受到的每个Message的Header及Body信息，来决定将Message路由到哪个queue中去。Message的Header中应该有个属性叫Routing Key，它由Message发送者产生，提供给Exchange路由这条Message的标准。Exchange根据不同路由算法有不同有Exchange Type。比如有Direct类似，需要Binding key 等于Routing key；也有Binding key与Routing key符合一个模式关系；也有根据Message包含的某些属性来判断。一些基础的路由算法由AMQP所提供，client application也可以自定义各种自己的扩展路由算法。 [<img src="http://blog.wachang.net/wp-content/uploads/2013/02/amqp-protocol-3.png" alt="amqp-protocol-3" width="714" height="117" class="alignnone size-full wp-image-58" />][2] 对于，上图，有一些具体的概念，下面会详细讨论。

### 2.3 AMQP 0-9-1 Model流程

AMQP 0-9-1 Model可以简单的总结如下:P产生消息，然后publish(发布)到exchange(交换机，类比成邮政的邮筒吧)，exchange根据一个规则(bindinds)把消息发送到队列(queue)中，消息协商器要么把消息传递给等待(订阅subdcribe)在某一队列上的的消费者(consumers)，要么消费者就从队列中根据自己的需要取消息。如下图： [<img src="http://blog.wachang.net/wp-content/uploads/2013/02/amqp-intro-11.png" alt="amqp-intro-1" width="712" height="319" class="alignnone size-full wp-image-60" />][3]

当publish消息的时候，P可能还会指定消息的属性（meta-data），可能一些meta会被broker使用，而其余的都是由C来使用的。

因为网络不可靠，应用可能处理消息的时候就挂掉了，所以就有一个`消息确认机制message acknowledgements`:当消息传递给C的时候需要通知broker。当使用消息确认机制的时候，一个broker只有在收到C对于此消息已经被接收的通知后才会删除消息。

某些场景，消息发不到合适的C的时候，消息可能会被返回给P，丢弃，或者说放进一个“死亡队列”，由P来决定如何操作。

队列，交换机，绑定作为AMQP中协作的三个元素。（Queues, exchanges and bindings are collectively referred to as AMQP entities.）

## 3 Exchanges and Exchange Types交换机和交换类型

交换机可以理解成具有路由表的路由程序，仅此而已。每个消息都有一个称为路由键（routing key）的属性，就是一个简单的字符串。交换机当中有一系列的绑定（binding），即路由规则（routes），例如，指明具有路由键 “X” 的消息要到名为timbuku的队列当中去。

交换机从P中接收到一个消息，然后路由(route)发送到一个或者多个队列，AMQP 0-9-1 brokers提供了四种交换类型。

    交换类型                默认名字
    Direct exchange      空字符串或者amq.direct
    Fanout exchange      amq.Fanout
    Topic exchange       amq.Topic
    Headers exchange     amq.match (and amq.headers in RabbitMQ)
    

除了交换类型以后，定义交换机的时候还有其他的属性，最重要的几个是：

*   Name
*   Durability（当broker重启后交换机是否能继续工作）
*   Auto-delete（所有队列都适用完以后，交换机就被删除）
*   Arguments（根据broker的选择而定）

exchanges交换机有两种模式(交换模式与交换类型概念不一样)，Durability表示在broker重启的时候能够恢复之前的工作，而transient模式下则会丢失之前的数据。

### 3.1 默认交换exchange

默认的交换机是一个已经预定义了的没有名字的Direct交换模式，他有一个简单的特性：每创建一个队列，都会通过一个routing\_key与exchange绑定，而这个routing\_key是和队列名字一样的。

### 3.2 直接交换Direct exchange

处理路由键。需要将一个队列绑定到交换机上，要求该消息与一个特定的路由键完全匹配。这是一个完整的匹配。如果一个队列绑定到该交换机上要求路由键 “dog”，则只有被标记为“dog”的消息才被转发，不会转发dog.puppy，也不会转发dog.guard，只会转发dog。

*   一个队列通过routing_key K与一个exchange绑定。
*   当一个携带routing_key K的消息到达exchange的时候，exchange将消息路由给队列。

直接交换主要用来在多个C，或者说worker（相同的程序实例）之间通过轮训方式分发任务，但是要记住，在AMQP 0-9-1中，消息时在C之间进行负载均衡而不是队列之间进行的。直接交换可以看看下图： [<img src="http://blog.wachang.net/wp-content/uploads/2013/02/amqp-intro-2.png" alt="amqp-intro-2" width="689" height="511" class="alignnone size-full wp-image-62" />][4]

### 3.3 fanout Exchange

fanout模式将消息发送给所有与之绑定的队列，不处理路由键。你只需要简单的将队列绑定到交换机上。一个发送到交换机的消息都会被转发到与该交换机绑定的所有队列上。很像子网广播，每台子网内的主机都获得了一份复制的消息。Fanout交换机转发消息是最快的。如下图： [<img src="http://blog.wachang.net/wp-content/uploads/2013/02/amqp-intro-3-fanout.png" alt="amqp-intro-3-fanout" width="713" height="447" class="alignnone size-full wp-image-61" />][5]

### 3.4 Topic exchange

根据消息的routing_key以及队列和交换机绑定的类型，Topic Exchange把消息传递给一个或者多个队列。这就是一个多播的概念。当场景中需要多个C，并且C是有根据的获得消息的时候，Topic Exchange就应该被考虑。具体来说，就是将路由键和某模式进行匹配。此时队列需要绑定要一个模式上。符号“#”匹配一个或多个词，符号“*”匹配不多不少一个词。因此“audit.#”能够匹配到“audit.irs.corporate”，但是“audit.*” 只会匹配到“audit.irs”。

### 3.5 Headers Exchange

如果不适应routing\_key，而是根据消息的多个属性（表现为消息头的形式）的交换就是Headers Exchange，Headers Exchange会忽略routing\_key值，相反，用来路由的信息实行消息的头属性中获得的。

## 5 队列Queue

在AMQP中队列就是一个消息的管道，存储C需要的消息，队列知道exchange的一些属性，同时增加了一些自己的属性：

*   名字
*   持续性（Durability），队列在broker重启后恢复
*   Exclusive，单个连接中使用，连接断掉后队列被删除
*   auto-delete：当C不在获得消息的时候队列被删除
*   Arguments：一些broker自己实现的附加功能

在队列使用之前必须被申明（declare），当队列不存在的时候申明队列会创建一个队列，如果队列已经存在，申明队列就不会进行其他操作，但是可以完成对队列存在与否的确认。

### 5.1 Queue Names

应用需要提供队列名，这样broker才能产生一个相应的队列。UTF-8格式，最多255字节，提供一个空字符作为队列名的话，broker会产生一个唯一的队列，同样的方式在C端也可以保证C取得的是P端产生的对应序列，因为P,C都是在一个channel下，而channel是能够记住上一次服务器产生的队列名的。

队列名以"amq."开头的是用于broker内部使用的队列。

### 5.2 Queue Durability

持续当broker重启的时候队列能够恢复，不具有持续性的队列就叫做transient。但是，`这里队列的持续性只是当broker重启的时候会自动重新申明队列，而要保证消息不丢失，还需要设置消息为永久性的（消息就存于磁盘而不是内存中）。`

### 5.3 Bindings

bindings就是一些规则，用来决定消息要路由到哪个队列中去，比如，需要一个交换机E路由一个消息到队列Q，则Q需要先和E进行绑定。Bindings可能需要一些可选的routing\_key，routing\_key的作用就是选择被publish的特定信息到相应绑定的队列，换句话说，routing_key有点像是一个过滤器。举个例子:

*   你住在纽约，队列就相当于你的目的地
*   交换机就相当于XXX航空
*   bindinds就XXX航空到你的住址的线路，可能没得，也可能有多条

如果消息无法送到相应的队列，那么就会丢弃或者返回给P,这些就看相应的机制是怎么样的了。

### 5.6 Consumers

在队列中存储的消息一定要被C所使用。在AMQP 0-9-1模型中，应用程序有两种处理消息的模式：

*   队列主动把消息传递给C应用(push API)
*   C从队列中抓取自己需要的消息(pull API)

在push模式下，应用C需要指明对哪个序列的哪类消息感兴趣，我们称此时队列注册了一个C，或者C订阅了一个队列。一个队列可以有多个C。

每个C都有一个标示符叫做consumer tag，可以用来取消(unsubscribe)对队列消息的订阅，这个tag是一个字符串。

## 6 Message消息

### 6.1 Message Acknowledgements 消息确认机制

Consumer applications-取得消息并且处理消息的应用，有时候可能会因为各种问题挂掉，于是乎就有，AMQP broker如何知道这个消息已经被C接收，是可以删除掉了？AMQP标准中给出了两个选择：

*   在broker向C应用发送了一个消息后，消息可以被删除(使用basic.deliver or basic.get-ok AMQP方法)
*   在C应用返回了一个确认ACK消息以后，消息可以被删除(使用basic.ack AMQP方法)

前一种被叫做自动确认模型(automatic acknowledgement model)，后一种被叫做严格确认模型?(explicit acknowledgement model),在严格模式下C可以选择何时返回这个ACK信息。可以在接收消息的时候就返回，或者处理完消息携带的任务信息以后再返回。

如果C挂掉，并且没有返回ACK信息，那么AMQP broker就会把消息传递给其他的C，如果当前没有可用的C存在，broker就等待，知道有新的C加入进来。

### 6.2 Rejecting Messages拒绝消息

当C取得了消息，但是处理过程中可能不成功，此时C就需要通过拒绝消息机制告诉broker这个消息处理失败，当拒绝消息的时候，C可以要求broker忽略或者从新把消息入队。

### 6.3 Negative acknowledgements

通过`basic.reject AMQP`方法消息就会被拒绝，但是这个方法有个限制：没办法拒绝一连串的多个消息，但是在RabbitMQ中，有一个解决方案，RabbitMQ提出了一种negative acknowledgements(nacks)机制，更多请参考RabbitMQ手册。

### 6.4 Prefetching Messages

在多个C存在的时候，最好有一种机制指定每个C在返回ACK之前最多能接收多少个消息，这个就有点像负载均衡的思想了。

### 6.5 Message Attributes and Payload

在AMQP中的消息时可以附带属性值(attributes)的，一些常见的属性有：

*   Content type
*   Content encoding
*   Routing key
*   Delivery mode (persistent or not)
*   Message priority
*   Message publishing timestamp
*   Expiration period
*   Producer application id

一些属性可以被选作是消息的头信息。类似HTTP中的X-headers。AMQP消息也有一个负载段（playload），承载相应的数据。broker不会检测和修改负载，当消息被设置成persistent的时候，AMQP broker就会把消息存在磁盘中而不会丢失了。

### 6.6 Message acknowledgements

## 7 AMQP 0-9-1 Methods

AMQP 0-9-1 被组织成各种各样的方法Methods,方法就是某些操作，类似HTTP Method但是和面向对象语言中的方法没有一点关系。AMQP方法被组织成各种类classes,类Class就是方法的集合。比如我们看看exchange class，就有一下操作：

exchange.declare exchange.declare-ok exchange.delete exchange.delete-ok

举例如下： 一个客户端请求broker申明一个新的交换机，使用exchange.declare方法，当然，申明中需要一些参数： 如果成功，broker就会使用exchange.declare-ok方法返回一个成功的标示。 [<img src="http://blog.wachang.net/wp-content/uploads/2013/02/amqp-intro-4-e1.png" alt="amqp-intro-4-e1" width="566" height="160" class="alignnone size-full wp-image-63" />][6] [<img src="http://blog.wachang.net/wp-content/uploads/2013/02/amqp-intro-4-e2.png" alt="amqp-intro-4-e2" width="574" height="161" class="alignnone size-full wp-image-64" />][7]

同样道理，对于队列类，也有这么一些方法： [<img src="http://blog.wachang.net/wp-content/uploads/2013/02/amqp-intro-4-e3.png" alt="amqp-intro-4-e3" width="571" height="156" class="alignnone size-full wp-image-65" />][8] [<img src="http://blog.wachang.net/wp-content/uploads/2013/02/amqp-intro-4-e4.png" alt="amqp-intro-4-e4" width="582" height="161" class="alignnone size-full wp-image-66" />][9]

### 7.1 Connections

AMQP的连接是长期的，AMQP是一个应用层，使用TCP来完成可靠的消息传递。AMQP的连接使用了认证并且可以使用TLS等加密协议。当一个应用不再需要与AMQP broker连接的时候，他需要友好的断开连接。

### 7.2 Channels

一些应用程序需要与AMQP broker建立多个连接，但是，同时保持多个TCP连接会消耗大量资源。AMQP 0-9-1提出了Channels的概念，Channels可以被认为是`在一个TCP连接中多个轻量级的连接`。

对于多线程/进程应用，很常见的做法就是一个线程/进程打开一个channel，并且channel之间不共享。

一个channel上的通信与另外一个channel间的通信时完全独立的，所以每个AMQP方法都会携带一个channel号，通过channel号，应用就知道这个方法是出于哪个channel上的。

## 7.3 Virtual Hosts

一个AMQP broler可以产生多个独立的"环境"，这就是vhost的概念。就类似于apache等的vhost一样，AMQP客户可以通过协商选择与哪个vhost进行连接。

## 参考资料:

<http://www.rabbitmq.com/tutorials/amqp-concepts.html> <http://langyu.iteye.com/blog/759663/> <http://pythoner.net/wiki/110/> <http://blog.csdn.net/zhangxinrun/article/details/6546479> <http://sunjun041640.blog.163.com/blog/static/25626832201032990829/> <http://www.oschina.net/question/12_9192> book:RabbitMQ in Action

 [1]: http://blog.wachang.net/wp-content/uploads/2013/02/amqp-protocol-2.png
 [2]: http://blog.wachang.net/wp-content/uploads/2013/02/amqp-protocol-3.png
 [3]: http://blog.wachang.net/wp-content/uploads/2013/02/amqp-intro-11.png
 [4]: http://blog.wachang.net/wp-content/uploads/2013/02/amqp-intro-2.png
 [5]: http://blog.wachang.net/wp-content/uploads/2013/02/amqp-intro-3-fanout.png
 [6]: http://blog.wachang.net/wp-content/uploads/2013/02/amqp-intro-4-e1.png
 [7]: http://blog.wachang.net/wp-content/uploads/2013/02/amqp-intro-4-e2.png
 [8]: http://blog.wachang.net/wp-content/uploads/2013/02/amqp-intro-4-e3.png
 [9]: http://blog.wachang.net/wp-content/uploads/2013/02/amqp-intro-4-e4.png

