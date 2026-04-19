# NeoKart

NeoKart is a PHP-based e-commerce web application with user authentication, product browsing, cart flow, checkout pages, and a basic admin area.

## Features

- User registration and login
- Product listing page
- Cart and checkout pages
- Order success flow
- Admin pages for users, products, and orders
- Shared navigation and footer components
- Responsive layout styling with separate page-specific CSS

## Tech Stack

- PHP
- MySQL
- HTML/CSS

## Project Structure

- `index.php`: Home page
- `products.php`: Product listing
- `cart.php`: Shopping cart page
- `checkout.php`: Checkout page
- `order_success.php`: Order confirmation page
- `auth/`: Login, register, and session auth handlers
- `admin/`: Admin dashboard and management pages
- `config/db_config.php`: Database connection (ignored from Git)
- `config/db_config.example.php`: Example DB config template
- `style/`: CSS files
- `assets/` and `new_img/`: Static assets

## Local Setup

1. Clone the repository:

```bash
git clone https://github.com/Aarif-Shrestha/Neokart-.git
cd Neokart-
```

2. Configure your web server (XAMPP/WAMP/LAMP) to serve the project folder.

3. Set up the database:
- Create a MySQL database.
- Update `config/db_config.php` with your local credentials.

4. Run the app by opening the project in your local PHP server, for example:
- `http://localhost/Neokart-` (path depends on your server setup)

## Git Ignore Notes

This project includes a `.gitignore` so local secrets and environment files are not pushed.

Ignored examples:
- `config/db_config.php`
- `.env` files
- logs and temporary files


## Recent Updates

- Polished authentication redirect flow to stop script execution immediately after redirect headers.
- Removed debug output from the login handler for cleaner production behavior.
- Updated project documentation to reflect these quality improvements.

## Author

Aarif Shrestha
