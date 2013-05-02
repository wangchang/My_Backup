wordpress的特点：

*   所有文章，评论，插件配置信息全部存放于数据库中。
*   备份wordpress的主程序文件其实没啥意思，因为是php就是一些脚本而已，但是主题文件夹和插件文件夹和附件文件夹一定要备份，恢复的时候覆盖就可以了！
*   重点备份plugins,themes,uploads三个文件夹，恢复的时候直接覆盖就好。

所以，备份wordpress的核心就是，`数据库+plugins,themes,uploads文件夹。`

<!--more-->

## 1 备份恢复方法总结

### phpmyadmin导出数据库，FTP备份恢复相关文件

但是貌似有点受限于主机提供商。

### WordPress自带的xml导出导入功能。

能很好的备份文章，但是据说不能备份`插件和主题设置`，这个就很痛苦了。

### 帝国备份王

一个php程序备份数据库，据说，非常完美。但是我看最新的版本也就是2010年的了，不知道效果怎么样。

## 2 我的备份方案

我自己博客的备份方案：

*   因为我文章是markdown格式，很通用，在github上My_Backup/posts/中备份所有的文章。
*   数据库就用帝国备份来吧，备份文件不大，放在My_Backup/wordpress/db/中
*   为了保证数据库备份不出错，就再做一个mydql dump命令的备份吧，放在My_Backup/wordpress/db/xxx.sql中
*   把plugins,themes,uploads做一个备份，放在My_Backup/wordpress/{plugins,themes,uploads}中
*   使用wordpress自带的导入导出工具，放在My_Backup/wordpress/xml中

这样看来，不管怎么样，我都可以很好的恢复了吧。

## 3 参考文章

<http://zmingcx.com/wordpress-backup-and-recovery.html> 

<http://www.kay1987.com/887> 

<http://www.phome.net/>