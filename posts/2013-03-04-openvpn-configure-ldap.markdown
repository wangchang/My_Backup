原来这个不是OpenVPN官方自带的内容，还要自己折腾一下啊，找了些资料，这里提供两种方案，总结如下：

## openvpn-auth-ldap

[OpenVPN-AUTH-LDAP][1]这是最常见的方案，不过现在一般Ubuntu现在都已经有编译好的包了，所以不用进行复杂的源码编译了。

    apt-get install openvpn-auth-ldap
    

然后，先是配置LDAP认证相关内容：

    mkdir /etc/openvpn/auth
    cp /usr/share/doc/openvpn-auth-ldap/examples/auth-ldap.conf /etc/openvpn/auth
    

<!--more--> 编辑文件

`/etc/openvpn/auth/auth-ldap.conf`:

    <LDAP>
    # LDAP server URL
    URL     ldap://X.X.X.X
    
    # Bind DN (If your LDAP server doesn't support anonymous binds)
    # BindDN        uid=Manager,ou=People,dc=example,dc=com
    
    # Bind Password
    # Password  SecretPassword
    
    # Network timeout (in seconds)
    Timeout     15
    
    # Enable Start TLS
    TLSEnable   no ##这里要注意
    
    # Follow LDAP Referrals (anonymously)
    FollowReferrals yes
    
    # TLS CA Certificate File
    TLSCACertFile   /usr/local/etc/ssl/ca.pem
    
    # TLS CA Certificate Directory
    TLSCACertDir    /etc/ssl/certs
    
    # Client Certificate and key
    # If TLS client authentication is required
    TLSCertFile /usr/local/etc/ssl/client-cert.pem
    TLSKeyFile  /usr/local/etc/ssl/client-key.pem
    
    # Cipher Suite
    # The defaults are usually fine here
    # TLSCipherSuite    ALL:!ADH:@STRENGTH
    </LDAP>
    
    <Authorization>
    # Base DN
    BaseDN      "ou=People,dc=oinlab"
    
    # User Search Filter
    SearchFilter    "(uid=%u)" ##这里要注意
    
    # Require Group Membership
    RequireGroup    false ##是否开启组验证
    
    # Add non-group members to a PF table (disabled)
    #PFTable    ips_vpn_users
    
    <Group>
        BaseDN      "ou=People,dc=oinlab"
        SearchFilter    "(|(cn=developers)(cn=artists))"
        MemberAttribute uniqueMember
        # Add group members to a PF table (disabled)
        #PFTable    ips_vpn_eng
    </Group>
    </Authorization>
    

完了以后在配置OpenVPN中`server.conf`文件，加入如下：

    plugin /usr/lib/openvpn/openvpn-auth-ldap.so /etc/openvpn/auth/auth-ldap.conf
    client-cert-not-required ##有了LDAP就不需要证书认证了嘛
    

最后就是配置一下客户端了：

客户端的配置简单，去掉`cert xxx.crt`以及`key xxx.key`部分，再加上`auth-user-pass`就OK。如下就是一个简单的例子：

    client
    dev tun
    proto udp
    remote X.X.X.X 1194
    resolv-retry infinite
    nobind
    user nobody
    group nobody
    persist-key
    persist-tun
    ca ca.crt
    ;cert wangchang.crt
    ;key wangchang.key
    comp-lzo
    verb 3
    auth-user-pass
    

参考资料(需要飞过Wall)：

<http://cheaster.blogspot.com/2009/11/openvpn-auth-over-ldap.html>

<http://www.howtoforge.com/setting-up-an-openvpn-server-with-authentication-against-openldap-on-ubuntu-10.04-lts>

## 脚本方式

有人就是把插件的功能用一个脚本来实现，相对来说，配置没那么复杂，我没测试过，应该可行。下附链接：

[CSDN上的一个下载][2]

<http://redmine.debuntu.org/projects/openvpn-ldap-auth/wiki>

<http://backreference.org/2012/09/14/openvpn-ldap-authentication/>

 [1]: https://code.google.com/p/openvpn-auth-ldap/
 [2]: http://down.51cto.com/data/573688

