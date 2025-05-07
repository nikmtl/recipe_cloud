# <img src="https://github.com/Edamame04/recipe_cloud/blob/main/src/img/logo_with_bg.svg" alt="logo" width="30"/> Recipe Cloud – Deine digitale Rezeptwolke

**Recipe Cloud** ist eine Webanwendung zur Verwaltung und zum Austausch von Rezepten. Nutzerinnen und Nutzer können Rezepte erstellen, speichern, durchsuchen und bewerten – alles zentral an einem Ort. Die Anwendung wurde im Rahmen des Moduls *Webengineering 2* entwickelt und basiert auf PHP, HTML, CSS, JavaScript und MySQL.

Das Projekt dient dazu, praktische Erfahrungen mit **PHP** und **SQL** zu sammeln und das Verständnis für serverseitige Webentwicklung mit Datenbankanbindung zu vertiefen.

---

## 🌟 Funktionen

### Öffentlich:
- Startseite mit Rezeptvorschlägen oder neuesten Rezepten
- Rezeptübersicht mit Filter- und Suchfunktion
- Detaillierte Rezeptanzeige (inkl. Zutaten, Zubereitung, Bildern)

### Für registrierte Nutzer:
- Benutzerregistrierung & Login (mit Passwort-Hashing & PHP-Sessions)
- Eigenes Benutzerprofil
- Rezepte erstellen, bearbeiten und löschen
- Rezepte von anderen Nutzern bewerten und kommentieren
- Favoritenliste oder „Merken“-Funktion (optional)

### Optional/Erweiterbar:
- Bild-Upload für Rezepte
- Rezeptkategorien & Tags
- Adminbereich zur Moderation

---

## 🧰 Tech-Stack

- **Frontend:** HTML, CSS, JavaScript
- **Backend:** PHP 8+ (Formularverarbeitung, Sessions)
- **Datenbank:** MySQL / MariaDB
- **Tools:** XAMPP / phpMyAdmin, Git & GitHub

---

## 🗃️ Datenbankmodell (Beispiel)

**users**  
`id` *(PK)*, `username`, `email`, `password_hash`

**recipes**  
`id` *(PK)*, `user_id` *(FK)*, `title`, `description`, `ingredients`, `instructions`, `category`, `image_path`, `created_at`

**ratings**  
`id` *(PK)*, `user_id` *(FK)*, `recipe_id` *(FK)*, `rating`, `comment_text`, `created_at`

**favorites**
`id` *(PK)*, `user_id` *(FK)*, `recipe_id` *(FK)*

---

## 🔧 Lokale Einrichtung

1. Projekt clonen:
   ```bash
   git clone https://github.com/Edamame04/recipe_cloud
