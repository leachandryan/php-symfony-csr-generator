CSR Generator Web Application
A web-based Certificate Signing Request (CSR) generator built with Symfony PHP framework. Generate and manage CSRs with support for both RSA and ECC key types.
Features

Generate CSRs with customizable parameters
Support for multiple key types:

RSA (2048, 3072, 4096 bits)
ECC (prime256v1, secp384r1, secp521r1)


Browser-based storage for managing multiple CSRs
Copy CSR to clipboard functionality
Responsive table view of stored CSRs
Form validation and error handling

Prerequisites

PHP 8.1 or higher
Composer
OpenSSL PHP extension
Symfony CLI (optional, for development server)

Installation

Clone the repository:

bashCopygit clone https://github.com/yourusername/csr-generator.git
cd csr-generator

Install dependencies:

bashCopycomposer install

Configure your environment:

bashCopycp .env .env.local
Edit .env.local with your specific configuration if needed.

Clear cache:

bashCopyphp bin/console cache:clear
Running the Application
Using Symfony CLI (recommended for development):
bashCopysymfony server:start
Or using PHP's built-in server:
bashCopyphp -S localhost:8000 -t public/
Visit http://localhost:8000 in your browser.
Project Structure
Copysrc/
├── Controller/
│   └── CSRController.php    # Handles form submission and CSR generation
├── DTO/
│   └── CSRData.php         # Data Transfer Object for CSR details
├── Form/
│   └── CSRFormType.php     # Defines form fields and validation
└── Service/
    └── CSRGenerator.php    # Core service for generating CSRs
templates/
├── base.html.twig          # Base template
└── csr/
    └── index.html.twig     # Main CSR generation form template
Usage

Fill in the CSR form with your details:

Common Name (CN) - e.g., domain.com
Organization (O)
Organizational Unit (OU) - optional
Locality (L) - optional
State/Province (ST) - optional
Country (C) - two-letter code
Email - optional
Key Type - RSA or ECC
Key Configuration - depends on key type selected


Click "Generate CSR" to create your CSR
The CSR will be stored in your browser and displayed in the table below
Use the "Copy" button to copy the CSR to your clipboard
Use the "Delete" button to remove stored CSRs

Security Considerations

CSRs are stored in browser local storage
No sensitive data is sent to external servers
Private keys are generated server-side and not stored
Form includes validation for all required fields

Development Notes

Built with Symfony 7.2
Uses browser localStorage for CSR management
Implements AJAX form submission
Includes dynamic form updates for key type selection