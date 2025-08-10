// Fix breadcrumb display issues
document.addEventListener('DOMContentLoaded', function() {
    // Remove any pixelstrap content from breadcrumb
    const breadcrumbs = document.querySelectorAll('.breadcrumb');
    breadcrumbs.forEach(function(breadcrumb) {
        // Remove any text nodes containing pixelstrap
        const walker = document.createTreeWalker(
            breadcrumb,
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
            if (textNode.textContent && textNode.textContent.includes('pixelstrap')) {
                textNode.textContent = '';
            }
            if (textNode.textContent && textNode.textContent.includes('themes.')) {
                textNode.textContent = '';
            }
        });

        // Also check for any elements with pixelstrap content
        const allElements = breadcrumb.querySelectorAll('*');
        allElements.forEach(function(element) {
            if (element.textContent && element.textContent.includes('pixelstrap')) {
                element.style.display = 'none';
            }
        });
    });

    // Ensure breadcrumb items display correctly
    const breadcrumbItems = document.querySelectorAll('.breadcrumb-item');
    breadcrumbItems.forEach(function(item, index) {
        // Remove any unwanted content
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
