<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ModelLogin;

class Login extends BaseController
{
    public function index()
    {
        return view('login/index');
    }

    public function cekUser()
    {
        $iduser = $this->request->getPost('iduser');
        $password = $this->request->getPost('password');

        $validation = \Config\Services::validation();
        $valid = $this->validate([
            'iduser' => [
                'rules' => 'required',
                'label' => 'ID User',
                'errors' => [
                    'required' => '{field} tidak boleh kosong'
                ]
            ],
            'password' => [
                'rules' => 'required',
                'label' => 'Password',
                'errors' => [
                    'required' => '{field} tidak boleh kosong'
                ]
            ]
        ]);

        if (!$valid) {
            $sesError = [
                'errorIdUser' => $validation->getError('iduser'),
                'errorPassword' => $validation->getError('password')
            ];

            session()->setFlashdata($sesError);
            return redirect()->to(site_url('login/index'))->withInput();
        } else {
            $modelLogin = new ModelLogin();
            $cekUserLogin = $modelLogin->find($iduser);

            if ($cekUserLogin == null) {
                $sesError = [
                    'errorIdUser' => 'Maaf user tidak terdaftar',
                ];
                session()->setFlashdata($sesError);
                return redirect()->to(site_url('login/index'))->withInput();
            } else {
                $passwordUser = $cekUserLogin['userpassword'];
                if (password_verify($password, $passwordUser)) {
                    // lanjutkan
                    $idlevel = $cekUserLogin['userlevelid'];
                    $simpan_session = [
                        'iduser' => $iduser,
                        'namauser' => $cekUserLogin['usernama'],
                        'idlevel' => $idlevel,
                    ];
                    session()->set($simpan_session);
                    return redirect()->to('/main/index');
                } else {
                    $sesError = [
                        'errorPassword' => 'Password anda salah',
                    ];
                    session()->setFlashdata($sesError);
                    return redirect()->to(site_url('login/index'))->withInput();
                }
            }
        }
    }

    public function keluar()
    {
        session()->destroy();
        return redirect()->to('/login/index');
    }
}
