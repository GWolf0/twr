<?php

namespace App\Interfaces;

use App\Types\MResponse;
use Illuminate\Http\Request;

// Interface defining core auth related functions
interface IAuthInterface
{
    public function register(Request $request): MResponse;
    public function login(Request $request): MResponse;
    public function logout(Request $request): MResponse;

    public function sendPasswordResetNotification(Request $request): MResponse;
    public function resetPassword(Request $request): MResponse;
    
    public function sendEmailConfirmationNotification(Request $request): MResponse;
    public function confirmEmail(Request $request): MResponse;
    
}
