<?php

namespace App\Service;

use App\DTO\CSRData;
use Exception;
use Psr\Log\LoggerInterface;

class CSRGenerator
{
    public function __construct(
        private LoggerInterface $logger
    ) {}

    public function generate(CSRData $data): string
    {
        // Configure key options
        if ($data->keyType === 'rsa') {
            $config = [
                'private_key_type' => OPENSSL_KEYTYPE_RSA,
                'private_key_bits' => (int)$data->keyConfig
            ];
        } else {
            $config = [
                'private_key_type' => OPENSSL_KEYTYPE_EC,
                'curve_name' => $data->keyConfig
            ];
        }

        // Generate private key
        $privateKey = openssl_pkey_new($config);

        if ($privateKey === false) {
            $error = openssl_error_string();
            $this->logger->error('Failed to generate private key: ' . $error);
            throw new Exception('Failed to generate private key: ' . $error);
        }

        // Create CSR
        $dn = array_filter([
            "commonName" => $data->commonName,
            "organizationName" => $data->organization,
            "organizationalUnitName" => $data->organizationalUnit,
            "localityName" => $data->locality,
            "stateOrProvinceName" => $data->state,
            "countryName" => $data->country,
            "emailAddress" => $data->email,
        ]);

        $csr = openssl_csr_new($dn, $privateKey);
        
        if ($csr === false) {
            $error = openssl_error_string();
            $this->logger->error('Failed to generate CSR: ' . $error);
            throw new Exception('Failed to generate CSR: ' . $error);
        }

        // Export CSR to string
        if (!openssl_csr_export($csr, $csrout)) {
            $error = openssl_error_string();
            $this->logger->error('Failed to export CSR: ' . $error);
            throw new Exception('Failed to export CSR: ' . $error);
        }

        // Free the key from memory
        openssl_pkey_free($privateKey);
        
        return $csrout;
    }
}