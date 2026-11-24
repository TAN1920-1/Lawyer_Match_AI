# Lawyer Match AI

A full-stack web platform that connects clients with **verified lawyers and law firms**.
Users can search by speciality and city, view detailed profiles, and book appointments
online. Lawyers and firms get their own dashboards, and administrators approve and manage
every account. An AI assistant ("Lexi") guides visitors through the whole journey.

- **Live site:** https://lawyer-match-ai.vercel.app/adv/
- **Repository:** https://github.com/TAN1920-1/Lawyer_Match_AI

---

## Table of Contents

1. [Overview](#overview)
2. [Features by Role](#features-by-role)
3. [Tech Stack](#tech-stack)
4. [How It Works](#how-it-works)
5. [Directory Structure](#directory-structure)
6. [Database Schema](#database-schema)
7. [AI Chatbot (Lexi)](#ai-chatbot-lexi)
8. [Lawyer Recommendation Engine](#lawyer-recommendation-engine)
9. [Email System](#email-system)
10. [Image Handling](#image-handling)
11. [Getting Started (Local)](#getting-started-local)
12. [Environment Variables](#environment-variables)
13. [Deployment (Vercel)](#deployment-vercel)
14. [HTTP API](#http-api)
15. [Security Notes](#security-notes)
16. [Troubleshooting](#troubleshooting)
17. [Team](#team)

---

## Overview

Lawyer Match AI is a legacy PHP/MySQL codebase that was modernised for **cloud hosting**.
The application is served from the `/adv` directory and is deployed to **Vercel** as a Docker
container (FrankenPHP + Caddy), while the database lives on **TiDB Cloud**.

The design goal was to keep the original PHP structure (so every relative include and link
continues to work) while making it run on serverless infrastructure — which means sessions
and uploads are persisted in the database instead of the ephemeral container filesystem.

---

## Features by Role

Roles are stored in the `user` table (`User`, `Lawyer`, `FIRM_ADMIN`, `FIRM_LAWYER`, `Admin`).

### User (Client)
- Register and receive a temporary password by email, then log in.
- Search lawyers and law firms by **speciality** and **city**.
- View lawyer profiles, ratings and reviews.
- Book appointments and track them under **My Bookings**.
- Rate a lawyer after a consultation.
- Forgot-password flow (email reset link) and in-dashboard password update.
- Report a bug from any page.

### Lawyer
- Self-register (account starts as **Pending** until an admin approves it).
- Dashboard with profile editing and incoming booking requests.

### Firm
- Register a firm (`FIRM_ADMIN`).
- Add or remove team lawyers (`FIRM_LAWYER`); added lawyers receive their login email automatically.
- View all firm bookings and reassign a booking to another lawyer.
- Unified bookings view across the whole firm.

### Admin
- Approve or reject pending lawyers and firms.
- Manage users, lawyers and firms.
- View notifications and the audit log.

---

## Tech Stack

| Layer | Technology |
| --- | --- |
| Language | PHP 8.4 |
| Database | MySQL / **TiDB Cloud** (mysqli, TLS) |
| Web server | **Caddy** via **FrankenPHP** |
| Container | Docker (`Dockerfile.vercel`) |
| Hosting | **Vercel** (container service) + GitHub integration |
| Email | **PHPMailer** over SMTP (Gmail App Password) |
| AI | Google **Gemini** (primary) + **OpenRouter** (fallback) |
| Front-end | Bootstrap 4, custom CSS, vanilla JavaScript |
| Sessions | Custom DB-backed session handler (`php_sessions`) |

---

## How It Works

### Request lifecycle

1. The browser hits Vercel, which routes everything to the single container service
   defined in `vercel.json`.
2. Caddy serves `/app/public` as the document root and redirects `/` to `/adv/`.
3. A PHP script boots by including `includes/session_init.php`, which registers the
   DB-backed session handler and starts the session.
4. `db_con/dbCon.php` resolves the database configuration (environment variables first,
   then a `.env.local` file) and opens a TLS connection to TiDB.
5. Page-specific logic runs, queries the database, and renders HTML.

### Configuration resolution

`db_con/db_con.php` reads configuration in this priority order:

1. Real environment variables (set in the hosting control panel / Vercel).
2. A `.env.local` file found in the web root, the project root, or `db_con/`.
3. A local-development fallback (XAMPP: `localhost:3306`, user `root`, db `lawyermanagement`).

The same `.env.local` parsing pattern is reused by the mailer (`includes/mail_util.php`)
and by the AI config (`db_con/config.php`).

### Sessions in the database

On serverless hosting the local filesystem is ephemeral, so PHP's native file-based session
handler cannot be used. `includes/session_init.php` defines `DbSessionHandler`, which
persists sessions in the **`php_sessions`** table in TiDB (14-day TTL).

### Access control

`includes/role_middleware.php` provides `check_role([...])`, used by dashboards and the
JSON API. It redirects unauthenticated users to `login.php`, returns `401`/`403` JSON for
AJAX requests, and blocks users whose role is not in the allowed list.

---

## Directory Structure

```
lawyermatch-presentation-project/
├── adv/                         # The application (served at /adv/)
│   ├── api/firm/                # JSON API for firm admins
│   ├── css/                     # Bootstrap + custom styles
│   ├── db_con/                  # DB connection, login handler, AI config, CA cert
│   ├── docs/                    # ERD, rollout plan, Swagger/Postman specs
│   ├── images/                  # Static images + developers + uploads
│   ├── includes/                # Shared PHP: session, mail, images, navbar, widgets
│   ├── js/                      # Front-end scripts (chatbot, widgets, theme)
│   ├── PHPMailer-master/        # Vendored PHPMailer library
│   ├── *.php                    # Pages (dashboards, search, booking, auth, ...)
├── Caddyfile                    # Caddy server configuration
├── Dockerfile.vercel            # Container image for Vercel
├── vercel.json                  # Vercel service + rewrite configuration
├── lawyermanagement.sql         # Schema + data (MySQL dump)
├── lawyermanagement.tidb.sql    # TiDB-compatible schema
└── README.md
```

### Key pages

| Page | Purpose |
| --- | --- |
| `index.php` | Landing page with slider and calls to action |
| `login.php` | Login for all roles (posts to `db_con/db_login.php`) |
| `register_options.php` | Choose User / Lawyer / Firm registration |
| `user_register.php`, `lawyer_register.php`, `firm_register.php` | Registration forms |
| `user_registation.php`, `lawyer_registation.php`, `firm_registation.php` | Registration handlers |
| `searchLawyer.php`, `searchFirm.php`, `lawyers.php` | Discovery |
| `single_lawyer.php` | Lawyer profile |
| `match.php` | AI-ranked lawyer recommendations |
| `*_dashboard.php` | User / Lawyer / Firm / Admin dashboards |
| `chatbot.php` | Full-page AI assistant |
| `forgot_password.php`, `reset_password.php` | Password reset by email |
| `report_bug.php`, `send_bug_report.php` | Bug report form + handler |
| `image.php` | Streams an image stored in TiDB |

---

## Database Schema

See `adv/docs/ERD.md` for entities and relationships.

### Core tables

| Table | Purpose |
| --- | --- |
| `user` | Every account (all roles). `u_id` prefix indicates type: `Client…`, `Lawyer…`, `Firm…`, `Admin…` |
| `lawyer` | Lawyer profile (speciality, experience, case types, image) |
| `client` | Client profile |
| `firm` | Firm profile |
| `booking` | Appointment between a client and a lawyer |
| `firm_booking` | Firm-level booking records |
| `lawyer_ratings` | Ratings and reviews |
| `notifications` | In-app notifications (bell icon) |
| `administrator` | Admin accounts (joined to `user` for login) |
| `audit_logs` | Firm/admin action log (add/remove lawyer, reassign booking, ...) |

### Runtime-created tables

These are created lazily by the code on first use (idempotent `CREATE TABLE IF NOT EXISTS`):

| Table | Created by | Purpose |
| --- | --- | --- |
| `uploaded_images` | `includes/image_util.php` | Runtime image uploads stored as BLOBs |
| `php_sessions` | `includes/session_init.php` | Session storage |
| `password_resets` | `adv/forgot_password.php` | One-time hashed reset tokens |

### Roles and statuses

- **Roles:** `User`, `Lawyer`, `FIRM_ADMIN`, `FIRM_LAWYER`, `Admin`
- **Statuses:** `Active`, `Pending` (lawyers/firms awaiting approval), `INACTIVE`

---

## AI Chatbot (Lexi)

The assistant is implemented in `adv/chatbot_api.php` with the UI in
`adv/chatbot.php` + `adv/js/chatbot.js`.

Responder priority:

1. **FAQ engine (primary).** About 29 intents are matched by regex scoring
   (`lm_faq_intents()`), covering greetings, lawyer/firm search, registration for each
   role, login issues, password reset, bookings, fees, ratings, verification, contact,
   bug reports, privacy, and more. This runs with **no API key and no quota**.
2. **AI provider (fallback).** If no intent matches, the message is sent to Google Gemini
   (`AI_BASE_URL` / `AI_MODEL`), and to OpenRouter if the primary fails.
3. **Generic help.** If everything is unavailable, a help message is returned.

When the chatbot recognises a lawyer search with both a **speciality** and a **city**, it
appends a machine-readable action that `chatbot.js` parses to redirect the user straight
to the search results:

```
||SEARCH_ACTION:{"speciality": "...", "location": "..."}||
```

---

## Lawyer Recommendation Engine

`adv/includes/ai_recommendation_engine.php` scores and ranks lawyers using an
**AGE-MOEA**-inspired multi-objective fitness function. Objectives:

- Maximise **success rate** (45% weight)
- Maximise **average rating** (35% weight)
- Maximise **experience** (10% weight)
- Minimise **distance** — lawyers in the same city as the client score higher (10% weight)

The engine initialises a population from the database, evaluates fitness, and applies
selection/mutation steps to produce a ranked recommendation list surfaced on `match.php`.

---

## Email System

`adv/includes/mail_util.php` centralises all outbound mail:

- `send_notification_email($to, $subject, $htmlBody)` — sends HTML mail via PHPMailer
  over SMTP (Gmail by default).
- `site_base_url()` — detects the live domain so links and redirects are not hard-coded
  to `localhost`.

Credentials are read from environment variables with `.env.local` fallback:
`MAIL_HOST`, `MAIL_PORT`, `MAIL_ENCRYPTION`, `MAIL_USERNAME`, `MAIL_PASSWORD`,
`MAIL_FROM`, `MAIL_FROM_NAME`.

Mail is sent by: user/lawyer/firm registration (`send_mail_client.php`,
`send_mail_lawyer.php`, `send_mail_firm.php`), firm-added lawyers
(`api/firm/lawyers.php`), the bug report form (`send_bug_report.php`), and the
password reset flow (`forgot_password.php`).

> **Note:** For Gmail you must use a 16-character **App Password**
> (Google Account → Security → 2-Step Verification → App passwords), not the account
> password. Plain passwords are rejected with SMTP error `535 BadCredentials`.

---

## Image Handling

Images follow a strict precedence implemented in `includes/image_util.php` and `image.php`:

1. **Runtime uploads** → stored as BLOBs in the `uploaded_images` table and streamed via
   `image.php?f=<name>`.
2. **Repository images** → served directly from `images/upload/`.
3. **Fallback** → `images/random.png` is used whenever an image is missing or empty.

The helper `img_src($img, $fallback)` returns the correct `src` for any given value.

---

## Getting Started (Local)

### Requirements

- PHP 8.0+ (XAMPP is fine) with the `mysqli` extension
- MySQL (local) **or** a TiDB Cloud connection
- A web server whose document root can reach the project, or XAMPP's `htdocs`

### Steps

1. Clone the repository.
2. Create `adv/.env.local` (this file is git-ignored) with your database and mail settings
   — see [Environment Variables](#environment-variables).
3. Import the schema:
   - Local MySQL: import `lawyermanagement.sql`
   - TiDB: import `lawyermanagement.tidb.sql`
4. Serve the project and open:

```
http://localhost/<project-folder>/adv/
```

Without a `.env.local`, the app automatically falls back to a local XAMPP MySQL server
(`localhost:3306`, user `root`, database `lawyermanagement`).

---

## Environment Variables

Create `adv/.env.local` for local development. For Vercel, set the same keys in the
dashboard (see [Deployment](#deployment-vercel)).

| Variable | Purpose |
| --- | --- |
| `DB_HOST`, `DB_PORT`, `DB_USER`, `DB_PASSWORD`, `DB_NAME` | Database connection |
| `DB_SSL` | `true` to connect over TLS (required by TiDB) |
| `DB_CA_PATH` | Path to the CA certificate (bundled: `db_con/isrgrootx1.pem`) |
| `SESSION_SECRET` | Session secret |
| `MAIL_HOST`, `MAIL_PORT`, `MAIL_ENCRYPTION` | SMTP server settings |
| `MAIL_USERNAME`, `MAIL_PASSWORD` | SMTP credentials (Gmail App Password) |
| `MAIL_FROM`, `MAIL_FROM_NAME` | Sender identity |
| `AI_CHATBOT_API_KEY`, `AI_PROVIDER`, `AI_BASE_URL`, `AI_MODEL`, `AI_HEADER_NAME` | Primary AI provider (optional) |
| `AI_FALLBACK_KEY`, `AI_FALLBACK_BASE_URL`, `AI_FALLBACK_MODEL`, `AI_FALLBACK_HEADER_NAME` | Fallback AI provider (optional) |
| `SITE_URL` | Public base URL used in emails (optional) |

> Never commit real credentials. `.env.local`, `.env.vercel` and
> `adv/db_con/config.local.php` are already excluded via `.gitignore`.

---

## Deployment (Vercel)

The project deploys as a **Docker container** using:

- `vercel.json` — declares a `container` service named `api` and rewrites all traffic to it.
- `Dockerfile.vercel` — builds on `dunglas/frankenphp:1-php8.4-alpine`, installs the
  `mysqli` extension, copies the app to `/app/public/adv`, and starts Caddy.
- `Caddyfile` — serves `/app/public`, redirects `/` to `/adv/`, and enables the PHP server.

Deployment steps:

1. Push to the `main` branch (Vercel is connected via GitHub and builds automatically).
2. In **Vercel → Settings → Environment Variables**, add the database, mail and (optional)
   AI variables for **Production, Preview and Development**.
3. Redeploy.

---

## HTTP API

The firm module exposes a small JSON API, guarded by `check_role(['FIRM_ADMIN'])`:

| Endpoint | Method | Description |
| --- | --- | --- |
| `/adv/api/firm/lawyers.php` | `POST` | Add a lawyer to the firm (creates user + lawyer, sends login email) |
| `/adv/api/firm/lawyers.php/{lawyerId}` | `DELETE` | Soft-delete a firm lawyer (`is_active = 0`) |
| `/adv/api/firm/bookings.php` | `GET` | List all bookings belonging to the firm |
| `/adv/api/firm/bookings.php/{bookingId}` | `PATCH` | Reassign a booking to another firm lawyer |

API specifications are also available in `adv/docs/swagger.json` and
`adv/docs/postman_collection.json`.

---

## Security Notes

- Database credentials and API keys live in environment variables, never in code.
- Passwords are stored hashed (`password_hash` / `password_verify`).
- Password reset tokens are 32 random bytes, stored **hashed** (SHA-256), single-use,
  and expire after 1 hour.
- Registration and reset endpoints avoid account enumeration (they always return a generic
  result).
- Role checks are enforced server-side by `role_middleware.php`.

---

## Troubleshooting

| Symptom | Cause / Fix |
| --- | --- |
| Login or logout does nothing (warnings about "headers already sent") | A PHP file starts with a blank line or BOM **before** `<?php`. Vercel flushes output immediately, so `header()`/session calls fail. Ensure every script starts with `<?php` as the very first byte. |
| Vercel build fails with `NAME_INVALID` during image push | The container registry rejects **underscores** in the project name. Rename the Vercel project to use hyphens only (e.g. `lawyer-match-ai`). |
| Emails not delivered | `MAIL_PASSWORD` must be a valid Gmail **App Password**; otherwise SMTP returns `535 BadCredentials`. Also confirm the `MAIL_*` variables exist in the Vercel dashboard (Vercel does not read `.env.vercel` from the repo). |
| TiDB connection fails | TiDB requires TLS (`DB_SSL=true`) and port `4000`. Some shared hosts block that port outbound. |
| `mysqli_stmt_get_result` undefined | Use `mysqli_stmt_bind_result` / `mysqli_stmt_fetch` instead (the codebase already does) for host compatibility. |
| Chatbot gives generic replies | The FAQ engine answered nothing obvious and the AI providers are unavailable — the built-in FAQ still works without keys. |
| Uploaded images vanish after redeploy | The container filesystem is ephemeral; uploads must be stored in the database (`uploaded_images`), which the code already does. |

---

## Team

Built by the Lawyer Match AI team:

- **Tanishq** — Feature engineering, database management, backend improvements
- **Jeet** — Chat application development, system connectivity, performance
- **Spandan** — UI design, development support, documentation, testing
- **Sarvesh** — Project lead, core testing, feature improvements, admin

---

## License

This project is provided for educational and demonstration purposes.
Third-party components (PHPMailer, Bootstrap, Font Awesome) remain under their own licenses.
