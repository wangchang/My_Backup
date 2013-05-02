> 浅出OpenStack源码系列
> 
> Quantum WSGI服务基础(1)
> 
> 本文涉及代码文件:quantum\quantum\wsgi.py

## 1 背景

在上一篇文章**Quantum 基础知识及服务启动**中简单的阐述了一下quantum server是如何启动，其中重要的两段代码：

    quantum_service = service.serve_wsgi(service.QuantumApiService)#准备WSGI服务
    quantum_service.wait()#启动WSGI服务
    

这两句构造了一个WSGI服务器实例并调用了相应的方法。在WSGI部分有两个文件:

*   `quantum\quantum\wsgi.py`：实现了WSGI中的基本概念，可以当成一个Lib库。
*   `quantum\quantum\service.py`:基于上面wsgi提供的基本操作构建一个wsgi服务，比如quantum-api-server。

在OpenStack的其他组件中，wsgi.py内容都是差不多的，而service.py则是不同的。

<!--more-->

## 2 wsgi.py

首先我们来分析这个文件，如上所说，该文件包含了wsgi中的一些概念的实现。首先看import部分，里面import了几个主要的东西：

    import webob.dec#webob库
    import webob.exc
    from xml.etree import ElementTree as etree#XML处理库
    import eventlet.wsgi
    from quantum.openstack.common import jsonutils#JSON相关库
    

以上主要模块我在基础部分都有讲到。请先回顾。

然后我们看看这个类的UML图：

[<img src="http://blog.wachang.net/wp-content/uploads/2013/04/wsgi.py_-1024x461.png" alt="wsgi.py" width="640" height="288" class="aligncenter size-large wp-image-305" />][1]

这里再提示wsgi.py中定义的类等就是WSGI的一些基本实现，在quantum架构中，大多会从wsgi.py中定义的类进行继承。

首先，我将[Quantum WSGI中代码概念及如何处理API][2]中涉及的概念在代码上做一个总结。

### 2.1 class Application(object)

这里的Application主要是一个WSGI应用的装饰器，实际的WSGI应用需要继承他。这个类主要是完成配置文件中:**[app:name]内的初始化！！！！**

    class Application(object):
        """Base WSGI application wrapper. Subclasses need to implement __call__."""
    
        @classmethod
        def factory(cls, global_config, **local_config): #主要用来实例化类
            """Used for paste app factories in paste.deploy config files.
    
            Any local configuration (that is, values under the [app:APPNAME]
            section of the paste config) will be passed into the `__init__` method
            as kwargs.#这里说明了api-paste.ini文中中的配置如何使用！
    
            A hypothetical configuration would look like:
    
                [app:wadl]
                latest_version = 1.3
                paste.app_factory = nova.api.fancy_api:Wadl.factory
    
            which would result in a call to the `Wadl` class as
            #Class Wadl需要有一个call方法
    
                import quantum.api.fancy_api
                fancy_api.Wadl(latest_version='1.3')
    
            You could of course re-implement the `factory` method in subclasses,
            but using the kwarg passing it shouldn't be necessary.
    
            """
            return cls(**local_config)
    
        def __call__(self, environ, start_response):
            """Subclasses will probably want to implement __call__ like this:
            #call方法用来实现一个WSGI应用的功能，处理请求，返回结果。子类需要重写这个方法。
    
            @webob.dec.wsgify(RequestClass=Request)
            #此装饰器将一个函数封装为一个WSGI应用。
            def __call__(self, req):
              # Any of the following objects work as responses:
    
              # Option 1: simple string
              res = 'message\n'
    
              # Option 2: a nicely formatted HTTP exception page
              res = exc.HTTPForbidden(detail='Nice try')
    
              # Option 3: a webob Response object (in case you need to play with
              # headers, or you want to be treated like an iterable, or or or)
              res = Response();
              res.app_iter = open('somefile')
    
              # Option 4: any wsgi app to be run next
              res = self.application #quantum中使用的是这种方法。子类需要有一个application参数
    
              # Option 5: you can get a Response object for a wsgi app, too, to
              # play with headers etc
              res = req.get_response(self.application) #quantum中使用的是这种方法。子类需要有一个application参数
    
              # You can then just return your response...
              return res
              # ... or set req.response and return None.
              req.response = res
    
            See the end of http://pythonpaste.org/webob/modules/dec.html
            for more info.
    
            """
            raise NotImplementedError(_('You must implement __call__'))    
    

