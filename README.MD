#ABOUT THIS
____________
This is a small and simple calculator that can be expanded (hopefully).
It calculates basic arithmetic using Reverse Polish Notation.

##Vhost
You'll need a vhost to get this up and running. Here's mine:

```<VirtualHost *:80>
        ServerName calculator.local
        DocumentRoot /var/www/calculator/public
        Alias "/var/www/calculator/assets" "/var/www/calculator/public"
        <Directory /var/www/calculator/public>
                AllowOverride None
                Order Allow,Deny
                Allow from All
                Require all granted
                <IfModule mod_rewrite.c>
                        Options -MultiViews
                        RewriteEngine On
                        RewriteCond %{REQUEST_FILENAME} !-f
                        RewriteRule ^(.*)$ index.php [QSA,L]
                </IfModule>
        </Directory>

        # uncomment the following lines if you install assets as symlinks
        # or run into problems when compiling LESS/Sass/CoffeeScript assets
        # <Directory /var/www/project>
        #     Options FollowSymlinks
        # </Directory>

        # optionally disable the RewriteEngine for the asset directories
        # which will allow apache to simply reply with a 404 when files are
        # not found instead of passing the request into the full symfony stack
        <Directory /var/www/calculator/public/bundles>
                <IfModule mod_rewrite.c>
                        RewriteEngine Off
                </IfModule>
        </Directory>
        # optionally set the value of the environment variables used in the application
        SetEnv APP_ENV dev
        SetEnv APP_SECRET 37136574a188a621e4de0b50a961a701
</VirtualHost>

<VirtualHost *:443>
        ServerName calculator.local
        DocumentRoot /var/www/calculator/public
        <Directory /var/www/calculator/public>
                AllowOverride None
                Order Allow,Deny
                Allow from All
                Require all granted
                <IfModule mod_rewrite.c>
                        Options -MultiViews
                        RewriteEngine On
                        RewriteCond %{REQUEST_FILENAME} !-f
                        RewriteRule ^(.*)$ index.php [QSA,L]
                </IfModule>
        </Directory>

        # uncomment the following lines if you install assets as symlinks
        # or run into problems when compiling LESS/Sass/CoffeeScript assets
        # <Directory /var/www/project>
        #     Options FollowSymlinks
        # </Directory>

        # optionally disable the RewriteEngine for the asset directories
        # which will allow apache to simply reply with a 404 when files are
        # not found instead of passing the request into the full symfony stack
        <Directory /var/www/calculator/public/bundles>
                <IfModule mod_rewrite.c>
                        RewriteEngine Off
                </IfModule>
        </Directory>
        # optionally set the value of the environment variables used in the application
        SetEnv APP_ENV dev
        SetEnv APP_SECRET 37136574a188a621e4de0b50a961a701
        SetEnv HTTPS on
        SSLEngine on
        SSLProtocol +TLSv1.2 +TLSv1.1 +TLSv1
        SSLCompression off
        SSLHonorCipherOrder on
        SSLCipherSuite "ECDHE-ECDSA-AES256-GCM-SHA384:ECDHE-RSA-AES256-GCM-SHA384:ECDHE-ECDSA-AES256-SHA384:ECDHE-RSA-AES256-SHA384:ECDHE-ECDSA-AES128-GCM-SHA256:ECDHE-RSA-AES128-GCM-SHA256:ECDHE-ECDSA-AES128-SHA256:ECDHE-RSA-AES128-SHA256:ECDHE-RSA-AES256-SHA:ECDHE-ECDSA-RC4-SHA:ECDHE-RSA-RC4-SHA:ECDH-ECDSA-RC4-SHA:ECDH-RSA-RC4-SHA:RC4-SHA"
        SSLCertificateChainFile "/etc/ssl/private/local.crt"
        SSLCertificateFile "/etc/ssl/private/local.crt"
        SSLCertificateKeyFile "/etc/ssl/private/local.key"
</VirtualHost>```