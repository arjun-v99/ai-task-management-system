# Task Manager — Laravel Senior Machine Test

A production-ready AI-assisted Task Management System built with Laravel 12, following clean architecture principles including the Repository Pattern, Service Layer, and AI integration via the Claude API.

---

## Tech Stack

| Layer | Technology |
|-------|------------|
| Backend | Laravel 12 |
| Frontend | Blade + Tailwind CSS |
| Auth | Laravel Breeze |
| Database | MySQL (via WAMP) |
| AI | Claude API (claude-sonnet-4-20250514) |
| Local Server | WAMP Server |
| PHP | 8.2.0 |

---

## Architecture Overview

```
HTTP Request
    └── Controller          (thin — only calls Service)
            └── Service     (business logic + transactions + AI trigger)
                    ├── RepositoryInterface   (contract)
                    │       └── Repository    (Eloquent implementation)
                    └── AIService             (prompt + API call + mock fallback)
```

### Why Repository Pattern?
- Controllers contain zero Eloquent/database calls
- Business logic is isolated in the Service layer
- Easy to swap database implementation without touching business logic
- Cleaner, more testable code

---

## Project Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── TaskController.php
│   │   └── DashboardController.php
│   ├── Requests/
│   │   ├── StoreTaskRequest.php
│   │   └── UpdateTaskRequest.php
│   └── Resources/
│       └── TaskResource.php
├── Models/
│   ├── Task.php
│   └── User.php
├── Repositories/
│   ├── Contracts/
│   │   └── TaskRepositoryInterface.php
│   └── Eloquent/
│       └── TaskRepository.php
├── Services/
│   ├── TaskService.php
│   └── AIService.php
├── Policies/
│   └── TaskPolicy.php
├── Enums/
│   ├── TaskStatus.php
│   └── TaskPriority.php
└── Providers/
    └── RepositoryServiceProvider.php
```

---

## Things to Know Before Running



### Roles
The app has two roles — `admin` and `user`. Admins see and manage all tasks. Regular users only see tasks assigned to them. Make sure you seed the database (see below) to get test accounts for both roles.

---

## Steps to Run

### 1. Clone the repository
```bash
git clone <your-repo-url>
cd task-manager
```

### 2. Install dependencies
```bash
composer install
npm install
```

### 3. Environment setup
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Configure your database in `.env`
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=task_manager
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Add your Claude API key in `.env`
```env
ANTHROPIC_API_KEY=sk-ant-xxxxxxxxxxxxxxxx
```
> Leave this empty if you want to use mock AI responses instead.

### Important: Before you proceed further make sure you have created the DB as specified in your .env file and the DB_PORT, DB_USERNAME and DB_PASSWORD matches according to your current system configuration.

### 6. Run migrations and seed
```bash
php artisan migrate
php artisan db:seed --class=UserSeeder
```

### 7. Build frontend assets
```bash
npm run dev
```

### 8. Serve the application
```bash
php artisan serve
```

Visit: http://localhost:8000

---

## Default Test Accounts

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@test.com | password |
| User | user@test.com | password |

---

## AI Integration

**Provider:** Anthropic Claude (`claude-sonnet-4-20250514`)

**Trigger:** AI is automatically called every time a task is created or its title/description is updated.

**What it returns:**
- `ai_summary` — a concise one-sentence summary of the task
- `ai_priority` — a suggested priority level (`low`, `medium`, or `high`)

**Prompt used:**
```
You are a project management assistant. Given the following task details,
provide a concise one-sentence summary and suggest a priority level (low, medium, high).
Respond ONLY in raw JSON with no extra text or markdown:
{ "ai_summary": "...", "ai_priority": "high|medium|low" }

Task Title: {title}
Task Description: {description}
```

**Flow:**
```
TaskController → TaskService → (creates task) → AIService → Claude API
                                                           ↓
                                             saves ai_summary + ai_priority back to task
```

**Mock fallback:** If `ANTHROPIC_API_KEY` is missing or the API call fails, `AIService` automatically returns a safe placeholder response so the app continues to work normally. Errors are logged to `storage/logs/laravel.log`.

---

## API Endpoints

All endpoints require authentication via Laravel Sanctum.

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/tasks` | List all tasks (paginated) |
| POST | `/api/tasks` | Create a new task |
| GET | `/api/tasks/{id}` | Get a single task |
| PUT | `/api/tasks/{id}` | Update a task |
| DELETE | `/api/tasks/{id}` | Delete a task |

Responses follow the `TaskResource` format with proper HTTP status codes.

---

## Authorization

Task access is controlled via `TaskPolicy`:

| Action | Admin | User |
|--------|-------|------|
| View all tasks | ✅ | ❌ |
| View assigned tasks | ✅ | ✅ |
| Create task | ✅ | ✅ |
| Edit task | ✅ | Own only |
| Delete task | ✅ | ❌ |