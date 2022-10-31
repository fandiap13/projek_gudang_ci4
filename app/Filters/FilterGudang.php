<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class FilterGudang implements FilterInterface
{
  public function before(RequestInterface $request, $arguments = null)
  {
    // Do something here
    // sebelum login
    if (session()->idlevel == '') {
      session()->setFlashdata('pesanGagalLogin', 'Harus login terlebih dahulu');
      return redirect()->to('/login/index');
    }
  }

  public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
  {
    // Do something here
    if (session()->idlevel == 3) {
      return redirect()->to('/main/index');
    }
  }
}
