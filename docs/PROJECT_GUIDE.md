# 🎬 MovPrima — Movie Review & Recommendation Website

### UAS Pemrograman Web · STMIK Primakara · IF/Malam 2026

> **Dosen:** I Putu Satwika, S.Kom., M.Kom
> **Framework:** CodeIgniter 4 · Tailwind CSS v4 · DaisyUI v5 · MySQL

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

| #   | Name      | Role                              | Scope                                                                                                                                                   |
| --- | --------- | --------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------- |
| 1   | **Nata**  | Database Architect + Frontend Dev | Database design, all 7 migrations, seeds, all View files (Tailwind CSS v4 + DaisyUI v5), CSS animations, JS interactions                                |
| 2   | **Shyfa** | Backend — Movie, Review & Genre   | MovieController, ReviewController, GenreController, MovieModel, ReviewModel, GenreModel, WatchlistModel, AJAX endpoints, image upload, avg_rating logic |
| 3   | **Riski** | Backend — Authentication          | Auth.php controller (login, register, logout), AuthFilter, AdminFilter, session management, CSRF handling                                               |
| 4   | **Gita**  | Backend — User & Admin Panel      | User.php controller (profile, edit), UserModel, Admin.php controller (dashboard, user list), avatar upload                                              |

---

## 2. Tech Stack

| Layer         | Technology    | Version | Purpose                                                        |
| ------------- | ------------- | ------- | -------------------------------------------------------------- |
| Backend       | CodeIgniter 4 | ^4.x    | MVC framework, routing, ORM                                    |
| Frontend CSS  | Tailwind CSS  | v4.3    | Utility-first styling                                          |
| Component Lib | DaisyUI       | v5.5    | Pre-built UI components (cards, modals, navbar)                |
| Bundler       | Tailwind CLI  | v4.3    | Compiles `resources/css/app.css` → `public/assets/css/app.css` |
| Database      | MySQL 8       | 8.x     | Relational data storage                                        |
| Runtime       | PHP           | ≥8.1    | Server-side execution                                          |
| Package Mgr   | Bun           | latest  | Fast JS/CSS dependency management                              |

---

## 3. Application Flow

### 3.1 High-Level User Journey

