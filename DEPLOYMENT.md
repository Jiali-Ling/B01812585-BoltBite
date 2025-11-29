# Deployment Guide

## Server Requirements

Ubuntu 20.04 or higher
PHP 8.1 or higher with PHP-FPM
Nginx or Apache web server
MySQL 5.7 or higher
Composer
Node.js and NPM

## Deployment Steps

1. Install PHP and required extensions:
sudo apt update
sudo apt install php8.1-fpm php8.1-mysql php8.1-mbstring php8.1-xml php8.1-curl php8.1-zip

2. Install Composer:
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

3. Install Node.js and NPM:
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt install -y nodejs

4. Clone or upload project files to server:
cd /home/ubuntu
git clone your-repository-url BoltBite
cd BoltBite

5. Install dependencies:
composer install --no-dev --optimize-autoloader
npm install
npm run build

6. Configure environment:
cp .env.example .env
php artisan key:generate

Edit .env file with production settings:
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password

7. Run migrations:
php artisan migrate --force

8. Create storage link:
php artisan storage:link

9. Set permissions:
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R ug+rwx storage bootstrap/cache

10. Cache configuration:
php artisan config:cache
php artisan route:cache
php artisan view:cache

## Nginx Configuration Example

Create configuration file: /etc/nginx/sites-available/boltbite

server {
    listen 80;
    server_name your-domain.com;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    server_name your-domain.com;

    ssl_certificate /etc/ssl/certs/nginx-selfsigned.crt;
    ssl_certificate_key /etc/ssl/private/nginx-selfsigned.key;
    ssl_protocols TLSv1.2 TLSv1.3;

    root /home/ubuntu/BoltBite/public;
    index index.php;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";
    add_header Strict-Transport-Security "max-age=31536000; includeSubDomains";

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\. {
        deny all;
    }

    client_max_body_size 10M;
}

Enable the site:
sudo ln -s /etc/nginx/sites-available/boltbite /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx

## HTTPS Configuration with Certbot

### For Custom Domain

1. Install Certbot:
sudo apt install certbot python3-certbot-nginx

2. Obtain SSL certificate:
sudo certbot --nginx -d your-domain.com --non-interactive --agree-tos --email your@email.com --redirect

3. Test automatic renewal:
sudo certbot renew --dry-run

### For AWS EC2 Public DNS (Self-Signed Certificate)

Let's Encrypt does not issue certificates for AWS EC2 public DNS names. Use self-signed certificate:

1. Generate self-signed certificate:
sudo openssl req -x509 -nodes -days 365 -newkey rsa:2048 \
  -keyout /etc/ssl/private/nginx-selfsigned.key \
  -out /etc/ssl/certs/nginx-selfsigned.crt \
  -subj "/C=US/ST=State/L=City/O=Organization/CN=your-domain.com"

2. Update Nginx configuration to use self-signed certificate paths.

3. Reload Nginx:
sudo systemctl reload nginx

Note: Browsers will display security warning for self-signed certificates. Users must click Advanced and Proceed to site.

### Certificate Renewal

Self-signed certificates:
Renew before expiration (365 days) using the same command as generation.

Let's Encrypt certificates:
Certbot automatically renews certificates. Set up cron job:
sudo crontab -e
Add: 0 0 1 1-12 1-7 certbot renew --quiet

## Common Issues

### Images Not Displaying

Problem: Images uploaded but not displaying in browser.

Solution:
1. Verify storage link exists:
ls -la public/storage

2. If missing, create link:
php artisan storage:link

3. Check permissions:
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R ug+rwx storage bootstrap/cache

4. Verify Nginx can access files:
Check Nginx error logs: sudo tail -f /var/log/nginx/error.log

5. Clear application cache:
php artisan cache:clear
php artisan config:clear
php artisan view:clear

### 403 Authorization Failure

Problem: Users see 403 Forbidden when accessing certain routes.

Solution:
1. Verify user role is correct in database:
Check users table role field

2. Verify Policy is registered:
Check app/Providers/AuthServiceProvider.php

3. Verify route middleware:
Check routes/web.php for can middleware usage

4. Check Policy logic:
Review app/Policies/ files for correct authorization rules

5. Clear route cache:
php artisan route:clear
php artisan route:cache

### Database Connection Errors

Problem: Cannot connect to database.

Solution:
1. Verify database credentials in .env file
2. Check MySQL service is running: sudo systemctl status mysql
3. Verify database exists: mysql -u root -p -e "SHOW DATABASES;"
4. Check user permissions: mysql -u root -p -e "SELECT user, host FROM mysql.user;"

### Migration Errors

Problem: Migrations fail to run.

Solution:
1. Check database connection
2. Verify all migration files are present
3. Check for syntax errors in migration files
4. If needed, rollback and re-run:
php artisan migrate:rollback
php artisan migrate

### Permission Denied Errors

Problem: Cannot write to storage or cache directories.

Solution:
1. Set correct ownership:
sudo chown -R www-data:www-data storage bootstrap/cache

2. Set correct permissions:
sudo chmod -R ug+rwx storage bootstrap/cache

3. Verify PHP-FPM user matches:
Check /etc/php/8.1/fpm/pool.d/www.conf for user setting

### Route Not Found

Problem: 404 errors for valid routes.

Solution:
1. Clear route cache:
php artisan route:clear

2. Rebuild route cache:
php artisan route:cache

3. Verify routes are registered:
php artisan route:list

4. Check Nginx try_files directive includes index.php

## Verification Checklist

After deployment, verify:

1. Website accessible via HTTPS
2. All routes working correctly
3. Images displaying properly
4. Database connections working
5. File uploads working
6. Authentication working
7. Authorization policies enforcing correctly
8. Storage link exists and accessible
9. Permissions set correctly
10. Caches cleared and rebuilt

## Performance Optimization

1. Enable OPcache in PHP:
Edit /etc/php/8.1/fpm/php.ini:
opcache.enable=1
opcache.memory_consumption=128

2. Enable gzip compression in Nginx:
Add to server block:
gzip on;
gzip_types text/plain text/css application/json application/javascript text/xml application/xml;

3. Use Redis for caching (optional):
Install Redis and configure in .env:
CACHE_DRIVER=redis

4. Optimize images before upload:
Use ImageUploadService which automatically resizes images to 800x800 max.

## Security Recommendations

1. Use strong database passwords
2. Keep Laravel and dependencies updated
3. Regularly backup database
4. Monitor application logs: tail -f storage/logs/laravel.log
5. Review Nginx access and error logs regularly
6. Use Let's Encrypt for production domains
7. Enable firewall: sudo ufw enable
8. Restrict SSH access
9. Keep system packages updated: sudo apt update && sudo apt upgrade

