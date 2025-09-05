<?php

/**
 * Email Validation Helper Class
 * Provides comprehensive email validation methods
 */
class EmailValidator
{

    /**
     * Validate email using PHP's built-in filter
     */
    public static function validateBasic($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Validate email using regex (same as frontend)
     */
    public static function validateRegex($email)
    {
        return preg_match('/^[^\s@]+@[^\s@]+\.[^\s@]+$/', $email);
    }

    /**
     * Comprehensive email validation
     */
    public static function validateComprehensive($email)
    {
        // Trim whitespace
        $email = trim($email);

        // Basic format check
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['valid' => false, 'error' => 'Format email tidak valid'];
        }

        // Split email into parts
        $parts = explode('@', $email);
        if (count($parts) !== 2) {
            return ['valid' => false, 'error' => 'Format email tidak valid'];
        }

        $local = $parts[0];
        $domain = $parts[1];

        // Check local part length (max 64 characters)
        if (strlen($local) > 64) {
            return ['valid' => false, 'error' => 'Bagian sebelum @ terlalu panjang (max 64 karakter)'];
        }

        // Check domain part length (max 253 characters)
        if (strlen($domain) > 253) {
            return ['valid' => false, 'error' => 'Domain terlalu panjang (max 253 karakter)'];
        }

        // Check if domain has at least one dot
        if (strpos($domain, '.') === false) {
            return ['valid' => false, 'error' => 'Domain harus memiliki ekstensi (contoh: .com, .id)'];
        }

        // Check for common typos in Indonesian domains
        $indonesianDomains = ['.co.id', '.ac.id', '.or.id', '.go.id', '.web.id'];
        $hasIndonesianDomain = false;
        foreach ($indonesianDomains as $idDomain) {
            if (str_ends_with($domain, $idDomain)) {
                $hasIndonesianDomain = true;
                break;
            }
        }

        return ['valid' => true, 'error' => null, 'is_indonesian' => $hasIndonesianDomain];
    }

    /**
     * Validate using PHPMailer (if available)
     */
    public static function validateWithPHPMailer($email)
    {
        if (class_exists('PHPMailer\PHPMailer\PHPMailer')) {
            return \PHPMailer\PHPMailer\PHPMailer::validateAddress($email);
        }
        return false;
    }

    /**
     * Sanitize email for safe usage
     */
    public static function sanitize($email)
    {
        $email = trim($email);
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        return strtolower($email);
    }

    /**
     * Check if email domain exists (DNS check)
     */
    public static function validateDomain($email)
    {
        $parts = explode('@', $email);
        if (count($parts) !== 2) {
            return false;
        }

        $domain = $parts[1];
        return checkdnsrr($domain, 'MX') || checkdnsrr($domain, 'A');
    }
}

/**
 * Usage examples:
 */
function testEmailValidation()
{
    $testEmails = [
        'user@example.com',
        'test.email@domain.co.id',
        'invalid.email',
        'user@gmail.com',
        'admin@universitas.ac.id'
    ];

    echo "<h3>Email Validation Test Results:</h3>\n";

    foreach ($testEmails as $email) {
        echo "<h4>Testing: $email</h4>\n";

        // Basic validation
        $basicValid = EmailValidator::validateBasic($email);
        echo "Basic validation: " . ($basicValid ? "✅ VALID" : "❌ INVALID") . "<br>\n";

        // Comprehensive validation
        $comprehensiveResult = EmailValidator::validateComprehensive($email);
        echo "Comprehensive validation: " . ($comprehensiveResult['valid'] ? "✅ VALID" : "❌ INVALID");
        if (!$comprehensiveResult['valid']) {
            echo " - " . $comprehensiveResult['error'];
        } elseif (isset($comprehensiveResult['is_indonesian']) && $comprehensiveResult['is_indonesian']) {
            echo " (Domain Indonesia)";
        }
        echo "<br>\n";

        // Sanitized version
        $sanitized = EmailValidator::sanitize($email);
        echo "Sanitized: $sanitized<br>\n";

        echo "<br>\n";
    }
}

// Uncomment to run test
// testEmailValidation();
