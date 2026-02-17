#!/bin/bash
# E2E Test Suite for CodeReview.pl
# Tests all routes, content, forms, i18n, and sessions

set -e

RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

BASE_URL="${1:-http://localhost:8080}"
PASSED=0
FAILED=0
TOTAL=0

pass() {
    PASSED=$((PASSED + 1))
    TOTAL=$((TOTAL + 1))
    echo -e "${GREEN}  ✅ PASS${NC} $1"
}

fail() {
    FAILED=$((FAILED + 1))
    TOTAL=$((TOTAL + 1))
    echo -e "${RED}  ❌ FAIL${NC} $1"
}

test_status() {
    local url="$1"
    local expected="$2"
    local label="$3"
    local code
    code=$(curl -s -o /dev/null -w "%{http_code}" "$url" 2>/dev/null)
    if [ "$code" = "$expected" ]; then
        pass "$label (HTTP $code)"
    else
        fail "$label (expected $expected, got $code)"
    fi
}

test_content() {
    local url="$1"
    local needle="$2"
    local label="$3"
    local body
    body=$(curl -s "$url" 2>/dev/null)
    if echo "$body" | grep -qi "$needle"; then
        pass "$label"
    else
        fail "$label (missing: $needle)"
    fi
}

test_header() {
    local url="$1"
    local header="$2"
    local label="$3"
    local headers
    headers=$(curl -sI "$url" 2>/dev/null)
    if echo "$headers" | grep -qi "$header"; then
        pass "$label"
    else
        fail "$label (missing header: $header)"
    fi
}

echo "=========================================="
echo -e "${BLUE}🧪 CodeReview.pl E2E Test Suite${NC}"
echo "Base URL: $BASE_URL"
echo "=========================================="
echo ""

# ─── 1. Route Status Tests ───
echo -e "${BLUE}1. Route Status Tests${NC}"
test_status "$BASE_URL/"             "200" "Homepage"
test_status "$BASE_URL/sesje"        "200" "Sessions page"
test_status "$BASE_URL/marketplace"  "200" "Marketplace page"
test_status "$BASE_URL/cennik"       "200" "Pricing page"
test_status "$BASE_URL/kontakt"      "200" "Contact page"
test_status "$BASE_URL/porownanie"   "200" "Comparison page"
test_status "$BASE_URL/api"          "200" "API docs page"
test_status "$BASE_URL/regulamin"    "200" "Terms page"
test_status "$BASE_URL/prywatnosc"   "200" "Privacy page"
test_status "$BASE_URL/dokumentacja" "200" "Docs page"
test_status "$BASE_URL/nonexistent"  "404" "404 for unknown route"
test_status "$BASE_URL/dashboard"    "401" "Dashboard requires auth"
echo ""

# ─── 2. Content Tests ───
echo -e "${BLUE}2. Content Tests${NC}"
test_content "$BASE_URL/" "CodeReview" "Homepage has brand name"
test_content "$BASE_URL/" "nav-links" "Homepage has navigation"
test_content "$BASE_URL/" "footer" "Homepage has footer"
test_content "$BASE_URL/marketplace" "mentor" "Marketplace has mentor content"
test_content "$BASE_URL/cennik" "PLN\|zł" "Pricing has currency"
test_content "$BASE_URL/kontakt" "form" "Contact has form"
test_content "$BASE_URL/api" "Stripe\|GitHub\|WebSocket" "API has integration docs"
test_content "$BASE_URL/sesje" "session\|sesj" "Sessions page has session content"
test_content "$BASE_URL/porownanie" "CodeReview" "Comparison mentions CodeReview"
echo ""

# ─── 3. i18n Tests ───
echo -e "${BLUE}3. i18n Tests${NC}"
test_content "$BASE_URL/?lang=pl" "Strona główna\|Cennik\|Kontakt" "Polish language works"
test_content "$BASE_URL/?lang=en" "Home\|Pricing\|Contact" "English language works"
test_content "$BASE_URL/?lang=pl" "lang-switcher\|PL\|EN" "Language switcher present (PL)"
test_content "$BASE_URL/sesje?lang=pl" "Sesje\|sesj" "Sessions page in Polish"
test_content "$BASE_URL/sesje?lang=en" "Session" "Sessions page in English"
echo ""

