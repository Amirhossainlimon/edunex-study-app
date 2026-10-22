# EduNex — Student Study & Productivity Web App

A Laravel 12 + MySQL server-rendered web application using Blade, Tailwind CSS 4, Alpine.js, and Vite. This is a standalone study/productivity app (tasks, exams, and study sessions), separate from any EduNex school-management project.

## Included features

- Session-based registration, login, logout, login throttling, CSRF protection, and password hashing.
- Personal task CRUD, status workflow (`todo`, `in_progress`, `completed`), due dates, and one-click completion toggle.
- Personal exam CRUD with an upcoming-exam countdown.
- Study-session CRUD plus a Pomodoro timer that saves a completed session to MySQL.
- Dashboard statistics: task completion percentage, upcoming exam, recent tasks, and last-seven-day study minutes.
- Server-side validation via Form Request classes and user-scoped database queries to prevent cross-account record access.
- Responsive Blade layout with Tailwind CSS and Alpine.js.

## Requirements

- PHP 8.2 or newer (Laravel 12 target), Composer 2
- MySQL 8+ or compatible MariaDB
- Node.js 20+ and npm
- A fresh Laravel 12 project

## 1. Create a fresh project

```bash
composer create-project laravel/laravel:^12.0 edunex-study-app
cd edunex-study-app
```

Do not copy this overlay over an existing school-management database or project without reviewing the conflicting files first. It is intended for a separate app.

## 2. Copy this bundle into the Laravel project root

Copy the contents of this bundle into the new `edunex-study-app` folder, preserving folders. Allow the supplied files to merge into `app/`, `database/`, `resources/`, `routes/`, and `tests/`. This bundle supplies a `package.json` and `vite.config.js`; use those in this fresh project.

The default Laravel users migration is retained. It already creates `users` with the required `id`, `name`, `email`, `password`, and timestamps (and Laravel's standard `email_verified_at` / `remember_token` columns).

## 3. Configure MySQL

Create a database, for example `edunex_study_db`, in MySQL. Then update `.env`:

```dotenv
APP_NAME=EduNex
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=edunex_study_db
DB_USERNAME=root
DB_PASSWORD=
```

Use your own MySQL username and password. Do not use an empty password in production. Keep `.env` private and never commit it.

## 4. Install and run

```bash
php artisan key:generate
npm install
php artisan migrate
npm run build
php artisan serve
```

Open `http://127.0.0.1:8000`. Register an account, then add tasks, exams, and study sessions. For hot-reloading development, run `npm run dev` in a second terminal instead of `npm run build`.

## 5. Useful commands

```bash
php artisan route:list
php artisan migrate:status
php artisan test
npm run build
```

The included feature test uses Laravel's normal testing database configuration. Configure an isolated testing database (or SQLite in-memory) before running tests; never point `RefreshDatabase` tests at a database containing important data.

## Data model

- `users`: Laravel's default user table; each user owns their personal data.
- `tasks`: `user_id`, `title`, optional `description`, `status`, optional `due_date`.
- `exams`: `user_id`, `subject`, `exam_date`.
- `study_sessions`: `user_id`, `duration_minutes`, `date`.

Each personal record is fetched through the signed-in user's relationship before it is changed or deleted. The database also enforces foreign keys with `ON DELETE CASCADE`.

## Routes

- `/register`, `/login`, `/logout`
- `/dashboard`
- `/tasks`, `/tasks/create`, `/tasks/{task}/edit`, plus `PATCH /tasks/{task}/toggle`
- `/exams`, `/exams/create`, `/exams/{exam}/edit`
- `/study-sessions`, `/study-sessions/create`, `/study-sessions/{study_session}/edit`

All application data routes require authentication.

## Before production deployment

1. Set `APP_ENV=production`, `APP_DEBUG=false`, configure HTTPS and a real secret `APP_KEY`.
2. Configure a least-privilege database user, scheduled backups, logging, mail, and server-level rate limits.
3. Run `npm ci && npm run build`, then `php artisan config:cache && php artisan route:cache && php artisan view:cache` in your deployment pipeline.
4. Run the test suite against an isolated test database and review it against your own deployment environment.
5. Add verified email/password reset flows, monitoring, privacy/retention policies, and any required accessibility/security review before offering this to real users.

This is a complete functional starter for the requested feature set, not a substitute for environment-specific production review, security testing, backups, or operational setup.
