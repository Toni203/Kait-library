<?php

class Auth
{
    public static function user()
    {
        if (empty($_SESSION['user_id'])) {
            return null;
        }
         practice/m04
        static $user = null;
        if ($user === null) {
            $user = Database::fetchOne('SELECT * FROM users WHERE id = ?', [$_SESSION['user_id']]);
        }
        return $user;
=======
        static $currentUser = null;
        if ($currentUser === null) {
            $currentUser = Database::fetchOne('SELECT * FROM users WHERE id = ?', [$_SESSION['user_id']]);
        }
        return $currentUser;
         main
    }

    public static function check()
    {
        return self::user() !== null;
    }

    public static function hasRole($roles)
    {
     practice/m04
        $user = self::user();
        if (!$user) {
            return false;
        }
        return in_array($user['role'], (array)$roles);
    }

    public static function attempt($email, $password)
    {
        $user = Database::fetchOne('SELECT * FROM users WHERE email = ?', [$email]);
        if (!$user) {
            return false;
        }
        if ($user['password'] !== md5($password)) {
            return false;
        }
        if ($user['is_blocked']) {
            return false;
        }
        $_SESSION['user_id'] = $user['id'];

        $currentUser = self::user();
        if (!$currentUser) {
            return false;
        }
        return in_array($currentUser['role'], (array)$roles);
    }

    public static function attempt($userEmail, $rawPassword)
    {
        $foundUser = Database::fetchOne('SELECT * FROM users WHERE email = ?', [$userEmail]);
        if (!$foundUser) {
            return false;
        }
        
        if (!self::verifyCredentials($foundUser, $rawPassword)) {
            return false;
        }

        $_SESSION['user_id'] = $foundUser['id'];
        return true;
    }

    private static function verifyCredentials($userRecord, $rawPassword)
    {
        if ($userRecord['password'] !== md5($rawPassword)) {
            return false;
        }
        if ($userRecord['is_blocked']) {
            return false;
        }
         main
        return true;
    }

    public static function register($data)
    {
        return Database::insert(
            'INSERT INTO users (full_name, email, password, role, category, group_name, phone, created_at)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)',
            [
                $data['full_name'],
                $data['email'],
                md5($data['password']),
                'reader',
                $data['category'] ?? 'student',
                $data['group_name'] ?? null,
                $data['phone'] ?? null,
                date('Y-m-d H:i:s'),
            ]
        );
    }

    public static function logout()
    {
        $_SESSION = [];
        session_destroy();
    }

    public static function requireLogin()
    {
        if (!self::check()) {
            redirect('login');
        }
    }

    public static function requireRole($roles)
    {
        self::requireLogin();
        if (!self::hasRole($roles)) {
            http_response_code(403);
            echo 'Доступ запрещён';
            exit;
        }
    }
       practice/m04
}

}
        main
