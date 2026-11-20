<?php
/**
 * Robust session management for Lawyer Match AI.
 *
 * On serverless hosting (Vercel containers) the local filesystem is
 * ephemeral, so PHP's native file based session handler cannot be used.
 * Sessions are therefore persisted in TiDB (MySQL protocol) instead.
 */

require_once __DIR__ . '/../db_con/dbCon.php';

if (!class_exists('DbSessionHandler')) {
    class DbSessionHandler implements SessionHandlerInterface
    {
        /** @var mysqli|null */
        private $link = null;
        /** @var int */
        private $ttl;

        public function __construct($ttl = 1209600) // 14 days
        {
            $this->ttl = $ttl;
        }

        /**
         * Lazily open a dedicated connection for the session handler.
         * The app may close its own connection, so the handler never
         * shares one.
         */
        private function db()
        {
            if ($this->link instanceof mysqli) {
                return $this->link;
            }
            $this->link = connect();
            return $this->link;
        }

        public function open(string $path, string $name): bool
        {
            return true;
        }

        public function close(): bool
        {
            if ($this->link instanceof mysqli) {
                @mysqli_close($this->link);
                $this->link = null;
            }
            return true;
        }

        public function read(string $id): string|false
        {
            $con = $this->db();
            if (!$con) {
                return '';
            }
            $stmt = @mysqli_prepare($con, "SELECT data FROM php_sessions WHERE id = ? AND last_access >= ?");
            if (!$stmt) {
                return '';
            }
            $cutoff = time() - $this->ttl;
            mysqli_stmt_bind_param($stmt, 'si', $id, $cutoff);
            mysqli_stmt_execute($stmt);
            $data = '';
            if (mysqli_stmt_bind_result($stmt, $col) && mysqli_stmt_fetch($stmt)) {
                $data = (string) $col;
            }
            mysqli_stmt_close($stmt);
            return $data;
        }

        public function write(string $id, string $data): bool
        {
            $con = $this->db();
            if (!$con) {
                return false;
            }
            $now = time();
            $stmt = @mysqli_prepare(
                $con,
                "INSERT INTO php_sessions (id, data, last_access) VALUES (?, ?, ?)
                 ON DUPLICATE KEY UPDATE data = VALUES(data), last_access = VALUES(last_access)"
            );
            if (!$stmt) {
                return false;
            }
            mysqli_stmt_bind_param($stmt, 'ssi', $id, $data, $now);
            $ok = mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            return (bool) $ok;
        }

        public function destroy(string $id): bool
        {
            $con = $this->db();
            if (!$con) {
                return false;
            }
            $stmt = @mysqli_prepare($con, "DELETE FROM php_sessions WHERE id = ?");
            if (!$stmt) {
                return false;
            }
            mysqli_stmt_bind_param($stmt, 's', $id);
            $ok = mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            return (bool) $ok;
        }

        public function gc(int $max_lifetime): int|false
        {
            $con = $this->db();
            if (!$con) {
                return false;
            }
            $cutoff = time() - ($max_lifetime > 0 ? $max_lifetime : $this->ttl);
            $stmt = @mysqli_prepare($con, "DELETE FROM php_sessions WHERE last_access < ?");
            if (!$stmt) {
                return false;
            }
            mysqli_stmt_bind_param($stmt, 'i', $cutoff);
            mysqli_stmt_execute($stmt);
            $affected = mysqli_stmt_affected_rows($stmt);
            mysqli_stmt_close($stmt);
            return $affected;
        }
    }
}

function init_session($name = 'LAWYERMATCHAI_SESS')
{
    if (session_status() === PHP_SESSION_NONE) {
        session_set_save_handler(new DbSessionHandler(1209600), true);

        session_name($name);

        // Secure session settings (honour the reverse proxy used by Vercel)
        $secure = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
            || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https');

        session_set_cookie_params([
            'lifetime' => 0,
            'path' => '/',
            'secure' => $secure,
            'httponly' => true,
            'samesite' => 'Lax'
        ]);

        session_start();
    }
}

// Automatically initialize session if not already done
if (!defined('NO_AUTO_SESSION')) {
    init_session();
}
