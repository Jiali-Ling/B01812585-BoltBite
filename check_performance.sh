#!/bin/bash
echo "=== BoltBite Performance Diagnostic ==="
echo ""
echo "1. Server Status:"
echo "   Apache: $(sudo systemctl is-active apache2 2>/dev/null || echo 'unknown')"
echo "   PHP-FPM: $(sudo systemctl is-active php8.1-fpm 2>/dev/null || echo 'unknown')"
echo ""
echo "2. Server Response Time:"
time curl -k -s -o /dev/null https://b01812585-uws24.duckdns.org/ 2>&1 | tail -1
echo ""
echo "3. Port Listening:"
sudo ss -tlnp | grep -E ":(80|443)" | awk '{print "   " $4}'
echo ""
echo "4. PHP-FPM Configuration:"
sudo grep -E "pm.max_children|pm.start_servers" /etc/php/8.1/fpm/pool.d/www.conf | grep -v "^;" | head -2
echo ""
echo "5. DNS Resolution:"
echo "   DuckDNS: $(dig +short b01812585-uws24.duckdns.org 2>/dev/null || echo 'Unable to resolve')"
echo ""
echo "6. Important Notes:"
echo "   If external access is slow, please check AWS Security Group:"
echo "   - Port 80 (HTTP) must allow 0.0.0.0/0"
echo "   - Port 443 (HTTPS) must allow 0.0.0.0/0"
echo "   - If ports are not open, external access will fail with 'connection timeout'"
echo ""

