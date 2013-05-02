> ǳ��OpenStackԴ��ϵ��
> 
> Pythonģ�����ƪ��Python.Pasteָ��֮Deploy(1)-����

Paste.Deploy��Ҫ����������WSGI�е�Web Appʹ�ã���νWSGI��app��������������ͻ��˷��͹���������ģ�Python.Paste�ĺ��ĺ�����loadapp(),������PD��ָ��Paste.Deploy��

## 1 ��鼰��װ

Paste Deployment��һ�ֻ��ƣ�ͨ��loadapp������һ�������ļ�����egg��������WSGIӦ�á���װ�ܼ򵥣��������ַ�ʽ��

    $ sudo pip install PasteDeploy
    

���߿��Դ�github�Ͻ���Դ�밲װ

    $ hg clone http://bitbucket.org/ianb/pastedeploy
    $ cd pastedeploy
    $ sudo python setup.py develop
    

<!--more-->

## 2 �����ļ�Config Flie

һ�������ļ���׺Ϊini�����ݱ���Ϊ�ܶ�Σ�section����PDֻ���Ĵ���ǰ׺�ĶΣ�����`[app:main]`����`[filter:errors]`���ܵ���˵��һ��section�ı�ʶ����`[type:name]`,�����������͵�section���ᱻ���ԡ�

һ��section����������`��=ֵ`����ʾ�ġ�#��һ��ע�͡��ڶεĶ����У������¼��ࣺ

*   [app:main]:����WSGIӦ�ã�main��ʾֻ��һ��Ӧ�ã��ж��Ӧ�õĻ�main��ΪӦ������

*   [server:main]:����WSGI��һ��server��

*   [composite:xxx]����ʾ��Ҫ��һ��������ȶ���dispatched�������,���߶���Ӧ���ϡ�������һ���򵥵����ӣ������У�ʹ����composite��ͨ��urlmap��ʵ�������Ӧ�á�

*   [fliter:]�����塰������������Ӧ�ý��н�һ���ķ�װ��

*   [DEFAULT]������һЩĬ�ϱ�����ֵ��

������һ�����ӣ�

    [composite:main]
    use = egg:Paste#urlmap
    / = home
    /blog = blog
    /wiki = wiki
    /cms = config:cms.ini
    
    [app:home]
    use = egg:Paste#static
    document_root = %(here)s/htdocs
    
    [filter-app:blog]
    use = egg:Authentication#auth
    next = blogapp
    roles = admin
    htpasswd = /home/me/users.htpasswd
    
    [app:blogapp]
    use = egg:BlogApp
    database = sqlite:/home/me/blog.db
    
    [app:wiki]
    use = call:mywiki.main:application
    database = sqlite:/home/me/wiki.db
    

�������зֶεĽ���

### 2.1 composite

    [composite:main]
    use = egg:Paste#urlmap
    / = home
    /blog = blog
    /wiki = wiki
    /cms = config:cms.ini
    

����һ��composite�Σ���ʾ�⽫�����һЩ������web������ȵ���ͬ��Ӧ�á�`use = egg:Paste#urlmap`��ʾ���ǽ�ʹ��`Paste`egg����`urlmap`��ʵ��composite����һ����(urlmap)��������һ��ͨ�õ�composite�����ˡ�����web�����path��ǰ׺����һ����Ӧ�õ�ӳ��(map)����Щ��ӳ��ĳ���Ͱ���blog,home,wiki,config:cms.ini��ӳ�䵽������һ�������ļ���PD�ٸ�������ļ��������룩

### 2.2 App type1

    [app:home]
    use = egg:Paste#static
    document_root = %(here)s/htdocs
    

