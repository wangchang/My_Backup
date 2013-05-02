> ǳ��OpenStackԴ��ϵ��
> 
> Quantum WSGI�������(1)
> 
> �����漰�����ļ�:quantum\quantum\wsgi.py

## 1 ����

����һƪ����**Quantum ����֪ʶ����������**�м򵥵Ĳ�����һ��quantum server�����������������Ҫ�����δ��룺

    quantum_service = service.serve_wsgi(service.QuantumApiService)#׼��WSGI����
    quantum_service.wait()#����WSGI����
    

�����乹����һ��WSGI������ʵ������������Ӧ�ķ�������WSGI�����������ļ�:

*   `quantum\quantum\wsgi.py`��ʵ����WSGI�еĻ���������Ե���һ��Lib�⡣
*   `quantum\quantum\service.py`:��������wsgi�ṩ�Ļ�����������һ��wsgi���񣬱���quantum-api-server��

��OpenStack����������У�wsgi.py���ݶ��ǲ��ģ���service.py���ǲ�ͬ�ġ�

<!--more-->

## 2 wsgi.py

������������������ļ���������˵�����ļ�������wsgi�е�һЩ�����ʵ�֡����ȿ�import���֣�����import�˼�����Ҫ�Ķ�����

    import webob.dec#webob��
    import webob.exc
    from xml.etree import ElementTree as etree#XML�����
    import eventlet.wsgi
    from quantum.openstack.common import jsonutils#JSON��ؿ�
    

������Ҫģ�����ڻ������ֶ��н��������Ȼعˡ�

Ȼ�����ǿ���������UMLͼ��

[<img src="http://blog.wachang.net/wp-content/uploads/2013/04/wsgi.py_-1024x461.png" alt="wsgi.py" width="640" height="288" class="aligncenter size-large wp-image-305" />][1]

��������ʾwsgi.py�ж������Ⱦ���WSGI��һЩ����ʵ�֣���quantum�ܹ��У������wsgi.py�ж��������м̳С�

���ȣ��ҽ�[Quantum WSGI�д�������δ���API][2]���漰�ĸ����ڴ�������һ���ܽᡣ

### 2.1 class Application(object)

�����Application��Ҫ��һ��WSGIӦ�õ�װ������ʵ�ʵ�WSGIӦ����Ҫ�̳������������Ҫ����������ļ���:**[app:name]�ڵĳ�ʼ����������**

    class Application(object):
        """Base WSGI application wrapper. Subclasses need to implement __call__."""
    
        @classmethod
        def factory(cls, global_config, **local_config): #��Ҫ����ʵ������
            """Used for paste app factories in paste.deploy config files.
    
            Any local configuration (that is, values under the [app:APPNAME]
            section of the paste config) will be passed into the `__init__` method
            as kwargs.#����˵����api-paste.ini�����е��������ʹ�ã�
    
            A hypothetical configuration would look like:
    
                [app:wadl]
                latest_version = 1.3
                paste.app_factory = nova.api.fancy_api:Wadl.factory
    
            which would result in a call to the `Wadl` class as
            #Class Wadl��Ҫ��һ��call����
    
                import quantum.api.fancy_api
                fancy_api.Wadl(latest_version='1.3')
    
            You could of course re-implement the `factory` method in subclasses,
            but using the kwarg passing it shouldn't be necessary.
    
            """
            return cls(**local_config)
    
        def __call__(self, environ, start_response):
            """Subclasses will probably want to implement __call__ like this:
            #call��������ʵ��һ��WSGIӦ�õĹ��ܣ��������󣬷��ؽ����������Ҫ��д���������
    
            @webob.dec.wsgify(RequestClass=Request)
            #��װ������һ��������װΪһ��WSGIӦ�á�
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
              res = self.application #quantum��ʹ�õ������ַ�����������Ҫ��һ��application����
    
              # Option 5: you can get a Response object for a wsgi app, too, to
              # play with headers etc
              res = req.get_response(self.application) #quantum��ʹ�õ������ַ�����������Ҫ��һ��application����
    
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
        """WSGI app that dispatched to methods.��Method��Ӧ��controller������˵����һ��·�ɱ��ָ��һ��controller�����controller�Ļ�����
    
        WSGI app that reads routing information supplied by RoutesMiddleware
        and calls the requested action method upon itself.  All action methods
        must, in addition to their normal parameters, accept a 'req' argument
        which is the incoming wsgi.Request.  They raise a webob.exc exception,
        or return a dict which will be serialized by requested content type.
    
        """
    
        @webob.dec.wsgify(RequestClass=Request)#װ��Ϊһ��WSGIӦ��
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
            result = method(**arg_dict)#ִ�з�����������һ����������̳к����������ôִ��    
    

### 2.3 class Middleware(object)

������Ϊ��һ��filter����һ��WSGI��װһ���µĹ��ܣ�**��Ҫ������ʼ��api-paste.ini������[filter:]���е�ʵ����ʼ����**����Ϊһ��filter��app��ȻҪ��Ϊһ��������

    class Middleware(object):#��ν�м����Ҳ����һ��WSGI APP
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
    
        @webob.dec.wsgify#��װΪWSGIӦ��
        def __call__(self, req):
            response = self.process_request(req)
            if response:
                return response
            response = req.get_response(self.application)
            return self.process_response(response)     
    

### 2.4 class Resource(Application)

�̳���Application�������һ��WSGIӦ���ˡ���������������Controller��ΪResource���һ����Ա�������������������������controller�Լ����ݸ�ʽ���ġ�

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
                return controller_method(request=request, **action_args)#ִ��controller�ķ����ˣ���
            except TypeError as exc:
                LOG.exception(exc)
                return Fault(webob.exc.HTTPBadRequest(),
                             self._xmlns) 
    

### 2.5 class Server(object)

��Ҫ��һ��WSGI��������Ҳ����Quantum API Server�Ļ�������һ���socket��������࣬�����������eventlet�����̴߳���

    class Server(object):
        """Server class to manage multiple WSGI sockets and applications."""
    
        def _run(self, application, socket):
            """Start a WSGI server in a new green thread."""
            logger = logging.getLogger('eventlet.wsgi.server')
            eventlet.wsgi.server(socket, application, custom_pool=self.pool,
                             log=logging.WritableLogger(logger))   
    

### 2.6 class Router(object)

��API��·��ת������Ӧ��app����������Ҫ��

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
            Create a router for the given routes.Mapper.#����routes.Mapper�е�·�ɹ������һ��·������
            ��ص�·�ɹ����ڽ���api-paste.ini�е�extensions�����Ѿ���ɡ�
    
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
            app = match['controller']#������controller������
            return app

 [1]: http://blog.wachang.net/wp-content/uploads/2013/04/wsgi.py_.png
 [2]: http://blog.wachang.net/2013/04/quantum-wsgi-define-and-how-to-handle-api/