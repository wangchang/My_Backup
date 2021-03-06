> 本文主要罗列了CGI,FASTCGI,PHP-CGI,Spawn-FCGI,PHP-FPM的一些概念，后续做了一些相应的上机演示，主要是以php为例子说明，顺带提到了nginx中PHP环境的构建问题。

## 1 CGI

CGI全称是“公共网关接口”(Common Gateway Interface)，HTTP服务器与你的或其它机器上的程序进行“交谈”的一种工具，其程序须运行在网络服务器上。CGI可以用任何一种语言编写，只要这种语言具有标准输入、输出和环境变量。如php,perl,tcl等。`CGI是一种模型规范`，然后我们可以编写一个CGI程序来实现CGI模型中定义的功能。

最常见的例子，服务器S上有一个Web服务W，这个服务W是用php写的，客户C请求服务W，S拿到请求以后就需要执行服务W中的代码，这个时候CGI程序就调用服务W的程序，得到结果后返回给服务器S，S再封装返回信息返回给客户C,或者说CGI直接把结果返回给客户C。

所以CGI你可以认识是一个程序，得到一个请求以后就开启一个线程用来执行php/python/asp脚本用，得到结果后返回给服务器S的程序。

传统CGI接口方式的主要缺点是性能很差，因为每次HTTP服务器遇到动态程序时都需要重新启动脚本解析器来执行解析，然后结果被返回给HTTP服务器。这在处理高并发访问时，几乎是不可用的。另外传统的CGI接口方式安全性也很差，现在已经很少被使用了。

## 2 FastCGI

FastCGI是从CGI发展改进而来的,FastCGI是一个可伸缩地、高速地在HTTP server和动态脚本语言间通信的接口。多数流行的HTTP server都支持FastCGI，包括Apache、Nginx和lighttpd等，同时，FastCGI也被许多脚本语言所支持。

FastCGI接口方式采用C/S结构，可以将HTTP服务器和脚本解析服务器分开，同时在脚本解析服务器上启动一个或者多个脚本解析守护进程。当HTTP服务器每次遇到动态程序时，可以将其直接交付给FastCGI进程来执行，然后将得到的结果返回给浏览器。这种方式可以让HTTP服务器专一地处理静态请求或者将动态脚本服务器的结果返回给客户端，这在很大程度上提高了整个应用系统的性能。

更具体来讲，之前的CGI是遇到动态语言就启动一下CGI进程，而FastCGI像是一个常驻(long-live)型的CGI，它可以一直执行着，只要激活后，不会每次都要花费时间去fork一次。所以说，`fasc-cgi也更像是一种模型规范`。

## 3 Cgi/Fast-cgi工作原理

我们以php文件举例，原理同样适合python,asp等。

### 3.1 CGI模式下php-cgi

当请求一个php页面，比如index.php以后，服务器得到请求，这是一个php脚本，服务器看不懂，于是他就启动一个程序php-cgi程序，这个程序就是一个php语言的解释器，类推就还有(python-cgi等等)，这个php-cgi就执行index.php中的代码，得到结构以后返回给客户或者先给服务器再由服务器传递给客户，然后这个php-cgi程序就退出。

所以，php-cgi就是一个程序，用来解释php的。可以如下安装：

    apt-get install php-cgi
    

### 3.2 Fast-CGI模式

因为fast-cgi模式就是一个进程不退出，有请求到来时就产生或者调用一个新的进程处理，处理完了相应的处理程序就关闭，而fast-cgi程序又继续等待。`所以fast-cgi程序更像是一个管理器，用来管理cgi进程的。`结合3.1中所讲，更详细的解释是：

*   fast-cgi管理进程初始化，启动多个cgi解释进程，比如php环境下就启动多个php-cgi进程。然后就等待服务器的连接。可以使用TCP或者socket方式连接（这是与服务器的连接）。
*   当相应的请求到达web服务器的时候，服务器发送到fast-cgi管理程序，fast-cgi管理程序就激活一个cgi进程然后把相关的环境变量（CGI环境变量）和参数发送给这个cgi进程，php中就会发送到php-cgi。
*   cgi进程一般都是一个动态语言解释器，所以他执行，php中php-cgi就执行代码，执行完成以后结果返回给fast-cgi，fast-cgi再递交给服务器或者直接给用户，而此时cgi进程就应该退出或者休眠了，在php中，php-cgi此时就关闭了。

## 4 总结

从上面可以总结如下：

*   CGI就是一个解释动态语言的程序，解释php的一般是php-cgi
*   fast-cgi就是一个管理CGI的程序，php中环境有php-fpm，spwan-fcgi等。

但是，php-cgi虽然我们一般认为是一个cgi程序，其实他也是一个fast-cgi程序，通过一些配置也可以完成fast-cgi的功能。具体的实际，我们下一节中说明。

