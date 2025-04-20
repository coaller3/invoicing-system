# Invoicing System

A Laravel-based invoicing system.

## Requirements

- **Laragon**: 6.0.0
- **PHP**: 8.2.28
- **MySQL**: 8.0.41
- **Apache**: 2.4.63-250207
- **Composer**: 2.8.6
- **PHP Extensions**: curl, exif, fileinfo, gd, intl, mbstring, mysqli, openssl, pdo_mysql, sodium, xsl, zip

## Setup Instructions

### 1. Clone the Repository

git clone https://github.com/coaller3/invoicing-system.git

Then cd invoicing-system

### 2. Place in Laragon's `www` Folder

Move the project folder into your Laragon `www` directory.

### 3. Configure Virtual Host

- Open Laragon.
- Go to **Menu > Preferences > General** tab and check if **Auto-create Virtual Hosts** is ticked.
- Laragon will auto generate the virtual host base on folder name in **www** with **.test** at end (default).

### 4. Install Dependencies

composer install

### 5. Edit .env

`.env` had exclude from gitignore and upload to the github.

Edit `.env` as needed (database, mail, etc.).

### 6. Generate Application Key (if dont have)

php artisan key:generate

### 7. Run Migrations and Seed the Database

php artisan migrate --seed

### 8. Install Passport

php artisan passport:install

php artisan passport:keys to generate private and public key.

php artisan vendor:publish --tag=passport-config to publish Passport's configuration file.

Run php artisan passport:client --password to create Password Grant Client.

- Copy the generated **client ID** and **client secret** from the output.
- Update the following lines in your `.env` file:

PASSPORT_CLIENT_ID=your-client-id

PASSPORT_CLIENT_SECRET=your-client-secret

### 9. Start the Application

- Open http://invoicing-system.test (if your folder name is invoicing-system) in your browser.

## Notes

- Make sure all required PHP extensions are enabled in your Laragon PHP settings.
- The default login credentials are seeded via the database seeders.
