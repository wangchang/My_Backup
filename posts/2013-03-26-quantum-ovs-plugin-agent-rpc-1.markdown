> ǳ��OpenStackԴ��ϵ��
> 
> Quantum OpenvSwitch PluginԴ�����
> 
> Plugin��Agent��RPC���ƣ�һ��
> 
> ����2013��03�µ�QuantumԴ���룬��Ҫ��Folsom�汾��

��һ����Ҫ����OVS Plugin��OVS agent֮����ν��н�����Ҳ����RPC���ƵĹ��̡�

���ȣ���Ҫ�˽�һ��RPC��֪ʶ����Ҫ��AMQPЭ�飬�ⷽ�����������һ�����¿�����

## 1.Plugin/Agent��RPC��������

�������������һЩ������������ף��Ǿ���Ҫ�Ȳ���AMQP��RabbitMQ��֪ʶ�ˡ�

��Quantum Plugin��OpenvSwitch����У���һЩ������ҪAgent��ͨ��ִ��`ovs-vsctl`����ɣ���Щ����������

*   port-update������һ��port����Ϣ
*   network-delete:ɾ�����磬��ô����Ҫɾ�����������е�port��Ϣ
*   tunnel:��GREģʽ�У���ʱ����Ҫ����GRE port����Ϣ

��Plugin�����þ��Ǹ���AgentӦ����ʲô��������ͨ��RPC������ͨ�ţ�����˵������:`Plugin��һ������������͵�RabbitMQ����Ϣ�����У�Agent�������ȡ��Ϣ����ִ�У��������Ҫ֪ͨPlugin��ʱ������PLugin����һ��RPC��Ϣ��Plugin��ʱ����Ҫ�Ǹ������ݿ���Ӧ����Ϣ��

<!--more-->

ͬʱ��Agent����һ�����ã�����ͨ��RPCͨ��plugin�Լ��Ƿ��Ҳ����Լ����豸��Ϣ���͸�Plugin�����߸���Plugin����Agent�������������ݿ�ɾ���ҵ���Ϣ�ɣ�������ֽ~

## 2.Plugin/Agent��RPC�����ṹ

���»��漰����Ϣ���е�֪ʶ����һ����ѧϰ���֪ʶ��

������ʱ������L3-agent���֣��ͼ򵥵�ѧϰOVS PLUGIN��AGENT�Ľ�����

����Plugin��Agent������RPCͨ����ע�⣬��˵���߼��ϵĹ���

ͨ��1��Plugin��Agent����port-update,network-delete,tunnel-update����Ϣ������Ϣ������ʹ�õ�Exchange�ֱ���Ϊ��q-agent-notifier-network-delete\_fanout��q-agent-notifier-port-update\_fanout��q-agent-notifier-tunnel-update_fanout����������fanout.

��ͨ��1�ϣ�������������ΪOpenStack���ж��OVS AGENT��ÿ��Agent����һ��ID�����ң�ÿ��AGENT��ÿ��Exchange�϶���һ����Ϣ���С���������Ϊ��q-agent-notifier-tunnel-update\_fanout\_995ad5d516d1430fa7f8535337ed5b24���֣��򵥵�˵������һ��Agent��ͨ��1�Ĺ����У�����3����Ӧ�������а����Լ�ID�ŵĶ���������Exchange���а󶨡�

ͨ��2��Agent��Plugin����ͨ��1�Ľ�����������һ��������Ϣ����Ҫ���豸�򿪹رգ��豸info������Ϣ������Ϣ������Exchange��Ϊ��openstack����������topic������

��OpenStack�У�Quantum Server�ڵ�ֻ��һ������֮�����������topic����������Plugin���뽻����openstack��ֻ��һ�����У�����Ϊ��q-plugin

˵���ˣ�����˵Plugin-->Agentʹ�õ�Exchange��q-agent-notifier-\***|||||��Agent-->Pluginʹ�õ�Exchange��openstack��

������Quantum�ٷ����ϵ�һ��ͼ����ֻ��ȡ��ز��֣���Ӧ���ܿ����ˣ���ʱ����ֻѧϰ��Ȧ��Ĳ��֡�

[<img src="http://blog.wachang.net/wp-content/uploads/2013/03/PluginAgent�Ľ���1-1024x707.png" alt="PluginAgent�Ľ���1" width="640" height="441" class="aligncenter size-large wp-image-228" />][1]

�Ҷ����ͼ������һЩ���䣬����������OVS AGENT�������

[<img src="http://blog.wachang.net/wp-content/uploads/2013/03/PluginAgent�Ľ���2.png" alt="PluginAgent�Ľ���2" width="570" height="621" class="aligncenter size-full wp-image-229" />][2]

## 3.Դ��������

���ȣ��ٴ�ǿ������ȻOpenvSwitch Plugin��Plugin+��agent��ʽ�ģ�������ʵ���У�Plugin��������Agent������Ϣ������portɾ��network��tunnel_update�ȵȣ���AgentҲ��������Plugin������Ϣ���������¼���ڵ�����ʱ����Ҫ����OVS��Ϣ���Լ�OVS�иĶ���Ҫ֪ͨPlugin�������ݿ�ȣ������Դ���Ϣ���еķ�����˵��Plugin��Agent���������ߣ�Ҳ�������ߡ���Դ�����У�������ô�������

*   manager����ʵ����ָPlugin����Agent��ʵ����
*   dispatcher:��RPC�У���������Ϣ�Ժ���Ҫ����һ���������������Ϊ�ص�callback��dispatcher��������ôһ�����ȹ���,һ���������Ϣ���Ա����ȣ�dispatch����һ����������һ�����С�
*   create_consumer��������AMQP������ָ�������ĸ����е���Ϣ�����������Ҫdispatcher��Ϊ�����������ԣ����յ���Ϣ�Ժ�ͽ��������������
*   ��Plugin�Ĵ����У�`Class AgentNotifierApi`�����������Agent����RPC��Ϣ
*   ��Plugin�Ĵ�����:`Class OVSRpcCallbacks`��ΪPlugin����RPC��Ϣ��Ŀ�꣬������dispatcher��������
*   ��Agent�����У�`Class OVSPluginApi`�����������Plugin����RPC��Ϣ

�����ٸ���Դ�����и��������һ������ͼ,����Plugin��Agent�˶���ͨ�õģ���һ���л��Դ���Ϸ����������ݣ�

[<img src="http://blog.wachang.net/wp-content/uploads/2013/03/RPCԴ������.jpg" alt="RPCԴ������" width="855" height="400" class="aligncenter size-full wp-image-235" />][3]

 [1]: http://blog.wachang.net/wp-content/uploads/2013/03/PluginAgent�Ľ���1.png
 [2]: http://blog.wachang.net/wp-content/uploads/2013/03/PluginAgent�Ľ���2.png
 [3]: http://blog.wachang.net/wp-content/uploads/2013/03/RPCԴ������.jpg