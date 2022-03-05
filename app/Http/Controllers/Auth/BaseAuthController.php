<?php
declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class BaseAuthController extends Controller
{
    public function logout(): RedirectResponse
    {
        Auth::logout();

        return redirect()->to('/');
    }
}
