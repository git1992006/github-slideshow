# Digital Second Brain – Personal Knowledge Vault

A complete responsive database-driven web application built with **PHP, MySQL, HTML, CSS, JavaScript, and Bootstrap**. The project is designed as a polished, presentation-ready personal knowledge management platform where users can capture, organize, search, favorite, archive, and connect notes.

## Features

- User registration, login, logout, sessions, and hashed passwords.
- Landing page with startup-style hero section and call-to-action.
- Dashboard with quick stats, recent notes, and favorite notes.
- Notes CRUD with category, tags, priority, status, color label, pin/favorite controls, and timestamps.
- Category management and automatic tag library.
- Search and filters by keyword, category, tag, priority, and sort order.
- Linked notes to simulate a lightweight second-brain graph.
- Archive flow instead of permanent-only deletion.
- Random note resurfacing and TXT export.
- Responsive UI with Bootstrap 5 and a dark mode toggle.

## Folder Structure

```text
/workspace/github-slideshow
├── add_note.php
├── archive_note.php
├── archived_notes.php
├── assets/
│   ├── css/style.css
│   └── js/app.js
├── categories.php
├── dashboard.php
├── db_connect.php
├── delete_note.php
├── edit_note.php
├── export_note.php
├── footer.php
├── header.php
├── includes/functions.php
├── index.php
├── login.php
├── logout.php
├── note_form.php
├── notes.php
├── random_note.php
├── register.php
├── sql/
│   ├── sample_data.sql
│   └── schema.sql
├── tags.php
├── toggle_favorite.php
└── view_note.php
```

## Database Schema

The relational design includes these tables:

- `users`
- `categories`
- `notes`
- `tags`
- `note_tags`
- `linked_notes`

The SQL files are located in `sql/schema.sql` and `sql/sample_data.sql`.

## Demo Credentials

Sample demo user loaded by `sample_data.sql`:

- **Email:** `demo@example.com`
- **Password:** `password`

## How to Run on XAMPP or MAMP

1. Copy this project folder into your web root.
   - XAMPP: `htdocs/digital-second-brain`
   - MAMP: `htdocs/digital-second-brain`
2. Start **Apache** and **MySQL**.
3. Create the database and tables:
   - Open **phpMyAdmin**.
   - Import `sql/schema.sql`.
   - Import `sql/sample_data.sql` if you want demo content.
4. Update the database credentials in `db_connect.php` if your MySQL username, password, or database name differs.
5. Open the project in your browser:
   - `http://localhost/digital-second-brain/`
6. Register a new account or log in with the sample credentials.

## CRUD Demonstration Guide

- **Create:** Add a new note from `add_note.php`.
- **Read:** View all notes from `notes.php` and detailed note pages from `view_note.php`.
- **Update:** Edit any note from `edit_note.php`.
- **Delete:** Permanently remove a note from `delete_note.php`.
- **Archive:** Move notes in and out of archive using `archive_note.php` and `archived_notes.php`.

## Security Notes

- Passwords are stored using `password_hash()`.
- Authentication uses PHP sessions.
- Database reads and writes use prepared statements.
- Output is escaped with `htmlspecialchars()`.

## Project Presentation Tips

- Show the landing page first to demonstrate product-style branding.
- Register/login to demonstrate authentication.
- Use the dashboard to explain quick stats and favorites.
- Create a note with tags and linked notes to highlight relational design.
- Demonstrate filters, random note, archive, and export features.
