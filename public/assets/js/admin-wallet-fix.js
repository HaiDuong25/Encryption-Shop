// Admin fix for pixelstrap issues
document.addEventListener('DOMContentLoaded', function() {
    // Remove any pixelstrap content from breadcrumb and other elements
    const elementsToCheck = document.querySelectorAll('*');
    elementsToCheck.forEach(function(element) {
        // Check text content
        if (element.textContent && 
            (element.textContent.includes('pixelstrap') || 
             element.textContent.includes('themes.pixelstrap') ||
             element.textContent.includes('https://themes.'))) {
            
            // If it's a text node, replace the content
            const walker = document.createTreeWalker(
                element,
                NodeFilter.SHOW_TEXT,
                null,
                false
            );

            const textNodes = [];
            let node;
            while (node = walker.nextNode()) {
                textNodes.push(node);
            }

            textNodes.forEach(function(textNode) {
                if (textNode.textContent && 
                    (textNode.textContent.includes('pixelstrap') ||
                     textNode.textContent.includes('themes.') ||
                     textNode.textContent.includes('https://themes'))) {
                    textNode.textContent = '';
                }
            });
        }
    });

    // Fix breadcrumb display specifically
    const breadcrumbs = document.querySelectorAll('.breadcrumb');
    breadcrumbs.forEach(function(breadcrumb) {
        const items = breadcrumb.querySelectorAll('.breadcrumb-item');
        items.forEach(function(item, index) {
            // Remove any unwanted text content
            const children = Array.from(item.childNodes);
            children.forEach(function(child) {
                if (child.nodeType === Node.TEXT_NODE && 
                    (child.textContent.includes('https://') || 
                     child.textContent.includes('pixelstrap') ||
                     child.textContent.includes('themes.'))) {
                    child.remove();
                }
            });
        });
    });

    // Enhance transaction display
    enhanceTransactionDisplay();
});

function enhanceTransactionDisplay() {
    // Add smooth animations to cards
    const cards = document.querySelectorAll('.card');
    cards.forEach(function(card, index) {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'all 0.6s ease';
        
        setTimeout(function() {
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });

    // Add hover effects to buttons
    const buttons = document.querySelectorAll('.btn');
    buttons.forEach(function(button) {
        button.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
            this.style.boxShadow = '0 4px 15px rgba(0,0,0,0.2)';
        });
        
        button.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = '';
        });
    });
}
