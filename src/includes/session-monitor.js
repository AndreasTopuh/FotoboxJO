// Session monitoring untuk halaman canvas/customize
class SessionMonitor {
    constructor() {
        this.intervalId = null;
        this.warningShown = false;
        this.checkInterval = 15000; // Check setiap 15 detik
    }

    start() {
        // Start monitoring
        this.intervalId = setInterval(() => {
            this.checkSession();
        }, this.checkInterval);
        
        // Initial check
        this.checkSession();
    }

    stop() {
        if (this.intervalId) {
            clearInterval(this.intervalId);
            this.intervalId = null;
        }
    }

    async checkSession() {
        try {
            const response = await fetch('../api-fetch/session_status.php');
            const data = await response.json();
            
            if (!data.valid || data.expired) {
                this.handleSessionExpired();
                return;
            }
            
            // Check if session will expire soon (5 minutes)
            if (data.time_remaining <= 300 && !this.warningShown) {
                this.showWarning(data.time_remaining);
            }
            
        } catch (error) {
            console.error('Session monitoring error:', error);
        }
    }

    handleSessionExpired() {
        this.stop();
        
        // Show modal or alert
        const modal = this.createExpiredModal();
        document.body.appendChild(modal);
        modal.style.display = 'flex';
    }

    showWarning(timeRemaining) {
        this.warningShown = true;
        const minutes = Math.floor(timeRemaining / 60);
        
        const warning = this.createWarningModal(minutes);
        document.body.appendChild(warning);
        warning.style.display = 'flex';
        
        // Auto hide after 5 seconds
        setTimeout(() => {
            warning.remove();
            this.warningShown = false;
        }, 5000);
    }

    createExpiredModal() {
        const modal = document.createElement('div');
        modal.className = 'session-modal-overlay';
        modal.innerHTML = `
            <div class="session-modal-content">
                <h3>Session Berakhir</h3>
                <p>Session Anda telah berakhir. Silakan mulai ulang dari halaman utama.</p>
                <button onclick="this.redirectToHome()" class="session-btn-primary">Kembali ke Beranda</button>
            </div>
        `;
        
        // Add event listener to button
        modal.querySelector('button').onclick = this.redirectToHome;
        
        return modal;
    }

    createWarningModal(minutes) {
        const modal = document.createElement('div');
        modal.className = 'session-modal-overlay session-warning';
        modal.innerHTML = `
            <div class="session-modal-content">
                <h3>⚠️ Peringatan Session</h3>
                <p>Session akan berakhir dalam ${minutes} menit.</p>
                <div class="session-buttons">
                    <button onclick="this.parentElement.parentElement.parentElement.remove()" class="session-btn-secondary">OK</button>
                </div>
            </div>
        `;
        
        return modal;
    }

    redirectToHome() {
        fetch('../api-fetch/reset_session.php', { method: 'POST' })
            .then(() => {
                window.location.href = '/index.php';
            })
            .catch(() => {
                window.location.href = '/index.php';
            });
    }
}

// CSS untuk modal
const sessionModalCSS = `
    .session-modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.8);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 10000;
    }

    .session-modal-content {
        background: white;
        padding: 2rem;
        border-radius: 10px;
        text-align: center;
        max-width: 400px;
        margin: 0 1rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
    }

    .session-modal-content h3 {
        color: #ff4444;
        margin-bottom: 1rem;
    }

    .session-warning .session-modal-content h3 {
        color: #ff8800;
    }

    .session-buttons {
        display: flex;
        gap: 1rem;
        margin-top: 1.5rem;
        justify-content: center;
    }

    .session-btn-primary, .session-btn-secondary {
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-weight: 600;
        font-size: 1rem;
    }

    .session-btn-primary {
        background: #007bff;
        color: white;
    }

    .session-btn-primary:hover {
        background: #0056b3;
    }

    .session-btn-secondary {
        background: #6c757d;
        color: white;
    }

    .session-btn-secondary:hover {
        background: #545b62;
    }
`;

// Inject CSS
const style = document.createElement('style');
style.textContent = sessionModalCSS;
document.head.appendChild(style);

// Auto-start session monitoring when script loads
const sessionMonitor = new SessionMonitor();

// Start monitoring when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        sessionMonitor.start();
    });
} else {
    sessionMonitor.start();
}

// Stop monitoring when page unloads
window.addEventListener('beforeunload', () => {
    sessionMonitor.stop();
});

// Export untuk penggunaan manual jika diperlukan
window.SessionMonitor = SessionMonitor;
window.sessionMonitor = sessionMonitor;
