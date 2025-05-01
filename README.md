# Ecommerce Platform (TALL Stack and Filament Admin Dashboard)
A modern eCommerce platform built using the **TALL stack**: **Tailwind CSS**, **Alpine.js**, **Laravel**, and **Livewire**. Includes a powerful admin panel using **Filament** for resource management.


## Tech Stack

- **Laravel 12** – Backend framework
- **Livewire** – Reactive components for Laravel
- **Alpine.js** – JavaScript framework for interactivity
- **Tailwind CSS** – Utility-first CSS framework
- **Filament** – Elegant admin panel for Laravel
- **MySQL** – Relational database
- **PHP 8.2+**
- **Vite** – Asset bundling

## Installation

- Clone the Repo
- ``` composer install ```
- ``` cp .env.example .env ``` (If .env doesn't exist yet)
- ``` php artisan key:gen ```
- ``` npm install && npm run dev ```
- ``` php artisan migrate --seed```  (I added seeder for **categories** and **products**)
- ``` php artisan storage:link ```
- ``` php artisan make:filament-user ``` (to create **admin user** for admin panel)



## Project Logic
**Customer Interface**

- **Dashboard** with summary of order data and popular products
= **Shop** with products preview, filters, search and sorting. Also include **Detail View** and **Add to Cart**
- **My Orders** to show order summary and status
- **Manage My Orders** to redirect to filament admin panel to see user's orders and manage orders.

**Filament Admin Panel** (route - /superadmin ) 

- **Categories** to manage Categories. **Category is 2 steps tree structure.**
- **Products** to manage Products
- **Users** to manage Customers and Admins
> Note: Above pages can only access with **admin** role.

- **Orders** to manage Orders. Admin can see all orders and Customer can see their own orders.

## Requirements and ToDo
- **Deleting rules and requirements** 
	> Currently I used ```restrict``` in depending columns and protect deleting dependent data in filament dashboard. we may need to upgrade using **softdelete** or something like that according business requirements.
- **RBAC**
	> Currently I just added ```role``` in user table. If the user register from webshop, role will attach as 'customer' and admin create with ``` php artisan make:filament-user ``` command will attach as 'admin'.  I customized the command to attach with 'admin' role. 'admin' also can update customer as admin. We may need to use proper **RBAC** in future.

- **Add more customer information**
	> Collect customer address and other information
- **Add more Order status**
- **Admin Dashboard statistics**
- **Clean code structure and validation**
	 > Due to timeline, I decided to make it features work properly. Need to clean code and separate logic in future. Need to add validation and phpunit test as well.

