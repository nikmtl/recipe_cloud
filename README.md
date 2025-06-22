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
- User Profile and stats
- Create, edit and delete Recipes
- Rate and Comment other people's Recipes
- Favorite List to remember Recipes

---

## 🧰 Tech-Stack

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

---

## � Requirements

- **PHP:** 8.0 or higher
- **MySQL:** 5.7 or higher
- **Web Server:** Apache/Nginx (XAMPP, WAMP, or LAMP)
- **Browser:** Modern web browser with JavaScript enabled

---

## �🔧 Local Setup

1. Clone the project:
   ```bash
   git clone https://github.com/Edamame04/recipe_cloud
   ```

2. Create MySQL-Database, e. G. `recipe_cloud`

3. Configure database connection in `be-logic/db.php`:

   ```php
   $host = 'localhost';
   $db   = 'recipe_cloud';
   $user = 'root';
   $pass = '';
   ```

4. Open project in local Webserver (e. G. mit XAMPP):

   ```
   http://localhost/recipe_cloud/
   ```

In the future this project will be hosted so that users can share recipes around the world
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

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

---

## 📝 License

This project is created for educational purposes as part of the *Web Engineering 2* module. Feel free to use and modify for learning purposes.

---

## 🔮 Future Enhancements

- [ ] Mobile app development
- [ ] Multi-language support

---

## 📧 Contact

For questions or suggestions regarding this project, please open an issue on GitHub.

**Happy Cooking! 👨‍🍳👩‍🍳**