### 2.2 class Controller(object)

    class Controller(object):
        """WSGI app that dispatched to methods.与Method对应的controller，上面说到，一个路由表会指定一个controller，这个controller的基本。
    
        WSGI app that reads routing information supplied by RoutesMiddleware
        and calls the requested action method upon itself.  All action methods
        must, in addition to their normal parameters, accept a 'req' argument
        which is the incoming wsgi.Request.  They raise a webob.exc exception,
        or return a dict which will be serialized by requested content type.
    
        """
    
        @webob.dec.wsgify(RequestClass=Request)#装饰为一个WSGI应用
        def __call__(self, req):
            """
            Call the method specified in req.environ by RoutesMiddleware.
            """
            arg_dict = req.environ['wsgiorg.routing_args'][1]
            action = arg_dict['action']
            method = getattr(self, action)
            del arg_dict['controller']
            del arg_dict['action']
            if 'format' in arg_dict:
                del arg_dict['format']
            arg_dict['request'] = req
            result = method(**arg_dict)#执行方法，这里是一个抽象，子类继承后决定具体怎么执行    
    

### 2.3 class Middleware(object)

可以认为是一个filter，把一个WSGI包装一点新的功能，**主要用来初始化api-paste.ini配置中[filter:]段中的实例初始化。**，作为一个filter，app必然要作为一个参数。

    class Middleware(object):#所谓中间件，也就是一个WSGI APP
        """
        Base WSGI middleware wrapper. These classes require an application to be
        initialized that will be called next.  By default the middleware will
        simply call its wrapped app, or you can override __call__ to customize its
        behavior.
        """
    
        @classmethod
        def factory(cls, global_config, **local_config):
            """Used for paste app factories in paste.deploy config files.
    
            Any local configuration (that is, values under the [filter:APPNAME]
            section of the paste config) will be passed into the `__init__` method
            as kwargs.
    
            A hypothetical configuration would look like:
    
                [filter:analytics]
                redis_host = 127.0.0.1
                paste.filter_factory = nova.api.analytics:Analytics.factory
    
            which would result in a call to the `Analytics` class as
    
                import nova.api.analytics
                analytics.Analytics(app_from_paste, redis_host='127.0.0.1')
    
            You could of course re-implement the `factory` method in subclasses,
            but using the kwarg passing it shouldn't be necessary.
    
            """
            def _factory(app):
                return cls(app, **local_config)
            return _factory
    
        def __init__(self, application):
            self.application = application
    
        def process_request(self, req):
            """
            Called on each request.
    
            If this returns None, the next application down the stack will be
            executed. If it returns a response then that response will be returned
            and execution will stop here.
    
            """
            return None
    
        def process_response(self, response):
            """Do whatever you'd like to the response."""
            return response
    
        @webob.dec.wsgify#封装为WSGI应用
        def __call__(self, req):
            response = self.process_request(req)
            if response:
                return response
            response = req.get_response(self.application)
            return self.process_response(response)     
    

### 2.4 class Resource(Application)

