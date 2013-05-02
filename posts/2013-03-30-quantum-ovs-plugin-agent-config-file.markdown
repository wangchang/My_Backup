> 浅出OpenStack源码
> 
> Quantum OpenvSwitch Plugin&Agent读取配置文件

本节主要说一下Quantum OpenvSwitch Plugin和Agent是如何处理配置文件的。如果以后你需要自己做基于Quantum的Plugin开发，那么本文就告诉你如何在配置文件中加入你自己的一些变量信息，然后可以正确的让OpenStack的相应组件进行解释并放在程序的变量中。

开始之前，需要阅读一个OpenStack的配置文件处理部分的一些知识，请参考本系列OpenStack基础部分。我就直接以Agent为例进行分析了，这样简单一点，在Plugin端的流程也是一样的。

<!--more-->

## 1 OpenvSwitch Agent的配置文件：

Quantum OpenvSwitch Agent一共需要解析quantum.conf文件以及ovs\_quantum\_plugin.ini文件，下面是配置文件的一个例子：

    #Under the database section
    [DATABASE]
    sql_connection = mysql://quantumUser:quantumPass@100.10.10.51/quantum
    
    #Under the OVS section
    [OVS]
    tenant_network_type = gre
    tunnel_id_ranges = 1:1000
    integration_bridge = br-int
    tunnel_bridge = br-tun
    local_ip = 100.10.10.53
    enable_tunneling = True
    

下面是quantum.conf的部分（agent其实只读这一段）

    rabbit_host = 100.10.10.51
    

## 2 如何解析配置文件

### 2.1

首先，给个总览，同时也是一个回顾，要使得OpenStack里面组件，不管是plugin，还是agent或者其他需要解析配置文件参数的程序能获得相应的配置，需要以下几个步骤：

*   1、创建一个一个配置管理器(通过import oslo.config完成)，一般名字都为CONF
*   2、给这个配置管理器注册选项信息，通过CONF的register_opts方法。
*   3、读取配置，通过CONF.xxxxx(你注册的配置信息的名字)访问值

为什么要向配置管理器注册配置信息？因为配置管理器在读取配置文件的时候只会读注册配置信息的选项，比如我注册了一个选项名为name,加入到CONF中以后，在配置文件中配置管理器才会读取，而没注册的比如company就不会被读取到。当然，你注册的选项的名字和你在配置文件中这个新选项的名字是一样的，比如我注册了一个选项是name，那么配置文件中就应该是name=xxxxx这种。

### 2.2

以下就是Agent中的操作过程，可以结合理解。

Agent源代码首先：

    from oslo.config import cfg
    

import基本的oslo.config模块后，就会产生生成一个CONF实例，这就是一个配置管理器，因为在oslo.config的最后有一行：

    CONF = ConfigOpt()
    

这是一个实例化操作，一个配置管理器的作用：

*   注册配置参数（通过自己的API，就是实例的方法啦）
*   读取配置文件

接下来

    from quantum.plugins.openvswitch.common import config
    

在这个文件中，你可以看到：

    ovs_opts = [
    cfg.StrOpt('integration_bridge', default='br-int',
               help=_("Integration bridge to use")),
    cfg.BoolOpt('enable_tunneling', default=False,
                help=_("Enable tunneling support")),]
    

这是定义配置文件中有哪些选项，之后还有：

    cfg.CONF.register_opts(ovs_opts, "OVS")
    

这就是对ovs\_quantum\_plugin.ini文件中的选项进行注册，其中，后面这个OVS表示选项所在的组是OVS，那么在配置文件中的表现就是，ovs_opts中所定义的选项都是位于OVS这个Section下的。参考上面的配置文件。

当然，在这个config文件中，还import了几个模块，如下解释：

    from quantum.plugins.openvswitch.common import config 作用如上解释
         |（在上面的config文件中存在的import）
         |from quantum.agent.common import config 主要定义了root_help以及agent有关的状态state
             |（在上面的config文件中存在的import）
             |form oslo.config import cfg
             |from quantum.common import config 注册quantum核心选项，也就是quantum.conf中使用的选项
    

## 3 解析

在2中可以看到，实际上整个配置管理器注册了大大类的选项，一个quantum.conf中用的叫做core\_opts，一种是ovs\_quantum\_plugin.ini中使用的ovs\_opts和agent_opts。接下来就是如何触发读取操作了，在Agent的main()函数中，有一个：

    cfg.CONF(project='quantum')
    

这个不是实例化类哈，这是一个类的call方法的使用，这样以后cfg.CONF这个配置管理器就读取完配置文件了，之后你就可以获得配置文件中的值了，如下：

    integ_br=config.OVS.integration_bridge
    tun_br=config.OVS.tunnel_bridge
    local_ip=config.OVS.local_ip