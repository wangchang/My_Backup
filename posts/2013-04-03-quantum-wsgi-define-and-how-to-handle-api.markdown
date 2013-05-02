> 浅出OpenStack源码系列
> 
> Quantum WSGI中代码概念及如何处理API

在写WSGI的代码分析的过程中，发现要理解WSGI部分的代码，得先理解WSGI如何处理API的过程，这样理解代码会相当快，于是补上此篇文档。

在我前面的基础篇中，我已经讲述了quantum中的api-paste.ini文件如何定义了quantum api的处理流程。这里做一个回顾：

对于v2.0之类的API，使用keystone验证的话，会有以下几个过程：

    `/v2.0`（urlmap进行区分）---》读取authtoken段的参数---》与keystone进行交互---》增加extension---》APIRouter
    

上面这个流程是什么意思呢？我跳过认证部分讲：

quantum使用的是REST API，这个API的一个特点就是API的路径，有着重要作用，是作为一个参数传递的，比如一个api:

    /v2.0/networks/(the uuid of network).json
    

那么，这个networks加上这个HTTP请求的方法（GET/PUT等）就能决定最终是调用quantum plugin中的哪个函数（函数 get_network），而uuid则会作为一个参数传递，.json则会告诉quantum这个API请求的结果需要以什么形式返回。

<!--more-->

上面这个过程我们换算到程序中就有以下这么些东西：

## WSGI&API

**Application**

首先，quantum-server这个WSGI服务器需要一个“东西”，用来处理接收到的API，这个应用在代码中的概念就是一个application，那么这个application要处理些啥呢，如何处理？OK，这就是配置文件api-paste.ini所定义的了，你看，其实api-paste.ini中对一个API的处理分为了很多步骤，是由很多的类或者实例或者管道来联合处理的，那么，这些类的实例，就是一个application，所以中，api-paste.ini文件中定义了多个application，在Python.Paste中的loadapp()函数读取api-paste.ini这个配置文件，就生成了这么一个抽象的application，可以看到这个app准确的来说不是实例化类这种传统方式得到的，这个app主要就是通过调用不同函数不同实例的方法完成一个动作，所以说，他是一个概念，你可以认为这个application只定义了流程，而没有相应的代码。在api-paste.ini文件中的第一段:

    [composite:quantum]
    use = egg:Paste#urlmap
    /: quantumversions
    /v2.0: quantumapi_v2_0
    

如上，这个application（的名字）就叫做quantum。这个其实是个抽象的application,而后面的一些配置：

    [app:quantumversions]
    paste.app_factory = quantum.api.versions:Versions.factory
    

这里也定义了一个app，这个当然就有具体的代码和实例了。当然，你可以认为在api-paste.ini文件中每个段都定义了一个app，loadapp()会把这个app按照一定的规则组合成一个大的application.

**Routes**

在例子API中，我们说到REST API中这个后缀，专业点的说法就是路径path，很是重要。他能决定quantum最终调用的处理方式，那么如何决定这么个事呢，这个时候就需要引入路由这个概念了，与网络中的路由类似，OpenStack中引入Routes这个库就是可以根据REST API中的path信息进行一个调度。

**Router**

为了完成调度，肯定就需要一个调度器，这个就是Router，正如api-paste.ini中描述的一个API经过application处理以后最后到达APIRouter，这个API Router根据自己生成的路由规则把相应的API内容调度到一个处理它的东西上（controller,如下）。

由于quantum api有核心api和扩展api，所以api-paste.ini中定义的extension其实再载入了相应扩展API定义以后也会生成一个Router，处理扩展API相关，这个下面会讲。

**Routes Table：**

既然是路由，那么就需要一个路由表了，这个路由表的作用就是让程序能够根据REST API的path信息将相应的操作传递到一个处理它的“东西上”，这个“东西”是什么呢？名字就叫controller：

**controller**

controller是什么意思？controller就是一个调度器，我们知道，quantum api的操作其实都是plugin来完成的，那么最基本的，肯定是使用plugin中的函数来完成。前面的路由routes，只能根据一定的规则把path路径或者HTTP的body参数路由到一个controller，那么这个controller，要做的就是根据传递过来的信息，plugin中需要执行操作的函数，并调用这个函数，当然，controller也会把body信息(如果有)传递给plugin相关函数。OK，到这里，我必须引入quantum api中的一些基本概念了。

**resource**

从quantum api中就可以看出来，resource就是一个需要操作的资源，多个resource的集合就是resources,举个例子，quantum中三大概念，network,subnet,port，我们假设API形式为/v2.0/port/XXX /v2.0/network/XXX 这些中的port,network就是resource，那么多个resouce和在一起，就是resources了撒，不过quantum给他换了个名字，叫collection，所以quantum中的REST API都是/v2.0/ports/XXX /v2.0/networks/XXX这种了，因为resource，collection这个词会在代码中出现，所以你明白他是什么意思就OK了！简单一个以quantum中network的总结：

    networks = collection
    network = resource
    

我举一个简单的route中添加路由表的例子，仅作举例，不懂的话没关系，以后会深入讲：

    from routes import Mapper  
    map = Mapper()  
    map.connect(None, "/error/{action}/{id}, controller="error")  
    map.connect("home", "/", controller="main", action="index")  
    
    # Match a URL, returns a dict or None if no match  
    result = map.match('/error/myapp/4')  
    # result == {'controller': 'main', 'action': 'myapp', 'id': '4'}  
    

**action**

在routes表中，我们注意有一个action,这个在代码中也会涉及，action是啥意思，我们知道HTTP请求中GET PUT POST等方法，对应需要的操作，那么在quantum代码中，我们不这样叫，我们把HTTP的方法映射到对资源的action（操作上），这个映射关系如下所示，这个图中，collection就是上面的概念resources，而这其实也是一个简单的routes表了，不过目前我们只需要看看action和method的对应。

[<img src="http://blog.wachang.net/wp-content/uploads/2013/04/routepath.png" alt="routepath" width="888" height="170" class="aligncenter size-full wp-image-308" />][1]

整个上面所说的其实都是一个**MVC（模型-视图-控制）**框架，这是一个软件开发方式，可以自己学习一下,可以更好的理解OpenStack各个组件的REST API思想（用了python各种库来实现了一个MVC）。

## API&扩展API

Quantum是有两类API的，一类是核心API，包括networks,ports,subnets的API，这个API的Router是通过api-paste.ini中的APIRouter来进行的，同时你可以扩展API，相应的扩展都在quantum/extensions目录下，你可以把自己定义的resoucre加入到扩展中，形成扩展API，这一部分后续文章讲，现在只是基础概念。而扩展API的Router其实是在api-paste.ini中调用extension_middleware完成的，所以，整个流程可以如下两个图，这两个图体现了本文全部内容：

[<img src="http://blog.wachang.net/wp-content/uploads/2013/04/loadapp.png" alt="loadapp" width="794" height="547" class="aligncenter size-full wp-image-309" />][2]

[<img src="http://blog.wachang.net/wp-content/uploads/2013/04/http.png" alt="http" width="687" height="634" class="aligncenter size-full wp-image-310" />][3]

 [1]: http://blog.wachang.net/wp-content/uploads/2013/04/routepath.png
 [2]: http://blog.wachang.net/wp-content/uploads/2013/04/loadapp.png
 [3]: http://blog.wachang.net/wp-content/uploads/2013/04/http.png