继承了Application，这就是一个WSGI应用了。这个类用来干嘛？类Controller作为Resource类的一个成员，所以这个类就是用来处理调度controller以及内容格式化的。

    class Resource(Application):
        """WSGI app that handles (de)serialization and controller dispatch.
    
        WSGI app that reads routing information supplied by RoutesMiddleware
        and calls the requested action method upon its controller.  All
        controller action methods must accept a 'req' argument, which is the
        incoming wsgi.Request. If the operation is a PUT or POST, the controller
        method must also accept a 'body' argument (the deserialized request body).
        They may raise a webob.exc exception or return a dict, which will be
        serialized by requested content type.
    
        """
    
        def __init__(self, controller, fault_body_function,
                     deserializer=None, serializer=None):
            """
            :param controller: object that implement methods created by routes lib
            :param deserializer: object that can serialize the output of a
                                 controller into a webob response
            :param serializer: object that can deserialize a webob request
                               into necessary pieces
            :param fault_body_function: a function that will build the response
                                        body for HTTP errors raised by operations
                                        on this resource object
    
            """
            self.controller = controller
            self.deserializer = deserializer or RequestDeserializer()
            self.serializer = serializer or ResponseSerializer()
            self._fault_body_function = fault_body_function
            # use serializer's xmlns for populating Fault generator xmlns
            xml_serializer = self.serializer.body_serializers['application/xml']
            if hasattr(xml_serializer, 'xmlns'):
                self._xmlns = xml_serializer.xmlns
        def dispatch(self, request, action, action_args):
            """Find action-spefic method on controller and call it."""
    
            controller_method = getattr(self.controller, action)
            try:
                #NOTE(salvatore-orlando): the controller method must have
                # an argument whose name is 'request'
                return controller_method(request=request, **action_args)#执行controller的方法了！！
            except TypeError as exc:
                LOG.exception(exc)
                return Fault(webob.exc.HTTPBadRequest(),
                             self._xmlns) 
    

### 2.5 class Server(object)

主要是一个WSGI服务器，也就是Quantum API Server的基本，跟一般的socket服务器差不多，区别就是用了eventlet来做线程处理。

    class Server(object):
        """Server class to manage multiple WSGI sockets and applications."""
    
        def _run(self, application, socket):
            """Start a WSGI server in a new green thread."""
            logger = logging.getLogger('eventlet.wsgi.server')
            eventlet.wsgi.server(socket, application, custom_pool=self.pool,
                             log=logging.WritableLogger(logger))   
    

### 2.6 class Router(object)

把API的路径转换到相应的app，这个类很重要。

    class Router(object):
        """
        WSGI middleware that maps incoming requests to WSGI apps.
        """
    
        @classmethod
        def factory(cls, global_config, **local_config):
            """
            Returns an instance of the WSGI Router class
            """
            return cls()
    
        def __init__(self, mapper):
            """
            Create a router for the given routes.Mapper.#根据routes.Mapper中的路由规则产生一个路由器，
            相关的路由规则，在解析api-paste.ini中的extensions部分已经完成。
    
            Each route in `mapper` must specify a 'controller', which is a
            WSGI app to call.  You'll probably want to specify an 'action' as
            well and have your controller be a wsgi.Controller, who will route
            the request to the action method.
    
            Examples:
              mapper = routes.Mapper()
              sc = ServerController()
    
              # Explicit mapping of one route to a controller+action
              mapper.connect(None, "/svrlist", controller=sc, action="list")
    
              # Actions are all implicitly defined
              mapper.resource("network", "networks", controller=nc)
    
              # Pointing to an arbitrary WSGI app.  You can specify the
              # {path_info:.*} parameter so the target app can be handed just that
              # section of the URL.
              mapper.connect(None, "/v1.0/{path_info:.*}", controller=BlogApp())
            """
            self.map = mapper
            self._router = routes.middleware.RoutesMiddleware(self._dispatch,
                                                              self.map)
    
        @webob.dec.wsgify
        def __call__(self, req):
            """
            Route the incoming request to a controller based on self.map.
            If no match, return a 404.
            """
            return self._router
    
        @staticmethod
        @webob.dec.wsgify
        def _dispatch(req):
            """
            Called by self._router after matching the incoming request to a route
            and putting the information into req.environ.  Either returns 404
            or the routed WSGI app's response.
            """
            match = req.environ['wsgiorg.routing_args'][1]
            if not match:
                return webob.exc.HTTPNotFound()
            app = match['controller']#返回是controller的名字
            return app

 [1]: http://blog.wachang.net/wp-content/uploads/2013/04/wsgi.py_.png
 [2]: http://blog.wachang.net/2013/04/quantum-wsgi-define-and-how-to-handle-api/