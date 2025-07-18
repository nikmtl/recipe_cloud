# <img src="https://github.com/Edamame04/recipe_cloud/blob/main/assets/img/logo_with_bg.svg" alt="logo" width="30"/> Recipe Cloud 

**Recipe Cloud** is a web application for managing and sharing recipes. Users can create, save, search, and rate recipes – all centrally in one place. The application was developed as part of the *Web Engineering 2* module and is based on PHP, HTML, CSS, JavaScript, and MySQL.

The project serves to gain practical experience with **PHP** and **SQL** and deepen understanding of server-side web development with database integration. The application was deliberately developed without the use of frameworks or libraries. Only pure **HTML**, **CSS**, **JavaScript**, and **PHP** are used to deepen the fundamental concepts and techniques of web development.

---

## 🌟 Features

### Public Access:
- Homepage with recipe suggestions
- Recipe overview with filter and search functionality
- Detailed recipe display (including ingredients, preparation, images)

### For Registered Users:
- User profile and stats
- Create, edit and delete recipes
- Rate and comment on other people's recipes
- Favorites list to remember recipes

---

## 🧰 Tech Stack

- **Frontend:** HTML, CSS, JavaScript (AJAX within recipe search)
- **Backend:** PHP 8+ (Form processing, Sessions)
- **Database:** MySQL

---

## 🗃️ Database Schema

**users:**  
`first_name`, `last_name`, `username*(PK)*`, `email`, `password_hash`, `bio`, `profile_image`, `created_at`

**recipes:**  
`id *(PK)*`, `user_id *(FK)*`, `title`, `description`, `prep_time_min`, `cook_time_min`, `difficulty`, `servings`, `category`, `image_path`

**instructions:**
`id *(PK)*`, `step_number`, `instruction`, `recipe_id *(FK)*`

**ingredients:**
`id *(PK)*`, `amount`, `unit`, `ingredient`, `recipe_id *(FK)*`

**ratings:**  
`id *(PK)*`, `user_id *(FK)*`, `recipe_id *(FK)*`, `rating`, `comment_text`, `created_at`

**favorites:**
`id *(PK)*`, `user_id *(FK)*`, `recipe_id *(FK)*`

## 🗄️ Entity-Relationship Model

