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

    #[Assert\Choice(choices: ['2048', '4096'])]
    public string $keySize = '2048';
}