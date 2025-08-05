<?php
// Test script untuk verifikasi cash code
echo "=== Testing Cash Code Verification ===\n";

// Simulate POST request
$_SERVER['REQUEST_METHOD'] = 'POST';
$_POST['code'] = '82195';

// Set the input stream to simulate JSON POST
$json_data = json_encode(['code' => '82195']);
$temp_file = tempnam(sys_get_temp_dir(), 'php_input');
file_put_contents($temp_file, $json_data);

// Override php://input for this test
stream_wrapper_unregister('php');
stream_wrapper_register('php', 'PHPInputMock');

class PHPInputMock
{
    private $data;
    private $index = 0;

    public function stream_open($path, $mode, $options, &$opened_path)
    {
        if ($path == 'php://input') {
            $this->data = '{"code":"82195"}';
            return true;
        }
        return false;
    }

    public function stream_read($count)
    {
        $ret = substr($this->data, $this->index, $count);
        $this->index += strlen($ret);
        return $ret;
    }

    public function stream_eof()
    {
        return $this->index >= strlen($this->data);
    }

    public function stream_stat()
    {
        return [];
    }
}

// Now test the verification
ob_start();
include '/var/www/html/FotoboxJO/src/api-fetch/verify_cash_code.php';
$output = ob_get_clean();

echo "API Output:\n";
echo $output . "\n";

// Check the file after verification
$codes_file = '/var/www/html/FotoboxJO/src/data/cash_codes.json';
if (file_exists($codes_file)) {
    echo "\nFile content after verification:\n";
    echo file_get_contents($codes_file);
} else {
    echo "\nCodes file not found at: $codes_file\n";
}
