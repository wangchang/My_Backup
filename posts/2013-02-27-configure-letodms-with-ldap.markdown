LetoDMS(http://www.letodms.com/)是一个免费开源的文档管理系统，适合小型企业或者组织用来做整个的文档管理。因为是我们实验室需要相应的文档管理服务，所以在Opendocman和LetoDMS中都适用了一下，总的来看，有一下特点：

*   Opendocman:界面清爽，中文支持较好，不支持LDAP，使用上不太友好。
*   LetoDMS:界面不是很好看，但是使用很上手，最重要的，支持LDAP。

LetoDMS虽说支持LDAP，但是官网上木有配置文档，网上也没多少资料，自己折腾了好久，然后对着代码一步步Debug,才终于搞定，下面做一个记录。

<!--more-->

## LetoDMS中LDAP验证过程

在LetoDMS中，配置了LDAP以后，会用登陆的uid和密码去匹配LDAP服务器中DN下的uid和密码，成功后再与LetoDMS本地用户数据库进行匹配：1）如果本地LetoDMS中木有相应的用户信息，则根据CN名字信息和UID等创建用户，但不设置密码；2）如果已经有相应的用户信息，则允许登陆，相应的LDAP认证代码在**/op/op.Login.php**中。

## LetoDMS中LDAP验证配置

在conf/settings.xml中配置LDAP信息，有两个地方:

    <authentication enableGuestLogin="false" enablePasswordForgotten="false" restricted="false" enableUserImage="false" disableSelfEdit="false" passwordStrength="0" passwordExpiration="0" passwordHistory="0" passwordStrengthAlgorithm="simple" loginFailure="0" encryptionKey="88401c30b26bdf44f0facbf62849cf1e">  
    

这里的restricted一定要设置为false。当然，这一个也可以通过admin登陆以后，在全局settings里设置。随后，设置LDAP信息：

    <connector enable="enable" type="ldap" host="ldap.oinlab.com" port="389" baseDN="ou=People,dc=oinlab">
    

如果此时没效果，别急，编辑`inc/inc.ClassSettings.php`，再找到如下变量并设置好：

    // LDAP
    var $_ldapHost = "ldap.oinlab.com"; // URIs are supported, e.g.: ldaps://ldap.host.com
    var $_ldapPort = 389; // Optional.
    var $_ldapBaseDN = "ou=People,dc=oinlab";
    var $_ldapAccountDomainName = "oinlab";
    var $_ldapType = 0; // 0 = ldap; 1 = AD
    var $_converters = array(); // list of commands used to convert files to text for Indexer
    

注意，实际上这个inc中的才是最基本的，这里面的变量值是通过读取`conf/setting.xml`中的值来的，当然，改了这里面的值得话settings.xml文件也无效了。最后，LetoDMS中相应的LDAP登陆代码位于`op/op.Login.php`中，有问题的话可以根据这里的代码做一些DEBUG.

