��¼һ��DNS�е�һЩ�����Ϊ����������cloudflare���о��ܰ������ǻ���֪ʶ������DNS����ʹ��nsllookup�����ѯ��ֱ��ִ��nslookup������Ӧ������߼Ӳ����õ������

### A��¼

A��¼������������IP��ַ�ļ�¼��Ҳ����һ����������ӦIP��ַ�ļ�¼��

    root@Node1:~# nslookup -type=a www.baidu.com
    Server:     61.139.2.69
    Address:    61.139.2.69#53
    
    Non-authoritative answer:
    www.baidu.com   canonical name = www.a.shifen.com.
    Name:   www.a.shifen.com
    Address: 115.239.210.27
    Name:   www.a.shifen.com
    Address: 115.239.210.26
    

�������ʾDNS��������ַ�������`��Ȩ��Ӧ��`��ʾ��ѯ�õ���������IP��ַ�����ﻹ��ʾ��һ��������baidu�������ʽ�������������õġ�����A��¼�ļ��ɣ�

<!--more-->

*   ����봴������www�ļ�¼����ezloo.com����������¼����д@�������գ���ͬ��ע���̿��ܲ�һ����

*   �������������ͬһ��IP����������ͽ��˶�������������ʹ��*.blog.ezloo.com��ָ��һ��IP�������Ļ��������Ƿ���a.blog.ezloo.com����b.blog.ezloo.com���ܵ�ͬһ��IP��

*   ������ͬһ���������������˶��A��¼�������㽨������blog��A��¼������һ��ָ����111.111.111.111����һ��ָ����111.111.111.112�������ڲ�ѯ��ʱ��ÿ�η��ص����ݰ���������IP��ַ�������ڷ��صĹ������������е�˳��ÿ�ζ�����ͬ�����ڴ� ���ֵĿͻ���ֻѡ���һ����¼����ͨ�����ַ�ʽ����ʵ��һ���̶ȵĸ��ؾ��⡣

### NS��¼

NS��¼��������������¼������ָ����������̨�����������н�����

    root@Node1:~# nslookup -type=ns baidu.com
    Server:     61.139.2.69
    Address:    61.139.2.69#53
    
    Non-authoritative answer:
    baidu.com   nameserver = ns3.baidu.com.
    baidu.com   nameserver = ns4.baidu.com.
    baidu.com   nameserver = dns.baidu.com.
    baidu.com   nameserver = ns2.baidu.com.
    
    Authoritative answers can be found from:
    ns4.baidu.com   internet address = 220.181.38.10
    dns.baidu.com   internet address = 202.108.22.220
    ns2.baidu.com   internet address = 61.135.165.235
    ns3.baidu.com   internet address = 220.181.37.10
    

### AAAA��¼

��ѯ������IPv6��ַ����Ȼ�������������ָ���ipv6�ĵ�ַ��

    root@Node1:~# nslookup -type=aaaa ipv6.baidu.com    
    Server:     61.139.2.69
    Address:    61.139.2.69#53
    
    Non-authoritative answer:
    ipv6.baidu.com  has AAAA address 2400:da00::dbf:0:100
    

### CNAME

CNAME��ָA��¼������һ�����ƣ������㽫�����¼ӳ�䵽ͬһ̨������ϡ������ҵ�tumblr���͵ĵ�ַ��wachang.tumblr.com,����blog.wachang.netָ����domains.tumblr.com����ô����blog.wachang.net��ʵ���Ƿ��ʵ�domains.tumblr.com��domains.tumblr.com�ڸ���������ת��wachang.tumblr.com�ϡ�

    root@Node1:~# nslookup -type=cname blog.wachang.net
    Server:     61.139.2.69
    Address:    61.139.2.69#53
    
    Non-authoritative answer:
    blog.wachang.net    canonical name = domains.tumblr.com.
    

�ڱ���ٶȵģ����ǿ�����

    root@Node1:~# nslookup -type=cname www.baidu.com   
    Server:     61.139.2.69
    Address:    61.139.2.69#53
    
    Non-authoritative answer:
    www.baidu.com   canonical name = www.a.shifen.com.
    

### MX��¼

�ʼ�������¼�ṩ��SMTP�������ļ�¼���ͣ������ж�����ͬ����ӳ�䵽A��¼���ṩ��������ݱ��ݣ���ͬ�ķ������߱���ͬ�����ȼ�������ԽС�������ȼ�Խ�ߣ��ᱻ����ѡ��mx ��¼��Ȩ�ض� Mail �����Ǻ���Ҫ�ģ��������ʼ�ʱ��Mail �������ȶ��������н��������� mx ��¼������Ȩ������С�ķ�����������˵�� 10�����������ͨ�����۾ͽ����������͹�ȥ������޷���ͨ mx ��¼Ϊ 10 �ķ����������۲Ž��ʼ����͵�Ȩ��Ϊ 20 �� mail �������ϡ�

������һ����Ҫ�ĸ��Ȩ�� 20 �ķ�������������ֻ����ʱ���� mail ����Ȩ�� 20 �ķ���������ͨȨ��Ϊ 10 �ķ�����ʱ���ԻὫ�ʼ����͵�Ȩ��Ϊ 10 �� Mail �������ϡ���Ȼ�����������Ҫ�� Mail �����������á