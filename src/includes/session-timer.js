/**
 * Session Timer Client-Side Handler
 * Menangani timer 15 menit dari server dan sinkronisasi dengan backend
 */

class SessionTimer {
    constructor(options = {}) {
        this.timerElement = null;
        this.isRunning = false;
        this.timeRemaining = 0;
        this.intervalId = null;
        this.onExpired = options.onExpired || this.defaultExpiredHandler;
        this.onUpdate = options.onUpdate || null;
        this.checkInterval = options.checkInterval || 1000; // Check every second
        this.serverSyncInterval = options.serverSyncInterval || 30000; // Sync with server every 30 seconds
        this.syncIntervalId = null;
        this.currentPage = options.currentPage || 'unknown';
        
        this.init();
    }

    async init() {
        // Create timer display element
        this.createTimerDisplay();
        
        // Get initial time from server
        await this.syncWithServer();
        
        // Start the timer if there's time remaining
        if (this.timeRemaining > 0) {
            this.start();
        }
    }

    createTimerDisplay() {
        // Remove existing timer if any
        const existingTimer = document.getElementById('session-timer');
        if (existingTimer) {
            existingTimer.remove();
        }

        // Create timer container
        const timerContainer = document.createElement('div');
        timerContainer.id = 'session-timer';
        timerContainer.style.cssText = `
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: linear-gradient(135deg, #980000ff, #460801ff);
            color: white;
            padding: 10px 20px;
            border-radius: 25px;
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            font-size: 14px;
            z-index: 9999;
            box-shadow: 0 4px 15px rgba(231, 76, 60, 0.3);
            display: flex;
            align-items: center;
            gap: 8px;
            min-width: 140px;
            justify-content: center;
        `;

        // Add timer icon and text
        timerContainer.innerHTML = `
            <span style="font-size: 16px;">⏰</span>
            <span id="timer-text">--:--</span>
        `;

        document.body.appendChild(timerContainer);
        this.timerElement = timerContainer;
    }

    async syncWithServer() {
        try {
            const response = await fetch('/src/api-fetch/session_status.php', {
                method: 'GET',
                cache: 'no-cache'
            });

            if (response.ok) {
                const data = await response.json();
                
                if (data.success && data.session_active) {
                    this.timeRemaining = Math.max(0, data.time_remaining);
                    
                    // Update display
                    this.updateDisplay();
                } else {
                    // Session expired or invalid
                    this.timeRemaining = 0;
                    this.handleExpired();
                }
            } else {
                console.warn('Failed to sync with server');
            }
        } catch (error) {
            console.error('Error syncing with server:', error);
        }
    }

    start() {
        if (this.isRunning) return;

        this.isRunning = true;
        
        // Start countdown timer
        this.intervalId = setInterval(() => {
            this.timeRemaining = Math.max(0, this.timeRemaining - 1);
            this.updateDisplay();

            if (this.timeRemaining <= 0) {
                this.handleExpired();
            }
        }, this.checkInterval);

        // Start server sync timer
        this.syncIntervalId = setInterval(() => {
            this.syncWithServer();
        }, this.serverSyncInterval);

        // Initial display update
        this.updateDisplay();
    }

    stop() {
        this.isRunning = false;
        
        if (this.intervalId) {
            clearInterval(this.intervalId);
            this.intervalId = null;
        }

        if (this.syncIntervalId) {
            clearInterval(this.syncIntervalId);
            this.syncIntervalId = null;
        }
    }

    updateDisplay() {
        if (!this.timerElement) return;

        const timerText = document.getElementById('timer-text');
        if (timerText) {
            const minutes = Math.floor(this.timeRemaining / 60);
            const seconds = this.timeRemaining % 60;
            timerText.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
        }

        // Change color when time is running low
        if (this.timeRemaining <= 300) { // 5 minutes or less
            this.timerElement.style.background = 'linear-gradient(135deg, #FF4757, #FF3742)';
        } else if (this.timeRemaining <= 600) { // 10 minutes or less
            this.timerElement.style.background = 'linear-gradient(135deg, #FFA726, #FF9800)';
        }

        // Call custom update handler if provided
        if (this.onUpdate) {
            this.onUpdate(this.timeRemaining);
        }
    }

    handleExpired() {
        this.stop();
        this.onExpired(this.currentPage);
    }

    defaultExpiredHandler(page) {
        // Show expiration modal
        this.showExpirationModal(page);
    }

