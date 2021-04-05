<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use App\Models\User;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    public function verify(Request $request, User $user)
    {
        // Check if the url is a valid signed url
        if(! \URL::hasValidSignature($request)){
            return response()->json(["errors" => [
                "message" => "Link de verificación inválido"
            ]], 422);
        }

        // Check if the user has already verified account
        if($user->hasVerifiedEmail()){
            return response()->json(["errors" => [
                "message" => "Email ya ha sido verificado"
            ]], 422);
        }

        $user->markEmailAsVerified();
        event(new Verified($user));

        return response()->json(['message' => 'Email ha sido verificado con éxito'], 200);
    }

    public function resend(Request $request)
    {
        $this->validate($request, [
            'email' => ['email', 'required']
        ]);

        $user = User::where('email', $request->email)->first();
        if(! $user) {
            return response()->json(["errors" => [
                "email" => "Usuario no encontrado con este email"
            ]], 422);
        }

        if($user->hasVerifiedEmail()){
            return response()->json(["errors" => [
                "message" => "Email ya ha sido verificado"
            ]], 422);
        }

        $user->sendEmailVerificationNotification();

        return response()->json(['status' => "Link de verificación reenviado"]);
    }
}
