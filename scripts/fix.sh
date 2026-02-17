#!/bin/bash
# Fix common Docker/website issues

set -e

RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

echo "=========================================="
echo "🔧 CodeReview.pl Fix Script"
echo "=========================================="
echo ""

# Parse argument
ACTION=${1:-"all"}

fix_all() {
    echo -e "${BLUE}Running all fixes...${NC}"
    echo ""
    
    # Fix 1: Clean and rebuild
    echo -e "${YELLOW}1. Cleaning old containers...${NC}"
    docker-compose down
    echo -e "${GREEN}   Done${NC}"
    echo ""
    
    # Fix 2: Check volume mounts
    echo -e "${YELLOW}2. Checking volume mounts...${NC}"
    mkdir -p ./database ./logs
    echo -e "${GREEN}   Created required directories${NC}"
    echo ""
    
    # Fix 3: Start fresh
    echo -e "${YELLOW}3. Starting containers...${NC}"
    docker-compose up -d
    echo -e "${GREEN}   Done${NC}"
    echo ""
    
    # Fix 4: Wait for services
    echo -e "${YELLOW}4. Waiting for services to initialize...${NC}"
    sleep 15
    echo -e "${GREEN}   Done${NC}"
    echo ""
    
    # Fix 5: Health check
    echo -e "${YELLOW}5. Running health check...${NC}"
    bash ./scripts/healthcheck.sh
}

fix_web() {
    echo -e "${BLUE}Fixing web container...${NC}"
    docker-compose restart web
    sleep 10
    
    # Check if fixed
    if curl -s -o /dev/null -w "%{http_code}" http://localhost:8080 | grep -q "200"; then
        echo -e "${GREEN}✅ Web fixed${NC}"
    else
        echo -e "${RED}❌ Web still not responding${NC}"
        echo "Logs:"
        docker-compose logs --tail=20 web
    fi
}

fix_network() {
    echo -e "${BLUE}Fixing network...${NC}"
    docker network prune -f
    docker-compose down
    docker-compose up -d
    echo -e "${GREEN}✅ Network reset complete${NC}"
}

fix_volumes() {
    echo -e "${BLUE}Fixing volumes...${NC}"
    mkdir -p ./database ./logs
    chmod 777 ./database ./logs
    echo -e "${GREEN}✅ Volume directories created/fixed${NC}"
}

case $ACTION in
    all)
        fix_all
        ;;
    web)
        fix_web
        ;;
    network)
        fix_network
        ;;
    volumes)
        fix_volumes
        ;;
    *)
        echo "Usage: $0 [all|web|network|volumes]"
        echo ""
        echo "  all      - Full reset and fix (default)"
        echo "  web      - Restart web container only"
        echo "  network  - Reset Docker network"
        echo "  volumes  - Fix volume directories"
        exit 1
        ;;
esac

echo ""
echo "=========================================="
echo -e "${GREEN}Fix complete!${NC}"
echo "=========================================="
