---
layout: post
title: "Python类中的特殊方法"
date: 2012-12-27 16:21
comments: true
categories: 
- Python
---
这是一个学习笔记，主要记录一下Python类中的一些特殊的方法。首先解释一下类方法中的两个参数self和cls：

* self参数，类的普通方法需要这个参数，表示传入类的实例对象。

* cls参数，类方法中需要，表示传入的是这个类对象。
<!--more-->

##初始化方法\__init__方法##
类的初始化方法，初始化实例的时候调用。

##普通方法
类中的普通方法（函数），第一个参数是self，表示传入`实例对象`如

    class a(object):
        def hello(self):
            print 'Hello'

##静态方法staticmethod
基本上和一个全局函数差不多，只不过可以通过类或类的实例对象（python里说光说对象总是容易产生混淆，因为什么都是对象，包括类，而实际上类实例对象才是对应静态语言中所谓对象的东西）来调用而已，不会隐式地传入任何参数，也就是说不需要传入self和cls参数。这个和静态语言中的静态方法比较像。简单的来说，静态方法的效果就是`不需要实例化类就可以调用类中的方法`，如下：

    >>> class a(object):
    ...     @staticmethod
    ...     def hello():
    ...             print 'Hello World'
    ... 
    >>> a.hello()
    Hello World
    >>> instance = a()
    >>> instance.hello()
    Hello World

##类方法classmethod
需要传入cls对象，可以通过实例和类对象进行调用。一个类方法就是你可以通过类或它的实例来调用的方法, 不管你是用类调用这个方法还是类的实例调用这个方法,python只会将实际的类对象做为该方法的第一个参数.记住:方法的第一个参数都是类对象而不是实例对象. 按照惯例,类方法的第一个形参被命名为 cls. 任何时候定义类方法都不是必须的（类方法能实现的功能都可以通过定义一个普通函数来实现,只要这个函数接受一个类对象做为参数就可以了），这里注意python里class也是个真实地存在于内存中的对象如下：

    >>> class a(object):
    ...     @staticmethod
    ...     def hello():
    ...             print 'Hello World'
    ...     @classmethod
    ...     def hello_1(cls):
    ...             print 'clsmethod:Hello World'
    ... 
    >>> a.hello()
    Hello World
    >>> a.hello_1()
    clsmethod:Hello World
    >>> instance = a()
    >>> instance.hello()
    Hello World
    >>> instance.hello_1()
    clsmethod:Hello World

其实感觉静态方法和类方法用处差不多一样，只是类方法将方法的调用限制到了类中，其实也就是一个参数和格式的问题了。

##工厂方法factory

##属性方法property
属性方法property其实是一个python内置函数，其主要作用就是管理类中的一个属性。python的手册上说的很好了。首先我们来看看作为内置函数的使用方法：

    函数原型：
    property([fget[, fset[, fdel[, doc]]]])
    fget用于获得属性值，fset就是设置值，fdel就是删除值。

最通常的用法就是用property函数来管理类中某个属性。

    class C(object):
        def __init__(self):
            self._x = None

        def getx(self):
            return self._x
        def setx(self, value):
            self._x = value
        def delx(self):
            del self._x
        x = property(getx, setx, delx, "I'm the 'x' property.")

这样的话，我们就用一个类中的属性x来管理了类中的另一个属性_x，比如，c是类C的一个实例，那么：

* c.x就会调用到getx()方法
* c.x = ???就会调用到setx()方法
* del c.x 就会调用到delx()方法

这样用的好处就是因为在调用函数，可以做一些检查。如果没有严格的要求，直接使用实例属性可能更方便。

接下来，如果把property()函数用成一个装饰器@，那么就会把一个属性变为一个只读属性（相应于增加了getter方法）：

    class Parrot(object):
        def __init__(self):
            self._voltage = 100000

        @property
        def voltage(self):
            """Get the current voltage."""
            return self._voltage

可以看到，这只是用property装饰成了只读属性，那么更改和删除的呢？

    class C(object):
        def __init__(self):
            self._x = None

        @property
        def x(self):
            """I'm the 'x' property."""
            return self._x

        @x.setter
        def x(self, value):
            self._x = value

        @x.deleter
        def x(self):
            del self._x

这是一个典型使用例子，效果呢就和最开始使用property()函数效果是一样的。

##参考文章
下面第一篇文章虽然看懂了代码，但是还是不是很明白类方法在这里面起了个什么作用的。

<http://docs.python.org/2.7/library/functions.html?highlight=property#property>

<http://chen.junchang.blog.163.com/blog/static/63445192012816104645974/>

<http://besteam.im/blogs/article/71/>

<http://luozhaoyu.iteye.com/blog/1506376>

<http://blog.sina.com.cn/s/blog_759a8e39010166ov.html>
