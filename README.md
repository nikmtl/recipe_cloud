# <img src="http- U### For Registered Users:
- User registration & login 2. Create a4. Access the project in your local web server (e.g., with XAMPP):MySQL database, e.g., `recipe_cloud`, and optionally import `recipe_cloud.sql`with password hashing & PHP sessions)
- Personal user profile
- Create, edit, and delete recipes
- Rate and comment on other users' recipes
- Favorites list or "bookmark" functiongistration & login (with password hashing & 2. Create a4. Access the project in your local web server (e.g., with XAMPP):MySQL database, e.g., `recipe_cloud`, and optionally import `recipe_cloud.sql`HP sessions)
- Personal user profile
- Create, edit, and delete recipes
- Rate and comment on other users' recipes
- Favorites list or "bookmark" functionithub.com/Edamame04/recipe_cloud/blob/main/assets/img/logo_with_bg.svg" alt="logo" width="30"/> Recipe Cloud – Your Digital Recipe Cloud

**Recipe Cloud** is a web application for managing and sharing recipes. Users can create, save, search, and rate recipes – all centrally in one place. The application was developed as part of the *Web Engineering 2* module and is based on PHP, HTML, CSS, JavaScript, and MySQL.

The project serves to gain practical experience with **PHP** and **SQL** and deepen understanding of server-side web development with database integration. The application was deliberately developed without the use of frameworks or libraries. Only pure **HTML**, **CSS**, **JavaScript**, and **PHP** are used to deepen the fundamental concepts and techniques of web development.

---

## 🌟 Features

### Public Access:
- Homepage with recipe suggestions
- Recipe overview with filter and search functionality
- Detailed recipe display (including ingredients, preparation, images)

### For Registered Users:
- Benutzerregistrierung & Login (mit Passwort-Hashing & PHP-Sessions)
- Eigenes Benutzerprofil
- Rezepte erstellen, bearbeiten und löschen
- Rezepte von anderen Nutzern bewerten und kommentieren
- Favoritenliste oder „Merken“-Funktion 

### Optional/Expandable:
- Image upload for recipes
- Recipe categories & tags
- Admin area for moderation

---

## 🧰 Tech-Stack

- **Frontend:** HTML, CSS, JavaScript
- **Backend:** PHP 8+ (Form processing, Sessions)
- **Database:** MySQL

---

## 🗃️ Database Schema

**users:**  
`username*(PK)*`, `email`, `password_hash`

**recipes:**  
`id *(PK)*`, `user_id *(FK)*`, `title`, `description`, `prep_time`, `cook_time`, `difficulty`,  `servings`, `category`, `image_path`

**instructions**
`id *(PK)*`, `instruction`, `recipe_id *(FK)*`

**ingredients:**
`id *(PK)*`, `amount`, `unit`, `ingredient`, `recipe_id *(FK)*`

**ratings:**  
`id *(PK)*`, `user_id *(FK)*`, `recipe_id *(FK)*`, `rating`, `comment_text`, `created_at`

**favorites:**
`id *(PK)*`, `user_id *(FK)*`, `recipe_id *(FK)*`

---

## 🔧 Local Setup

1. Clone the project:
   ```bash
   git clone https://github.com/Edamame04/recipe_cloud
   ```

2. MySQL-Datenbank anlegen, z. B. `recipe_cloud`, und ggf. `recipe_cloud.sql` importieren

3. Configure database connection in `be-logic/db.php`:

   ```php
   $host = 'localhost';
   $db   = 'recipe_cloud';
   $user = 'root';
   $pass = '';
   ```

4. Projekt im lokalen Webserver (z. B. mit XAMPP) aufrufen:

   ```
   http://localhost/recipe_cloud/
   ```

---

## 📁 Current Project Structure

```
recipe_cloud/
│
├── index.php              # Homepage
├── login.php              # User login page
├── register.php           # User registration page
├── profile.php            # User profile page
├── recipes.php            # Recipe overview page
├── recipe.php             # Individual recipe display
├── upload.php             # Recipe upload page
├── edit_recipe.php        # Recipe editing page
├── settings.php           # User settings page
│
├── assets/
│   ├── css/               # Stylesheets
│   │   ├── styles.css
│   │   ├── auth.css
│   │   ├── recipe.css
│   │   ├── profile.css
│   │   └── ...
│   ├── fe-logic/          # Frontend JavaScript
│   │   ├── auth.js
│   │   ├── profile.js
│   │   ├── recipe-page.js
│   │   ├── upload/
│   │   │   ├── upload.js
│   │   │   └── ...
│   │   └── ...
│   ├── img/               # Images and logos
│   │   ├── logo.svg
│   │   └── logo_with_bg.svg
│   └── includes/          # Reusable PHP components
│       ├── header.php
│       ├── footer.php
│       └── recipe_card.php
│
├── be-logic/              # Backend PHP logic
│   ├── db.php             # Database connection
│   ├── auth.php           # Authentication handling
│   ├── upload.php         # Recipe upload processing
│   ├── edit_recipe.php    # Recipe editing logic
│   ├── save_recipe.php    # Recipe saving
│   ├── delete_recipe.php  # Recipe deletion
│   ├── submit_review.php  # Review submission
│   ├── delete_review.php  # Review deletion
│   ├── get_user_profile.php
│   ├── update_account.php
│   ├── delete_account.php
│   ├── load_more_recipes.php
│   └── protected_page.php
│
├── uploads/               # User uploaded files
│   └── recipes/           # Recipe images
│
├── docs/                  # Documentation
│   └── erm.drawio         # Entity Relationship Model
│
└── README.md
```

---

## 🖼️ Screenshots

> Screenshots will be added here

