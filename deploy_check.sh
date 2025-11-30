#!/bin/bash

echo "========================================="
echo "BoltBite Deployment Check Script"
echo "========================================="
echo ""

echo "1. Checking PHP version..."
php -v
if [ $? -eq 0 ]; then
    echo "PHP installed"
else
    echo "PHP not installed or not accessible"
fi
echo ""

echo "2. Checking Composer..."
composer --version
if [ $? -eq 0 ]; then
    echo "Composer installed"
else
    echo "Composer not installed"
fi
echo ""

echo "3. Checking environment configuration..."
if [ -f .env ]; then
    echo ".env file exists"
    if grep -q "APP_KEY=" .env && ! grep -q "APP_KEY=$" .env; then
        echo "APP_KEY is set"
    else
        echo "APP_KEY not set, run: php artisan key:generate"
    fi
else
    echo ".env file does not exist, please create it with required configuration"
fi
echo ""

echo "4. Checking storage link..."
if [ -L public/storage ]; then
    echo "storage link created"
else
    echo "storage link does not exist, run: php artisan storage:link"
fi
echo ""

echo "5. Checking storage directory permissions..."
if [ -w storage ] && [ -w bootstrap/cache ]; then
    echo "storage and bootstrap/cache directories are writable"
else
    echo "Permission issue, run: sudo chown -R www-data:www-data storage bootstrap/cache"
    echo "Then: sudo chmod -R ug+rwx storage bootstrap/cache"
fi
echo ""

echo "6. Checking database connection..."
php artisan db:show 2>/dev/null
if [ $? -eq 0 ]; then
    echo "Database connection OK"
else
    echo "Database connection failed, check database configuration in .env"
fi
echo ""

echo "7. Checking migration status..."
php artisan migrate:status 2>/dev/null
if [ $? -eq 0 ]; then
    echo "Migration system OK"
    PENDING=$(php artisan migrate:status 2>/dev/null | grep -c "Pending")
    if [ "$PENDING" -gt 0 ]; then
        echo "There are $PENDING pending migrations, run: php artisan migrate"
    else
        echo "All migrations executed"
    fi
else
    echo "Cannot check migration status"
fi
echo ""

echo "8. Checking Nginx configuration..."
if command -v nginx &> /dev/null; then
    sudo nginx -t 2>/dev/null
    if [ $? -eq 0 ]; then
        echo "Nginx configuration valid"
    else
        echo "Nginx configuration has errors"
    fi
else
    echo "Nginx not installed or not in PATH"
fi
echo ""

echo "9. Checking HTTPS certificate..."
if [ -f /etc/ssl/certs/nginx-selfsigned.crt ] && [ -f /etc/ssl/private/nginx-selfsigned.key ]; then
    echo "SSL certificate files exist"
    CERT_EXPIRY=$(openssl x509 -enddate -noout -in /etc/ssl/certs/nginx-selfsigned.crt 2>/dev/null | cut -d= -f2)
    if [ ! -z "$CERT_EXPIRY" ]; then
        echo "Certificate valid until: $CERT_EXPIRY"
    fi
else
    echo "SSL certificate files do not exist (normal if using Let's Encrypt)"
fi
echo ""

echo "10. Checking route cache..."
if [ -f bootstrap/cache/routes-v7.php ]; then
    echo "Routes cached"
else
    echo "Routes not cached, run: php artisan route:cache"
fi
echo ""

echo "11. Checking config cache..."
if [ -f bootstrap/cache/config.php ]; then
    echo "Config cached"
else
    echo "Config not cached, run: php artisan config:cache"
fi
echo ""

echo "========================================="
echo "Check completed"
echo "========================================="
echo ""
echo "Deployment recommendations:"
echo "1. Ensure all migrations are executed: php artisan migrate --force"
echo "2. Create storage link: php artisan storage:link"
echo "3. Set correct file permissions"
echo "4. Cache config and routes: php artisan config:cache && php artisan route:cache"
echo "5. Check Nginx config and reload: sudo nginx -t && sudo systemctl reload nginx"
echo ""
