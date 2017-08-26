#! /bin/bash
echo '<VirtualHost *:80>
  ServerAdmin webmaster@localhost
  DocumentRoot /var/www/html
  ServerAdmin admin@test.com
  ServerName shedoesntfollowmeback
  ServerAlias shedoesntfollowmeback
  ErrorLog /docker.stderr
  CustomLog /docker.stdout  combined
</VirtualHost>' > /etc/apache2/sites-available/shedoesntfollowmeback.conf;
a2ensite shedoesntfollowmeback;
service apache2 restart;
