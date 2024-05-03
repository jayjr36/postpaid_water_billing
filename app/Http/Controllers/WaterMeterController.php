<?php

namespace App\Http\Controllers;

use App\Models\WaterMeter;
use Illuminate\Http\Request;

class WaterMeterController extends Controller
{
    public function storeReading(Request $request)
    {
        $validatedData = $request->validate([
            'meter_id' => 'required',
            'reading' => 'required|numeric',
        ]);

        WaterMeter::create($validatedData);

        return response()->json(['success' => true]);
    }
}
