> OpenvSwitch��ȫʹ���ֲ�(��)-�����������2
> 
> ��һ��������һ���򵥵����ӣ�˵�������⻯������OpenvSwitch�ĵ��͹������̡�

ǰ���Ѿ�˵����OVS��Ҫ�����������⻯�����С������֮��һ�������������֮���ͨ�����ã�������һ�����͵Ľṹͼ��

��ô��ͨ������µĹ����������£�

[<img src="http://blog.wachang.net/wp-content/uploads/2013/03/vm-ovs-workflow.png" alt="vm-ovs-workflow" width="752" height="416" class="aligncenter size-full wp-image-217" />][1]

*   1 VMʵ��instance����һ�����ݰ���������ʵ���ڵ���������ӿ�VNIC��ͼ�о���instance�е�eth0.
*   2 ������ݰ��ᴫ�͵�����ڵ��ϵ�VNIC�ӿڣ���ͼ����vnet�ӿڡ�
*   3 ���ݰ���vnet NIC�����������ţ����⽻������br100��.
*   4 ���ݰ������������Ĵ���������ڵ��ϵ�����ӿڷ�������ͼ������ڵ��ϵ�eth0.
*   5 ���ݰ���eth0��ȥ��ʱ���ǰ�������ڵ��ϵ�·���Լ�Ĭ�����ز����ģ����ʱ������ݰ���ʵ�Ѿ�������Ŀ����ˡ�

 [1]: http://blog.wachang.net/wp-content/uploads/2013/03/vm-ovs-workflow.png