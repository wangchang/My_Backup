> ǳ��OpenStackԴ��ϵ��
> 
> Quantum WSGI�д�������δ���API

��дWSGI�Ĵ�������Ĺ����У�����Ҫ���WSGI���ֵĴ��룬�������WSGI��δ���API�Ĺ��̣�������������൱�죬���ǲ��ϴ�ƪ�ĵ���

����ǰ��Ļ���ƪ�У����Ѿ�������quantum�е�api-paste.ini�ļ���ζ�����quantum api�Ĵ������̡�������һ���عˣ�

����v2.0֮���API��ʹ��keystone��֤�Ļ����������¼������̣�

    `/v2.0`��urlmap�������֣�---����ȡauthtoken�εĲ���---����keystone���н���---������extension---��APIRouter
    

�������������ʲô��˼�أ���������֤���ֽ���

quantumʹ�õ���REST API�����API��һ���ص����API��·����������Ҫ���ã�����Ϊһ���������ݵģ�����һ��api:

    /v2.0/networks/(the uuid of network).json
    

��ô�����networks�������HTTP����ķ�����GET/PUT�ȣ����ܾ��������ǵ���quantum plugin�е��ĸ����������� get_network������uuid�����Ϊһ���������ݣ�.json������quantum���API����Ľ����Ҫ��ʲô��ʽ���ء�

<!--more-->

��������������ǻ��㵽�����о���������ôЩ������

## WSGI&API

**Application**

���ȣ�quantum-server���WSGI��������Ҫһ����������������������յ���API�����Ӧ���ڴ����еĸ������һ��application����ô���applicationҪ����Щɶ�أ���δ���OK������������ļ�api-paste.ini��������ˣ��㿴����ʵapi-paste.ini�ж�һ��API�Ĵ����Ϊ�˺ܶಽ�裬���ɺܶ�������ʵ�����߹ܵ������ϴ���ģ���ô����Щ���ʵ��������һ��application�������У�api-paste.ini�ļ��ж����˶��application����Python.Paste�е�loadapp()������ȡapi-paste.ini��������ļ�������������ôһ�������application�����Կ������app׼ȷ����˵����ʵ���������ִ�ͳ��ʽ�õ��ģ����app��Ҫ����ͨ�����ò�ͬ������ͬʵ���ķ������һ������������˵������һ������������Ϊ���applicationֻ���������̣���û����Ӧ�Ĵ��롣��api-paste.ini�ļ��еĵ�һ��:

    [composite:quantum]
    use = egg:Paste#urlmap
    /: quantumversions
    /v2.0: quantumapi_v2_0
    

���ϣ����application�������֣��ͽ���quantum�������ʵ�Ǹ������application,�������һЩ���ã�

    [app:quantumversions]
    paste.app_factory = quantum.api.versions:Versions.factory
    

����Ҳ������һ��app�������Ȼ���о���Ĵ����ʵ���ˡ���Ȼ���������Ϊ��api-paste.ini�ļ���ÿ���ζ�������һ��app��loadapp()������app����һ���Ĺ�����ϳ�һ�����application.

**Routes**

������API�У�����˵��REST API�������׺��רҵ���˵������·��path��������Ҫ�����ܾ���quantum���յ��õĴ���ʽ����ô��ξ�����ô�����أ����ʱ�����Ҫ����·����������ˣ��������е�·�����ƣ�OpenStack������Routes�������ǿ��Ը���REST API�е�path��Ϣ����һ�����ȡ�

**Router**

Ϊ����ɵ��ȣ��϶�����Ҫһ�����������������Router������api-paste.ini��������һ��API����application�����Ժ���󵽴�APIRouter�����API Router�����Լ����ɵ�·�ɹ������Ӧ��API���ݵ��ȵ�һ���������Ķ����ϣ�controller,���£���

����quantum api�к���api����չapi������api-paste.ini�ж����extension��ʵ����������Ӧ��չAPI�����Ժ�Ҳ������һ��Router��������չAPI��أ��������ὲ��

**Routes Table��**

��Ȼ��·�ɣ���ô����Ҫһ��·�ɱ��ˣ����·�ɱ�����þ����ó����ܹ�����REST API��path��Ϣ����Ӧ�Ĳ������ݵ�һ���������ġ������ϡ����������������ʲô�أ����־ͽ�controller��

