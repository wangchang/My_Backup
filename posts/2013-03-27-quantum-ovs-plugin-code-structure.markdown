> ǳ��OpenStackԴ��ϵ��
> 
> Quantum OpenvSwitch Plugin����ܹ�

������Ҫ����һ��OpenvSwitch Plugin�Ĵ���ܹ�����һ����ͣ�Ϊ����������׼����

## 1.����Ŀ¼�ṹ��

[<img src="http://blog.wachang.net/wp-content/uploads/2013/03/quantum-ovs-plugin����Ŀ¼.jpg" alt="quantum ovs plugin����Ŀ¼" width="315" height="405" class="aligncenter size-full wp-image-239" />][1]

`ovs_quantum_plugin.py`:��plugin���ֵ����ļ����������ļ���ָ��ѡ�ô��ļ�����`OVSQuantumPluginV2`��quantum��������ʱ��ͻ�ʵ�������࣬�Ӷ�plugin��������������

`ovs_models_v2.py`:�����ݿ�models�ļ�����Ҫ��sqlachemyʹ�ã����ڰ����ݿ��һ��Table��һ������й�����

`ovs_db_v2.py`:openvswitch plugin�����ݿ�֧�֣�ͨ��ʹ��sqlalchemyʵ�����ݿ�Ĳ���д��Ȳ�����

`ovs_quantum_agent.py`��agent�����ļ�����agent��ִ��

`common`:������˽���OVS������ص�֧�֡�

<!--more-->

## 2.OpenvSwitch Plugin���ϵ

�����Ҫ��ovs\_quantum\_plugin.py�ļ������Ȳ鿴һ�¸��ļ���import�����ݣ�

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
    

����plugin���ֵ����ϵ����ͼ��ʾ��

[<img src="http://blog.wachang.net/wp-content/uploads/2013/03/ovs_plugin_uml-1024x581.jpg" alt="ovs_plugin_uml" width="640" height="363" class="aligncenter size-large wp-image-240" />][2]

���У�**��ͷ��ʾ��֮��ļ̳й�ϵ����������߱�ʾ�����һ��ʵ������Ϊ����һ�����е�һ������**

## 3.penvSwitch Agent���ϵ

�����ȿ�import�����ݣ�

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
    

��Ӧ��UMLͼ��

[<img src="http://blog.wachang.net/wp-content/uploads/2013/03/ovs_agent_uml.jpg" alt="ovs_agent_uml" width="897" height="917" class="aligncenter size-full wp-image-241" />][3]

 [1]: http://blog.wachang.net/wp-content/uploads/2013/03/quantum-ovs-plugin����Ŀ¼.jpg
 [2]: http://blog.wachang.net/wp-content/uploads/2013/03/ovs_plugin_uml.jpg
 [3]: http://blog.wachang.net/wp-content/uploads/2013/03/ovs_agent_uml.jpg