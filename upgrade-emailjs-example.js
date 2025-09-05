/**
 * UPGRADE EXAMPLE: Enhanced EmailJS Implementation
 * File: customizeLayout1.js (Enhanced Version)
 * 
 * Tambahkan setelah line CONFIG dan sebelum state variables
 */

// Enhanced email configuration dengan EmailJS Helper
const EMAIL_CONFIG = {
    ...CONFIG, // Keep existing config
    // Enhanced email settings
    MAX_EMAIL_LENGTH: 254,
    MIN_EMAIL_LENGTH: 5,
    RETRY_ATTEMPTS: 3,
    TIMEOUT_MS: 30000
};

// Load EmailJS Helper
// Pastikan script ini dimuat di HTML: <script src="/src/assets/js/emailjs-helper.js"></script>

/**
 * Enhanced validateEmail function (replace existing)
 */
function validateEmail(email) {
    // Gunakan EmailJS Helper untuk validasi yang lebih comprehensive
    if (typeof window.emailJSHelper === 'undefined') {
        console.warn('EmailJS Helper not loaded, using basic validation');
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }
    
    const validation = window.emailJSHelper.validateEmail(email);
    
    // Show suggestions if email has typos
    if (!validation.isValid && validation.suggestions.length > 0) {
        const suggestion = validation.suggestions[0];
        const message = `${validation.error}\n\nMungkin maksud Anda: ${suggestion}?`;
        
        // Show suggestion to user
        showValidationError(message);
        
        // Auto-correct if user wants
        setTimeout(() => {
            if (confirm(`Auto-correct ke ${suggestion}?`)) {
                const emailInput = document.getElementById('emailInput');
                if (emailInput) {
                    emailInput.value = suggestion;
                    hideValidationError();
                }
            }
        }, 2000);
        
        return false;
    }
    
    if (!validation.isValid) {
        showValidationError(validation.error);
        return false;
    }
    
    // Show success for Indonesian domains
    if (validation.emailType === 'indonesian') {
        console.log('✅ Indonesian email domain detected');
    }
    
    return true;
}

/**
 * Enhanced sendPhotoEmail function (replace existing)
 */
