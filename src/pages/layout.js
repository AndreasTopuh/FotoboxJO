document.addEventListener('DOMContentLoaded', () => { 
    // Popup functionality
    let selectedLayout = '';
    const layoutPopup = document.getElementById('layoutPopup');
    const confirmBtn = document.getElementById('confirmBtn');
    const closeBtn = document.getElementById('closeBtn');

    // Add click event listeners to all layout buttons
    document.querySelectorAll('.layout-holder').forEach(button => {
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
                    case 'canvas': targetPage = 'canvas.php'; break;
                    default: targetPage = 'canvas.php'; break;
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

    // Legacy direct navigation (backup)
    const layoutBtn1 = document.querySelector('#layout1Btn')
    const layoutBtn2 = document.querySelector('#layout2Btn')
    const layoutBtn3 = document.querySelector('#layout3Btn')
    const layoutBtn4 = document.querySelector('#layout4Btn')
    const layoutBtn5 = document.querySelector('#layout5Btn')
    const layoutBtn6 = document.querySelector('#layout6Btn')

    // Original Canvas Buttons
    const canvas2Btn = document.querySelector('#canvas2Btn')
    const canvas4Btn = document.querySelector('#canvas4Btn')
    const canvas6Btn = document.querySelector('#canvas6Btn')

    if (canvas2Btn) {
        canvas2Btn.addEventListener('click', (e) => {
            e.preventDefault()
            window.location.href = './canvas2.php' // Canvas 2 - 2 photos
        })
    }

    if (canvas4Btn) {
        canvas4Btn.addEventListener('click', (e) => {
            e.preventDefault()
            window.location.href = './canvas4.php' // Canvas 4 - 4 photos
        })
    }

    if (canvas6Btn) {
        canvas6Btn.addEventListener('click', (e) => {
            e.preventDefault()
            window.location.href = './canvas6.php' // Canvas 6 - 6 photos
        })
    }
});