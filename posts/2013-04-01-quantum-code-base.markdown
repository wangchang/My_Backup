> 浅出OpenStack源码系列
> 
> Quantum 基础知识及服务启动

我认为Quantum在代码上可以分为三个部分：

*   Quantum Server：启动进程，处理REST API
*   Quantum Plugin：对于一个API请求，如何将此API内容递交给Plugin处理。
*   Plugin与Agent：两者之间是如何交互的，RPC相关的实现。

<!--more-->

## 1 关于配置文件

Quantum有四类配置文件：

*   api-paste.ini:这是用来配置Quantum WSGI服务的，主要就是如何处理一个REST API请求。此文件的解析是通过Paste.Deploy库来完成的。
*   quantum.conf:主要是配置Quantum选用的plugin,以及和数据库交互，以及与其他组件的交互，此文件解析是通过OpenStack自己基于库ConfigParser开发的oslo库完成的。
*   插件配置文件，比如：ovs\_quantum\_plugin.ini，这个配置文件，Plugin和Agent都会用到，此文件解析是通过OpenStack自己基于库ConfigParser开发的oslo库完成的。
*   rootwrap.conf：貌似是具体执行一些linux命令时候的包装。

关于配置文件如何解析，很重要，我在基础篇中已经有详细的介绍，可以先看看～

## 2 服务启动目录

在quantum/bin下有下图的一些文件：

[<img src="http://blog.wachang.net/wp-content/uploads/2013/04/quantum-bin.png" alt="quantum-bin" width="289" height="458" class="aligncenter size-full wp-image-282" />][1]

这里面的每一个文件在安装quantum之后都是放在/usr/bin下面的，也就是作为服务启动的脚本，那么我们打开quantum-server这个，作为quantum启动的脚本，里面内容很简单：

    mport eventlet
    eventlet.monkey_patch()
    
    import os
    import sys
    sys.path.insert(0, os.getcwd())
    from quantum.server import main as server
    
    server()
    

说白了，其实这个目录的东西可以看成是一个"引入"，关键还是得看import的部分，才是真正的执行代码。

## 3 Quantum服务启动

quantum服务的启动主要是quantum/server/**init**.py文件：

    import sys
    from oslo.config import cfg #创建一个配置管理器cfg.CONF
    from quantum.common import config #向cfg.CONF注册核心选项core_opts,指明需要读取哪些选项或者哪些CLI参数
    from quantum import service
    
    def main():
        # the configuration will be read into the cfg.CONF global data structure
        config.parse(sys.argv[1:]) #解析配置文件，即是quantum.conf,把相应的配置信息写入到cfg.CONF中。
    
        try:
            quantum_service = service.serve_wsgi(service.QuantumApiService)#准备WSGI服务
            quantum_service.wait()#启动WSGI服务
    
    if __name__ == "__main__":
        main()
    

主要讲两个部分，第一是配置读取，`from oslo.config import cfg`创建了一个cfg.CONF配置管理器，`from quantum.common import config`而quantum common则会想配置管理器注册核心选项core\_opts信息，主要是读取quantum.conf文件使用，以及cli\_opts，提供CLI操作支持，同时会定义两个比较重要的函数：

    def parse(args):#解析配置文件的，实际上是调用cdg.CONF()的call方法
    def setup_logging(conf):#设置LOG信息用
    def load_paste_app(app_name):#载入WSGI应用的，涉及API处理部分
    

关于这一部分，如果不是很懂的话，请先阅读本系列文章基础部分，关于配置文件cfg的部分，请阅读[Quantum OpenvSwitch Plugin&Agent读取配置文件][2],原理都是一样的。

第二部分，就是启动相应的WSGI服务器，我们主要看

    quantum_service = service.serve_wsgi(service.QuantumApiService)
    quantum_service.wait()
    

主要就是这两句，这一部分的细节会在随后中讲，这里的核心就是使用paste.deploy加载一个app，并作为处理API请求的应用，然后启动相应的服务器。

 [1]: http://blog.wachang.net/wp-content/uploads/2013/04/quantum-bin.png
 [2]: http://blog.wachang.net/2013/03/quantum-ovs-plugin-agent-config-file/