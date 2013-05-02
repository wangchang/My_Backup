��һ����������Python Paste��Deploy���Ƶĸ����һ�ھ���һ��СС��ʵ�������ȣ����Ǿ�һ��ʹ����Deploy�����ӣ��������OpenStack��Quantum�����WSGI���֡���������������WSGI���ֵ������ļ�����ini��׺����ô����api-paste.ini�ļ���������API�Ĵ������̡��Ҽ������ʵ���ע�͡�

<!--more-->

## 1 OpenStack Quantum�����ļ�api-paste.ini

    [composite:quantum]
    use = egg:Paste#urlmap
    /: quantumversions
    /v2.0: quantumapi_v2_0
    #ʹ��composite�ֽ���ƣ�compositeʹ����usrlmap��xxxxx/xxx��API����quantumversions����xxxx/v2.0/xxxx��API����quantumapi_v2_0����
    
    [composite:quantumapi_v2_0]
    use = call:quantum.auth:pipeline_factory
    noauth = extensions quantumapiapp_v2_0
    keystone = authtoken keystonecontext extensions quantumapiapp_v2_0
    #quantumapi_v2_0��Ȼ��һ���ֽ������ʹ����quantum.authģ���µ�pipeline_factory���������factory������������������һ����noauth,һ����keystone��
    
    [filter:keystonecontext]
    paste.filter_factory = quantum.auth:QuantumKeystoneContext.factory
    #����keystonecontext��ʵ������һ����������ʹ����quantum.authģ���µ����QuantumKeystoneContext��factory����
    
    [filter:authtoken]
    paste.filter_factory = keystone.middleware.auth_token:filter_factory
    auth_host = 127.0.0.1
    auth_port = 35357
    auth_protocol = http
    admin_tenant_name = %SERVICE_TENANT_NAME%
    admin_user = %SERVICE_USER%
    admin_password = %SERVICE_PASSWORD%
    #����������һ��filter
    
    [filter:extensions]
    paste.filter_factory = quantum.extensions.extensions:plugin_aware_extension_middleware_factory
    #����������һ��filter,���filter��Ϊ��֧����չquantum api��
    
    [app:quantumversions]
    paste.app_factory = quantum.api.versions:Versions.factory
    #���ĵ�app���֣�ʹ�ù�����������appָ��python���롣app_factory���������������һϵ�в�����[DEFAULET]�Լ�[app:]����ģ������ֱ�sectionû�в�����������һ����������
    
    [app:quantumapiapp_v2_0]
    paste.app_factory = quantum.api.v2.router:APIRouter.factory
    #ͬ��
    

�������ܽ�һ�£�����Quantum����api����������,���У�ǿ���Ĳ���Ϊ�������룬����Ϊ�����ļ��е�section���֡�

����·��Ϊ`/`���API-->quantumversions����-->����`quantum.api.versions:Version`���`factory����`����

����·��Ϊ`/2.0`���API-->quantumapi\_v2\_0����-->����`quantum.auth`�е�`pipeline_factory`����,ͬʱ��������������noauth��keystone,����Ϊ�ֵ䡣

���pipeline_factory�л��ȡ����һ������CONF.auth����������һ�������ļ��������ǣ���ѡ����õ���֤��ʽ��Ȼ��ѡ��noauth����keystone������ȡ������ֵ.

��ô���������������

noauth: Ӧ�ý����Ⱦ���extensions���filter����\---||||-������`quantum.extensions.extensions:plugin_aware_extension_middleware_factory`������������չapi�������ǵ�һ�ΰ�װ\---||||-quantumapiapp\_v2\_0�������ʵ�ʵ�WSGIӦ�ã�������`quantum.api.v2.router:APIRouter.factory`���������ؽ����

keystone�����������ƣ���ͬ���Ƕ��˼���filter,authtoken keystonecontext extensions quantumapiapp\_v2\_0,������ÿ��filter�п��ܻ����в������ݸ����fliter��

�ܵ���˵��ͨ��pipelineװ�ض��filter,���������app--APIRouter������װ��ʹ���Ϊһ�����д�����֤����չAPI�ȵ�Ӧ�ã��߼��Ͽ�����filter�ĺô����ǿ����Զ��壬������Բ�Ҫ��֤���ܣ����дһ������ȫ�����ܵ�Ӧ������Ҫ�õĶࡣ

## 2 ����ʵ��

### 2.1 �����ļ�

    [DEFAULT]
    company = UESTC
    school = Commuication and Information
    
    [composite:common]
    use = egg:Paste#urlmap
    /:showversion
    /detail:showdetail
    
    [pipeline:showdetail]
    pipeline = filter1 filter2 showstudetail
    
    [filter:filter1]
    #filter1 deal with auth,read args below
    paste.filter_factory = python_paste:AuthFilter.factory
    user = admin
    passwd = admin
    
    [filter:filter2]
    #filter2 deal with time,read args below
    paste.filter_factory = python_paste:LogFilter.factory
    #all value is string
    date = 20121120
    
    [app:showstudetail]
    name = wangchang
    age = 23
    paste.app_factory = python_paste:ShowStuDetail.factory
    
    [app:showversion]
    version = 1.0.0
    

�������ļ����Կ������������������²�����

*   ����http://localhost/�ķ��ʣ������showversion���Ӧ�ã�Ӧ�ö�ȡini�ļ��е�versionֵ�����ء�__ע�⣬��ini�е�����ֵ�����ַ�����
*   ����http://localhost/detail�ķ��ʣ����Ⱦ���filter1�Լ�filter2��������filter�ֱ�����֤��LOG��Ϣ�����ǻ��ȡini�����е��û���Ϣ�Լ�ʱ�䡣�����ǽ���showstudetail����showstudetail���ȡini�е��û���Ϣ�����ء�__ע�⣬ʹ�ö��filter��ʱ����Ҫʹ��pipeline��ʽ��

### 2.2 ����

    import sys
    import os
    import webob
    from webob import Request
    from webob import Response
    #from webob import environ
    from paste.deploy import loadapp
    from wsgiref.simple_server import make_server
    from pprint import pprint
    
    class AuthFilter(object):
          '''filter1,auth
             1.factory read args and print,return self instance
             2.call method return app
             3.AuthFilter(app)
          '''
          def __init__(self,app):
              self.app = app
    
          def __call__(self,environ,start_response):
              print 'this is Auth call filter1'
              #pass environ and start_response to app
              return self.app(environ,start_response)
          @classmethod
          def factory(cls,global_conf,**kwargs):
              '''global_conf and kwargs are dict'''
              print '######filter1##########'
              print 'global_conf type:',type(global_conf)
              print '[DEFAULT]',global_conf
              print 'kwargs type:',type(kwargs)
              print 'Auth Info',kwargs
              return AuthFilter
    
    class LogFilter(object):
          '''
          filter2,Log
          '''
          def __init__(self,app):
              self.app = app
          def __call__(self,environ,start_response):
              print 'This is call LogFilter filter2'
              return self.app(environ,start_response)
          @classmethod
          def factory(cls,global_conf,**kwargs):
              #print type(global_conf)
              #print type(kwargs)
              print '######filter2###########'
              print '[DEFAULT]',global_conf
              print 'Log Info',kwargs
              return LogFilter
    
    class ShowStuDetail(object):
          '''
          app
          '''
          def __init__(self,name,age):
              self.name = name
              self.age = age
          def __call__(self,environ,start_response):
              print 'this is call ShowStuDetail'
              #pprint(environ)
              #pprint environ
              start_response("200 OK",[("Content-type","text/plain")])
              content = []
              content.append("name: %s age:%s\n" % (self.name,self.age))
              content.append("**********WSGI INFO***********\n")
              for k,v in environ.iteritems():
                  content.append('%s:%s \n' % (k,v))
              return ['\n'.join(content)] #return a list
    
          @classmethod
          def factory(cls,global_conf,**kwargs):
              #self.name = kwargs['name']
              #self.age = kwargs['age']
              return ShowStuDetail(kwargs['name'],kwargs['age'])
    
    class ShowVersion(object):
          '''
          app
          '''
          def __init__(self,version):
              self.version = version
          def __call__(self,environ,start_response):
              print 'this is call ShowVersion'
              req = Request(environ)
              res = Response()
              res.status = '200 OK'
              res.content_type = "text/plain"
              content = []
              #pprint(req.environ)
              content.append("%s\n" % self.version)
              content.append("*********WSGI INFO*********")
              for k,v in environ.iteritems():
                  content.append('%s:%s\n' % (k,v))
              res.body = '\n'.join(content)
              return res(environ,start_response)
          @classmethod
          def factory(cls,global_conf,**kwargs):
              #self.version = kwargs['version']
              return ShowVersion(kwargs['version'])
    
    if __name__ == '__main__':
         config = "python_paste.ini"
         appname = "common"
         wsgi_app = loadapp("config:%s" % os.path.abspath(config), appname)
         server = make_server('localhost',7070,wsgi_app)
         server.serve_forever()
         pass
    

�ڳ����У�����web����Ĵ����ҷֱ������webob����ͨWSGI����ķ�ʽ�������һᲹ��webob��ʹ�á�

### 2.3 ���

�ȴӷ���˽������һ�µ������̣�

    Ubuntu:~/python$ python python_paste.py
    ######filter1##########
    global_conf type: 
    [DEFAULT] {'school': 'Commuication and Information', 'company': 'UESTC', 'here':         '/home/wachang/python', '__file__': '/home/wachang/python/python_paste.ini'}
    kwargs type: 
    Auth Info {'passwd': 'admin', 'user': 'admin'}
    ######filter2###########
    [DEFAULT] {'school': 'Commuication and Information', 'company': 'UESTC', 'here':     '/home/wachang/python', '__file__': '/home/wachang/python/python_paste.ini'}
    Log Info {'date': '20121120'}
    ������PD����Ӧ��ʱ������filter��factory��������Ľ�������Կ������˶�������صı�����Ϣ��
    
    
    this is call ShowVersion
    localhost - - [21/Nov/2012 13:23:40] "GET / HTTP/1.1" 200 2938
    this is call ShowVersion
    localhost - - [21/Nov/2012 13:23:40] "GET /favicon.ico HTTP/1.1" 200 2889
    �����ǽ���/������Ϊû��ʹ��filter��ֱ�ӽ���showversionӦ�ô��������ء�
    
    this is Auth call filter1
    This is call LogFilter filter2
    this is call ShowStuDetail
    localhost - - [21/Nov/2012 13:24:23] "GET /detail HTTP/1.1" 200 3016
    this is call ShowVersion
    localhost - - [21/Nov/2012 13:24:24] "GET /favicon.ico HTTP/1.1" 200 2889
    filter�ĵ���ʱ�ص㰡�����Կ��������õ�˳���pipeline��һ�������ŵ���Ӧ�á�
    

��Ҫ�������ڵĻ����Ϳ���[webob:do-it-yourselfrself][1]

 [1]: http://docs.webob.org/en/latest/do-it-yourself.html