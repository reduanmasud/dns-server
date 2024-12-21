<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Coupon;

class CaptivePortalController extends Controller
{
    public function showForm()
    {
        return view('captive-portal');
    }

    public function handleSubscription(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'coupon' => 'nullable|string'
        ]);

        $email = $request->input('email');
        $couponCode = $request->input('coupon');

        // Handle coupon validation
        if ($couponCode) {
            $coupon = Coupon::where('code', $couponCode)->where('is_used', false)->first();

            if ($coupon) {
                // Mark the coupon as used
                $coupon->is_used = true;
                $coupon->save();

                // Create user as authorized
                User::create(['email' => $email, 'status' => 'authorized']);

                return redirect()->route('portal.success')->with('message', 'Coupon applied successfully!');
            } else {
                return redirect()->route('portal.index')->withErrors('Invalid or used coupon.');
            }
        } else {
            // Simulate subscription (e.g., redirect to payment gateway)
            return redirect()->route('portal.payment');
        }
    }

    public function success()
    {
        return view('success');
    }

    public function payment()
    {
        return view('payment');
    }
}
