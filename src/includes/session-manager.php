<?php
class SessionManager {
    
    // Timer constants (semua 10 menit)
    const SESSION_TIMEOUT = 600; // 10 menit dalam detik (total session time)
    const PAYMENT_TIMEOUT = 600; // 10 menit untuk pembayaran
    const LAYOUT_TIMEOUT = 600; // 10 menit untuk layout selection
    
    // Session states - progressive flow
    const STATE_START = 'start';
    const STATE_PAYMENT_PENDING = 'payment_pending'; 
    const STATE_PAYMENT_COMPLETED = 'payment_completed';
    const STATE_LAYOUT_SELECTED = 'layout_selected';
    const STATE_PHOTO_SESSION = 'photo_session';
    const STATE_EXPIRED = 'expired';
    
    public static function initializeSession() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    public static function setSessionState($state) {
        self::initializeSession();
        $_SESSION['session_state'] = $state;
        $_SESSION['state_updated_at'] = time();
        
        // Log state transition
        error_log("Session state changed to: $state at " . date('Y-m-d H:i:s'));
    }
    
    public static function getSessionState() {
        self::initializeSession();
        return $_SESSION['session_state'] ?? self::STATE_START;
    }
    
    public static function canAccessPaymentSelection() {
        $state = self::getSessionState();
        return in_array($state, [self::STATE_START, self::STATE_PAYMENT_PENDING]);
    }
    
    public static function canAccessPaymentPage() {
        $state = self::getSessionState();
        return $state === self::STATE_PAYMENT_PENDING;
    }
    
    public static function canAccessLayoutSelection() {
        $state = self::getSessionState();
        return in_array($state, [self::STATE_PAYMENT_COMPLETED, self::STATE_LAYOUT_SELECTED]);
    }
    
    public static function canAccessCanvas() {
        $state = self::getSessionState();
        return in_array($state, [self::STATE_LAYOUT_SELECTED, self::STATE_PHOTO_SESSION]);
    }
    
    public static function startPaymentSession() {
        // Reset session sebelumnya
        session_unset();
        session_destroy();
        session_start();
        
    // Set session dengan timer 10 menit total
        $_SESSION['session_type'] = 'payment';
        $_SESSION['session_start'] = time();
    $_SESSION['session_expires'] = time() + self::SESSION_TIMEOUT; // 10 menit total
        $_SESSION['payment_completed'] = false;
        $_SESSION['main_timer_start'] = time(); // Main timer dimulai saat pilih metode pembayaran
        
        self::setSessionState(self::STATE_PAYMENT_PENDING);
        
        return $_SESSION;
    }
    
    public static function completePayment($orderId = null) {
        if (!self::isValidSession()) {
            return false;
        }
        
        // Mark payment as completed - timer tetap menggunakan main timer (tidak direset)
        $_SESSION['payment_completed'] = true;
        $_SESSION['payment_completed_at'] = time();
        $_SESSION['session_type'] = 'layout_selection';
    // Tidak mengubah session_expires karena tetap menggunakan timer 10 menit dari awal
        
        if ($orderId) {
            $_SESSION['order_id'] = $orderId;
        }
        
        self::setSessionState(self::STATE_PAYMENT_COMPLETED);
        
        return true;
    }
    
    public static function selectLayout($layoutId) {
        if (!self::canAccessLayoutSelection()) {
            return false;
        }
        
        $_SESSION['selected_layout'] = $layoutId;
        $_SESSION['layout_selected_at'] = time();
    // Tidak mengubah session_expires karena tetap menggunakan timer 10 menit dari awal
        
        self::setSessionState(self::STATE_LAYOUT_SELECTED);
        
        return true;
    }
    
    public static function startPhotoSession() {
        if (!self::canAccessCanvas()) {
            return false;
        }
        
        self::setSessionState(self::STATE_PHOTO_SESSION);
        return true;
    }
    
    public static function isValidSession() {
        self::initializeSession();
        
        if (!isset($_SESSION['session_state']) || !isset($_SESSION['session_expires'])) {
            return false;
        }
        
        // Check if session has expired
        if (time() > $_SESSION['session_expires']) {
            self::setSessionState(self::STATE_EXPIRED);
            self::destroySession();
            return false;
        }
        
        return true;
    }
    
