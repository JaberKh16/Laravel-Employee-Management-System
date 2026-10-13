<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SocialServiceRequest;
use App\Models\RegisteredSocialService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SocialServiceController extends Controller
{
    /** Providers Socialite supports out-of-the-box + config hints */
    public const KNOWN_PROVIDERS = [
        'google' => ['label' => 'Google', 'icon' => 'google', 'color' => '#4285F4', 'scopes' => 'openid, email, profile'],
        'github' => ['label' => 'GitHub', 'icon' => 'github', 'color' => '#181717', 'scopes' => 'read:user, user:email'],
        'facebook' => ['label' => 'Facebook', 'icon' => 'facebook', 'color' => '#1877F2', 'scopes' => 'email, public_profile'],
        'linkedin' => ['label' => 'LinkedIn', 'icon' => 'linkedin', 'color' => '#0A66C2', 'scopes' => 'r_liteprofile, r_emailaddress'],
        'twitter' => ['label' => 'Twitter', 'icon' => 'twitter', 'color' => '#1DA1F2', 'scopes' => 'users.read, tweet.read'],
        'gitlab' => ['label' => 'GitLab', 'icon' => 'gitlab', 'color' => '#FC6D26', 'scopes' => 'read_user'],
        'bitbucket' => ['label' => 'Bitbucket', 'icon' => 'bitbucket', 'color' => '#0052CC', 'scopes' => 'account, email'],
        'slack' => ['label' => 'Slack', 'icon' => 'slack', 'color' => '#4A154B', 'scopes' => 'identity.basic, identity.email'],
    ];

    public function index()
    {
        $services = RegisteredSocialService::ordered()->get();

        return view('admin.pages.social-services.index', [
            'services' => $services,
            'knownProviders' => self::KNOWN_PROVIDERS,
        ]);
    }

    public function store(SocialServiceRequest $request)
    {
        $data = $request->validated();
        $data['created_by'] = $request->user()->id;
        $data['updated_by'] = $request->user()->id;

        RegisteredSocialService::create($data);

        $this->bustCache();

        return redirect()
            ->route('admin.social-services.index')
            ->with('status', __('Social service registered.'));
    }

    public function update(SocialServiceRequest $request, RegisteredSocialService $socialService)
    {
        $data = $request->validated();
        $data['updated_by'] = $request->user()->id;

        // If secret field submitted as placeholder, keep old one
        if (($data['client_secret'] ?? null) === '__unchanged__') {
            unset($data['client_secret']);
        }

        $socialService->update($data);

        $this->bustCache();

        return redirect()
            ->route('admin.social-services.index')
            ->with('status', __('Social service updated.'));
    }

    public function destroy(RegisteredSocialService $socialService)
    {
        $socialService->delete();
        $this->bustCache();

        return back()->with('status', __('Social service deleted.'));
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'order' => ['required', 'array'],
            'order.*' => ['integer', 'exists:registered_social_services,id'],
        ]);

        foreach ($request->input('order') as $index => $id) {
            RegisteredSocialService::where('id', $id)->update(['sort_order' => $index]);
        }

        $this->bustCache();

        return response()->json(['ok' => true]);
    }

    protected function bustCache(): void
    {
        Cache::forget('social_services.enabled');
    }
}