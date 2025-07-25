<?php
// PWA Helper Functions
class PWAHelper {
    
    /**
     * Add PWA meta tags to HTML head
     */
    public static function addPWAHeaders() {
        echo '
        <!-- PWA Manifest -->
        <link rel="manifest" href="/manifest.json">
        
        <!-- Theme colors -->
        <meta name="theme-color" content="#E28585">
        <meta name="background-color" content="#FFE4EA">
        
        <!-- Modern PWA meta tags -->
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="mobile-web-app-status-bar-style" content="default">
        <meta name="mobile-web-app-title" content="GoFotobox">
        
        <!-- Apple specific (for backward compatibility) -->
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="default">
        <meta name="apple-mobile-web-app-title" content="GoFotobox">
        <link rel="apple-touch-icon" href="/src/assets/icons/photobooth-new-logo.png">
        
        <!-- Windows specific -->
        <meta name="msapplication-TileColor" content="#E28585">
        <meta name="msapplication-TileImage" content="/src/assets/icons/photobooth-new-logo.png">
        <meta name="msapplication-navbutton-color" content="#E28585">
        ';
    }
    
    /**
     * Add PWA JavaScript
     */
    public static function addPWAScript() {
        echo '
        <script>
            // Register service worker
            if ("serviceWorker" in navigator) {
                window.addEventListener("load", () => {
                    navigator.serviceWorker.register("/sw.js")
                        .then((registration) => {
                            console.log("SW registered: ", registration);
                        })
                        .catch((registrationError) => {
                            console.log("SW registration failed: ", registrationError);
                        });
                });
            }
            
            // Check if running as PWA
            function isPWA() {
                return window.matchMedia("(display-mode: standalone)").matches || 
                       window.navigator.standalone === true;
            }
            
            // Network status indicator
            function updateNetworkStatus() {
                if (!navigator.onLine) {
                    console.log("App is offline");
                    // Show offline message if needed
                }
            }
            
            window.addEventListener("online", updateNetworkStatus);
            window.addEventListener("offline", updateNetworkStatus);
            updateNetworkStatus();
        </script>
        ';
    }
    
    /**
     * Check if user is using PWA
     */
    public static function isPWARequest() {
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        return strpos($userAgent, 'GoFotobox') !== false || 
               isset($_SERVER['HTTP_X_REQUESTED_WITH']);
    }
}
?>
