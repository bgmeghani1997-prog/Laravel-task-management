# Mini Task Management System (Laravel 11)

## 📌 Project Overview

This is a **Mini Task Management System** built using **Laravel 11**.
It includes both **Web (Blade UI)** and **REST API** features with authentication, task management, and background job processing.

---

## 🚀 Features

### 1. User Authentication

* User Registration & Login
* API Authentication using **Laravel Sanctum**
* Only authenticated users can manage tasks

---

### 2. Task Management (CRUD)

Each task contains:

* **Title** (required)
* **Description** (optional)
* **Status** (pending, in-progress, completed)
* **Due Date** (required)

Users can:

* Create tasks
* View tasks (with pagination)
* Update tasks
* Delete tasks

---

### 3. Background Job (Queue)

* A scheduled job runs daily
* It checks for tasks due **tomorrow**
* Sends **email reminders** to users

---

### 4. REST API Endpoints

| Method | Endpoint          | Description            |
| ------ | ----------------- | ---------------------- |
| GET    | `/api/tasks`      | List tasks (paginated) |
| POST   | `/api/tasks`      | Create task            |
| PUT    | `/api/tasks/{id}` | Update task            |
| DELETE | `/api/tasks/{id}` | Delete task            |

✅ All responses are in **JSON format** with proper HTTP status codes.

---

### 5. Web Interface (Blade)

* Dashboard with task list
* Add new task form
* Edit/Delete actions
* Pagination support
* Basic UI using Bootstrap/Tailwind

---

### 6. Bonus Features

* Task filtering (by status or due date)
* Role-based access (Admin/User)

---

## 🛠️ Installation & Setup

### 1. Clone the Repository

```bash
git clone <your-repo-url>
cd task-management-system
```

---

### 2. Install Dependencies

```bash
composer install
npm install
npm run build
```

---

### 3. Environment Configuration

Copy `.env.example` to `.env`:

```bash
cp .env.example .env
```

Update the following:

#### Database Configuration (MySQL)

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

#### Mail Configuration

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="noreply@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```

### 5. Run Migrations & Seed Database

```bash
php artisan migrate:fresh --seed
```

---

### 6. Install Sanctum

```bash
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
php artisan migrate
```

---

### 7. Run the Application

```bash
php artisan serve
```

Visit:

```
http://127.0.0.1:8000
```

---

## ⚙️ Queue & Scheduler Setup

### Run Queue Worker

```bash
php artisan queue:work
```

### Run Scheduler
php artisan app:send-task-reminders

## 📧 Email Reminder Logic

* A scheduled command runs daily
* Finds tasks with **due_date = tomorrow**
* Sends reminder emails to users

---

## 📂 Project Structure (Important Files)

```
app/
 ├── Models/Task.php
 ├── Http/Controllers/
 │    ├── TaskController.php
 │    └── Api/TaskController.php
 ├── Jobs/SendTaskReminderJob.php
 └── Console/Kernel.php

database/
 ├── migrations/
 └── seeders/

resources/views/
 ├── tasks/
 └── layouts/
```

---

## ✅ Validation & Error Handling

* Form validation using Laravel validation rules
* API returns structured JSON errors
* Proper HTTP status codes used (200, 201, 422, 404, etc.)

---

## 🔐 Authentication Notes

* Web uses session-based authentication
* API uses **Sanctum tokens**

---

## 🧪 Testing API

Use tools like Postman:

* Set headers:

```json
Authorization: Bearer {token}
Accept: application/json
```

## 📎 Notes

* Make sure queue worker is running for email reminders
* Ensure correct mail credentials
* Use `php artisan migrate:fresh --seed` to reset database

---

🚀 Ready to use!
