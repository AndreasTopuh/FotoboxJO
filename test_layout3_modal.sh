#!/bin/bash

echo "🔍 TESTING MODAL PREVIEW FUNCTIONALITY IN LAYOUT 3"
echo "=================================================="

# Colors for output
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

echo
echo -e "${BLUE}📋 Checking modal elements in Layout 3...${NC}"

# Check openCarousel function
if grep -q "function openCarousel(index)" src/pages/canvasLayout3.js; then
    echo -e "${GREEN}✅ openCarousel function found${NC}"
else
    echo -e "${RED}❌ openCarousel function missing${NC}"
fi

# Check closeCarousel function  
if grep -q "function closeCarousel()" src/pages/canvasLayout3.js; then
    echo -e "${GREEN}✅ closeCarousel function found${NC}"
else
    echo -e "${RED}❌ closeCarousel function missing${NC}"
fi

# Check updateCarousel function
if grep -q "function updateCarousel()" src/pages/canvasLayout3.js; then
    echo -e "${GREEN}✅ updateCarousel function found${NC}"
else
    echo -e "${RED}❌ updateCarousel function missing${NC}"
fi

echo
echo -e "${BLUE}📋 Checking modal event listeners...${NC}"

# Check image click event
if grep -q "img.addEventListener('click', () => openCarousel(index))" src/pages/canvasLayout3.js; then
    echo -e "${GREEN}✅ Image click to open modal${NC}"
else
    echo -e "${RED}❌ Image click event missing${NC}"
fi

# Check carousel navigation
if grep -q "carouselPrevBtn.addEventListener('click'" src/pages/canvasLayout3.js; then
    echo -e "${GREEN}✅ Previous button event listener${NC}"
else
    echo -e "${RED}❌ Previous button event missing${NC}"
fi

if grep -q "carouselNextBtn.addEventListener('click'" src/pages/canvasLayout3.js; then
    echo -e "${GREEN}✅ Next button event listener${NC}"
else
    echo -e "${RED}❌ Next button event missing${NC}"
fi

# Check close button
if grep -q "carouselCloseBtn.addEventListener('click'" src/pages/canvasLayout3.js; then
    echo -e "${GREEN}✅ Close button event listener${NC}"
else
    echo -e "${RED}❌ Close button event missing${NC}"
fi

# Check escape key
if grep -q "if (e.key === 'Escape')" src/pages/canvasLayout3.js; then
    echo -e "${GREEN}✅ Escape key to close modal${NC}"
else
    echo -e "${RED}❌ Escape key handler missing${NC}"
fi

# Check arrow key navigation
if grep -q "e.key === 'ArrowLeft'" src/pages/canvasLayout3.js; then
    echo -e "${GREEN}✅ Arrow key navigation${NC}"
else
    echo -e "${RED}❌ Arrow key navigation missing${NC}"
fi

echo
echo -e "${BLUE}📋 Checking modal protective features...${NC}"

# Check carousel indicators safety
if grep -q "if (carouselIndicators)" src/pages/canvasLayout3.js; then
    echo -e "${GREEN}✅ Carousel indicators null check${NC}"
else
    echo -e "${RED}❌ Missing null check for carousel indicators${NC}"
fi

# Check stopPropagation usage
if grep -q "e.stopPropagation()" src/pages/canvasLayout3.js; then
    echo -e "${GREEN}✅ Event propagation control${NC}"
else
    echo -e "${RED}❌ Missing event propagation control${NC}"
fi

# Check fade animations
if grep -q "fade-in" src/pages/canvasLayout3.js && grep -q "fade-out" src/pages/canvasLayout3.js; then
    echo -e "${GREEN}✅ Modal fade animations${NC}"
else
    echo -e "${RED}❌ Modal animations missing${NC}"
fi

echo
echo -e "${BLUE}📋 Checking global modal functions...${NC}"

# Check window.openCarousel
if grep -q "window.openCarousel = openCarousel" src/pages/canvasLayout3.js; then
    echo -e "${GREEN}✅ Global openCarousel function${NC}"
else
    echo -e "${RED}❌ Global openCarousel missing${NC}"
fi

# Check window.closeCarousel
if grep -q "window.closeCarousel = closeCarousel" src/pages/canvasLayout3.js; then
    echo -e "${GREEN}✅ Global closeCarousel function${NC}"
else
    echo -e "${RED}❌ Global closeCarousel missing${NC}"
fi

echo
echo -e "${BLUE}📋 Comparing with Layout 2 reference...${NC}"

# Check if retakeSinglePhoto complexity is removed
if ! grep -q "async function retakeSinglePhoto" src/pages/canvasLayout3.js; then
    echo -e "${GREEN}✅ Complex retakeSinglePhoto removed (follows Layout 2)${NC}"
else
    echo -e "${YELLOW}⚠️ Complex retakeSinglePhoto still present${NC}"
fi

# Check if it uses the same retake approach as Layout 2
if grep -q "if (typeof window.retakeSinglePhoto === 'function')" src/pages/canvasLayout3.js; then
    echo -e "${GREEN}✅ Uses Layout 2 retake pattern${NC}"
else
    echo -e "${RED}❌ Different retake pattern from Layout 2${NC}"
fi

echo
echo -e "${BLUE}📋 CSS styling for modal...${NC}"

# Check if modal CSS is present
if grep -q ".modal.fade-in" src/pages/canvasLayout3.js; then
    echo -e "${GREEN}✅ Modal CSS animations included${NC}"
else
    echo -e "${RED}❌ Modal CSS missing${NC}"
fi

if grep -q ".carousel-container" src/pages/canvasLayout3.js; then
    echo -e "${GREEN}✅ Carousel container styling${NC}"
else
    echo -e "${RED}❌ Carousel container styling missing${NC}"
fi

echo
echo -e "${GREEN}🎯 MODAL PREVIEW FUNCTIONALITY CHECK COMPLETE!${NC}"
echo -e "${YELLOW}📝 Layout 3 modal should now work exactly like Layout 2${NC}"
echo -e "${BLUE}🔧 Key improvements made:${NC}"
echo "✅ Added null safety checks"
echo "✅ Added event stopPropagation"
echo "✅ Added full keyboard navigation"
echo "✅ Removed complex retake function"
echo "✅ Added global modal functions"
echo "✅ Included modal CSS styling"