```
[Guest]
  │
  ├─→ Landing Page (/)             → Browse featured movies
  ├─→ Movie List (/movies)         → Search, filter by genre/year/rating
  ├─→ Movie Detail (/movies/{slug}) → Read synopsis, cast, reviews
  ├─→ Login (/auth/login)          → Authenticate
  └─→ Register (/auth/register)    → Create account
         │
         ▼
[Logged-in User]
  ├─→ Write Review (/reviews/create?movie={id})
  ├─→ Edit/Delete own review
  ├─→ Add to Watchlist
  ├─→ Like a review
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
    avatar      VARCHAR(255)  NULL,                 -- path to uploaded image
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
│   │       ├── GenreSeeder.php
│   │       ├── AdminUserSeeder.php
│   │       └── MovieSeeder.php
│   │
│   └── Views/
│       ├── layouts/
│       │   ├── main.php        ← Public layout (navbar + footer)
│       │   └── admin.php       ← Admin sidebar layout
│       ├── home/
│       │   └── index.php
│       ├── movies/
│       │   ├── index.php
│       │   └── detail.php
│       ├── genres/
│       │   └── show.php
│       ├── auth/
│       │   ├── login.php
│       │   └── register.php
│       ├── reviews/
│       │   ├── create.php
│       │   └── edit.php
│       ├── user/
│       │   ├── profile.php
│       │   └── edit.php
│       └── admin/
│           ├── dashboard.php
│           ├── movies/
│           │   ├── index.php
│           │   └── form.php
│           ├── genres/
│           │   └── index.php
│           ├── reviews/
│           │   └── index.php
│           └── users/
│               └── index.php
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

| Model            | Key Methods                                                                                   | Notes                                      |
| ---------------- | --------------------------------------------------------------------------------------------- | ------------------------------------------ |
| `UserModel`      | `findByEmail()`, `createUser()`, `updateProfile()`                                            | Password hashing via `password_hash()`     |
| `MovieModel`     | `getWithGenres()`, `findBySlug()`, `search()`, `paginate()`, `getTopRated()`, `getFeatured()` | Uses JOIN with `movie_genres` and `genres` |
| `GenreModel`     | `getAllWithCount()`, `findBySlug()`                                                           | `COUNT` join for movie count per genre     |
| `ReviewModel`    | `getByMovie()`, `getByUser()`, `getLatest()`, `updateMovieRating()`                           | Calls `movies.avg_rating` update after CUD |
| `WatchlistModel` | `toggle()`, `getUserList()`, `getByStatus()`                                                  | Upsert pattern                             |

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

- [ ] DaisyUI `dim` dark theme applied globally
- [ ] Hero section with backdrop gradient + animated text
- [ ] Movie cards with poster, rating badge, genre chips
- [ ] Hover scale animation on cards (`transition-transform`)
- [ ] Star rating component (interactive, CSS only)
- [ ] Skeleton loading state for movie grid
- [ ] Responsive navbar with hamburger menu on mobile
- [ ] Toast notifications for success/error feedback
- [ ] Smooth page transitions using CSS
- [ ] Google Fonts — `Inter` for body, `Bebas Neue` for headings

### ✅ Error Handling — Target: Sangat Baik (4)

- [ ] CI4 validation rules on all forms (required, min_length, valid_email, etc.)
- [ ] 404 custom view for missing movies/pages
- [ ] CSRF token on all POST forms (`csrf_field()`)
- [ ] SQL errors caught with try/catch in controllers
- [ ] Auth filter returns 401/redirect (not PHP fatal error)
- [ ] Zero browser console errors or warnings
- [ ] Zero PHP warnings/notices in terminal

### ✅ CRUD — Target: Sangat Baik (4)

| Entity    | Create            | Read                 | Update          | Delete                 |
| --------- | ----------------- | -------------------- | --------------- | ---------------------- |
| Movie     | Admin form        | Public list & detail | Admin form      | Admin soft/hard delete |
| Genre     | Admin inline form | Browse page          | Admin modal     | Admin delete           |
| Review    | Logged-in form    | Movie detail page    | Own review edit | Own review delete      |
| Watchlist | One-click add     | Profile page list    | Status toggle   | Remove button          |
| User      | Register          | Profile page         | Edit profile    | Admin delete           |

### ✅ Kreatifitas — Target: Sangat Baik (4)

- [ ] DaisyUI v5 component library (creative use of `card`, `badge`, `modal`, `tabs`)
- [ ] Star-rating UI with half-star display (1–10 scale → 5 stars)
- [ ] Watchlist with 3 statuses (Want to Watch / Watching / Watched)
- [ ] Spoiler toggle on review body
- [ ] Review like system (heart icon, AJAX)
- [ ] Genre filter chips on movie list (no page reload)
- [ ] Admin dashboard with stat cards (total movies, reviews, users)

### ✅ Struktur Kode — Target: Sangat Baik (4)

- [ ] Strict MVC — no DB queries in views
- [ ] Base layout template with `renderSection()` / `extend()`
- [ ] Filters for auth (`AuthFilter`, `AdminFilter`)
- [ ] Named routes in `Routes.php` for easy URL generation
- [ ] Models use CI4 `Model` class (validation rules, return types defined)
- [ ] `resources/css/app.css` compiled → `public/assets/css/app.css` (never edit public directly)
- [ ] `.editorconfig` enforced (already present in repo)

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
php spark db:seed GenreSeeder
php spark db:seed AdminUserSeeder
php spark db:seed MovieSeeder

# 6. Start servers (two terminals)
php spark serve          # → http://localhost:8080
bun run dev              # → watches Tailwind CSS changes
```

### .env Key Settings

```ini
CI_ENVIRONMENT = development

app.baseURL = 'http://localhost:8080/'
app.forceGlobalSecureRequests = false

database.default.hostname = localhost
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
  └── dev     ← integration branch
        ├── feat/database-migrations   ← Nata
        ├── feat/frontend-views        ← Nata
        ├── feat/movies-reviews-genre  ← Shyfa
        ├── feat/auth                  ← Riski
        └── feat/user-admin            ← Gita
```

> **Rule:** Never push directly to `main`. Always PR into `dev`, then merge `dev` → `main` before demo.

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

