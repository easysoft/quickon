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
    # setting for admin
    Alias /adminer "/apps/qucheng/adminer"
    <Directory "/apps/qucheng/adminer">
        <Files "index.php">
            SetHandler application/x-httpd-php
        </Files>
    </Directory>
    <DirectoryMatch "/apps/qucheng/adminer/.+/.*">
        <FilesMatch ".+\.ph(p[3457]?|t|tml)$">
            SetHandler text/plain
        </FilesMatch>
    </DirectoryMatch>
    ErrorLog /dev/stderr
    CustomLog /dev/stdout combined
</VirtualHost>
