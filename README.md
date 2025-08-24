# beaumont-inventory-system

Inventory management system for Beaumont Jewelry Boutique.  
Backend built with PHP and MySQL.

## Technologies Used
- PHP (backend logic)
- MySQL (database)

## Database

The project uses a MySQL database named `495f4bpoulton`.  
Tables included:

- **categories** – product categories (e.g., earrings, necklaces)  
- **product_inventory** – inventory details (`id`, `name`, `category_id`, `quantity`, `price`, `supplier_id`)  
- **sales_data** – sales transactions  
- **inventory** – overall stock levels  
- **suppliers** – supplier information  
- **users** – user login info and roles (admin or regular user)  

You can import the database using the included `495f4bpoulton.sql` file

## Setup / How to Run

1. Install a local server with PHP and MySQL (e.g., XAMPP, MAMP, WAMP).  
2. Create a MySQL database named `495f4bpoulton`.  
3. Import `495f4bpoulton.sql` 
4. Rename connect_example.php to connect.php. Open connect.php and replace the placeholder values (your_username and your_password) with your own MySQL credentials. 
5. Open `index.php` in a browser to use the system.

## Database Connection

- `connect.php` connects to the MySQL database.  

## Notes

- The system is for **employee/admin use only**; no customer-facing features.  
- Ensure your local server is running before opening the project.