- [ ] `resources/css/app.css` — Tailwind v4 `@import`, custom CSS variables (colors, fonts)
- [ ] `views/layouts/main.php` — navbar (logo, links, login/logout toggle), footer, `renderSection('content')`
- [ ] `views/layouts/admin.php` — sidebar (Dashboard, Movies, Genres, Reviews, Users links), top bar, `renderSection('content')`
- [ ] Google Fonts import — `Inter` (body), `Bebas Neue` (headings)
- [ ] DaisyUI `dim` dark theme set as default (`data-theme="dim"`)

#### Phase 4 — Public Views

- [ ] `views/home/index.php` — hero banner, featured movies row, top-rated grid, latest reviews strip
- [ ] `views/movies/index.php` — search bar, genre filter chips, sort dropdown, movie card grid, DaisyUI pagination
- [ ] `views/movies/detail.php` — backdrop hero, movie metadata, genre badges, trailer embed, reviews list, watchlist button, write-review CTA
- [ ] `views/genres/show.php` — genre hero, filtered movie grid
- [ ] `views/auth/login.php` — centered DaisyUI card, email + password fields, flash error display
- [ ] `views/auth/register.php` — name, email, password, confirm password fields

#### Phase 5 — User & Review Views

- [ ] `views/user/profile.php` — avatar, bio, DaisyUI tabs: My Reviews | Watchlist
- [ ] `views/user/edit.php` — edit name/bio form, avatar file input with live preview
- [ ] `views/reviews/create.php` — movie title header, star-rating picker (1–10), title, body textarea, spoiler checkbox
- [ ] `views/reviews/edit.php` — same form pre-filled with existing data

#### Phase 6 — Admin Views

- [ ] `views/admin/dashboard.php` — stat cards: Total Movies, Total Reviews, Total Users, Latest Reviews table
- [ ] `views/admin/movies/index.php` — searchable data table, poster thumbnail, Edit/Delete action buttons
- [ ] `views/admin/movies/form.php` — shared create/edit: title, synopsis, genre checkboxes, year, duration, poster upload, backdrop upload, trailer URL, status toggle
- [ ] `views/admin/genres/index.php` — inline add form + table with Edit (modal) / Delete
- [ ] `views/admin/reviews/index.php` — table with movie, reviewer, rating, status, Delete button
- [ ] `views/admin/users/index.php` — table with name, email, role badge, Delete button

---

### 9.2 Shyfa — Movie, Review & Genre Backend

> **Role:** Backend — Movie, Review, Genre & Watchlist

#### Phase 1 — Models

**`app/Models/MovieModel.php`**

- [ ] Set `$table = 'movies'`, `$primaryKey = 'id'`, define `$allowedFields`
- [ ] `getWithGenres(int $id): array` — JOIN movies + movie_genres + genres, returns single movie with genres string
- [ ] `findBySlug(string $slug): ?array` — WHERE slug = ?, return null if not found
- [ ] `search(string $q, ?string $genreSlug, ?int $year, ?string $sort): array` — multi-condition query with optional filters
- [ ] `getTopRated(int $limit = 10, ?string $genreSlug = null): array` — GROUP_CONCAT genres, ORDER BY avg_rating DESC
- [ ] `getFeatured(int $limit = 6): array` — published movies, ORDER BY RAND()
- [ ] `syncGenres(int $movieId, array $genreIds): void` — delete old pivot rows, re-insert new ones

**`app/Models/GenreModel.php`**

- [ ] `getAllWithCount(): array` — LEFT JOIN movies COUNT(m.id) AS movie_count
- [ ] `findBySlug(string $slug): ?array`

**`app/Models/ReviewModel.php`**

- [ ] `getByMovie(int $movieId): array` — paginated (5/page), JOIN users (name, avatar)
- [ ] `getByUser(int $userId): array` — JOIN movies (title, poster)
- [ ] `getLatest(int $limit = 5): array` — JOIN movies + users, ORDER BY created_at DESC
- [ ] `updateMovieRating(int $movieId): void` — single UPDATE query with subqueries for avg_rating + review_count

**`app/Models/ReviewLikeModel.php`**

- [ ] `toggle(int $userId, int $reviewId): bool` — insert if not exists, delete if exists; returns true = now liked
- [ ] `hasLiked(int $userId, int $reviewId): bool`
- [ ] `syncCounter(int $reviewId): void` — UPDATE reviews SET likes_count = COUNT(\*)

**`app/Models/WatchlistModel.php`**