async function sendPhotoEmail(email) {
    if (!state.finalCanvas) {
        handleError('Tidak ada foto untuk dikirim', 'alert');
        return;
    }

    // Validate email using enhanced validation
    if (!validateEmail(email)) {
        return;
    }

    const sendBtn = document.getElementById('sendEmailBtn');
    const originalHtml = sendBtn.innerHTML;
    sendBtn.disabled = true;

    try {
        console.log('📧 Starting enhanced email process...');
        
        // Update UI
        sendBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Generating High Quality...';
        
        // Generate high quality version
        const highQualityCanvas = await generateHighQualityCanvas();
        
        sendBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Preparing for Email...';
        
        // Enhanced image quality for email
        const blob = await new Promise((resolve) => {
            highQualityCanvas.toBlob(resolve, 'image/jpeg', 0.98);
        });
        
        const base64data = await new Promise((resolve) => {
            const reader = new FileReader();
            reader.onloadend = () => resolve(reader.result);
            reader.readAsDataURL(blob);
        });

        sendBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving High Quality Photo...';

        // Save photo with enhanced metadata
        const saveResponse = await fetch('../api-fetch/save_final_photo_v2.php', {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/json', 
                'Accept': 'application/json' 
            },
            body: JSON.stringify({ 
                image: base64data,
                quality: 'high',
                source: 'customize_high_quality_enhanced',
                email_target: email, // Add email for tracking
                timestamp: new Date().toISOString()
            }),
        });

        const saveText = await saveResponse.text();
        if (saveText.trim().startsWith('<') || saveText.includes('<br />')) {
            throw new Error('Server returned HTML error page');
        }

        const saveData = JSON.parse(saveText);
        if (!saveData.success) {
            throw new Error(saveData.message || 'Server error');
        }

        sendBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending Enhanced Email...';

        // Enhanced email data
        const photoLink = window.location.origin + saveData.url;
        const emailData = {
            to_email: email,
            link: photoLink,
            photo_link: photoLink,
            url: photoLink,
            name: 'Sobat',
            user_name: 'Sobat',
            to_name: 'Sobat',
            from_name: 'GOFOTOBOX - HIGH QUALITY ENHANCED',
            message: `Halo Sobat! Foto berkualitas tinggi Anda telah siap: ${photoLink}`,
            subject: '📸 Foto Berkualitas Tinggi dari GOFOTOBOX',
            // Additional metadata
            layout_type: 'layout1',
            generation_time: new Date().toLocaleString('id-ID'),
            photo_count: CONFIG.EXPECTED_PHOTOS
        };

        // Use enhanced EmailJS helper
        let emailResult;
        if (typeof window.emailJSHelper !== 'undefined') {
            emailResult = await window.emailJSHelper.sendPhotoEmail(email, {
                link: photoLink
            });
        } else {
            // Fallback to original method
            console.warn('Using fallback email method');
            emailResult = await emailjs.send(
                CONFIG.EMAILJS_SERVICE_ID, 
                CONFIG.EMAILJS_TEMPLATE_ID, 
                emailData
            );
        }

        // Update UI on success
        state.emailSent = true;
        const emailBtn = document.getElementById('emailBtn');
        if (emailBtn) {
            emailBtn.disabled = true;
            emailBtn.style.opacity = '0.5';
            emailBtn.style.cursor = 'not-allowed';
            emailBtn.innerHTML = '✅ Email Terkirim (Enhanced)';
        }
        
        updateContinueButtonState();
        
        // Enhanced success message
        const successMessage = emailResult.emailType 
            ? `Email berkualitas tinggi berhasil dikirim ke ${emailResult.emailType} account! ✅`
            : 'Email berkualitas tinggi berhasil dikirim! ✅';
            
        showValidationError(successMessage + ' Cek inbox Anda.');
        document.querySelector('.input-validation span').style.color = '#28a745';

        // Close modal after delay
        setTimeout(() => {
            closeEmailModal();
        }, 2000);

        console.log('✅ Enhanced email sent successfully:', emailResult);

    } catch (error) {
        console.error('❌ Enhanced email sending failed:', error);
        
        // Enhanced error handling
        let errorMessage = 'Gagal mengirim email';
        if (error.message.includes('network')) {
            errorMessage = 'Koneksi internet bermasalah, coba lagi';
        } else if (error.message.includes('invalid')) {
            errorMessage = 'Format email tidak valid';
        } else if (error.message.includes('quota')) {
            errorMessage = 'Limit pengiriman email tercapai, coba lagi nanti';
        }
        
        handleError(errorMessage, 'alert');
        
        // Reset button
        sendBtn.innerHTML = originalHtml;
        sendBtn.disabled = false;
    }
}

/**
 * Enhanced email modal initialization (replace existing initializeEmailModal)
 */
function initializeEmailModal() {
    console.log('📧 Initializing enhanced email modal...');
    
    // Initialize EmailJS Helper
    if (typeof window.emailJSHelper !== 'undefined') {
        window.emailJSHelper.init('9SDzOfKjxuULQ5ZW8'); // Your public key
    }
    
    const sendEmailBtn = document.getElementById('sendEmailBtn');
    const emailInput = document.getElementById('emailInput');

    if (sendEmailBtn) {
        sendEmailBtn.addEventListener('click', () => {
            const email = emailInput.value.trim();
            if (!email) {
                showValidationError('Masukkan alamat email terlebih dahulu');
                return;
            }
            
            if (!validateEmail(email)) return;
            
            sendPhotoEmail(email);
        });
    }

    if (emailInput) {
        // Enhanced email input with suggestions
        emailInput.addEventListener('input', (e) => {
            const email = e.target.value;
            hideValidationError();
            
            // Show suggestions as user types
            if (email.length > 3 && typeof window.emailJSHelper !== 'undefined') {
                const suggestions = window.emailJSHelper.getProviderSuggestions(email);
                if (suggestions.length > 0 && !email.includes('@')) {
                    // Show first suggestion as placeholder
                    emailInput.placeholder = suggestions[0];
                }
            }
        });

        emailInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                sendEmailBtn.click();
            }
        });
    }
}

/**
 * Enhanced error handling for email
 */
function showValidationError(message) {
    const validationMessage = document.getElementById('validation-message');
    const validationDiv = document.querySelector('.input-validation');
    
    if (validationMessage && validationDiv) {
        validationMessage.textContent = message;
        validationDiv.style.display = 'block';
        
        // Auto-hide success messages
        if (message.includes('✅')) {
            setTimeout(() => {
                hideValidationError();
            }, 5000);
        }
    }
}

// Add to your existing initialization
document.addEventListener('DOMContentLoaded', function() {
    // ... existing initialization code ...
    
    // Initialize enhanced email functionality
    initializeEmailModal();
});