app��һ��callable object�����ܵĲ���(environ,start\_response)������pasteϵͳ����application�ģ�����WSGI�淶�Ĳ���. app��Ҫ��ɵ���������Ӧenvrion�е�����׼������Ӧͷ����Ϣ�壬Ȼ�󽻸�start\_response������������Ӧ��Ϣ�塣`egg:Paste#static`Ҳ��Paste���е�һ���򵥳�����ֻ����̬�ļ�������Ҫһ�������ļ�document_root,�����ֵ������һ������,��ʽΪ%��var��s��Ӧ��ֵӦ����[DEFAULT]�ֶ�ָ���Ա�Paste��ȡ�����磺

    [app:test]
    use = egg:Paste#static
    document_root = %(path)s/htdocs
    [DEFAULT]
    path = /etc/test
    

### 2.3 fliter

filter��һ��callable object����Ψһ������(app)������WSGI��application����filter��Ҫ��ɵĹ����ǽ�application��װ����һ��application�������ˡ����������������װ���application��

    [filter-app:blog]
    use = egg:Authentication#auth
    next = blogapp
    roles = admin
    htpasswd = /home/me/users.htpasswd
    
    [app:blogapp]
    use = egg:BlogApp
    database = sqlite:/home/me/blog.db
    

`[filter-app:blog]`fliter-app�ֶα�����ϣ����ĳ��Ӧ�ý��а�װ����Ҫ��װ��Ӧ��ͨ��nextָ������������һ�����У�������ֶε���˼���ǣ�����ʽ����blogapp֮ǰ���һ����egg:Authentication#auth����һ���û�����֤�����Ż����blogapp���д��������[app:blogapp]���Ƕ�����blogapp����ָ������Ҫ��database������

### 2.4 App type2

    [app:wiki]
    use = call:mywiki.main:application
    database = sqlite:/home/me/wiki.db
    

����κ�֮ǰ��app�ζ������ƣ���ͬ���Ƕ���wiki���Ӧ�ã�����û��ʹ��egg��������ֱ�Ӷ�mywiki.main���ģ���е�application����ʹ����call������python����һ�нԶ�����ΪWSGI app�Ŀ�����һ��������һ���࣬����һ��ʵ����ʹ��call�Ļ�����Ӧ�ĺ������࣬ʵ���б���ʵ��**call**()����������app�ĸ�ʽ��ð�ŷָ�: `call(��ʾʹ��call����):ģ������·������:Ӧ�ñ�������������`

## 3 ����ʹ��

PD����Ҫʹ�þ���ͨ����ȡ�����ļ�����WSGIӦ�á����£�

    from paste.deploy import loadapp
    wsgi_app = loadapp('config:/path/to/config.ini')
    

ע�⣬������Ҫָ������·����

## 4 ������������ļ�

### 4.1 App

���������ļ��п��Զ�����Ӧ�ø���ÿ��Ӧ�����Լ������ĶΡ�Ӧ�õĶ�����[app:name]�ĸ�ʽ��[app:main]��ʾֻ��һ��Ӧ�á�Ӧ�õĶ���֧���������ָ�ʽ��

    [app:myapp]
    use = config:another_config_file.ini#app_name
    #ʹ������һ�������ļ�
    
    [app:myotherapp]
    use = egg:MyApp
    #ʹ��egg���е�����
    
    [app:mythirdapp]
    use = call:my.project:myapplication
    #ʹ��ģ���е�callable����
    
    [app:mylastapp]
    use = myotherapp
    #ʹ������һ��section
    
    [app:myfacapp]
    paste.app_factory = myapp.modulename:app_factory
    #ʹ�ù�������
    

���У����һ�ַ�ʽ����һ��appָ����ĳЩpython���롣��ģʽ�£�����ִ��appЭ�飬��app\_factory��ʾ�������ֵ��Ҫimport�Ķ����������������myapp.modulename�����룬��������ȡ����app\_factory��ʵ����

app_factory��һ��callable object������ܵĲ�����һЩ����application��������Ϣ��`(global_conf,**kwargs)`��`global_conf`����ini�ļ���default section�ж����һϵ��key-value�ԣ���`**kwargs`����һЩ�������ã�����ini�ļ��У�app:xxx section�ж����һϵ��key-value�ԡ�app_factory����ֵ��һ��application����

