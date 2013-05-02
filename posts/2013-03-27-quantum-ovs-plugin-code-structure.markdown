> 浅出OpenStack源码系列
> 
> Quantum OpenvSwitch Plugin代码架构

此文主要罗列一下OpenvSwitch Plugin的代码架构，做一点解释，为后续文章做准备。

## 1.代码目录结构：

[<img src="http://blog.wachang.net/wp-content/uploads/2013/03/quantum-ovs-plugin代码目录.jpg" alt="quantum ovs plugin代码目录" width="315" height="405" class="aligncenter size-full wp-image-239" />][1]

`ovs_quantum_plugin.py`:是plugin部分的主文件，在配置文件中指定选用此文件中类`OVSQuantumPluginV2`，quantum在启动的时候就会实例化此类，从而plugin可以正常工作。

`ovs_models_v2.py`:是数据库models文件，主要是sqlachemy使用，用于把数据库的一个Table和一个类进行关联。

`ovs_db_v2.py`:openvswitch plugin的数据库支持，通过使用sqlalchemy实现数据库的查找写入等操作。

`ovs_quantum_agent.py`：agent程序文件，在agent端执行

`common`:则包含了解析OVS配置相关的支持。

<!--more-->

## 2.OpenvSwitch Plugin类关系

插件主要是ovs\_quantum\_plugin.py文件，首先查看一下该文件中import的内容：

    from quantum.db import quota_db
    from quantum.db import securitygroups_rpc_base as sg_db_rpc
    from quantum.extensions import portbindings
    from quantum.extensions import providernet as provider
    from quantum.extensions import securitygroup as ext_sg
    from quantum.openstack.common import importutils
    from quantum.openstack.common import log as logging
    from quantum.openstack.common import rpc
    from quantum.openstack.common.rpc import proxy
    from quantum.plugins.openvswitch.common import config
    from quantum.plugins.openvswitch.common import constants
    from quantum.plugins.openvswitch import ovs_db_v2
    from quantum import policy
    

其中plugin部分的类关系如下图所示：

[<img src="http://blog.wachang.net/wp-content/uploads/2013/03/ovs_plugin_uml-1024x581.jpg" alt="ovs_plugin_uml" width="640" height="363" class="aligncenter size-large wp-image-240" />][2]

其中，**剪头表示类之间的继承关系，而带点的线表示该类的一个实例是作为另外一个类中的一个属性**

## 3.penvSwitch Agent类关系

还是先看import的内容：

    import sys
    import time
    
    import eventlet
    from oslo.config import cfg
    
    from quantum.agent.linux import ip_lib
    from quantum.agent.linux import ovs_lib
    from quantum.agent.linux import utils
    from quantum.agent import rpc as agent_rpc
    from quantum.agent import securitygroups_rpc as sg_rpc
    from quantum.common import config as logging_config
    from quantum.common import constants as q_const
    from quantum.common import topics
    from quantum.common import utils as q_utils
    from quantum import context
    from quantum.extensions import securitygroup as ext_sg
    from quantum.openstack.common import log as logging
    from quantum.openstack.common import loopingcall
    from quantum.openstack.common.rpc import dispatcher
    from quantum.plugins.openvswitch.common import config
    from quantum.plugins.openvswitch.common import constants
    

相应的UML图：

[<img src="http://blog.wachang.net/wp-content/uploads/2013/03/ovs_agent_uml.jpg" alt="ovs_agent_uml" width="897" height="917" class="aligncenter size-full wp-image-241" />][3]

 [1]: http://blog.wachang.net/wp-content/uploads/2013/03/quantum-ovs-plugin代码目录.jpg
 [2]: http://blog.wachang.net/wp-content/uploads/2013/03/ovs_plugin_uml.jpg
 [3]: http://blog.wachang.net/wp-content/uploads/2013/03/ovs_agent_uml.jpg