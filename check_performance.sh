#!/bin/bash
echo "=== BoltBite 性能诊断 ==="
echo ""
echo "1. 服务器状态:"
echo "   Apache: $(sudo systemctl is-active apache2 2>/dev/null || echo 'unknown')"
echo "   PHP-FPM: $(sudo systemctl is-active php8.1-fpm 2>/dev/null || echo 'unknown')"
echo ""
echo "2. 服务器响应时间:"
time curl -k -s -o /dev/null https://b01812585-uws24.duckdns.org/ 2>&1 | tail -1
echo ""
echo "3. 端口监听:"
sudo ss -tlnp | grep -E ":(80|443)" | awk '{print "   " $4}'
echo ""
echo "4. PHP-FPM 配置:"
sudo grep -E "pm.max_children|pm.start_servers" /etc/php/8.1/fpm/pool.d/www.conf | grep -v "^;" | head -2
echo ""
echo "5. DNS 解析:"
echo "   DuckDNS: $(dig +short b01812585-uws24.duckdns.org 2>/dev/null || echo '无法解析')"
echo ""
echo "6. 重要提示:"
echo "   如果外部访问很慢，请检查 AWS Security Group:"
echo "   - 端口 80 (HTTP) 必须允许 0.0.0.0/0"
echo "   - 端口 443 (HTTPS) 必须允许 0.0.0.0/0"
echo "   - 如果端口未开放，外部无法访问，会显示'响应时间过长'"
echo ""