��app�������У�use�����Ժ����þ�������ˡ�����ļ�ֵ����������Ϊ���������ݵ�factory�У����£�

    [app:blog]
    use = egg:MyBlog
    database = mysql://localhost/blogdb #���ǲ���
    blogname = This Is My Blog! #���ǲ���
    

### 4.2 ȫ������

ȫ��������Ҫ�����ڶ��Ӧ�ù���һЩ��������Щ�������ǹ涨���ڶ�[DEFAULT]�У������Ҫ���ǣ��������Լ���app�����¶��壬���£�

    [DEFAULT]
    admin_email = webmaster@example.com
    [app:main]
    use = ...
    set admin_email = bob@example.com
    

### 4.3 composite app

composite��һ������������app������ʵ�������ɶ��Ӧ����ɵġ�urlmap����composite app��һ�����ӣ�url��ͬ��path��Ӧ�˲�ͬ��Ӧ�á����£�

    [composite:main]
    use = egg:Paste#urlmap
    / = mainapp
    /files = staticapp
    
    [app:mainapp]
    use = egg:MyApp
    
    [app:staticapp]
    use = egg:Paste#static
    document_root = /path/to/docroot
    

��loadapp������ִ���У�composite app��ʵ��������ͬʱ������������ļ��ж��������Ӧ�á�

### 4.4 app����߼��÷�

��app���У�����Զ���fliters��servers��ͨ��`fliter:`��`server:` PDͨ��loadserver��loadfilter�������е��ã��������ƶ�һ�������ز�ͬ�Ķ���

#### 4.4.1 filter composition

Ӧ��filter�ķ�ʽ�ܶ࣬��Ҫ���ǿ���filter����������֯��ʽ�������һһ����Ӧ��fliter�ļ��ַ�ʽ��

1.ʹ��`filter-with`

    [app:main]
    use = egg:MyEgg
    filter-with = printdebug
    
    [filter:printdebug]
    use = egg:Paste#printdebug
    # and you could have another filter-with here, and so on...
    

2.ʹ��`fliter-app`

    [fliter-app:printdebug]
    use = egg:Paste
    next = main
    
    [app:main]
    use = egg:MyEgg
    

3.ʹ��pipeline

��ʹ�ö��filter��ʱ����Ҫʹ��pipeline�ķ�ʽ������Ҫ�ṩһ��key����pipeline,�����ֵ��һ���б������Ӧ�ý�β�����£�

    [pipeline:main]
    pipeline = filter1 egg:FilterEgg#filter2 filter3 app
    
    [filter:filter1]
    ...
    

������ini�ļ���, ĳ��pipeline��˳����filter1, filter2, filter3��app, ��ô���������е�app\_real��������֯�ģ� app\_real = filter1(filter2(filter3(app)))

��app���������õĹ����У�filter1.\_\_call\_\_(environ,start\_response)�����ȵ��ã���ĳ�ּ��δͨ����filter1������Ӧ�����򽻸�filter2.\\_\_call\\_\_(environ,start\_response)��һ��������ĳ�ּ��δͨ����filter2������Ӧ���ж����������򽻸�filter3.\_\_call\\_\_(environ,start\_response)������filter3��ĳ�ּ�鶼ͨ���ˣ���󽻸�app.\\_\_call\_\_(environ,start\_response)���д���

### 4.5 ��ȡ�����ļ�

���ϣ���ڲ�����Ӧ�õ�����µõ������ļ�������ʹ��appconfig(uri)�������������ֵ���ʽ����ʹ�õ����á�����ֵ������ȫ�ֺܱ��ص�������Ϣ�����Կ���ͨ�����Է��������Ӧ��attributes ��.local\_conf and .global\_conf��

## 5 ����

### 5.1 �������Egg��

egg��python��һ������pip easy_install�ȶ��ǰ�װegg���ķ�ʽ����עegg��Ҫע�⣺

*   ĳһegg�����б�׼˵����
    
    python setup.py name

*   ��entry point������̫���⣬���ֻ��˵�����ó���Ĳ�����

