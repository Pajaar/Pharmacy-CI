<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Libraries\DemoSession;
use App\Models\UserModel;
use CodeIgniter\HTTP\ResponseInterface;

class Auth extends BaseController
{
    public function login()
    {
        if (session()->get('logged_in')) {
            return redirect()->to('/');
        }

        return view('auth/login');
    }
    
    public function register(){
        if (session()->get('logged_in')) {
            return redirect()->to('/');
        }

        return view('auth/register');
    }
    
    public function process()
    {
        $model = new UserModel();
        $user = $model->where('email', $this->request->getPost('email'))->first();

        if($user && password_verify($this->request->getPost('password'), $user['password_hash']))
        {
            DemoSession::setUserSession($user);

            return redirect()->to('/');
        }

        return redirect()->back()->with('error','Email / Password salah');
    }

    public function processRegister()
    {
    $validation = \Config\Services::validation();

    $rules = [
        'name' => [
            'rules'  => 'required|min_length[3]',
            'errors' => [
                'required'   => 'Nama wajib diisi.',
                'min_length' => 'Nama minimal 3 karakter.',
            ],
        ],
        'email' => [
            'rules'  => 'required|valid_email|is_unique[users.email]',
            'errors' => [
                'required'   => 'Email wajib diisi.',
                'valid_email'=> 'Format email tidak valid.',
                'is_unique'  => 'Email sudah terdaftar.',
            ],
        ],
        'password' => [
            'rules'  => 'required|min_length[6]',
            'errors' => [
                'required'   => 'Password wajib diisi.',
                'min_length' => 'Password minimal 6 karakter.',
            ],
        ]
    ];

    if (! $this->validate($rules)) {
        return redirect()
            ->back()
            ->withInput()
            ->with('errors', $this->validator->getErrors());
    }

    $model = new UserModel();

    $model->insert([
        'name'          => $this->request->getPost('name'),
        'email'         => $this->request->getPost('email'),
        'password_hash'      => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
        'role'          => 'customer', // atau default lain
    ]);

    return redirect()
        ->to('/login')
        ->with('success', 'Registrasi berhasil, silakan login.');
    }

    public function logout()
    {
        session()->destroy();
        
        return redirect()->to('/login')->with('success', 'Successfully logged out.');
    }

}
