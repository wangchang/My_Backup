因为实验室环境需要提高上传文件大小限制，做个记录，如何在Nginx+PHP环境中配置文件上传相关的内容。

## PHP配置:

    root@kb310-node10:/etc/php5# ls
    cgi  cli  conf.d  fpm
    

根据使用的具体方式，更改相应文件夹下`php.ini`文件中的选项：

    1 max_execution_time
    

变量max\_execution\_time设置了在强制终止脚本前PHP等待脚本执行完毕的时间，此时间以秒计算。当脚本进入了一个无限循环状态 时此变量非常有用。然而，当存在一个需要很长时间完成的合法活动时（例如上传大型文件），这项功能也会导致操作失败。在这样的情况下必须考虑将此变量值增 加，以避免PHP在脚本正在执行某些重要过程的时候将脚本关闭。允许大文件上传就设置大一点。

    2 file_uploads = on
    

是否允许文件上传。

    3 upload_max_filesize =2M
    

允许上传文件大小

    4 post_max_size
    

同表单提交相关的一个变量是post\_max\_size，它将控制在采用POST方法进行一次表单提交中PHP所能够接收的最大数据量。需要将默认的8 MB改得更大。相反，应当适当将其降到更为实际的数值。但如果希望使用PHP文件上传功能，则需要将此值改为比upload\_max\_filesize还要大。

    5 max_input_time
    

此变量可以以秒为单位对通过POST、GET以及PUT方式接收数据时间进行限制。如果应用程序所运行环境处在低速链路上，则需要增加此值以适应接收数据所需的更多时间.

    6 memory_limit =10M
    

为了避免正在运行的脚本大量使用系统可用内存，PHP允许定义内存使用限额。通过memory\_limit变量来指定单个脚本程序可以使用的最大内存容量，变量memory\_limit的值(不要超出服务器内寸最大值)

## Nginx配置

貌似不需要进行设置。

## 参考资料：

<http://bbs.php100.com/read-htm-tid-297084.html>

