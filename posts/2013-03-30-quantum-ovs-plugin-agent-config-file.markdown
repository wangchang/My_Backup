> ǳ��OpenStackԴ��
> 
> Quantum OpenvSwitch Plugin&Agent��ȡ�����ļ�

������Ҫ˵һ��Quantum OpenvSwitch Plugin��Agent����δ��������ļ��ġ�����Ժ�����Ҫ�Լ�������Quantum��Plugin��������ô���ľ͸���������������ļ��м������Լ���һЩ������Ϣ��Ȼ�������ȷ����OpenStack����Ӧ������н��Ͳ����ڳ���ı����С�

��ʼ֮ǰ����Ҫ�Ķ�һ��OpenStack�������ļ������ֵ�һЩ֪ʶ����ο���ϵ��OpenStack�������֡��Ҿ�ֱ����AgentΪ�����з����ˣ�������һ�㣬��Plugin�˵�����Ҳ��һ���ġ�

<!--more-->

## 1 OpenvSwitch Agent�������ļ���

Quantum OpenvSwitch Agentһ����Ҫ����quantum.conf�ļ��Լ�ovs\_quantum\_plugin.ini�ļ��������������ļ���һ�����ӣ�

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
    

������quantum.conf�Ĳ��֣�agent��ʵֻ����һ�Σ�

    rabbit_host = 100.10.10.51
    

## 2 ��ν��������ļ�

### 2.1

���ȣ�����������ͬʱҲ��һ���عˣ�Ҫʹ��OpenStack���������������plugin������agent����������Ҫ���������ļ������ĳ����ܻ����Ӧ�����ã���Ҫ���¼������裺

*   1������һ��һ�����ù�����(ͨ��import oslo.config���)��һ�����ֶ�ΪCONF
*   2����������ù�����ע��ѡ����Ϣ��ͨ��CONF��register_opts������
*   3����ȡ���ã�ͨ��CONF.xxxxx(��ע���������Ϣ������)����ֵ

ΪʲôҪ�����ù�����ע��������Ϣ����Ϊ���ù������ڶ�ȡ�����ļ���ʱ��ֻ���ע��������Ϣ��ѡ�������ע����һ��ѡ����Ϊname,���뵽CONF���Ժ��������ļ������ù������Ż��ȡ����ûע��ı���company�Ͳ��ᱻ��ȡ������Ȼ����ע���ѡ������ֺ����������ļ��������ѡ���������һ���ģ�������ע����һ��ѡ����name����ô�����ļ��о�Ӧ����name=xxxxx���֡�

### 2.2

���¾���Agent�еĲ������̣����Խ����⡣

AgentԴ�������ȣ�

    from oslo.config import cfg
    

import������oslo.configģ��󣬾ͻ��������һ��CONFʵ���������һ�����ù���������Ϊ��oslo.config�������һ�У�

    CONF = ConfigOpt()
    

����һ��ʵ����������һ�����ù����������ã�

*   ע�����ò�����ͨ���Լ���API������ʵ���ķ�������
*   ��ȡ�����ļ�

������

    from quantum.plugins.openvswitch.common import config
    

������ļ��У�����Կ�����

    ovs_opts = [
    cfg.StrOpt('integration_bridge', default='br-int',
               help=_("Integration bridge to use")),
    cfg.BoolOpt('enable_tunneling', default=False,
                help=_("Enable tunneling support")),]
    

���Ƕ��������ļ�������Щѡ�֮���У�

    cfg.CONF.register_opts(ovs_opts, "OVS")
    

����Ƕ�ovs\_quantum\_plugin.ini�ļ��е�ѡ�����ע�ᣬ���У��������OVS��ʾѡ�����ڵ�����OVS����ô�������ļ��еı��־��ǣ�ovs_opts���������ѡ���λ��OVS���Section�µġ��ο�����������ļ���

��Ȼ�������config�ļ��У���import�˼���ģ�飬���½��ͣ�

    from quantum.plugins.openvswitch.common import config �������Ͻ���
         |���������config�ļ��д��ڵ�import��
         |from quantum.agent.common import config ��Ҫ������root_help�Լ�agent�йص�״̬state
             |���������config�ļ��д��ڵ�import��
             |form oslo.config import cfg
             |from quantum.common import config ע��quantum����ѡ�Ҳ����quantum.conf��ʹ�õ�ѡ��
    

## 3 ����

��2�п��Կ�����ʵ�����������ù�����ע���˴�����ѡ�һ��quantum.conf���õĽ���core\_opts��һ����ovs\_quantum\_plugin.ini��ʹ�õ�ovs\_opts��agent_opts��������������δ�����ȡ�����ˣ���Agent��main()�����У���һ����

    cfg.CONF(project='quantum')
    

�������ʵ�������������һ�����call������ʹ�ã������Ժ�cfg.CONF������ù������Ͷ�ȡ�������ļ��ˣ�֮����Ϳ��Ի�������ļ��е�ֵ�ˣ����£�

    integ_br=config.OVS.integration_bridge
    tun_br=config.OVS.tunnel_bridge
    local_ip=config.OVS.local_ip