# La pasión

Laravel 13, Blade, Eloquent and MySQL/MariaDB. Includes products, customers, staff, image processing, authenticated sales and historical transactions.

## Run this installation

Start MySQL in XAMPP, then run `powershell -ExecutionPolicy Bypass -File .\start.ps1`. Open http://127.0.0.1:8000. The launcher uses the isolated PHP 8.5 runtime with GD installed in `%LOCALAPPDATA%\IT0049\php85`.

Create additional staff securely with `php artisan pos:create-staff` (interactive prompts). Use PHP 8.3+ with GD, BCMath, PDO MySQL, mbstring, fileinfo, OpenSSL and standard Laravel extensions. XAMPP's PHP 8.2 cannot run this Laravel version.

## Fresh setup

1. `composer install`
2. Copy `.env.example` to `.env`, configure your database, create that database, and run `php artisan key:generate`.
3. `php artisan migrate` and `php artisan storage:link`.
4. `php artisan pos:create-staff`.
5. `npm install --ignore-scripts` and `npm run build`.
6. `php artisan serve --no-reload` on Windows. Laravel reload mode filters Windows TEMP/TMP variables and may prevent multipart uploads when PHP has no explicit upload_tmp_dir.

## Behavior

Each sale contains one product. Customer selection is optional. Sale totals use server-side decimal arithmetic; product locking and a database transaction keep stock and sales consistent. Submission tokens prevent duplicates. Products are archived; customer/staff deletion is soft deletion. Historical relationships include deleted records. The final staff account cannot be deleted. Names in history reflect current related names; totals remain fixed. Passwords use Laravel hashing. Images are prepared as square WebP files using Laravel's Image API and Storage. Uploaded files are limited to 4 MB and 6000 pixels per side. No public registration or default public credentials.

## Verify

`php artisan test` (isolated SQLite database), `php vendor/bin/pint --test`, `npm run build`. `php tests/concurrency.php` checks two real concurrent purchases against `it0049_pos_test` after running the suite on that database. MySQL tests must use a separate disposable database, never the application database. `.env`, uploads and local test output are not committed.

## Customer accounts

Customers can register at `/customer/register`, log in at `/customer/login`, and view their details at `/customer/account`. Customer authentication uses its own Laravel session guard; it grants no staff permissions. New registrations appear in Staff > Customers. Staff can edit details and login email, set or reset a password, and delete the customer to revoke access. Blank passwords preserve an existing login; setting a password enables login for a customer previously entered by staff. Public registration cannot claim an existing customer record; staff must enable that record's account. Email addresses are normalized to lowercase. Passwords must contain at least 8 characters and fit bcrypt's 72-byte limit.

## Purchase history, receipts and account controls

Customers can edit their name and phone and change their password after confirming their current password under My account. My purchases contains only transactions explicitly linked to that customer by staff. Receipts are available to staff and to the owning customer and have a dedicated print layout. New sales save product, customer and staff names and unit price so later edits do not rewrite receipts; older rows use their existing related records as a fallback.

Staff can search directories and filter sales by date, product and customer. Customer login can be paused and restored from the row action menu. Deleted customers can be restored from the Deleted tab. Password changes and login access changes invalidate older customer sessions. These controls preserve transaction history.

The interface uses Blade components, native CSS and JavaScript, and self-hosted Instrument Sans fonts. No frontend application framework was added.

## Branding assets and effects

The interface uses the supplied Specaura logo from PPTPROPOSAL and Space Grotesk fonts. La pasión remains the application name. Labels and helper text describe operations.

The sign-in screen uses Paper Shaders 0.0.82 for an optional liquid-metal version of the logo. Reduced motion and unavailable WebGL retain the original SVG. A pause button controls animation. Refractive glass surfaces adapt the technique from shuding/liquid-glass; reduced transparency uses solid surfaces. Visual effects load separately from Laravel form behavior. Third-party license files are included with the assets.
