> ǳ��OpenStackԴ��ϵ��
> 
> Quantum ����֪ʶ����������

����ΪQuantum�ڴ����Ͽ��Է�Ϊ�������֣�

*   Quantum Server���������̣�����REST API
*   Quantum Plugin������һ��API������ν���API���ݵݽ���Plugin����
*   Plugin��Agent������֮������ν����ģ�RPC��ص�ʵ�֡�

<!--more-->

## 1 ���������ļ�

Quantum�����������ļ���

*   api-paste.ini:������������Quantum WSGI����ģ���Ҫ������δ���һ��REST API���󡣴��ļ��Ľ�����ͨ��Paste.Deploy������ɵġ�
*   quantum.conf:��Ҫ������Quantumѡ�õ�plugin,�Լ������ݿ⽻�����Լ�����������Ľ��������ļ�������ͨ��OpenStack�Լ����ڿ�ConfigParser������oslo����ɵġ�
*   ��������ļ������磺ovs\_quantum\_plugin.ini����������ļ���Plugin��Agent�����õ������ļ�������ͨ��OpenStack�Լ����ڿ�ConfigParser������oslo����ɵġ�
*   rootwrap.conf��ò���Ǿ���ִ��һЩlinux����ʱ��İ�װ��

���������ļ���ν���������Ҫ�����ڻ���ƪ���Ѿ�����ϸ�Ľ��ܣ������ȿ�����

## 2 ��������Ŀ¼

��quantum/bin������ͼ��һЩ�ļ���

[<img src="http://blog.wachang.net/wp-content/uploads/2013/04/quantum-bin.png" alt="quantum-bin" width="289" height="458" class="aligncenter size-full wp-image-282" />][1]

�������ÿһ���ļ��ڰ�װquantum֮���Ƿ���/usr/bin����ģ�Ҳ������Ϊ���������Ľű�����ô���Ǵ�quantum-server�������Ϊquantum�����Ľű����������ݺܼ򵥣�

    mport eventlet
    eventlet.monkey_patch()
    
    import os
    import sys
    sys.path.insert(0, os.getcwd())
    from quantum.server import main as server
    
    server()
    

˵���ˣ���ʵ���Ŀ¼�Ķ������Կ�����һ��"����"���ؼ����ǵÿ�import�Ĳ��֣�����������ִ�д��롣

## 3 Quantum��������

quantum�����������Ҫ��quantum/server/**init**.py�ļ���

    import sys
    from oslo.config import cfg #����һ�����ù�����cfg.CONF
    from quantum.common import config #��cfg.CONFע�����ѡ��core_opts,ָ����Ҫ��ȡ��Щѡ�������ЩCLI����
    from quantum import service
    
    def main():
        # the configuration will be read into the cfg.CONF global data structure
        config.parse(sys.argv[1:]) #���������ļ�������quantum.conf,����Ӧ��������Ϣд�뵽cfg.CONF�С�
    
        try:
            quantum_service = service.serve_wsgi(service.QuantumApiService)#׼��WSGI����
            quantum_service.wait()#����WSGI����
    
    if __name__ == "__main__":
        main()
    

��Ҫ���������֣���һ�����ö�ȡ��`from oslo.config import cfg`������һ��cfg.CONF���ù�������`from quantum.common import config`��quantum common��������ù�����ע�����ѡ��core\_opts��Ϣ����Ҫ�Ƕ�ȡquantum.conf�ļ�ʹ�ã��Լ�cli\_opts���ṩCLI����֧�֣�ͬʱ�ᶨ�������Ƚ���Ҫ�ĺ�����

    def parse(args):#���������ļ��ģ�ʵ�����ǵ���cdg.CONF()��call����
    def setup_logging(conf):#����LOG��Ϣ��
    def load_paste_app(app_name):#����WSGIӦ�õģ��漰API������
    

������һ���֣�������Ǻܶ��Ļ��������Ķ���ϵ�����»������֣����������ļ�cfg�Ĳ��֣����Ķ�[Quantum OpenvSwitch Plugin&Agent��ȡ�����ļ�][2],ԭ����һ���ġ�

�ڶ����֣�����������Ӧ��WSGI��������������Ҫ��

    quantum_service = service.serve_wsgi(service.QuantumApiService)
    quantum_service.wait()
    

��Ҫ���������䣬��һ���ֵ�ϸ�ڻ�������н�������ĺ��ľ���ʹ��paste.deploy����һ��app������Ϊ����API�����Ӧ�ã�Ȼ��������Ӧ�ķ�������

 [1]: http://blog.wachang.net/wp-content/uploads/2013/04/quantum-bin.png
 [2]: http://blog.wachang.net/2013/03/quantum-ovs-plugin-agent-config-file/