    showExpirationModal(page) {
        // Remove existing modal if any
        const existingModal = document.getElementById('session-expired-modal');
        if (existingModal) {
            existingModal.remove();
        }

        // Create modal backdrop
        const modalBackdrop = document.createElement('div');
        modalBackdrop.id = 'session-expired-modal';
        modalBackdrop.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10000;
            font-family: 'Poppins', sans-serif;
        `;

        // Create modal content
        const modalContent = document.createElement('div');
        modalContent.style.cssText = `
            background: white;
            padding: 2rem;
            border-radius: 20px;
            text-align: center;
            max-width: 400px;
            width: 90%;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        `;

        // Determine message based on page
        let message = 'Waktu sesi Anda telah habis. Silakan mulai sesi baru.';
        let buttonText = 'Mulai Baru';
        
        if (page === 'canvas' || page === 'photo') {
            if (window.photoCount && window.photoCount > 0) {
                // User has taken some photos, redirect to customize
                this.redirectToCustomize();
                return;
            } else {
                message = 'Waktu sesi Anda telah habis. Anda belum mengambil foto apapun.';
            }
        } else if (page === 'customize') {
            // From customize page, go to thank you
            this.redirectToThankYou();
            return;
        }

        modalContent.innerHTML = `
            <div style="font-size: 3rem; margin-bottom: 1rem;">⏰</div>
            <h2 style="color: #333; margin-bottom: 1rem; font-size: 1.5rem;">Sesi Berakhir</h2>
            <p style="color: #666; margin-bottom: 2rem; line-height: 1.5;">${message}</p>
            <button id="session-expired-btn" style="
                background: linear-gradient(135deg, #E28585, #FF6B9D);
                color: white;
                border: none;
                padding: 12px 30px;
                border-radius: 25px;
                font-weight: 600;
                font-size: 1.1rem;
                cursor: pointer;
                transition: all 0.3s ease;
            ">${buttonText}</button>
        `;

        modalBackdrop.appendChild(modalContent);
        document.body.appendChild(modalBackdrop);

        // Add button event listener
        document.getElementById('session-expired-btn').addEventListener('click', () => {
            this.resetSessionAndRedirect();
        });

        // Prevent modal from being closed by clicking outside
        modalBackdrop.addEventListener('click', (e) => {
            if (e.target === modalBackdrop) {
                e.preventDefault();
            }
        });
    }

    async resetSessionAndRedirect() {
        try {
            // Reset session on server
            await fetch('/src/api-fetch/reset_session.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ action: 'reset' })
            });
        } catch (error) {
            console.error('Error resetting session:', error);
        }

        // Redirect to index
        window.location.href = '/index.php';
    }

    redirectToCustomize() {
        // Get current layout from URL or session
        const currentUrl = window.location.pathname;
        const layoutMatch = currentUrl.match(/canvasLayout(\d+)/);
        const layoutNumber = layoutMatch ? layoutMatch[1] : '1';
        
        window.location.href = `customizeLayout${layoutNumber}.php`;
    }

    redirectToThankYou() {
        window.location.href = 'thankyou.php';
    }

    // Public method to get remaining time
    getTimeRemaining() {
        return this.timeRemaining;
    }

    // Public method to check if session is active
    isSessionActive() {
        return this.timeRemaining > 0;
    }
}

// Auto-initialize timer when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Only initialize if we're on a page that needs timer
    const currentPath = window.location.pathname;
    const needsTimer = [
        'selectpayment.php',
        'payment-qris.php', 
        'payment-bank.php',
        'selectlayout.php',
        'canvas',
        'customize'
    ].some(page => currentPath.includes(page));

    if (needsTimer) {
        // Determine current page type
        let pageType = 'unknown';
        if (currentPath.includes('payment')) pageType = 'payment';
        else if (currentPath.includes('selectlayout')) pageType = 'layout';
        else if (currentPath.includes('canvas')) pageType = 'canvas';
        else if (currentPath.includes('customize')) pageType = 'customize';

        // Initialize timer
        window.sessionTimer = new SessionTimer({
            currentPage: pageType,
            onExpired: function(page) {
                // Handle different expiration scenarios based on page
                window.sessionTimer.defaultExpiredHandler(page);
            }
        });
    }
});

// Export for use in other scripts
if (typeof module !== 'undefined' && module.exports) {
    module.exports = SessionTimer;
}