### 5.2 ����factory����

���������Ķ��廹����ѭ֮ǰ�ᵽ��Ӧ�õ�Э�顣Ŀǰ�����ڹ���������Э�������£�

*paste.app_factory

*paste.composite_factory

*paste.filter_factory

*paste.server_factory

���е���Щ��ϣ����һ������\_\_call\_\_�����ģ��������������ࣩ��

1.`paste.app_factory`

    def app_factory(global_config, **local_conf):
        return wsgi_app
    

global\_config��һ���ֵ䣬��local\_conf���ǹؼ��ֲ���������һ��wsgi_app������**call**��������

2.paste.composite_factory`

    def composite_factory(loader, global_config, **local_conf):
       return wsgi_app
    

loader��һ�������м�����Ȥ�ķ���,get\_app(name\_or\_uri, global\_conf=None)����name����һ��wsgiӦ�ã�get\_filter������get\_server����Ҳ��һ������һ�����Ӹ��ӵ����ӣ�����һ��pipelineӦ�ã�

    def pipeline_factory(loader, global_config, pipeline):
        # space-separated list of filter and app names:
        pipeline = pipeline.split()
        filters = [loader.get_filter(n) for n in pipeline[:-1]]
        app = loader.get_app(pipeline[-1])
        filters.reverse() # apply in reverse order!
        for filter in filters:
          app = filter(app)
        return app
    

��Ӧ�������ļ����£�

    [composite:main]
    use = 
    pipeline = egg:Paste#printdebug session myapp
    
    [filter:session]
    use = egg:Paste#session
    store = memory
    
    [app:myapp]
    use = egg:MyApp
    

3.`paste.filter_factory`

fliter�Ĺ���������app�Ĺ����������ƣ����������ص���һ��filter,fliter��һ��������һ��wsgiӦ����ΪΨһ������callable���󣬷���һ����filter�˵�Ӧ�á� ������һ�����ӣ����filter����CGI��REMOTE_USER�����Ƿ���ڣ�������һ���򵥵���֤��������

    def auth_filter_factory(global_conf, req_usernames):
        # space-separated list of usernames:
        req_usernames = req_usernames.split()
        def filter(app):
            return AuthFilter(app, req_usernames)
        return filter
    
    class AuthFilter(object):
        def __init__(self, app, req_usernames):
            self.app = app
            self.req_usernames = req_usernames
    
        def __call__(self, environ, start_response):
            if environ.get('REMOTE_USER') in self.req_usernames:
                    return self.app(environ, start_response)
            start_response(
                    '403 Forbidden', [('Content-type', 'text/html')])
            return ['You are forbidden to view this resource']
    

4.`paste.filter_app_factory`

��paste.filter_factory���ƣ�����һ��wsgiӦ�ò���������һ��WSGIӦ�ã���������ı����ϴ���ģ�

    class AuthFilter(object):
        def __init__(self, app, global_conf, req_usernames):
            ....
    

��ô���� AuthFilter�ͻ���Ϊһ��filter\_app\_factory����ʹ�á�

5.`paste.server_factory`

�����ϲ�ͬ���ǣ��������ص���һ��server,һ��serverҲ��һ��callable������һ��WSGIӦ����Ϊ����������Ϊ���Ӧ�÷���

    def server_factory(global_conf, host, port):
        port = int(port)
        def serve(app):
            s = Server(app, host=host, port=port)
            s.serve_forever()
        return serve
    

Server��ʵ���û������Զ��壬���Բο�python��wsgiref

6.`paste.server_runner`

�� paste.server_factory���ƣ���ͬ���ǲ�����ʽ��

## 6 ����һЩֵ�����۵�����

ConfigParser��PD�ײ��õ����������ini�ļ�������ini�ļ����Ǻ���Ч�ʣ��Ƿ���Ҫ���ģ�

�������ļ��еĶ����Ƿ���Ҫ��python���ģ��������ַ�������ʽ��

��ע��Paste Deployment currently does not require other parts of Paste, and is distributed as a separate package.