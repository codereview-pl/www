#!/bin/bash
# Deep diagnostic script for CodeReview.pl

set -e

RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m'

echo "=========================================="
echo "🔍 CodeReview.pl Deep Diagnostic"
echo "=========================================="
echo ""

# Check Docker is running
echo "1. Checking Docker..."
if ! docker info > /dev/null 2>&1; then
    echo -e "${RED}❌ Docker is not running${NC}"
    exit 1
fi
echo -e "${GREEN}✅ Docker is running${NC}"
echo ""

# Check containers
echo "2. Checking containers..."
CONTAINERS=$(docker-compose ps -q)
if [ -z "$CONTAINERS" ]; then
    echo -e "${RED}❌ No containers running${NC}"
    echo -e "${YELLOW}Run: make up${NC}"
    exit 1
fi

for CONTAINER in $CONTAINERS; do
    NAME=$(docker inspect --format='{{.Name}}' $CONTAINER | sed 's/\///')
    STATUS=$(docker inspect --format='{{.State.Status}}' $CONTAINER)
    if [ "$STATUS" = "running" ]; then
        echo -e "${GREEN}✅ $NAME: running${NC}"
    else
        echo -e "${RED}❌ $NAME: $STATUS${NC}"
    fi
done
echo ""

# Check ports
echo "3. Checking ports..."
check_port() {
    local PORT=$1
    local NAME=$2
    if curl -s -o /dev/null -w "%{http_code}" http://localhost:$PORT 2>/dev/null | grep -q "200\|302\|301"; then
        echo -e "${GREEN}✅ Port $PORT ($NAME): OK${NC}"
        return 0
    else
        echo -e "${RED}❌ Port $PORT ($NAME): FAILED${NC}"
        return 1
    fi
}

check_port 8080 "Web HTTP"
check_port 8025 "Mailhog UI"
echo ""

# Check Apache config
echo "4. Checking Apache..."
WEB_RUNNING=$(docker-compose exec -T web ps aux 2>/dev/null | grep -c apache2 || echo "0")
if [ "$WEB_RUNNING" -gt "0" ]; then
    echo -e "${GREEN}✅ Apache is running inside container${NC}"
else
    echo -e "${RED}❌ Apache not running in container${NC}"
fi

# Check PHP
PHP_VERSION=$(docker-compose exec -T web php -v 2>/dev/null | head -1 || echo "FAILED")
echo "PHP: $PHP_VERSION"
echo ""

# Check document root
echo "5. Checking document root..."
INDEX_FILE=$(docker-compose exec -T web ls -la /var/www/vhosts/codereview.pl/httpdocs/index.php 2>/dev/null)
if [ -n "$INDEX_FILE" ]; then
    echo -e "${GREEN}✅ index.php exists${NC}"
else
    echo -e "${RED}❌ index.php not found${NC}"
fi
echo ""

# Check logs for errors
echo "6. Checking recent errors..."
ERRORS=$(docker-compose logs --tail=50 2>/dev/null | grep -i "error\|fatal\|warning" | tail -5)
if [ -n "$ERRORS" ]; then
    echo -e "${YELLOW}Recent errors/warnings:${NC}"
    echo "$ERRORS"
else
    echo -e "${GREEN}✅ No recent errors${NC}"
fi
echo ""

# Test actual website content
echo "7. Testing website content..."
CONTENT=$(curl -s http://localhost:8080 2>/dev/null | head -20)
if echo "$CONTENT" | grep -q "CodeReview"; then
    echo -e "${GREEN}✅ Website content: OK${NC}"
else
    echo -e "${RED}❌ Website content: Empty or invalid${NC}"
    echo "Response preview:"
    echo "$CONTENT" | head -5
fi
echo ""

# Network connectivity
echo "8. Container network..."
docker-compose exec -T web ping -c 1 8.8.8.8 > /dev/null 2>&1 && echo -e "${GREEN}✅ Container has internet${NC}" || echo -e "${YELLOW}⚠️ Container may have limited network${NC}"
echo ""

echo "=========================================="
echo "Diagnostic complete"
echo "=========================================="
