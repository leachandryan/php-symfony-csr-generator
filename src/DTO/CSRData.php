<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class CSRData
{
    #[Assert\NotBlank(message: "Common Name is required")]
    #[Assert\Length(min: 2, max: 255)]
    public string $commonName = '';

    #[Assert\NotBlank(message: "Organization is required")]
    #[Assert\Length(min: 2, max: 255)]
    public string $organization = '';

    public ?string $organizationalUnit = null;

    public ?string $locality = null;

    public ?string $state = null;

    #[Assert\NotBlank(message: "Country is required")]
    #[Assert\Length(exactly: 2, exactMessage: "Country code must be exactly 2 characters")]
    public string $country = '';

    #[Assert\Email(message: "Please enter a valid email address")]
    public ?string $email = null;

    #[Assert\NotBlank(message: "Key type is required")]
    #[Assert\Choice(choices: ['rsa', 'ecc'])]
    public string $keyType = 'rsa';

    #[Assert\NotBlank(message: "Key configuration is required")]
    #[Assert\Choice(choices: [
        // RSA sizes
        '2048', '3072', '4096',
        // ECC curves
        'prime256v1', 'secp384r1', 'secp521r1'
    ])]
    public string $keyConfig = '2048';
}