<?php

namespace App\Http\Controllers;

use App\Models\WaterMeter;
use Illuminate\Http\Request;

class WaterMeterController extends Controller
{
    public function getReadings()
    {
        $readings = WaterMeter::orderBy('created_at', 'desc')->get();
        return response()->json($readings);
    }

    public function storeReading(Request $request)
    {
        $validatedData = $request->validate([
            'meter_id' => 'required',
            'reading' => 'required|numeric',
        ]);

        $latestReading = WaterMeter::where('meter_id', $validatedData['meter_id'])
            ->latest()
            ->first();

        if ($latestReading) {
            $previousReading = $latestReading->reading;
            $currentReading = $validatedData['reading'];
            $usage = $currentReading - $previousReading;

            $bill = $usage * 500; 

            $validatedData['bill'] = $bill;
        }

        WaterMeter::create($validatedData);

        return response()->json(['success' => true]);
    }

    public function processPayment(Request $request)
    {
        $amount = $request->input('amount');
        $meterId = $request->input('meterId');
        
        $user = auth()->user(); 
        $balance = $user->amount;

        if ($balance >= $amount) {
            $user->amount -= $amount;
            $user->save();

            $waterMeter = WaterMeter::where('meter_id', $meterId)->first();
            $waterMeter->bill -= $amount;
            $waterMeter->due_amount += $amount;
            $waterMeter->save();

            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false]);
        }
    }

    public function topup(Request $request)
    {
        $amount = $request->input('amount');

        $user = auth()->user();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not authenticated.']);
        }

        $user->amount += $amount;
        $user->save();

        return response()->json(['success' => true]);
    }
}
