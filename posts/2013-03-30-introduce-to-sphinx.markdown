最近看python一些模块文档的时候发现有doc目录，研究了一下发现两个关键字：一是Sphinx,二是pygments，为了能有更好的文档，于是研究下这两个东西,先说第一个sphinx。

## 1 什么是Sphinx

Sphinx是一个工具，她能够轻易地创建智慧和优雅的文档，她是出自Georg Brandl之手，在BSD许可证下授权。

她最初是为了新版的python文档， 因此在python项目的文档具有完美的特性，但是同样支持c/c++，目前正在计划增加对其他的语言的支持。 Sphinx具有如下的特点：

*   输出格式： 超文本标记语言 (包括Windows HTML帮助)，LaTeX (可打印的PDF版本)，手册页，纯文本
*   丰富的交叉引用： 语义标记以及针对函数，类，引用，词汇表（术语）和相似的信息块的自动链接 *　层次结构： 简单的文本树定义，就能自动地链接到同层（兄弟姐妹）、上一层（父母）以及下一层（子女）的文本位置 *　自动生成目录： 通用索引以及语言模块的目录
*   代码高亮： 代码自动高亮，通过使用 Pygments
*   扩展功能： 自动测试的代码片段，包括从Python模块（API文档）的文档字符串

Sphinx 使用reStructuredText作为她的标记语言，她的优点大部分是来自于reStructuredText 以及reStructuredText的解析和转换工具（套件）Docutils的强大以及简单明了。

说白了,他就是把reStructuredText文本文档翻译成一些文件格式,类似PDF啊之类的工具,正如我的博客上用的wp-markdown插件能把markdown标记语言写的文章转换成合适的html文档一样。

换一种方式，和编译C程序一样，sphinx可以看成是gcc等编译器，负责把源码（reStructuredText）格式的文档编译成相应的PDF或者html的文件。

<!--more-->

## 2 安装

Sphinx的主页是<http://sphinx-doc.org/index.html>

中文站点<http://www.pythondoc.com/sphinx/index.html>

在Ubuntu下可以直接apt安装：

    apt-get install python-sphinx
    

也可以使用：

    easy_install -U Sphinx
    

或者在<https://pypi.python.org/pypi/Sphinx>下载安装。

每个文档工程的源码里面有一个conf.py文件，主要是关于文档如何生成等参数配置。可以自己学习。下面我们做一个简单的例子。

## 3 如何使用

首先我们下载一个olso的python库，这个主要是配置文件解析的，OpenStack中用，不用懂其详细语法，下载完以后解压，可以看到doc目录：

    root@Compute2:~/oslo.config-1.1.0/doc# tree
    .
    └── source
        ├── conf.py
        ├── index.rst
        ├── static
        │   ├── basic.css
        │   ├── default.css
        │   ├── header_bg.jpg
        │   ├── header-line.gif
        │   ├── jquery.tweet.js
        │   ├── nature.css
        │   ├── openstack_logo.png
        │   └── tweaks.css
        ├── _templates
        └── _theme
            ├── layout.html
            └── theme.conf
    4 directories, 12 files
    

下面我们开始编译：

    root@Compute2:~/oslo.config-1.1.0/doc/source# sphinx-build -b html ./ ./out
    Making output directory...
    Running Sphinx v1.1.3
    fatal: Not a git repository (or any of the parent directories): .git
    loading pickled environment... not yet created
    loading intersphinx inventory from http://docs.python.org/objects.inv...
    building [html]: targets for 1 source files that are out of date
    updating environment: 1 added, 0 changed, 0 removed
    reading sources... [100%] index                                                                                                                                                                                   
    /root/oslo.config-1.1.0/doc/source/index.rst:10: WARNING: toctree contains reference to nonexisting document u'api/autoindex'
    looking for now-outdated files... none found
    pickling environment... done
    checking consistency... done
    preparing documents... done
    writing output... [100%] index                                                                                                                                                                                    
    writing additional files... genindex search
    copying static files... done
    dumping search index... done
    dumping object inventory... done
    build succeeded, 1 warning.
    

最后我们打开out文件夹中的index.html，就如下所示了：是不是有点炫啊~~

[<img src="http://blog.wachang.net/wp-content/uploads/2013/03/sphinx-example.jpg" alt="sphinx-example" width="649" height="416" class="aligncenter size-full wp-image-256" />][1]

当然了，因为这个doc中暂时还没内容，所以这个网页目前是空的。

## 4 参考资料

<http://pygments.org/>

 [1]: http://blog.wachang.net/wp-content/uploads/2013/03/sphinx-example.jpg