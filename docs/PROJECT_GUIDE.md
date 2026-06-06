# 🎬 MovPrima — Movie Review & Recommendation Website

### UAS Pemrograman Web · STMIK Primakara · SI/Malam 2026

> **Dosen:** I Putu Satwika, S.Kom., M.Kom
> **Framework:** CodeIgniter 4 · Tailwind CSS v4 · MySQL

---

## 📋 Table of Contents

1. [Team & Division of Work](#1-team--division-of-work)
2. [Tech Stack](#2-tech-stack)
3. [Application Flow](#3-application-flow)
4. [Database Design](#4-database-design)
5. [Page List & Routes](#5-page-list--routes)
6. [MVC Structure](#6-mvc-structure)
7. [Assessment Checklist](#7-assessment-checklist)
8. [Dev Setup](#8-dev-setup)
9. [Individual Task Lists](#9-individual-task-lists)
   - [9.1 Nata — Database & Frontend](#91-nata--database--frontend)
   - [9.2 Shyfa — Movie, Review & Genre Backend](#92-shyfa--movie-review--genre-backend)
   - [9.3 Riski — Authentication Backend](#93-riski--authentication-backend)
   - [9.4 Gita — User, Profile & Admin Panel](#94-gita--user-profile--admin-panel)

---

## 1. Team & Division of Work

| #   | Name      | Role                              | Scope                                                                                                                                     |
| --- | --------- | --------------------------------- | ----------------------------------------------------------------------------------------------------------------------------------------- |
| 1   | **Nata**  | Database Architect + Frontend Dev | Database design, all 7 migrations, seeds, all View files (Tailwind CSS v4), CSS animations, JS interactions                               |
| 2   | **Shyfa** | Backend — Movie, Review & Genre   | MovieController, ReviewController, GenreController, MovieModel, ReviewModel, GenreModel, WatchlistModel, AJAX endpoints, avg_rating logic |
| 3   | **Riski** | Backend — Authentication          | Auth.php controller (login, register, logout), AuthFilter, AdminFilter, session management, CSRF handling                                 |
| 4   | **Gita**  | Backend — User & Admin Panel      | User.php controller (profile, edit), UserModel, Admin.php controller (dashboard, user list)                                               |

---

## 2. Tech Stack

| Layer        | Technology    | Version | Purpose                                                        |
| ------------ | ------------- | ------- | -------------------------------------------------------------- |
| Backend      | CodeIgniter 4 | ^4.x    | MVC framework, routing, ORM                                    |
| Frontend CSS | Tailwind CSS  | v4.3    | Utility-first styling                                          |
| Bundler      | Tailwind CLI  | v4.3    | Compiles `resources/css/app.css` → `public/assets/css/app.css` |
| Database     | MySQL 8       | 8.x     | Relational data storage                                        |
| Runtime      | PHP           | ≥8.1    | Server-side execution                                          |
| Package Mgr  | Bun           | latest  | Fast JS/CSS dependency management                              |

---

## 3. Application Flow

### 3.1 High-Level User Journey

```
[Guest]
  │
  ├─→ Landing Page (/)             → Browse featured movies
  ├─→ Movie List (/movies)         → Search, filter by genre/year/rating
  ├─→ Movie Detail (/movies/{slug}) → Read synopsis, cast, reviews
  ├─→ Genre List (/genres)         → Browse all genres
  ├─→ Genre Detail (/genres/{slug}) → Movies by specific genre
  ├─→ Login (/auth/login)          → Authenticate
  └─→ Register (/auth/register)    → Create account
         │
         ▼
[Logged-in User]
  ├─→ Movie Detail (/movies/{slug})
  │     ├─→ Write Review (Modal)
  │     ├─→ Edit/Delete own review
  │     ├─→ Add to Watchlist
  │     └─→ Like a review
  └─→ Profile (/profile)           → My reviews, my watchlist

         │
         ▼ (if role = admin)
[Admin]
  ├─→ Dashboard (/admin)
  ├─→ Manage Movies (CRUD)         → /admin/movies
  ├─→ Manage Genres (CRUD)         → /admin/genres
  ├─→ Manage Users                 → /admin/users
  └─→ Manage Reviews               → /admin/reviews
```

### 3.2 Request Lifecycle (CodeIgniter 4 MVC)

```
Browser Request
      │
      ▼
 Routes.php  ──→  Filter (AuthFilter / AdminFilter)
      │                   │ (redirect if not authenticated)
      ▼                   ▼
 Controller (e.g. MovieController::show)
      │
      ├─→ Model::findBySlug($slug)  ──→  MySQL Query
      │         └─→ returns $movie object
      │
      ├─→ ReviewModel::getByMovie($movieId, paginate: true)
      │
      └─→ return view('movies/detail', compact('movie', 'reviews'))
                │
                ▼
           View (PHP template + DaisyUI components)
                │
                ▼
           Response → Browser renders HTML + Tailwind CSS
```

---

## 4. Database Design

### 4.1 Entity Relationship Diagram (ERD)

```
users ──────────< reviews >──────────── movies
  │                  │                    │
  │            review_likes          movie_genres >── genres
  │
  └──────────< watchlist >──────────── movies
```

### 4.2 Table Definitions (Full DDL)

```sql
-- ============================================================
-- 1. users
-- ============================================================
CREATE TABLE users (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(100)  NOT NULL,
    email       VARCHAR(150)  NOT NULL UNIQUE,
    password    VARCHAR(255)  NOT NULL,             -- bcrypt hash
    role        ENUM('user','admin') NOT NULL DEFAULT 'user',
    bio         TEXT          NULL,
    email_verified_at TIMESTAMP NULL,
    created_at  DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at  DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_role  (role)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 2. genres
-- ============================================================
CREATE TABLE genres (
    id          SMALLINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(60)  NOT NULL,
    slug        VARCHAR(70)  NOT NULL UNIQUE,
    created_at  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 3. movies
-- ============================================================
CREATE TABLE movies (
    id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title        VARCHAR(200) NOT NULL,
    slug         VARCHAR(220) NOT NULL UNIQUE,       -- URL-friendly title
    synopsis     TEXT         NOT NULL,
    director     VARCHAR(120) NULL,
    release_year YEAR         NOT NULL,
    duration     SMALLINT UNSIGNED NULL,             -- minutes
    poster       VARCHAR(255) NULL,                  -- path or URL
    backdrop     VARCHAR(255) NULL,
    trailer_url  VARCHAR(255) NULL,                  -- YouTube embed URL
    language     VARCHAR(50)  NOT NULL DEFAULT 'English',
    country      VARCHAR(80)  NULL,
    status       ENUM('published','draft') NOT NULL DEFAULT 'published',
    avg_rating   DECIMAL(3,2) NOT NULL DEFAULT 0.00, -- denormalized, updated on review CUD
    review_count INT UNSIGNED NOT NULL DEFAULT 0,
    created_at   DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at   DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_slug        (slug),
    INDEX idx_status      (status),
    INDEX idx_release_year(release_year),
    INDEX idx_avg_rating  (avg_rating)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 4. movie_genres  (pivot — many-to-many)
-- ============================================================
CREATE TABLE movie_genres (
    movie_id  INT UNSIGNED     NOT NULL,
    genre_id  SMALLINT UNSIGNED NOT NULL,
    PRIMARY KEY (movie_id, genre_id),
    CONSTRAINT fk_mg_movie  FOREIGN KEY (movie_id)  REFERENCES movies(id) ON DELETE CASCADE,
    CONSTRAINT fk_mg_genre  FOREIGN KEY (genre_id)  REFERENCES genres(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 5. reviews
-- ============================================================
CREATE TABLE reviews (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id     INT UNSIGNED NOT NULL,
    movie_id    INT UNSIGNED NOT NULL,
    rating      TINYINT UNSIGNED NOT NULL CHECK (rating BETWEEN 1 AND 10),
    title       VARCHAR(200) NOT NULL,
    body        TEXT         NOT NULL,
    is_spoiler  TINYINT(1)   NOT NULL DEFAULT 0,
    likes_count INT UNSIGNED NOT NULL DEFAULT 0,    -- denormalized counter
    status      ENUM('published','flagged','removed') NOT NULL DEFAULT 'published',
    created_at  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE  uniq_user_movie (user_id, movie_id),    -- one review per user per movie
    CONSTRAINT fk_rev_user  FOREIGN KEY (user_id)  REFERENCES users(id)  ON DELETE CASCADE,
    CONSTRAINT fk_rev_movie FOREIGN KEY (movie_id) REFERENCES movies(id) ON DELETE CASCADE,
    INDEX idx_movie_id  (movie_id),
    INDEX idx_rating    (rating),
    INDEX idx_created   (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 6. review_likes
-- ============================================================
CREATE TABLE review_likes (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id     INT UNSIGNED NOT NULL,
    review_id   INT UNSIGNED NOT NULL,
    created_at  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE uniq_like (user_id, review_id),
    CONSTRAINT fk_rl_user   FOREIGN KEY (user_id)   REFERENCES users(id)   ON DELETE CASCADE,
    CONSTRAINT fk_rl_review FOREIGN KEY (review_id) REFERENCES reviews(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 7. watchlist
-- ============================================================
CREATE TABLE watchlist (
    id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id    INT UNSIGNED NOT NULL,
    movie_id   INT UNSIGNED NOT NULL,
    status     ENUM('want_to_watch','watching','watched') NOT NULL DEFAULT 'want_to_watch',
    created_at DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE uniq_watchlist (user_id, movie_id),
    CONSTRAINT fk_wl_user  FOREIGN KEY (user_id)  REFERENCES users(id)  ON DELETE CASCADE,
    CONSTRAINT fk_wl_movie FOREIGN KEY (movie_id) REFERENCES movies(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### 4.3 Seed Data (Genre & Sample Movies)

```sql
-- genres
INSERT INTO genres (name, slug) VALUES
  ('Action',      'action'),
  ('Comedy',      'comedy'),
  ('Drama',       'drama'),
  ('Horror',      'horror'),
  ('Sci-Fi',      'sci-fi'),
  ('Romance',     'romance'),
  ('Animation',   'animation'),
  ('Thriller',    'thriller'),
  ('Documentary', 'documentary'),
  ('Fantasy',     'fantasy');

-- sample admin user  (password: admin123)
INSERT INTO users (name, email, password, role) VALUES
  ('Admin MovPrima', 'admin@movprima.com',
   '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');
```

---

## 5. Page List & Routes

### 5.1 Public Routes (No Auth Required)

| Method | URL              | Controller::Method   | View File           | Description                             |
| ------ | ---------------- | -------------------- | ------------------- | --------------------------------------- |
| GET    | `/`              | `Home::index`        | `home/index.php`    | Landing page — hero, featured, trending |
| GET    | `/movies`        | `Movie::index`       | `movies/index.php`  | Browse all movies + filter/search       |
| GET    | `/movies/{slug}` | `Movie::show`        | `movies/detail.php` | Movie detail + reviews                  |
| GET    | `/genres/{slug}` | `Genre::show`        | `genres/show.php`   | Movies filtered by genre                |
| GET    | `/auth/login`    | `Auth::loginForm`    | `auth/login.php`    | Login form                              |
| GET    | `/auth/register` | `Auth::registerForm` | `auth/register.php` | Register form                           |
| POST   | `/auth/login`    | `Auth::login`        | —                   | Process login                           |
| POST   | `/auth/register` | `Auth::register`     | —                   | Process register                        |
| GET    | `/auth/logout`   | `Auth::logout`       | —                   | Destroy session                         |

### 5.2 Protected Routes (Auth Required)

| Method | URL                      | Controller::Method   | View File            | Description                 |
| ------ | ------------------------ | -------------------- | -------------------- | --------------------------- |
| GET    | `/profile`               | `User::profile`      | `user/profile.php`   | My info, reviews, watchlist |
| GET    | `/profile/edit`          | `User::editForm`     | `user/edit.php`      | Edit profile form           |
| POST   | `/profile/edit`          | `User::update`       | —                    | Save profile changes        |
| GET    | `/reviews/create`        | `Review::createForm` | `reviews/create.php` | Write a review              |
| POST   | `/reviews`               | `Review::store`      | —                    | Submit review               |
| GET    | `/reviews/{id}/edit`     | `Review::editForm`   | `reviews/edit.php`   | Edit own review             |
| POST   | `/reviews/{id}`          | `Review::update`     | —                    | Update review               |
| POST   | `/reviews/{id}/delete`   | `Review::destroy`    | —                    | Delete own review           |
| POST   | `/reviews/{id}/like`     | `Review::like`       | —                    | Toggle like (AJAX)          |
| POST   | `/watchlist`             | `Watchlist::store`   | —                    | Add to watchlist (AJAX)     |
| POST   | `/watchlist/{id}`        | `Watchlist::update`  | —                    | Update watchlist status     |
| POST   | `/watchlist/{id}/delete` | `Watchlist::destroy` | —                    | Remove from watchlist       |

### 5.3 Admin Routes (Role = admin)

| Method | URL                          | Controller::Method       | View File                 | Description      |
| ------ | ---------------------------- | ------------------------ | ------------------------- | ---------------- |
| GET    | `/admin`                     | `Admin\Dashboard::index` | `admin/dashboard.php`     | Stats overview   |
| GET    | `/admin/movies`              | `Admin\Movie::index`     | `admin/movies/index.php`  | Movie list table |
| GET    | `/admin/movies/create`       | `Admin\Movie::create`    | `admin/movies/form.php`   | Add movie form   |
| POST   | `/admin/movies`              | `Admin\Movie::store`     | —                         | Save new movie   |
| GET    | `/admin/movies/{id}/edit`    | `Admin\Movie::edit`      | `admin/movies/form.php`   | Edit movie form  |
| POST   | `/admin/movies/{id}`         | `Admin\Movie::update`    | —                         | Update movie     |
| POST   | `/admin/movies/{id}/delete`  | `Admin\Movie::destroy`   | —                         | Delete movie     |
| GET    | `/admin/genres`              | `Admin\Genre::index`     | `admin/genres/index.php`  | Genre list       |
| POST   | `/admin/genres`              | `Admin\Genre::store`     | —                         | Add genre        |
| POST   | `/admin/genres/{id}`         | `Admin\Genre::update`    | —                         | Update genre     |
| POST   | `/admin/genres/{id}/delete`  | `Admin\Genre::destroy`   | —                         | Delete genre     |
| GET    | `/admin/reviews`             | `Admin\Review::index`    | `admin/reviews/index.php` | Moderate reviews |
| POST   | `/admin/reviews/{id}/delete` | `Admin\Review::destroy`  | —                         | Remove review    |
| GET    | `/admin/users`               | `Admin\User::index`      | `admin/users/index.php`   | User list        |
| POST   | `/admin/users/{id}/delete`   | `Admin\User::destroy`    | —                         | Delete user      |

---

## 6. MVC Structure

### 6.1 Directory Tree

```
movprima/
├── app/
│   ├── Config/
│   │   ├── Routes.php          ← All route definitions
│   │   └── Filters.php         ← Register AuthFilter, AdminFilter
│   │
│   ├── Controllers/
│   │   ├── BaseController.php         ← CI4 base (don't edit)
│   │   ├── Home.php                   ← Landing page [Gita]
│   │   ├── Auth.php                   ← Login, register, logout [Riski]
│   │   ├── Movie.php                  ← Public movie list & detail [Shyfa]
│   │   ├── Genre.php                  ← Genre browsing [Shyfa]
│   │   ├── Review.php                 ← Reviews CRUD + like toggle [Shyfa]
│   │   ├── Watchlist.php              ← Watchlist CRUD + AJAX [Shyfa]
│   │   ├── User.php                   ← Profile view & edit [Gita]
│   │   └── Admin/                     ← Namespace: App\Controllers\Admin
│   │       ├── Dashboard.php          ← Stats overview [Gita]
│   │       ├── Movie.php              ← Admin movies CRUD [Gita]
│   │       ├── Genre.php              ← Admin genres CRUD [Gita]
│   │       ├── Review.php             ← Review moderation [Gita]
│   │       └── User.php               ← User management [Gita]
│   │
│   ├── Filters/
│   │   ├── AuthFilter.php      ← Redirect to /auth/login if not logged in
│   │   └── AdminFilter.php     ← Redirect to / if role != admin
│   │
│   ├── Models/
│   │   ├── UserModel.php
│   │   ├── MovieModel.php
│   │   ├── GenreModel.php
│   │   ├── ReviewModel.php
│   │   ├── ReviewLikeModel.php
│   │   └── WatchlistModel.php
│   │
│   ├── Database/
│   │   ├── Migrations/
│   │   │   ├── 2026-05-01-000001_CreateUsersTable.php
│   │   │   ├── 2026-05-01-000002_CreateGenresTable.php
│   │   │   ├── 2026-05-01-000003_CreateMoviesTable.php
│   │   │   ├── 2026-05-01-000004_CreateMovieGenresTable.php
│   │   │   ├── 2026-05-01-000005_CreateReviewsTable.php
│   │   │   ├── 2026-05-01-000006_CreateReviewLikesTable.php
│   │   │   └── 2026-05-01-000007_CreateWatchlistTable.php
│   │   └── Seeds/
│   │       ├── AdminUserSeeder.php
│   │       ├── DatabaseSeeder.php
│   │       ├── GenreSeeder.php
│   │       ├── MovieSeeder.php
│   │       ├── ReviewSeeder.php
│   │       └── UserSeeder.php
│   │
│   └── Views/
│       ├── admin/
│       │   ├── dashboard/
│       │   │   └── index.php
│       │   ├── genres/
│       │   │   └── index.php
│       │   ├── movies/
│       │   │   ├── form.php
│       │   │   └── index.php
│       │   ├── reviews/
│       │   │   └── index.php
│       │   └── users/
│       │       └── index.php
│       ├── auth/
│       │   ├── login.php
│       │   └── register.php
│       ├── errors/
│       │   ├── cli/
│       │   └── html/
│       ├── genres/
│       │   ├── index.php
│       │   └── show.php
│       ├── home/
│       │   └── index.php
│       ├── layouts/
│       │   ├── admin.php
│       │   └── main.php
│       ├── movies/
│       │   ├── detail.php
│       │   └── index.php
│       ├── pagers/
│       │   └── custom_tailwind.php
│       ├── partials/
│       │   └── movie_item.php
│       ├── reviews/
│       │   ├── create.php
│       │   └── edit.php
│       └── user/
│           ├── edit.php
│           └── profile.php
│
├── resources/
│   └── css/
│       └── app.css             ← Tailwind source (compiled by bun)
│
└── public/
    └── assets/
        └── css/
            └── app.css         ← Compiled output (DO NOT edit manually)
```

### 6.2 Model Responsibilities

| Model             | Key Methods                                                                                   | Notes                                      |
| ----------------- | --------------------------------------------------------------------------------------------- | ------------------------------------------ |
| `GenreModel`      | `getAllWithCount()`, `findBySlug()`                                                           | `COUNT` join for movie count per genre     |
| `MovieModel`      | `getWithGenres()`, `findBySlug()`, `search()`, `paginate()`, `getTopRated()`, `getFeatured()` | Uses JOIN with `movie_genres` and `genres` |
| `ReviewLikeModel` | `toggle()`, `hasLiked()`, `syncCounter()`                                                     | Likes system functionality                 |
| `ReviewModel`     | `getByMovie()`, `getByUser()`, `getLatest()`, `updateMovieRating()`                           | Calls `movies.avg_rating` update after CUD |
| `UserModel`       | `findByEmail()`, `createUser()`, `updateProfile()`                                            | Password hashing via `password_hash()`     |
| `WatchlistModel`  | `toggle()`, `getUserList()`, `getByStatus()`                                                  | Upsert pattern                             |

### 6.3 Complex Query Examples (for "CRUD + query complex" criteria)

```php
// MovieModel.php — getTopRated with genre filter
public function getTopRated(int $limit = 10, ?string $genreSlug = null): array
{
    $builder = $this->db->table('movies m')
        ->select('m.*, GROUP_CONCAT(g.name SEPARATOR ", ") AS genres')
        ->join('movie_genres mg', 'mg.movie_id = m.id', 'left')
        ->join('genres g', 'g.id = mg.genre_id', 'left')
        ->where('m.status', 'published')
        ->orderBy('m.avg_rating', 'DESC')
        ->groupBy('m.id')
        ->limit($limit);

    if ($genreSlug) {
        $builder->where('g.slug', $genreSlug);
    }

    return $builder->get()->getResultArray();
}

// ReviewModel.php — update denormalized avg_rating after a review change
public function updateMovieRating(int $movieId): void
{
    $this->db->query("
        UPDATE movies
        SET avg_rating   = (SELECT IFNULL(AVG(rating), 0) FROM reviews WHERE movie_id = ? AND status = 'published'),
            review_count = (SELECT COUNT(*)               FROM reviews WHERE movie_id = ? AND status = 'published')
        WHERE id = ?
    ", [$movieId, $movieId, $movieId]);
}
```

---

## 7. Assessment Checklist

> Map every rubric criterion → what we implement.

### ✅ Tampilan (UI/UX) — Target: Sangat Baik (4)

- [x] Hero section with backdrop gradient + animated text
- [x] Movie cards with poster, rating badge, genre chips
- [x] Hover scale animation on cards (`transition-transform`)
- [x] Star rating component (interactive, CSS only)
- [x] Skeleton loading state for movie grid
- [x] Responsive navbar with hamburger menu on mobile
- [x] Toast notifications for success/error feedback
- [x] Smooth page transitions using CSS

### ✅ Error Handling — Target: Sangat Baik (4)

- [x] CI4 validation rules on all forms (required, min_length, valid_email, etc.)
- [x] 404 custom view for missing movies/pages
- [x] CSRF token on all POST forms (`csrf_field()`)
- [x] SQL errors caught with try/catch in controllers
- [x] Auth filter returns 401/redirect (not PHP fatal error)
- [x] Zero browser console errors or warnings
- [x] Zero PHP warnings/notices in terminal

### ✅ CRUD — Target: Sangat Baik (4)

| Entity    | Create            | Read                 | Update          | Delete                 |
| --------- | ----------------- | -------------------- | --------------- | ---------------------- |
| Movie     | Admin form        | Public list & detail | Admin form      | Admin soft/hard delete |
| Genre     | Admin inline form | Browse page          | Admin modal     | Admin delete           |
| Review    | Logged-in form    | Movie detail page    | Own review edit | Own review delete      |
| Watchlist | One-click add     | Profile page list    | Status toggle   | Remove button          |
| User      | Register          | Profile page         | Edit profile    | Admin delete           |

### ✅ Kreatifitas — Target: Sangat Baik (4)

- [x] Star-rating UI with half-star display (1–10 scale → 5 stars)
- [x] Watchlist with 3 statuses (Want to Watch / Watching / Watched)
- [x] Spoiler toggle on review body
- [x] Review like system (heart icon, AJAX)
- [x] Genre filter chips on movie list (no page reload)
- [x] Admin dashboard with stat cards (total movies, reviews, users)

### ✅ Struktur Kode — Target: Sangat Baik (4)

- [x] Strict MVC — no DB queries in views
- [x] Base layout template with `renderSection()` / `extend()`
- [x] Filters for auth (`AuthFilter`, `AdminFilter`)
- [x] Named routes in `Routes.php` for easy URL generation
- [x] Models use CI4 `Model` class (validation rules, return types defined)
- [x] `resources/css/app.css` compiled → `public/assets/css/app.css` (never edit public directly)
- [x] `.editorconfig` enforced (already present in repo)

---

## 8. Dev Setup

### First-time Setup (all team members)

```bash
# 1. Clone repo
git clone <repo-url>
cd movprima

# 2. Install PHP deps
composer install

# 3. Install JS/CSS deps
bun install

# 4. Copy env file and configure
cp env .env
# Edit .env: set CI_ENVIRONMENT, database credentials

# 5. Run migrations + seeds
php spark migrate
php spark db:seed DatabaseSeeder

# 6. Start servers (two terminals)
php spark serve          # → http://localhost:8080
bun run dev              # → watches Tailwind CSS changes
```

### .env Key Settings

```ini
CI_ENVIRONMENT = development

app.baseURL = 'http://localhost:8080/'
app.forceGlobalSecureRequests = false

database.default.hostname = 127.0.0.1
database.default.database  = movprima_db
database.default.username  = root
database.default.password  =
database.default.DBDriver  = MySQLi
database.default.DBPrefix  =
database.default.port      = 3306
```

### Git Workflow

```
main          ← stable, demo-ready
  └── develop     ← integration branch
        ├── feat/database-migrations   ← Nata
        ├── feat/frontend-views        ← Nata
        ├── feat/movies-reviews-genre  ← Shyfa
        ├── feat/auth                  ← Riski
        └── feat/user-admin            ← Gita
```

> **Rule:** Never push directly to `main`. Always PR into `develop`, then merge `develop` → `main` before demo.

---

## 9. Individual Task Lists

---

### 9.1 Nata — Database & Frontend

> **Role:** Database Architect + Frontend Developer

#### Phase 1 — Database Migrations

- [x] `2026-05-12-170001_CreateUsersTable.php` — users table (id, name, email, password, role, avatar, bio, timestamps)
- [x] `2026-05-12-170002_CreateGenresTable.php` — genres table (id, name, slug, timestamps)
- [x] `2026-05-12-170003_CreateMoviesTable.php` — movies table (id, title, slug, synopsis, director, release_year, duration, poster, backdrop, trailer_url, language, country, status, avg_rating, review_count, timestamps)
- [x] `2026-05-12-170004_CreateMovieGenresTable.php` — pivot table + FK constraints
- [x] `2026-05-12-170005_CreateReviewsTable.php` — reviews table + unique(user_id, movie_id)
- [x] `2026-05-12-170006_CreateReviewLikesTable.php` — review_likes table + unique(user_id, review_id)
- [x] `2026-05-12-170007_CreateWatchlistTable.php` — watchlist table + unique(user_id, movie_id)
- [x] Run `php spark migrate` — completed with zero errors ✅ (Batch 1, 2026-05-12)

#### Phase 2 — Seeders

- [x] `GenreSeeder.php` — 10 genres inserted with correct slugs
- [x] `AdminUserSeeder.php` — admin@movprima.com / Admin@123 (bcrypt)
- [x] `MovieSeeder.php` — 15 movies + movie_genres pivot rows inserted
- [x] `DatabaseSeeder.php` — master seeder (calls all 3 in order)
- [x] Run `php spark db:seed DatabaseSeeder` — completed with zero errors ✅

#### Phase 3 — Layout & Design System

- [x] `resources/css/app.css` — Tailwind v4 `@import`, custom CSS variables (colors, fonts)
- [x] `views/layouts/main.php` — navbar (logo, links, login/logout toggle), footer, `renderSection('content')`, Lucide icons, GSAP, and Lenis scroll
- [x] `views/layouts/admin.php` — sidebar (Dashboard, Movies, Genres, Reviews, Users links), top bar, `renderSection('content')`, and DataTables Tailwind integration

#### Phase 4 — Public Views

- [x] `views/home/index.php` — hero banner, "FILM TERBARU", "TERPOPULER", "REKOMENDASI", and "FILM KLASIK" rows
- [x] `views/movies/index.php` — search bar, sort dropdown, movie card grid, custom Tailwind pagination
- [x] `views/movies/detail.php` — backdrop hero, movie metadata, genre badges, INFO & ULASAN tabs, review list, watchlist button, inline `<dialog>` for writing and editing reviews
- [x] `views/genres/show.php` — genre hero, filtered movie grid, custom search bar and sort dropdown
- [x] `views/auth/login.php` — custom login page class, email + password fields, flash error display
- [x] `views/auth/register.php` — name, email, password, confirm password fields, terms & conditions checkbox

#### Phase 5 — User & Review Views

- [x] `views/user/profile.php` — avatar, stacked sections for Watchlist (grid) and My Reviews (list), inline edit review modal
- [x] `views/user/edit.php` — edit name, email, and password form
- [x] `views/reviews/create.php` — standalone form with rating number input (1-10), title, body textarea, spoiler checkbox
- [x] `views/reviews/edit.php` — same standalone form pre-filled with existing data

#### Phase 6 — Admin Views

- [x] `views/admin/dashboard/index.php` — stat cards: Total Film, Ulasan, Pengguna, Genre, Latest Reviews table
- [x] `views/admin/movies/index.php` — DataTables searchable table (Title, Year, Rating, Reviews, Genre, Action), Edit/Delete action buttons
- [x] `views/admin/movies/form.php` — shared create/edit: title, synopsis, release year, duration, genre checkboxes, poster URL, backdrop URL
- [x] `views/admin/genres/index.php` — inline add form + DataTables table with Edit (modal) / Delete
- [x] `views/admin/reviews/index.php` — DataTables table with User, Movie, Rating, Quote, Spoiler badge, Date, Delete button
- [x] `views/admin/users/index.php` — DataTables table with name, email, role badge, review count, join date, Delete button

---

### 9.2 Shyfa — Movie, Review & Genre Backend

> **Role:** Backend — Movie, Review, Genre & Watchlist

#### Phase 1 — Models

**`app/Models/MovieModel.php`**

- [x] Set `$table = 'movies'`, `$primaryKey = 'id'`, define `$allowedFields`
- [x] `getWithGenres(int $id): ?array` — returns a movie with genres string
- [x] `findBySlug(string $slug): ?array` — WHERE slug = ?, return null if not found
- [x] `search(string $q = ""): self` — filter by query; along with chainable `byGenre(int $genreId): self` and `sortBy(string $sort = "newest"): self`
- [x] `getTopRated(int $limit = 10, ?string $genreSlug = null): array` — uses `withTopGenre()`, ORDER BY `avg_rating` DESC
- [x] `getFeatured(int $limit = 6): array` — published movies with top genre, ORDER BY RAND()
- [x] `syncGenres(int $movieId, array $genreIds): void` — delete old pivot rows, re-insert new ones
- [x] Also includes helper methods: `getMoviesWithTopGenre()`, `getGenres()`, `getBySlug()`, `getIdsByGenres()`, `getRelatedMovies()`, `getAllAdmin()`

**`app/Models/GenreModel.php`**

- [x] `withMovieCount(): self` and `getAllSorted(): array` — splits query modification and fetching
- [x] `getBySlug(string $slug): ?array`

**`app/Models/ReviewModel.php`**

- [x] `getByMovie(int $movieId, ?int $userId = null, int $perPage = 5): array` — paginated, JOIN users and `review_likes`
- [x] `getByUser(int $userId): array` — JOIN movies (title, poster)
- [x] `getLatest(int $limit = 5): array` — JOIN movies + users, ORDER BY created_at DESC
- [x] `updateMovieRating(int $movieId): void` — single UPDATE query using `COALESCE(AVG(rating), 0)`
- [x] Also includes helper methods: `hasUserReviewed()`, `getReviewById()`, `addReview()`, `updateReview()`, `deleteReview()`, `getAllAdmin()`

**`app/Models/ReviewLikeModel.php`**

- [x] `toggle(int $userId, int $reviewId): bool` — insert if not exists, delete if exists; returns true = now liked
- [x] `hasLiked(int $userId, int $reviewId): bool`
- [x] `syncCounter(int $reviewId): void` — UPDATE reviews SET likes_count = COUNT(\*)

**`app/Models/WatchlistModel.php`**

- [x] `findByUserAndMovie(int $userId, int $movieId): ?array`
- [x] `toggle(int $userId, int $movieId): string` — add with status=want_to_watch if absent, return current status
- [x] `updateStatus(int $id, string $status): bool` — validate status is one of enum values
- [x] `getUserList(int $userId, ?string $status = null): array` — JOIN movies (poster, title, slug)
- [x] Also includes helper methods: `checkUserWatchlist()`, `getEntryById()`, `removeEntry()`

#### Phase 2 — Public Controllers

**`app/Controllers/Movie.php`**

- [x] `index()` — read `$_GET` (q, genre, year, sort), call `MovieModel::search()`, paginate 12/page, pass to view
- [x] `show(string $slug)` — call `findBySlug()`, 404 if null; load paginated reviews; check watchlist status for logged-in user

**`app/Controllers/Genre.php`**

- [x] `index()` — list all genres
- [x] `show(string $slug)` — call `GenreModel::findBySlug()`, 404 if null; load filtered+paginated movies

#### Phase 3 — Protected Controllers

**`app/Controllers/Review.php`**

- [x] `store()` — validate (rating 1–10, title min 3, body min 10); check no duplicate (unique user+movie); insert; call `updateMovieRating()`; redirect to movie detail
- [x] `update(int $id)` — validate same rules; update; call `updateMovieRating()`; redirect
- [x] `destroy(int $id)` — assert ownership or admin role; delete; call `updateMovieRating()`; redirect
- [x] `like(int $id)` — AJAX only (check `$this->request->isAJAX()`); toggle like; sync counter; return `$this->response->setJSON(['liked' => bool, 'count' => int])`

**`app/Controllers/Watchlist.php`**

- [x] `store()` — AJAX; read JSON body `{movie_id}`; toggle; return JSON `{status, message}`
- [x] `update(int $id)` — assert ownership; update status; redirect back
- [x] `destroy(int $id)` — assert ownership; delete; redirect to profile

#### Phase 4 — Image Upload (in Admin\Movie — coordinate with Gita)

- [x] Image upload skipped; replaced with `permit_empty|valid_url` validation for poster and backdrop
- [x] Accept direct image URL strings from the form
- [x] Store URL strings directly into the database
- [x] On update: replace old URL string with new URL string
- [x] Return validation error message if URL is not valid

---

### 9.3 Riski — Authentication Backend

> **Role:** Backend — Authentication, Filters & Route Configuration

#### Phase 1 — Auth Controller (`app/Controllers/Auth.php`)

**`loginForm()`**

- [x] GET — render `auth/login.php`
- [x] If `session('user_id')` already set, redirect to `/`

**`login()`**

- [x] POST — validate: `email` (required|valid_email), `password` (required|min_length[8])
- [x] Find user: `UserModel::findByEmail($email)`
- [x] Verify: `password_verify($password, $user['password'])`
- [x] On success: set session data (`user_id`, `user_name`, `user_email`, `user_role`)
- [x] Redirect: role=admin → `/admin`, else → `/`
- [x] On fail: `session()->setFlashdata('error', 'Incorrect email or password')` → redirect back

**`registerForm()`**

- [x] GET — render `auth/register.php`
- [x] If already logged in, redirect to `/`

**`register()`**

- [x] POST — validate: name (required|min_length[2]), email (required|valid_email|is_unique[users.email]), password (required|min_length[8]), password_confirm (required|matches[password])
- [x] Hash: `password_hash($password, PASSWORD_BCRYPT)`
- [x] Insert via `UserModel` with role=user
- [x] Set session (same fields as login)
- [x] Redirect to `/`

**`logout()`**

- [x] GET — `session()->destroy()`
- [x] Redirect to `/auth/login`

#### Phase 2 — Filters

**`app/Filters/AuthFilter.php`**

- [x] Implement `CodeIgniter\Filters\FilterInterface`
- [x] `before()`: if `!session('user_id')` → return `redirect()->to('/auth/login')`
- [x] `after()`: return null (no-op)

**`app/Filters/AdminFilter.php`**

- [x] `before()`: if `!session('user_id')` OR `session('user_role') !== 'admin'` → return `redirect()->to('/')->with('error', 'Access denied')`

#### Phase 3 — Register Filters (`app/Config/Filters.php`)

- [x] Add to `$aliases`: `'auth' => AuthFilter::class`, `'admin' => AdminFilter::class`

#### Phase 4 — Define All Routes (`app/Config/Routes.php`)

- [x] Public routes: `/`, `/movies`, `/movies/(:segment)`, `/genres/(:segment)`
- [x] Auth group (`/auth`): login GET+POST, register GET+POST, logout GET
- [x] Protected group (filter: auth): `/profile`, `/profile/edit`, `/reviews/*`, `/watchlist/*`
- [x] Admin group (filter: admin): all `/admin/*` routes pointing to `Admin\*` controllers
- [x] Add 404 override: `$routes->set404Override('App\Controllers\Home::error404')`

> **Tip:** Use `$routes->group()` to apply filters at group level — do not repeat filter on every individual route.

---

### 9.4 Gita — User, Profile & Admin Panel

> **Role:** Backend — User Controller, Home Controller & All Admin Sub-controllers

#### Phase 1 — UserModel (`app/Models/UserModel.php`)

- [x] `$table = 'users'`, `$allowedFields = ['name', 'email', 'password', 'role', 'bio', 'email_verified_at']` (Avatar omitted)
- [x] `$beforeInsert = ['hashPassword']` and `$beforeUpdate = ['hashPassword']` — auto-hash password
- [x] `findByEmail(string $email): ?array`
- [x] `updateProfile(int $id, array $data): bool` — updates profile info including password if provided
- [ ] ~`updateAvatar(int $id, string $avatarPath): bool`~ (Omitted from final implementation)

#### Phase 2 — Home Controller (`app/Controllers/Home.php`)

- [x] `index()` — load `MovieModel::getMoviesWithTopGenre` for featured, latest, topRated, recommended, and classic lists

#### Phase 3 — User Controller (`app/Controllers/User.php`)

- [x] `profile()` — load user from session ID, load `ReviewModel::getByUser()`, load `WatchlistModel::getUserList()`; pass to view
- [x] `editForm()` — load current user data, pass to view
- [x] `update()` — validate name, email, password; omits avatar upload logic; calls `UserModel::updateProfile()`; updates session; redirect to `/profile`

#### Phase 4 — Admin Sub-controllers

**`app/Controllers/Admin/Dashboard.php`**

- [x] `index()` — query: `COUNT(*)` from users, movies, reviews; `ReviewModel::getLatest(5)`; pass stats to view

**`app/Controllers/Admin/Movie.php`**

- [x] `index()` — list all movies (with genre string), searchable, paginate 15/page
- [x] `create()` — render `admin/movies/form.php` (empty form)
- [x] `store()` — validate fields; uses direct URL strings for poster and backdrop instead of file uploads; calls `MovieModel::syncGenres()`; clears cache; redirect to `/admin/movies`
- [x] `edit(int $id)` — load movie + current genres, render form pre-filled
- [x] `update(int $id)` — validate fields; update record with image URLs; re-sync genres; clears cache
- [x] `destroy(int $id)` — delete movie (cascade deletes relations via FK); clears cache; redirect

**`app/Controllers/Admin/Genre.php`**

- [x] `index()` — list all genres with movie count
- [x] `store()` — validate: name (required|min_length[2]|is_unique[genres.name]); auto-generate slug from name; insert
- [x] `update(int $id)` — validate same; update name + slug
- [x] `destroy(int $id)` — delete genre (pivot rows cascade automatically)

**`app/Controllers/Admin/Review.php`**

- [x] `index()` — list all reviews JOIN users + movies; filterable by status; paginate 20/page
- [x] `destroy(int $id)` — delete review; call `ReviewModel::updateMovieRating()`; redirect

**`app/Controllers/Admin/User.php`**

- [x] `index()` — list all users with review count; searchable by name/email; paginate 20/page
- [x] `destroy(int $id)` — prevent self-delete (check `$id !== session('user_id')`); delete user; redirect

---

_Last updated: May 2026 · MovPrima UAS PWEB STMIK Primakara_