    // Validation methods with proper state checking
    public static function requireValidPaymentSession() {
        if (!self::canAccessPaymentPage()) {
            $currentState = self::getSessionState();
            
            if ($currentState === self::STATE_PAYMENT_COMPLETED) {
                // Already paid, redirect to layout selection
                header('Location: selectlayout.php');
                exit();
            } elseif ($currentState === self::STATE_LAYOUT_SELECTED) {
                // Layout already selected, redirect to layout (let user re-select if needed)
                header('Location: selectlayout.php');
                exit();
            } elseif ($currentState === self::STATE_PHOTO_SESSION) {
                // Already in photo session
                header('Location: selectlayout.php');
                exit();
            } else {
                // Invalid state, reset and go to start
                self::resetSession();
                header('Location: /index.php');
                exit();
            }
        }
        
        // Additional check for session validity
        if (!self::isValidSession()) {
            self::resetSession();
            header('Location: /index.php');
            exit();
        }
        
        return true;
    }
    
    public static function requirePayment() {
        if (!self::canAccessLayoutSelection()) {
            $currentState = self::getSessionState();
            
            if ($currentState === self::STATE_START || $currentState === self::STATE_PAYMENT_PENDING) {
                // Need to pay first
                header('Location: selectpayment.php');
                exit();
            } else {
                // Invalid state, reset
                self::resetSession();
                header('Location: /index.php');
                exit();
            }
        }
        
        // Additional check for session validity
        if (!self::isValidSession()) {
            self::resetSession();
            header('Location: /index.php');
            exit();
        }
        
        return true;
    }
    
    public static function requireCanvasAccess() {
        if (!self::canAccessCanvas()) {
            $currentState = self::getSessionState();
            
            if ($currentState === self::STATE_START || $currentState === self::STATE_PAYMENT_PENDING) {
                header('Location: selectpayment.php');
                exit();
            } elseif ($currentState === self::STATE_PAYMENT_COMPLETED) {
                header('Location: selectlayout.php');
                exit();
            } else {
                self::resetSession();
                header('Location: /index.php');
                exit();
            }
        }
        
        // Additional check for session validity
        if (!self::isValidSession()) {
            self::resetSession();
            header('Location: /index.php');
            exit();
        }
        
        return true;
    }
    
    public static function getTimeRemaining() {
        if (!self::isValidSession()) {
            return 0;
        }
        
        $remaining = $_SESSION['session_expires'] - time();
        return max(0, $remaining);
    }
    
    public static function getMainTimerRemaining() {
        self::initializeSession();
        
        if (!isset($_SESSION['main_timer_start']) || !isset($_SESSION['session_expires'])) {
            return 0;
        }
        
        $remaining = $_SESSION['session_expires'] - time();
        return max(0, $remaining);
    }
    
    public static function isMainTimerExpired() {
        return self::getMainTimerRemaining() <= 0;
    }
    
    public static function destroySession() {
        session_unset();
        session_destroy();
    }
    
    public static function resetSession() {
        self::destroySession();
        session_start();
        self::setSessionState(self::STATE_START);
    }
    
    public static function redirectToIndex() {
        self::resetSession();
        header('Location: /index.php');
        exit();
    }
    
    public static function redirectToPayment() {
        header('Location: /src/pages/selectpayment.php');
        exit();
    }
    
    public static function getSessionInfo() {
        if (!self::isValidSession()) {
            return null;
        }
        
        return [
            'session_state' => self::getSessionState(),
            'session_type' => $_SESSION['session_type'] ?? null,
            'time_remaining' => self::getTimeRemaining(),
            'payment_completed' => $_SESSION['payment_completed'] ?? false,
            'order_id' => $_SESSION['order_id'] ?? null,
            'selected_layout' => $_SESSION['selected_layout'] ?? null,
            'expires_at' => $_SESSION['session_expires'] ?? null,
            'state_updated_at' => $_SESSION['state_updated_at'] ?? null
        ];
    }
    
    public static function getStateDisplayName($state = null) {
        $state = $state ?? self::getSessionState();
        
        $displayNames = [
            self::STATE_START => 'Belum Mulai',
            self::STATE_PAYMENT_PENDING => 'Menunggu Pembayaran',
            self::STATE_PAYMENT_COMPLETED => 'Pembayaran Selesai',
            self::STATE_LAYOUT_SELECTED => 'Layout Dipilih',
            self::STATE_PHOTO_SESSION => 'Sesi Foto',
            self::STATE_EXPIRED => 'Session Berakhir'
        ];
        
        return $displayNames[$state] ?? 'Unknown State';
    }
}
?>
