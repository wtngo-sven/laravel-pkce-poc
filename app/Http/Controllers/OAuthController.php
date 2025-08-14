<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class OAuthController extends Controller
{
    public function redirect(Request $request)
    {
        $request->session()->put('state', $state = Str::random(40));
        $request->session()->put('code_verifier', $verifier = Str::random(128));

        // S256 code_challenge (base64url( sha256(verifier) ))
        $encoded = base64_encode(hash('sha256', $verifier, true));
        $codeChallenge = strtr(rtrim($encoded, '='), '+/', '-_');

        $query = http_build_query([
            'client_id'             => config('passport.pkce_client_id', env('PASSPORT_PKCE_CLIENT_ID')),
            'redirect_uri'          => env('PASSPORT_PKCE_REDIRECT_URI'),
            'response_type'         => 'code',
            'scope'                 => 'user:read', // optional; adjust to your scopes
            'state'                 => $state,
            'code_challenge'        => $codeChallenge,
            'code_challenge_method' => 'S256',
            // 'prompt' => 'login|consent|none' // optional
        ]);

        return redirect(rtrim(env('PASSPORT_BASE_URL'),'/').'/oauth/authorize?'.$query);
    }

    public function callback(Request $request)
    {
        // CSRF/State check
        $state = $request->session()->pull('state');
        abort_unless(strlen($state) && $state === $request->query('state'), 422, 'Invalid state');

        $verifier = $request->session()->pull('code_verifier');

        // Exchange code + verifier for tokens
        $resp = Http::asForm()->post(
            rtrim(env('PASSPORT_BASE_URL'),'/').'/oauth/token',
            [
                'grant_type'    => 'authorization_code',
                'client_id'     => env('PASSPORT_PKCE_CLIENT_ID'),
                'redirect_uri'  => env('PASSPORT_PKCE_REDIRECT_URI'),
                'code_verifier' => $verifier,
                'code'          => $request->query('code'),
            ]
        )->throw()->json();

        // TODO: store tokens (see notes below)
        // return response()->json($resp);
        return redirect('/')->with('oauth_tokens', $resp);
    }
}
