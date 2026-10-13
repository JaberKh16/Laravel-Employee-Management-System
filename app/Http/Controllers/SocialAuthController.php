<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\RegisteredSocialService;
use App\Models\SocialAccount;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Contracts\User as SocialUser;

class SocialAuthController extends Controller
{
    /**
     * Step 1: Redirect user to provider's OAuth page.
     */
    public function redirect(string $provider)
    {
        $service = $this->resolveService($provider);

        // Optional: pass extra scopes from DB
        $driver = Socialite::driver($service->provider);

        if (! empty($service->scopes)) {
            $driver->scopes($service->scopes);
        }

        return $driver->redirect();
    }

    /**
     * Step 2: Handle callback.
     */
    public function callback(Request $request, string $provider)
    {
        $service = $this->resolveService($provider);

        try {
            $social = Socialite::driver($service->provider)->user();
        } catch (\Throwable $e) {
            report($e);
            return redirect()->route('login')
                ->withErrors(['email' => __('Social login failed. Please try again.')]);
        }

        // If already authenticated → this is a "link account" flow
        if (Auth::check()) {
            return $this->linkToCurrentUser(Auth::user(), $service, $social);
        }

        return $this->loginOrRegister($service, $social, $request);
    }

    // -----------------------------------------------------------------

    protected function loginOrRegister(
        RegisteredSocialService $service,
        SocialUser $social,
        Request $request
    ) {
        $user = DB::transaction(function () use ($service, $social) {

            // 1. Already linked?
            $account = SocialAccount::where('provider', $service->provider)
                ->where('provider_user_id', $social->getId())
                ->first();

            if ($account) {
                $account->update($this->tokenPayload($social));
                return $account->user;
            }

            // 2. Existing user by email → link + return
            $email = $social->getEmail();
            if ($email) {
                $user = User::where('email', $email)->first();
                if ($user) {
                    $this->attachAccount($user, $service, $social);
                    return $user;
                }
            }

            // 3. New user + profile
            $user = $this->createUserFromSocial($service, $social);
            $this->attachAccount($user, $service, $social);

            return $user;
        });

        // Update last-login meta
        $user->forceFill([
            'last_login_at' => now(),
            'last_login_ip' => $request->ip(),
        ])->save();

        Auth::login($user, remember: true);
        $request->session()->regenerate();

        return redirect()->intended(route('home'));
    }

    protected function linkToCurrentUser(
        User $user,
        RegisteredSocialService $service,
        SocialUser $social
    ) {
        // Already linked elsewhere?
        $exists = SocialAccount::where('provider', $service->provider)
            ->where('provider_user_id', $social->getId())
            ->where('user_id', '!=', $user->id)
            ->exists();

        if ($exists) {
            return back()->withErrors([
                'social' => __('This :provider account is already linked to another user.', [
                    'provider' => $service->label,
                ]),
            ]);
        }

        $this->attachAccount($user, $service, $social);

        return redirect()->route('profile.edit')
            ->with('status', __(':provider account linked.', ['provider' => $service->label]));
    }

    // -----------------------------------------------------------------

    protected function createUserFromSocial(
        RegisteredSocialService $service,
        SocialUser $social
    ): User {
        // Ensure we always have something unique for email + username
        $email    = $social->getEmail() ?: "{$service->provider}_{$social->getId()}@social.local";
        $username = $this->uniqueUsername(
            $social->getNickname()
            ?: Str::before($email, '@')
            ?: "{$service->provider}_{$social->getId()}"
        );

        $user = User::create([
            'username'          => $username,
            'email'             => $email,
            'password'          => null,                  // social-only
            'email_verified_at' => now(),                 // provider already verified
            'last_login_at'     => now(),
        ]);

        // Profile row
        $profile = $user->profile()->create([
            'first_name' => $this->firstChunk($social->getName()),
            'last_name'  => $this->lastChunk($social->getName()),
            'avatar'     => $social->getAvatar(),         // will be stored as-is (external URL supported)
        ]);

        $user->forceFill(['profile_id' => $profile->id])->save();

        return $user;
    }

    protected function attachAccount(
        User $user,
        RegisteredSocialService $service,
        SocialUser $social
    ): SocialAccount {
        return SocialAccount::updateOrCreate(
            [
                'user_id'          => $user->id,
                'provider'         => $service->provider,
            ],
            array_merge(
                [
                    'provider_user_id' => $social->getId(),
                    'provider_email'   => $social->getEmail(),
                    'nickname'         => $social->getNickname(),
                    'name'             => $social->getName(),
                    'avatar'           => $social->getAvatar(),
                ],
                $this->tokenPayload($social)
            )
        );
    }

    protected function tokenPayload(SocialUser $social): array
    {
        return [
            'access_token'     => $social->token ?? null,
            'refresh_token'    => $social->refreshToken ?? null,
            'token_expires_at' => isset($social->expiresIn)
                ? now()->addSeconds((int) $social->expiresIn)
                : null,
            'raw'              => method_exists($social, 'getRaw') ? $social->getRaw() : null,
        ];
    }

    // -----------------------------------------------------------------

    protected function resolveService(string $provider): RegisteredSocialService
    {
        $service = RegisteredSocialService::enabled()
            ->where('provider', $provider)
            ->first();

        abort_unless($service, 404, 'Provider not registered.');

        // Push the DB creds into Socialite config on the fly too (belt & braces)
        config()->set("services.{$service->provider}", $service->toSocialiteConfig());

        return $service;
    }

    protected function uniqueUsername(string $base): string
    {
        $base = Str::slug($base, '_') ?: 'user';
        $name = $base;
        $i = 1;
        while (User::withTrashed()->where('username', $name)->exists()) {
            $name = $base . '_' . $i++;
        }
        return $name;
    }

    protected function firstChunk(?string $full): ?string
    {
        if (! $full) return null;
        $parts = preg_split('/\s+/', trim($full));
        return $parts[0] ?? null;
    }

    protected function lastChunk(?string $full): ?string
    {
        if (! $full) return null;
        $parts = preg_split('/\s+/', trim($full));
        return count($parts) > 1 ? end($parts) : null;
    }
}