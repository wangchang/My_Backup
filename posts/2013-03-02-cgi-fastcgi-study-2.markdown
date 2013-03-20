在上一节中，将到了Fastcgi中的一些概念，这一节就实例展示下。以php环境为例：

从上节中，我们明白了，php-cgi就是一个php的解释器，当服务器接收到一个php文件的时候，就会连接Fastcgi管理程序，连接的方式可以通过TCP连接（fastcgi管理程序监听一个端口）或者unix sock（通过文件读写的方式交互）两种方式，这个管理程序会执行php-cgi这个php解释器，从而得到结果。而这个fastcgi管理程序，就有php-fpm以及spawn-fcgi两个。

<!--more-->

## Php-cgi

实际上，php-cgi也可以作为一个fast-cgi管理程序，有如下参数：

     sudo -u www-data PHP_FCGI_CHILDREN=5 PHP_FCGI_MAX_REQUESTS=125 /usr/bin/php-cgi -q -b 127.0.0.1:9000 &
    

以上就表示启动了5个php-cgi进程，监听地址127.0.0.1:9000，服务器需要把php请求发到这个地址上。此时可以查看进程：

    root@kb310-node10:~# ps -e|grep php-cgi
    3527 ?        00:00:00 php-cgi
    3529 ?        00:00:00 php-cgi
    3530 ?        00:00:00 php-cgi
    3531 ?        00:00:00 php-cgi
    3532 ?        00:00:00 php-cgi
    3533 ?        00:00:00 php-cgi
    

而相应的nginx关于php的配置就为：

    location ~ .php$ 
        { 
          fastcgi_pass 127.0.0.1:9000; 
          fastcgi_index index.php; 
          fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name; 
          include fastcgi_params; 
        }
    

php-cgi的缺点：

*   php-cgi变更php.ini配置后需重启php-cgi才能让新的php-ini生效，不可以平滑重启
*   直接杀死php-cgi进程,php就不能运行了。(PHP-FPM和Spawn-FCGI就没有这个问题,守护进程会平滑从新生成新的子进程。）

## Php-FPM http://php-fpm.org/

php-fpm作为一个fasc-cgi管理程序，在CPU和内存方面的控制都比较好,用他来管理php-cgi，有很高的稳定性，如下安装：

    apt-get install php5-fpm
    

然后在`/etc/php5/fpm`中进行配置，php-fpm.conf配置php-fpm的基本信息，这里可以更改`采用TCP或者UNIX SOCK`以及监视进程数的多少，`php.ini`是更改php配置，`pool.d`里面的东西是更改FPM的线程管理机制，一般用epoll，性能好。我自己采用的是UNIX SOCK监听：

    ##in php-fpm.ini
    [global]
    ; Pid file
    ; Note: the default prefix is /var
    ; Default Value: none
    ;pid = run/php-fpm.pid
    pid = /var/run/php5-fpm.pid
    

启动并查看服务：

    service php5-fpm restart
    root@kb310-node10:/etc/php5/fpm# ps -e|grep php
    1738 ?        00:00:01 php5-fpm
    1739 ?        00:00:28 php5-fpm
    1740 ?        00:00:31 php5-fpm
    1741 ?        00:00:32 php5-fpm
    

相应nginx中就需要把`fastcgi_pass`参数更改为：

    fastcgi_pass    unix:/var/run/php5-fpm.sock;
    

更多使用说明：

    service php5-fpm {start|stop|quit|restart|reload|logrotate}
    --start 启动php的fastcgi进程
    --stop 强制终止php的fastcgi进程
    --quit 平滑终止php的fastcgi进程
    --restart 重启php的fastcgi进程
    --reload 重新平滑加载php的php.ini
    --logrotate 重新启用log文件
    

## Spawn-fcgi http://redmine.lighttpd.net/projects/spawn-fcgi

和PHP-FPM功能一样，要问他和php-fpm有什么区别，请看<http://php-fpm.org/about/>，使用上：

    spawn-fcgi -a 127.0.0.1 -p 9000 -C 10 -u www-data -f /usr/bin/php-cgi
    -f 指定调用FastCGI的进程的执行程序位置，根据系统上所装的PHP的情况具体设置
    -a 绑定到地址addr
    -p 绑定到端口port
    -s 绑定到unix socket的路径path
    -C 指定产生的FastCGI的进程数，默认为5(仅用于PHP)
    -P 指定产生的进程的PID文件路径
    -u和-g FastCGI使用什么身份(-u 用户 -g 用户组)运行，Ubuntu下可以使用www-data，其他的根据情况配置，如nobody、apache等
    

## 参考文章：

[FAST-CGI主页][1]

<http://www.mike.org.cn/articles/what-is-cgi-fastcgi-php-fpm-spawn-fcgi/>

<http://www.myhack58.com/Article/sort099/sort0102/2012/33364.htm>

<http://wenku.baidu.com/view/1215375e3b3567ec102d8a67.html>

<http://www.ayuelee.cn/fastcgi-spawn-fcgi-init-script.html>

<http://hi.baidu.com/winsyk/item/7958e1313dbccdbd633aff8e>

 [1]: http://www.fastcgi.com/drupal/node/2

