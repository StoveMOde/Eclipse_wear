# Eclipse Wear – Eyewear E-Commerce Web Application

Eclipse Wear is a PHP/MySQL-powered e-commerce web application that allows users to browse eyewear products, create an account, manage their profile, add items to a shopping cart, and perform full CRUD operations. The project demonstrates user authentication, input validation, SQL operations, and session-based cart management.

---

## 🚀 Features Implemented

### **1. Create Functionality**
- User account creation (Sign Up)
- Secure password hashing using `password_hash()`

### **2. Read Functionality**
- Dynamic Shop page displaying products
- Personalized user dashboard with session-based welcome message
- Profile page loading live user data from database

### **3. Update Functionality**
- Profile updates:
  - First Name
  - Last Name
  - Email
  - Password (with validation & hashing)
- Toast notifications confirming updates

### **4. Delete Functionality**
- “Delete My Account” permanently removes user from DB
- Session destroyed after deletion

### **5. Additional Features**
- Session-based Shopping Cart:
  - Add to Cart
  - View Cart
  - Remove Item from Cart
- Secure login system with validation
- Clean responsive UI built with custom CSS
- Redirects and access protection for unauthorized pages

---

## 🧩 Features NOT Implemented (Yet)

- Full checkout system  
  (payment processing, order confirmation pages)
- Admin dashboard for product management  
  (Add/Edit/Delete products)
- User order history / past purchases
- Persistent cart saved in database instead of session
- Mobile-responsive layout for all pages
- Search bar & product filtering

---

## 🐞 Known Bugs / Limitations

- Some pages require manual refresh to show updated session values.
- Cart is session-based only (not stored per user).
- Password update requires correct "current password," but error messages may appear late depending on input order.
- Redirect behavior was initially affected by duplicate folders and outdated files (now fixed).
- No validation for duplicate product addition; multiple identical items appear separately in cart.

---

## 🛠 Tech Stack / Tools

- **Frontend:** HTML5, CSS3, Vanilla JS
- **Backend:** PHP 8, MySQL (via MAMP)
- **Authentication:** Sessions, hashed passwords
- **Database Tools:** phpMyAdmin
- **Development Tools:** VS Code, MAMP, Git/GitHub

---

## 📁 Project Structure (Summary)