![Entity-Relationship Diagram](https://github.com/Edamame04/recipe_cloud/blob/main/docs/erm.png)

The diagram illustrates the relationships between the database entities including users, recipes, ingredients, instructions, ratings, and favorites.

---

## 📁 Current Project Structure

```
recipe_cloud/
│
├── index.php              # Homepage
├── login.php              # User login page
├── register.php           # User registration page
├── profile.php            # User profile page
├── recipes.php            # Recipe overview and search page
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
│   │   ├── favicon.png
│   │   ├── favicon.svg
│   │   ├── logo.svg
│   │   └── logo_with_bg.svg
│   └── includes/          # Reusable PHP components
│       ├── header.php
│       ├── footer.php
│       └── recipe_card.php
│
├── be-logic/                 # Backend PHP logic
│   ├── db.php                # Database connection
│   ├── db_config.php         # Database configuration
│   ├── db_config.php.template # Database config template
│   ├── auth.php              # Authentication handling 
│   ├── upload.php            # Recipe upload processing
│   ├── edit_recipe.php       # Recipe editing logic
│   ├── save_recipe.php       # Recipe saving
│   ├── delete_recipe.php     # Recipe deletion
│   ├── submit_review.php     # Review submission
│   ├── delete_review.php     # Review deletion
│   ├── get_user_profile.php  # User profile loading
│   ├── update_account.php    # User profile editing
│   ├── update_password.php   # Password update handling
│   ├── delete_account.php    # Account and data deletion
│   ├── export_user_data.php  # User data export
│   ├── load_more_recipes.php # AJAX dynamic recipe loading
│   ├── load_standard_data.php # Load demo recipes
│   └── protected_page.php    # Protected route handler
│
├── docs/                     # Documentation
│   ├── erm.drawio            # Entity Relationship Model (editable)
│   └── erm.png               # Entity Relationship Diagram (image)
│
└── README.md
```

---

## 🛠️ Development

### Database Structure
The application uses a well-structured MySQL database with proper foreign key relationships and constraints. All tables are automatically created when the application first runs.

### Security Features
- Password hashing using PHP's `password_hash()`
- SQL injection prevention using PDO prepared statements
- Session-based authentication
- Input validation and sanitization
- Protected routes for authenticated users

### Code Organization
- **Frontend**: Vanilla JavaScript for dynamic interactions
- **Backend**: Pure PHP with no frameworks for educational purposes
- **Database**: MySQL with PDO for secure database operations
---

## 🔧 Local Setup with Docker
> **Note:** This project is hosted online so users worldwide can share recipes and culinary experiences with each other. For testing you can still set Recipe Cloud up on your local device using Docker.

### 🚥 Requirements

- **Docker:** Latest version with Docker Compose
- **Browser:** Modern web browser with JavaScript enabled

### 📁 Project Structure

Create the following project structure:
```
recipe_cloud/
├── docker-compose.yml
├── nginx/
│   └── default.conf
├── php/
│   ├── Dockerfile
│   └── php.ini
├── src/                ← your cloned Git repo
└── uploads/recipes/    ← target for images (local & server same)
```

### 🐳 Setup Steps

1. **Create project directory and clone:**
   ```bash
   mkdir recipe_cloud
   cd recipe_cloud
   git clone https://github.com/Edamame04/recipe_cloud src
   ```

2. **Create Docker configuration files:**

   **Create `docker-compose.yml`:**
   ```yaml
   services:
     web:
       image: nginx:alpine
       container_name: recipe_web
       ports:
         - "8083:80"
       volumes:
         - ./src:/var/www/html
         - ./nginx/default.conf:/etc/nginx/conf.d/default.conf
         - ./uploads:/var/www/html/uploads
       depends_on:
         - php
       restart: unless-stopped

     php:
       build:
         context: ./php
       container_name: recipe_php
       volumes:
         - ./src:/var/www/html
         - ./uploads:/var/www/html/uploads
       restart: unless-stopped

     db:
       image: mariadb
       container_name: recipe_db
       environment:
         MYSQL_ROOT_PASSWORD: changeme
         MYSQL_DATABASE: recipe_cloud
         MYSQL_USER: recipe_user
         MYSQL_PASSWORD: changemetoo
       volumes:
         - ./db:/var/lib/mysql
       restart: unless-stopped
   ```

   **Create `nginx/default.conf`:**
   ```nginx
   server {
       listen 80;
       server_name localhost;

       root /var/www/html;
       index index.php index.html;

       location / {
           try_files $uri $uri/ $uri.php?$args;
       }

       location ~ \.php$ {
           include fastcgi_params;
           fastcgi_pass php:9000;
           fastcgi_index index.php;
           fastcgi_param SCRIPT_FILENAME /var/www/html$fastcgi_script_name;
       }

       location ~ /\.ht {
           deny all;
       }
   }
   ```

   **Create `php/Dockerfile`:**
   ```dockerfile
   FROM php:8.2-fpm

   RUN docker-php-ext-install pdo pdo_mysql

   COPY php.ini /usr/local/etc/php/conf.d/custom.ini
   RUN touch /var/log/php_errors.log && chmod 777 /var/log/php_errors.log
   ```

   **Create `php/php.ini`:**
   ```ini
   display_errors = On
   display_startup_errors = On
   log_errors = On
   error_reporting = E_ALL
   error_log = /var/log/php_errors.log
   ```

   **Create `uploads/recipes/` directory:**
   ```bash
   mkdir -p uploads/recipes
   ```

3. **Configure database connection:**
   - Copy `src/be-logic/db_config.php.template` to `src/be-logic/db_config.php`
   - Update the database credentials in `src/be-logic/db_config.php`:
   ```php
   return [
       'host' => 'db',
       'database' => 'recipe_cloud',
       'username' => 'recipe_user',
       'password' => 'changemetoo',
       'charset' => 'utf8mb4'
   ];
   ```

4. **Start containers:**
   ```bash
   docker compose up -d --build
   ```

5. **Open application:**
   ```
   http://localhost:8083
   ```

6. **Load dummy recipes (optional):**
   ```
   Visit: http://localhost:8083/be-logic/load_standard_data
   ```
---

## 📧 Contact

For questions or suggestions regarding this project, please open an issue on GitHub.

**Happy Cooking! 👨‍🍳👩‍🍳**

