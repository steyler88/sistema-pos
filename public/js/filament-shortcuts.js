// Keyboard shortcuts for Filament Admin Panel
document.addEventListener('keydown', function(event) {
    // Check if F2 is pressed
    if (event.key === 'F2') {
        event.preventDefault(); // Prevent default browser action
        
        console.log('F2 pressed - searching for New Order button...');
        
        // Find the "New order" button using the selector from inspection
        let newOrderButton = document.querySelector('a[href*="/orders/create"]');
        
        if (newOrderButton) {
            console.log('Found New Order button:', newOrderButton);
            newOrderButton.click();
            console.log('✓ New Order button clicked successfully');
        } else {
            console.warn('✗ New Order button not found');
            console.log('Available links in page:', 
                Array.from(document.querySelectorAll('a[href*="create"]')).map(el => ({
                    href: el.href,
                    text: el.textContent.trim()
                }))
            );
        }
    }
});

console.log('✓ Filament keyboard shortcuts loaded: Press F2 to create New Order');

