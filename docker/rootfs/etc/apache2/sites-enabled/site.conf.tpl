<VirtualHost *:80>
    ServerAlias {{APP_DOMAIN}}
    DocumentRoot /apps/qucheng/www
    AcceptPathInfo On
    <Directory />
        AcceptPathInfo On
        Options FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
    ErrorLog /dev/stderr
    CustomLog /dev/stdout combined
</VirtualHost>
