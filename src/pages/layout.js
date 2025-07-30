document.addEventListener('DOMContentLoaded', () => { 
    // Popup functionality
    let selectedLayout = '';
    const layoutPopup = document.getElementById('layoutPopup');
    const confirmBtn = document.getElementById('confirmBtn');
    const closeBtn = document.getElementById('closeBtn');

    // Add click event listeners to all layout buttons
    document.querySelectorAll('.layout-card').forEach(button => {
        button.addEventListener('click', function() {
            selectedLayout = this.id.replace('Btn', '');
            layoutPopup.style.display = 'flex';
        });
    });

    // Close popup
    if (closeBtn) {
        closeBtn.addEventListener('click', () => {
            layoutPopup.style.display = 'none';
        });
    }

    // Close popup when clicking outside
    layoutPopup.addEventListener('click', (e) => {
        if (e.target === layoutPopup) {
            layoutPopup.style.display = 'none';
        }
    });

    // Confirm button - create new session and navigate
    confirmBtn.addEventListener('click', function() {
        // Create photo session
        fetch('../api-fetch/create_photo_session.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ layout: selectedLayout })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Navigate to appropriate canvas page
                let targetPage = '';
                switch(selectedLayout) {
                    case 'layout1': targetPage = 'canvasLayout1.php'; break;
                    case 'layout2': targetPage = 'canvasLayout2.php'; break;
                    case 'layout3': targetPage = 'canvasLayout3.php'; break;
                    case 'layout4': targetPage = 'canvasLayout4.php'; break;
                    case 'layout5': targetPage = 'canvasLayout5.php'; break;
                    case 'layout6': targetPage = 'canvasLayout6.php'; break;
                    default: targetPage = 'canvasLayout2.php'; break;
                }
                window.location.href = targetPage;
            } else {
                alert('Failed to create photo session. Please try again.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error creating session. Please try again.');
        });
    });

    // Legacy direct navigation removed to prevent conflicts with popup system
});