- [ ] `findByUserAndMovie(int $userId, int $movieId): ?array`
- [ ] `toggle(int $userId, int $movieId): string` — add with status=want_to_watch if absent, return current status
- [ ] `updateStatus(int $id, string $status): bool` — validate status is one of enum values
- [ ] `getUserList(int $userId, ?string $status = null): array` — JOIN movies (poster, title, slug)

#### Phase 2 — Public Controllers

**`app/Controllers/Movie.php`**

- [ ] `index()` — read `$_GET` (q, genre, year, sort), call `MovieModel::search()`, paginate 12/page, pass to view
- [ ] `show(string $slug)` — call `findBySlug()`, 404 if null; load paginated reviews; check watchlist status for logged-in user

**`app/Controllers/Genre.php`**

- [ ] `show(string $slug)` — call `GenreModel::findBySlug()`, 404 if null; load filtered+paginated movies

#### Phase 3 — Protected Controllers

**`app/Controllers/Review.php`**

- [ ] `createForm()` — read `?movie_id=` GET param, validate movie exists, pass to view
- [ ] `store()` — validate (rating 1–10, title min 3, body min 10); check no duplicate (unique user+movie); insert; call `updateMovieRating()`; redirect to movie detail
- [ ] `editForm(int $id)` — load review, assert `review.user_id === session user_id` (or admin), pass to view
- [ ] `update(int $id)` — validate same rules; update; call `updateMovieRating()`; redirect
- [ ] `destroy(int $id)` — assert ownership or admin role; delete; call `updateMovieRating()`; redirect
- [ ] `like(int $id)` — AJAX only (check `$this->request->isAJAX()`); toggle like; sync counter; return `$this->response->setJSON(['liked' => bool, 'count' => int])`

**`app/Controllers/Watchlist.php`**

- [ ] `store()` — AJAX; read JSON body `{movie_id}`; toggle; return JSON `{status, message}`
- [ ] `update(int $id)` — assert ownership; update status; redirect back
- [ ] `destroy(int $id)` — assert ownership; delete; redirect to profile

#### Phase 4 — Image Upload (in Admin\Movie — coordinate with Gita)

- [ ] Validate file: max 2 MB, types: jpg/jpeg/png/webp
- [ ] Generate unique filename: `uniqid() . '.' . $ext`
- [ ] Store to: `public/uploads/movies/`
- [ ] On update: delete old file if a new file is provided
- [ ] Return validation error message if file fails

---

### 9.3 Riski — Authentication Backend

> **Role:** Backend — Authentication, Filters & Route Configuration

#### Phase 1 — Auth Controller (`app/Controllers/Auth.php`)

**`loginForm()`**

- [ ] GET — render `auth/login.php`
- [ ] If `session('user_id')` already set, redirect to `/`

**`login()`**

- [ ] POST — validate: `email` (required|valid_email), `password` (required|min_length[8])
- [ ] Find user: `UserModel::findByEmail($email)`
- [ ] Verify: `password_verify($password, $user['password'])`
- [ ] On success: set session data (`user_id`, `user_name`, `user_email`, `user_role`)
- [ ] Redirect: role=admin → `/admin`, else → `/`
- [ ] On fail: `session()->setFlashdata('error', 'Incorrect email or password')` → redirect back

**`registerForm()`**

- [ ] GET — render `auth/register.php`
- [ ] If already logged in, redirect to `/`

**`register()`**

- [ ] POST — validate: name (required|min_length[2]), email (required|valid_email|is_unique[users.email]), password (required|min_length[8]), password_confirm (required|matches[password])
- [ ] Hash: `password_hash($password, PASSWORD_BCRYPT)`
- [ ] Insert via `UserModel` with role=user
- [ ] Set session (same fields as login)
- [ ] Redirect to `/`

**`logout()`**

- [ ] GET — `session()->destroy()`
- [ ] Redirect to `/auth/login`

#### Phase 2 — Filters

**`app/Filters/AuthFilter.php`**

- [ ] Implement `CodeIgniter\Filters\FilterInterface`
- [ ] `before()`: if `!session('user_id')` → return `redirect()->to('/auth/login')`
- [ ] `after()`: return null (no-op)

**`app/Filters/AdminFilter.php`**

- [ ] `before()`: if `!session('user_id')` OR `session('user_role') !== 'admin'` → return `redirect()->to('/')->with('error', 'Access denied')`

