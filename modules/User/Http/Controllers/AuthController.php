<?php

namespace Modules\User\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Page\Entities\Page;
use Modules\User\Entities\User;
use Modules\User\LoginProvider;
use Illuminate\Support\Facades\Cache;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends BaseAuthController
{
    /**
     * Show login form.
     *
     * @return Response
     */
    public function getLogin()
    {
        return view('storefront::public.auth.login', [
            'providers' => LoginProvider::enabled(),
        ]);
    }


    /**
     * Redirect the user to the given provider authentication page.
     *
     * @param string $provider
     *
     * @return Response
     */
    public function redirectToProvider($provider)
    {
        if (!LoginProvider::isEnable($provider)) {
            abort(404);
        }

        return Socialite::driver($provider)->redirect();
    }


    /**
     * Obtain the user information from the given provider.
     *
     * @param string $provider
     *
     * @return Response
     */
    public function handleProviderCallback($provider)
    {
        if (!LoginProvider::isEnable($provider)) {
            abort(404);
        }

        try {
            $user = Socialite::driver($provider)->user();
        } catch (Exception $e) {
            return redirect()->route('login')->with('error', $e->getMessage());
        }

        if (User::registered($user->getEmail())) {
            auth()->login(
                User::findByEmail($user->getEmail())
            );

            return redirect($this->redirectTo());
        }

        [$firstName, $lastName] = $this->extractName($user->getName());

        $registeredUser = $this->auth->registerAndActivate([
            'first_name' => $firstName,
            'last_name' => $lastName,
            'username' => $this->generateUsername($user->getEmail()),
            'email' => $user->getEmail(),
            'phone' => '',
            'password' => str_random(),
            
        ]);

        $this->assignCustomerRole($registeredUser);

        auth()->login($registeredUser);

        return redirect($this->redirectTo());
    }


    /**
     * Show registrations form.
     *
     * @return Response
     */
    public function getRegister(Request $request)
    {
        return view('storefront::public.auth.register', [
            'privacyPageUrl' => $this->getPrivacyPageUrl(),
            'providers' => LoginProvider::enabled(),
            'request' => $request
        ]);
    }


    /**
     * Show reset password form.
     *
     * @return Response
     */
    public function getReset()
    {
        return view('storefront::public.auth.reset.begin');
    }


    /**
     * Where to redirect users after login.
     *
     * @return string
     */
    protected function redirectTo()
    {
        // Tüm kullanıcılar (vendor dahil) account paneline gitsin
        return route('account.dashboard.index');
    }


    /**
     * The login URL.
     *
     * @return string
     */
    protected function loginUrl()
    {
        return route('login');
    }


    /**
     * Reset complete form route.
     *
     * @param User $user
     * @param string $code
     *
     * @return string
     */
    protected function resetCompleteRoute($user, $code)
    {
        return route('reset.complete', [$user->email, $code]);
    }


    /**
     * Password reset complete view.
     *
     * @return string
     */
    protected function resetCompleteView()
    {
        return view('storefront::public.auth.reset.complete');
    }


    private function extractName($name)
    {
        return explode(' ', $name, 2);
    }

    private function generateUsername($email)
    {
        $baseUsername = strtolower(explode('@', $email)[0]);
        $baseUsername = preg_replace('/[^a-z0-9]/', '', $baseUsername);
        $baseUsername = substr($baseUsername, 0, 12);
        
        $username = $baseUsername;
        $counter = 1;
        
        while (User::where('username', $username)->exists()) {
            $suffix = str_pad($counter, 4, '0', STR_PAD_LEFT);
            $username = substr($baseUsername, 0, 12) . $suffix;
            $username = substr($username, 0, 16);
            $counter++;
        }
        
        return $username;
    }


    /**
     * Get privacy page url.
     *
     * @return string
     */
    private function getPrivacyPageUrl()
    {
        return Cache::tags('settings')->rememberForever('privacy_page_url', function () {
            return Page::urlForPage(setting('storefront_privacy_page'));
        });
    }
}