**controller**

controller��ʲô��˼��controller����һ��������������֪����quantum api�Ĳ�����ʵ����plugin����ɵģ���ô������ģ��϶���ʹ��plugin�еĺ�������ɡ�ǰ���·��routes��ֻ�ܸ���һ���Ĺ����path·������HTTP��body����·�ɵ�һ��controller����ô���controller��Ҫ���ľ��Ǹ��ݴ��ݹ�������Ϣ��plugin����Ҫִ�в����ĺ����������������������Ȼ��controllerҲ���body��Ϣ(�����)���ݸ�plugin��غ�����OK��������ұ�������quantum api�е�һЩ���������ˡ�

**resource**

��quantum api�оͿ��Կ�������resource����һ����Ҫ��������Դ�����resource�ļ��Ͼ���resources,�ٸ����ӣ�quantum��������network,subnet,port�����Ǽ���API��ʽΪ/v2.0/port/XXX /v2.0/network/XXX ��Щ�е�port,network����resource����ô���resouce����һ�𣬾���resources����������quantum�������˸����֣���collection������quantum�е�REST API����/v2.0/ports/XXX /v2.0/networks/XXX�����ˣ���Ϊresource��collection����ʻ��ڴ����г��֣���������������ʲô��˼��OK�ˣ���һ����quantum��network���ܽ᣺

    networks = collection
    network = resource
    

�Ҿ�һ���򵥵�route�����·�ɱ�����ӣ����������������Ļ�û��ϵ���Ժ�����뽲��

    from routes import Mapper  
    map = Mapper()  
    map.connect(None, "/error/{action}/{id}, controller="error")  
    map.connect("home", "/", controller="main", action="index")  
    
    # Match a URL, returns a dict or None if no match  
    result = map.match('/error/myapp/4')  
    # result == {'controller': 'main', 'action': 'myapp', 'id': '4'}  
    

**action**

��routes���У�����ע����һ��action,����ڴ�����Ҳ���漰��action��ɶ��˼������֪��HTTP������GET PUT POST�ȷ�������Ӧ��Ҫ�Ĳ�������ô��quantum�����У����ǲ������У����ǰ�HTTP�ķ���ӳ�䵽����Դ��action�������ϣ������ӳ���ϵ������ʾ�����ͼ�У�collection��������ĸ���resources��������ʵҲ��һ���򵥵�routes���ˣ�����Ŀǰ����ֻ��Ҫ����action��method�Ķ�Ӧ��

[<img src="http://blog.wachang.net/wp-content/uploads/2013/04/routepath.png" alt="routepath" width="888" height="170" class="aligncenter size-full wp-image-308" />][1]

����������˵����ʵ����һ��**MVC��ģ��-��ͼ-���ƣ�**��ܣ�����һ�����������ʽ�������Լ�ѧϰһ��,���Ը��õ����OpenStack���������REST API˼�루����python���ֿ���ʵ����һ��MVC����

## API&��չAPI

Quantum��������API�ģ�һ���Ǻ���API������networks,ports,subnets��API�����API��Router��ͨ��api-paste.ini�е�APIRouter�����еģ�ͬʱ�������չAPI����Ӧ����չ����quantum/extensionsĿ¼�£�����԰��Լ������resoucre���뵽��չ�У��γ���չAPI����һ���ֺ������½�������ֻ�ǻ����������չAPI��Router��ʵ����api-paste.ini�е���extension_middleware��ɵģ����ԣ��������̿�����������ͼ��������ͼ�����˱���ȫ�����ݣ�

[<img src="http://blog.wachang.net/wp-content/uploads/2013/04/loadapp.png" alt="loadapp" width="794" height="547" class="aligncenter size-full wp-image-309" />][2]

[<img src="http://blog.wachang.net/wp-content/uploads/2013/04/http.png" alt="http" width="687" height="634" class="aligncenter size-full wp-image-310" />][3]

 [1]: http://blog.wachang.net/wp-content/uploads/2013/04/routepath.png
 [2]: http://blog.wachang.net/wp-content/uploads/2013/04/loadapp.png
 [3]: http://blog.wachang.net/wp-content/uploads/2013/04/http.png