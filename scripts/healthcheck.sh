#!/bin/bash
# Service health check and auto-fix script

set -e

RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m'

echo "=========================================="
echo "🔧 Service Health Check & Auto-Fix"
echo "=========================================="
echo ""

FIXED=0

# Test HTTP 8080
echo "Testing HTTP 8080..."
if curl -s -o /dev/null -w "%{http_code}" http://localhost:8080 2>/dev/null | grep -q "200"; then
    echo -e "${GREEN}✅ HTTP 8080: OK${NC}"
else
    echo -e "${YELLOW}⚠️ HTTP 8080: FAILED - Attempting fix...${NC}"
    echo "Restarting web container..."
    docker-compose restart web
    sleep 8
    
    if curl -s -o /dev/null -w "%{http_code}" http://localhost:8080 2>/dev/null | grep -q "200"; then
        echo -e "${GREEN}✅ HTTP 8080: Fixed after restart${NC}"
        FIXED=$((FIXED + 1))
    else
        echo -e "${RED}❌ HTTP 8080: Still failed${NC}"
        echo "--- Container logs ---"
        docker-compose logs --tail=10 web
    fi
fi
echo ""

# Test Mailhog 8025
echo "Testing Mailhog 8025..."
if curl -s -o /dev/null -w "%{http_code}" http://localhost:8025 2>/dev/null | grep -q "200"; then
    echo -e "${GREEN}✅ Mailhog 8025: OK${NC}"
else
    echo -e "${YELLOW}⚠️ Mailhog 8025: FAILED - Attempting fix...${NC}"
    echo "Restarting mail container..."
    docker-compose restart mail
    sleep 3
    
    if curl -s -o /dev/null -w "%{http_code}" http://localhost:8025 2>/dev/null | grep -q "200"; then
        echo -e "${GREEN}✅ Mailhog 8025: Fixed after restart${NC}"
        FIXED=$((FIXED + 1))
    else
        echo -e "${RED}❌ Mailhog 8025: Still failed${NC}"
    fi
fi
echo ""

# Check Apache process
echo "Checking Apache process..."
APACHE_PID=$(docker-compose exec -T web pgrep apache2 2>/dev/null || echo "")
if [ -n "$APACHE_PID" ]; then
    echo -e "${GREEN}✅ Apache is running (PID: $APACHE_PID)${NC}"
else
    echo -e "${RED}❌ Apache not running - restarting container...${NC}"
    docker-compose restart web
    sleep 5
    FIXED=$((FIXED + 1))
fi
echo ""

# Check PHP
echo "Checking PHP..."
PHP_TEST=$(docker-compose exec -T web php -r "echo 'OK';" 2>/dev/null || echo "FAILED")
if [ "$PHP_TEST" = "OK" ]; then
    echo -e "${GREEN}✅ PHP: OK${NC}"
else
    echo -e "${RED}❌ PHP: FAILED${NC}"
fi
echo ""

echo "=========================================="
if [ $FIXED -gt 0 ]; then
    echo -e "${GREEN}Fixed $FIXED issue(s)${NC}"
else
    echo -e "${GREEN}All services OK - no fixes needed${NC}"
fi
echo "=========================================="