# ─── 4. Security Headers ───
echo -e "${BLUE}4. Security Headers${NC}"
test_header "$BASE_URL/" "X-Content-Type-Options" "X-Content-Type-Options header"
test_header "$BASE_URL/" "X-Frame-Options" "X-Frame-Options header"
test_header "$BASE_URL/" "X-XSS-Protection" "X-XSS-Protection header"
test_header "$BASE_URL/" "Referrer-Policy" "Referrer-Policy header"
echo ""

# ─── 5. Static Assets ───
echo -e "${BLUE}5. Static Assets${NC}"
test_status "$BASE_URL/assets/css/style.css" "200" "CSS file loads"
test_status "$BASE_URL/assets/js/main.js"    "200" "JS file loads"
echo ""

# ─── 6. Security - Blocked Paths ───
echo -e "${BLUE}6. Security - Blocked Paths${NC}"
test_status "$BASE_URL/includes/config.php" "403" "includes/ blocked"
test_status "$BASE_URL/docker/Dockerfile"   "403" "docker/ blocked"
test_status "$BASE_URL/.env"                "403" ".env blocked"
test_status "$BASE_URL/.git/config"         "403" ".git blocked"
echo ""

# ─── 7. Mobile / Responsive ───
echo -e "${BLUE}7. Mobile / Responsive${NC}"
test_content "$BASE_URL/" "viewport" "Viewport meta tag present"
test_content "$BASE_URL/" "mobile-menu-btn" "Mobile menu button present"
echo ""

# ─── 8. Sessions Board Functional Tests ───
echo -e "${BLUE}8. Sessions Board Functional Tests${NC}"
test_content "$BASE_URL/sesje" "create_session\|sessions_create" "Create session form present"
test_content "$BASE_URL/sesje" "sessions_topic\|topic" "Topic field present"

# Test session creation via POST
SESSION_RESPONSE=$(curl -s -o /dev/null -w "%{http_code}" -X POST \
    -d "create_session=1&host_name=TestUser&topic=E2E+Test+Session" \
    "$BASE_URL/sesje" 2>/dev/null)
if [ "$SESSION_RESPONSE" = "302" ] || [ "$SESSION_RESPONSE" = "200" ]; then
    pass "Session creation POST returns redirect/OK ($SESSION_RESPONSE)"
else
    fail "Session creation POST (expected 302/200, got $SESSION_RESPONSE)"
fi

# Verify session appears in listing
sleep 1
test_content "$BASE_URL/sesje" "E2E Test Session\|TestUser" "Created session visible in listing"
echo ""

# ─── 9. Performance ───
echo -e "${BLUE}9. Performance${NC}"
RESPONSE_TIME=$(curl -s -o /dev/null -w "%{time_total}" "$BASE_URL/" 2>/dev/null)
if (( $(echo "$RESPONSE_TIME < 2.0" | bc -l 2>/dev/null || echo 1) )); then
    pass "Homepage loads in ${RESPONSE_TIME}s (< 2s)"
else
    fail "Homepage slow: ${RESPONSE_TIME}s (> 2s)"
fi
echo ""

# ─── 10. Docker / Plesk Compatibility ───
echo -e "${BLUE}10. Docker / Plesk Compatibility${NC}"
DOCKER_STATUS=$(docker-compose ps --format "{{.Status}}" 2>/dev/null | head -1)
if echo "$DOCKER_STATUS" | grep -qi "up"; then
    pass "Docker containers running"
else
    fail "Docker containers not running"
fi

PHP_VERSION=$(docker-compose exec -T web php -r "echo PHP_VERSION;" 2>/dev/null)
if [ -n "$PHP_VERSION" ]; then
    pass "PHP available: $PHP_VERSION"
else
    fail "PHP not available in container"
fi

# Check Apache mod_rewrite
REWRITE_CHECK=$(docker-compose exec -T web apache2ctl -M 2>/dev/null | grep rewrite)
if [ -n "$REWRITE_CHECK" ]; then
    pass "Apache mod_rewrite enabled"
else
    fail "Apache mod_rewrite not enabled"
fi
echo ""

# ─── Summary ───
echo "=========================================="
echo -e "${BLUE}Test Summary${NC}"
echo "=========================================="
echo -e "Total:  $TOTAL"
echo -e "Passed: ${GREEN}$PASSED${NC}"
echo -e "Failed: ${RED}$FAILED${NC}"
echo ""

if [ $FAILED -eq 0 ]; then
    echo -e "${GREEN}🎉 All tests passed!${NC}"
    exit 0
else
    echo -e "${RED}⚠️  $FAILED test(s) failed${NC}"
    exit 1
fi
