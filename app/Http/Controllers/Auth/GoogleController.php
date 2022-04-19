<?php
declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirect(): RedirectResponse
    {
        return Socialite::driver('google')
            ->setScopes(['email'])
            ->redirect();
    }

    public function callback(): RedirectResponse
    {
        $googleUser = Socialite::driver('google')->user();

        /** @var User $user */
        $user = User::query()
            ->firstOrNew([
                'google_id' => $googleUser->getId()
            ]);

        $user->saveOrFail();
        Auth::login($user, true);

        if ($user->player) {
            $playerRoute = route('player', [$user->player]);
            return redirect()->intended($playerRoute);
        }

        return redirect()->to('/');
    }
}
