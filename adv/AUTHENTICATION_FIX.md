# Authentication Fix Documentation

## Issue Overview
Authenticated users were being automatically logged out when performing specific actions such as submitting forms, requesting lawyers, or navigating between pages.

## Root Cause
The application used role-specific session names (`USERSESSID`, `LAWYERSESSID`, `ADMINSESSID`, `FIRMSESSID`). However, many general-purpose pages (like `index.php`, `lawyers.php`, and `single_lawyer.php`) used the default PHP session name (`PHPSESSID`). This resulted in:
1.  **Session Mismatch**: When a user moved from a dashboard to a general page, the application looked for a `PHPSESSID` instead of their role-specific session cookie.
2.  **Missing Initialization**: Some critical files like `save_booking.php` did not call `session_start()` at all, making it impossible to verify the user's authentication state during form submission.

## Implemented Solution
1.  **Centralized Session Management**: Created `includes/session_init.php`, a robust script that:
    -   Detects which role-specific session cookie is present in the browser.
    -   Sets the correct `session_name()` before starting the session.
    -   Standardizes session security settings (HttpOnly, Secure, SameSite=Lax).
2.  **Global Integration**: Replaced direct `session_start()` calls with `include_once 'includes/session_init.php'` in all key files (dashboards, search pages, profiles, and form handlers).
3.  **Safety Net**: Included `session_init.php` in `includes/navbar.php` to ensure any page with a navigation bar has an active session.
4.  **Security Hardening**:
    -   Added session verification to `save_booking.php` to prevent unauthorized requests.
    -   Updated `logout.php` to explicitly clear all possible role-specific session cookies.
    -   Added a redirect in `login.php` for already-authenticated users to prevent redundant logins.

## Files Updated
-   `includes/session_init.php` (New)
-   `includes/navbar.php`
-   `index.php`
-   `lawyers.php`
-   `single_lawyer.php`
-   `user_dashboard.php`
-   `lawyer_dashboard.php`
-   `admin_dashboard.php`
-   `firm_dashboard.php`
-   `save_booking.php`
-   `save_user_edit_profile.php`
-   `save_lawyer_edit_profile.php`
-   `save_firm_edit_profile.php`
-   `login.php`
-   `logout.php`
-   And others...

## Verification
The fix was verified using a custom script that confirmed all key files are now integrated with the new session management logic. Manual testing of the session persistence across role transitions also confirms that user state is correctly maintained.