#### Phase 3 — Register Filters (`app/Config/Filters.php`)

- [ ] Add to `$aliases`: `'auth' => AuthFilter::class`, `'admin' => AdminFilter::class`

#### Phase 4 — Define All Routes (`app/Config/Routes.php`)

- [ ] Public routes: `/`, `/movies`, `/movies/(:segment)`, `/genres/(:segment)`
- [ ] Auth group (`/auth`): login GET+POST, register GET+POST, logout GET
- [ ] Protected group (filter: auth): `/profile`, `/profile/edit`, `/reviews/*`, `/watchlist/*`
- [ ] Admin group (filter: admin): all `/admin/*` routes pointing to `Admin\*` controllers
- [ ] Add 404 override: `$routes->set404Override('App\Controllers\Home::error404')`

> **Tip:** Use `$routes->group()` to apply filters at group level — do not repeat filter on every individual route.

---

### 9.4 Gita — User, Profile & Admin Panel

> **Role:** Backend — User Controller, Home Controller & All Admin Sub-controllers

#### Phase 1 — UserModel (`app/Models/UserModel.php`)

- [ ] `$table = 'users'`, `$allowedFields = ['name', 'email', 'password', 'role', 'avatar', 'bio']`
- [ ] `$beforeInsert = ['hashPasswordCallback']` — auto-hash password on insert
- [ ] `findByEmail(string $email): ?array`
- [ ] `updateProfile(int $id, array $data): bool` — only update allowed fields
- [ ] `updateAvatar(int $id, string $avatarPath): bool`

#### Phase 2 — Home Controller (`app/Controllers/Home.php`)

- [ ] `index()` — load `MovieModel::getFeatured()`, `MovieModel::getTopRated()`, `ReviewModel::getLatest()`; pass all to `views/home/index.php`
- [ ] `error404()` — render custom 404 view

#### Phase 3 — User Controller (`app/Controllers/User.php`)

- [ ] `profile()` — load user from session ID, load `ReviewModel::getByUser()`, load `WatchlistModel::getUserList()`; pass to view
- [ ] `editForm()` — load current user data, pass to view
- [ ] `update()` — validate: name (required|min_length[2]), bio (optional|max_length[500]); handle avatar upload (max 1 MB, jpg/png/webp); store to `public/uploads/avatars/`; call `UserModel::updateProfile()`; redirect to `/profile`

#### Phase 4 — Admin Sub-controllers

**`app/Controllers/Admin/Dashboard.php`**

- [ ] `index()` — query: `COUNT(*)` from users, movies, reviews; `ReviewModel::getLatest(5)`; pass stats to view

**`app/Controllers/Admin/Movie.php`**

- [ ] `index()` — list all movies (with genre string), searchable, paginate 15/page
- [ ] `create()` — render `admin/movies/form.php` (empty form)
- [ ] `store()` — validate all fields (title required, release_year required|integer, status required); handle poster + backdrop upload (coordinate with Shyfa's upload logic); call `MovieModel::syncGenres()`; redirect to `/admin/movies`
- [ ] `edit(int $id)` — load movie + current genres, render form pre-filled
- [ ] `update(int $id)` — same validation as store; update record; re-sync genres; handle new image if uploaded
- [ ] `destroy(int $id)` — delete movie (cascade deletes reviews, watchlist, genres via FK); delete image files; redirect

**`app/Controllers/Admin/Genre.php`**

- [ ] `index()` — list all genres with movie count
- [ ] `store()` — validate: name (required|min_length[2]|is_unique[genres.name]); auto-generate slug from name; insert
- [ ] `update(int $id)` — validate same; update name + slug
- [ ] `destroy(int $id)` — delete genre (pivot rows cascade automatically)

**`app/Controllers/Admin/Review.php`**

- [ ] `index()` — list all reviews JOIN users + movies; filterable by status; paginate 20/page
- [ ] `destroy(int $id)` — delete review; call `ReviewModel::updateMovieRating()`; redirect

**`app/Controllers/Admin/User.php`**

- [ ] `index()` — list all users with review count; searchable by name/email; paginate 20/page
- [ ] `destroy(int $id)` — prevent self-delete (check `$id !== session('user_id')`); delete user; redirect

---

_Last updated: May 2026 · MovPrima UAS PWEB STMIK Primakara_
