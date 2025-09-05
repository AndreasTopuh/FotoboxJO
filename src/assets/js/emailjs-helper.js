/**
 * Enhanced EmailJS Helper with Improved Validation
 * Compatible dengan existing EmailJS implementation
 */

class EmailJSHelper {
    constructor() {
        // EmailJS configuration (sesuaikan dengan config Anda)
        this.config = {
            serviceId: 'service_gtqjb2j',
            templateId: 'template_pp5i4hm',
            publicKey: 'your_public_key' // Ganti dengan public key EmailJS Anda
        };
        
        this.isInitialized = false;
    }

    /**
     * Initialize EmailJS (call this once per page)
     */
    init(publicKey = null) {
        if (typeof emailjs === 'undefined') {
            console.error('EmailJS library not loaded');
            return false;
        }

        try {
            const key = publicKey || this.config.publicKey;
            emailjs.init({ publicKey: key });
            this.isInitialized = true;
            console.log('✅ EmailJS initialized successfully');
            return true;
        } catch (error) {
            console.error('❌ EmailJS initialization failed:', error);
            return false;
        }
    }

    /**
     * Enhanced email validation
     */
    validateEmail(email) {
        const result = {
            isValid: false,
            error: null,
            suggestions: [],
            emailType: null
        };

        // Trim whitespace
        email = email.trim();

        // Basic format validation
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            result.error = 'Format email tidak valid';
            return result;
        }

        // Check email length
        if (email.length > 254) {
            result.error = 'Email terlalu panjang (maksimal 254 karakter)';
            return result;
        }

        // Split email parts
        const [localPart, domain] = email.split('@');

        // Validate local part
        if (localPart.length > 64) {
            result.error = 'Bagian sebelum @ terlalu panjang (maksimal 64 karakter)';
            return result;
        }

        // Check for common typos in domains
        const commonDomains = {
            'gmial.com': 'gmail.com',
            'gmai.com': 'gmail.com',
            'yahooo.com': 'yahoo.com',
            'hotmial.com': 'hotmail.com',
            'outlok.com': 'outlook.com'
        };

        if (commonDomains[domain]) {
            result.error = `Mungkin maksud Anda "${localPart}@${commonDomains[domain]}"?`;
            result.suggestions.push(`${localPart}@${commonDomains[domain]}`);
            return result;
        }

        // Detect email provider type
        const emailProviders = {
            gmail: ['gmail.com'],
            yahoo: ['yahoo.com', 'ymail.com'],
            outlook: ['outlook.com', 'hotmail.com', 'live.com'],
            indonesian: ['.co.id', '.ac.id', '.or.id', '.go.id', '.web.id']
        };

        for (const [provider, domains] of Object.entries(emailProviders)) {
            if (domains.some(d => domain.includes(d))) {
                result.emailType = provider;
                break;
            }
        }

        result.isValid = true;
        return result;
    }

    /**
     * Sanitize email
     */
    sanitizeEmail(email) {
        return email.trim().toLowerCase();
    }

    /**
     * Enhanced email sending with validation
     */
    async sendEmail(emailData) {
        try {
            // Validate email
            const validation = this.validateEmail(emailData.to_email);
            if (!validation.isValid) {
                throw new Error(validation.error);
            }

            // Show suggestions if any
            if (validation.suggestions.length > 0) {
                const confirmed = confirm(`${validation.error}\n\nLanjutkan dengan email: ${emailData.to_email}?`);
                if (!confirmed) {
                    throw new Error('Email sending cancelled by user');
                }
            }

            // Sanitize email
            emailData.to_email = this.sanitizeEmail(emailData.to_email);
            emailData.email = emailData.to_email;
            emailData.recipient_email = emailData.to_email;

            // Check if EmailJS is initialized
            if (!this.isInitialized) {
                throw new Error('EmailJS not initialized. Call init() first.');
            }

            // Enhanced email parameters
            const enhancedParams = {
                ...emailData,
                timestamp: new Date().toISOString(),
                user_agent: navigator.userAgent,
                email_type: validation.emailType || 'unknown',
                from_name: emailData.from_name || 'GOFOTOBOX',
                subject: emailData.subject || 'Foto dari GOFOTOBOX'
            };

            // Send email via EmailJS
            console.log('📧 Sending email via EmailJS...', enhancedParams);
            const response = await emailjs.send(
                this.config.serviceId,
                this.config.templateId,
                enhancedParams
            );

            console.log('✅ Email sent successfully:', response);
            return {
                success: true,
                messageId: response.text,
                emailType: validation.emailType
            };

        } catch (error) {
            console.error('❌ Email sending failed:', error);
            throw error;
        }
    }

    /**
     * Send high quality photo email (untuk compatibility dengan existing code)
     */
    async sendPhotoEmail(email, photoData) {
        const emailParams = {
            to_email: email,
            email: email,
            recipient_email: email,
            name: 'Sobat',
            user_name: 'Sobat',
            to_name: 'Sobat',
            from_name: 'GOFOTOBOX - HIGH QUALITY',
            message: `Halo Sobat! Link foto berkualitas tinggi Anda: ${photoData.link}`,
            subject: 'Foto Berkualitas Tinggi dari GOFOTOBOX',
            link: photoData.link,
            photo_link: photoData.link,
            url: photoData.link
        };

        return await this.sendEmail(emailParams);
    }

    /**
     * Validate multiple emails
     */
    validateEmails(emails) {
        return emails.map(email => ({
            email,
            ...this.validateEmail(email)
        }));
    }

    /**
     * Get email provider suggestions
     */
    getProviderSuggestions(partialEmail) {
        const commonProviders = [
            '@gmail.com',
            '@yahoo.com',
            '@outlook.com',
            '@hotmail.com',
            '@student.ac.id',
            '@ui.ac.id',
            '@itb.ac.id',
            '@ugm.ac.id'
        ];

        if (!partialEmail.includes('@')) {
            return commonProviders.map(provider => partialEmail + provider);
        }

        const [local, domain] = partialEmail.split('@');
        return commonProviders
            .filter(provider => provider.includes(domain))
            .map(provider => local + provider);
    }
}

// Create global instance
window.emailJSHelper = new EmailJSHelper();

// Export for module usage
if (typeof module !== 'undefined' && module.exports) {
    module.exports = EmailJSHelper;
}
