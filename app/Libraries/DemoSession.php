<?php

namespace App\Libraries;

use App\Models\UserModel;
use Throwable;

class DemoSession
{
    public static function ensureLoggedIn(): void
    {
        $session = session();

        if ($session->get('logged_in')) {
            self::syncUserPayload();

            return;
        }

        $user = self::findDemoUser();

        if (! $user) {
            return;
        }

        self::setUserSession($user);
    }

    public static function setUserSession(array $user): void
    {
        $role = strtolower(trim((string) ($user['role'] ?? 'customer')));

        session()->set([
            'user_id'   => $user['id'],
            'name'      => $user['name'],
            'role'      => $role,
            'logged_in' => true,
            'user'      => [
                'id'    => $user['id'],
                'name'  => $user['name'],
                'email' => $user['email'] ?? null,
                'role'  => $role,
            ],
        ]);
    }

    private static function syncUserPayload(): void
    {
        $session = session();

        if ($session->get('user')) {
            return;
        }

        $session->set('user', [
            'id'    => $session->get('user_id'),
            'name'  => $session->get('name'),
            'email' => $session->get('email'),
            'role'  => strtolower(trim((string) $session->get('role'))),
        ]);
    }

    private static function findDemoUser(): ?array
    {
        try {
            $userModel = new UserModel();
            $demoEmail = getenv('DEMO_USER_EMAIL') ?: 'admin@example.com';

            $user = $userModel->where('email', $demoEmail)->first();

            if ($user) {
                return $user;
            }

            $user = $userModel->where('role', 'admin')->first();

            if ($user) {
                return $user;
            }

            return $userModel->first() ?: null;
        } catch (Throwable) {
            return null;
        }
    }
}
