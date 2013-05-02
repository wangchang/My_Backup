记录一下DNS中的一些概念，因为今天试了下cloudflare，感觉很棒，这是基础知识。关于DNS可以使用nsllookup命令查询，直接执行nslookup进入相应界面或者加参数得到结果。

### A记录

A记录是用来创建到IP地址的记录。也就是一个域名和相应IP地址的记录。

    root@Node1:~# nslookup -type=a www.baidu.com
    Server:     61.139.2.69
    Address:    61.139.2.69#53
    
    Non-authoritative answer:
    www.baidu.com   canonical name = www.a.shifen.com.
    Name:   www.a.shifen.com
    Address: 115.239.210.27
    Name:   www.a.shifen.com
    Address: 115.239.210.26
    

最上面表示DNS服务器地址，下面的`非权威应答`表示查询得到的域名的IP地址，这里还显示了一个别名，baidu的这个形式估计是做负载用的。关于A记录的技巧：

<!--more-->

*   如果想创建不带www的记录，即ezloo.com，在主机记录中填写@或者留空，不同的注册商可能不一样。

*   创建多个域名到同一个IP，比如给博客建了二级域名，可以使用*.blog.ezloo.com来指向一个IP，这样的话，不管是访问a.blog.ezloo.com还是b.blog.ezloo.com都能到同一个IP。

*   如果你给同一个二级域名设置了多个A记录，比如你建了两个blog的A记录，其中一个指向了111.111.111.111，另一个指向了111.111.111.112，那幺在查询的时候，每次返回的数据包含了两个IP地址，但是在返回的过程中数据排列的顺序每次都不相同。由于大 部分的客户端只选择第一条记录所以通过这种方式可以实现一定程度的负载均衡。

### NS记录

NS记录是域名服务器记录，用来指定域名由哪台服务器来进行解析。

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
    

### AAAA记录

查询域名的IPv6地址。当然，这里的域名是指向的ipv6的地址。

    root@Node1:~# nslookup -type=aaaa ipv6.baidu.com    
    Server:     61.139.2.69
    Address:    61.139.2.69#53
    
    Non-authoritative answer:
    ipv6.baidu.com  has AAAA address 2400:da00::dbf:0:100
    

### CNAME

CNAME是指A记录的另外一个名称，允许你将多个记录映射到同一台计算机上。比如我的tumblr博客的地址是wachang.tumblr.com,别名blog.wachang.net指向了domains.tumblr.com，那么访问blog.wachang.net其实就是访问的domains.tumblr.com。domains.tumblr.com在根据内容跳转到wachang.tumblr.com上。

    root@Node1:~# nslookup -type=cname blog.wachang.net
    Server:     61.139.2.69
    Address:    61.139.2.69#53
    
    Non-authoritative answer:
    blog.wachang.net    canonical name = domains.tumblr.com.
    

在比如百度的，我们看看：

    root@Node1:~# nslookup -type=cname www.baidu.com   
    Server:     61.139.2.69
    Address:    61.139.2.69#53
    
    Non-authoritative answer:
    www.baidu.com   canonical name = www.a.shifen.com.
    

### MX记录

邮件交换记录提供到SMTP服务器的记录类型，可以有多条，同样样映射到A记录，提供冗余和数据备份，不同的服务器具备不同的优先级，数字越小代表优先级越高，会被优先选择。mx 记录的权重对 Mail 服务是很重要的，当发送邮件时，Mail 服务器先对域名进行解析，查找 mx 记录。先找权重数最小的服务器（比如说是 10），如果能连通，那幺就将服务器发送过去；如果无法连通 mx 记录为 10 的服务器，那幺才将邮件发送到权重为 20 的 mail 服务器上。

这里有一个重要的概念，权重 20 的服务器在配置上只是暂时缓存 mail ，当权重 20 的服务器能连通权重为 10 的服务器时，仍会将邮件发送的权重为 10 的 Mail 服务器上。当然，这个机制需要在 Mail 服务器上配置。