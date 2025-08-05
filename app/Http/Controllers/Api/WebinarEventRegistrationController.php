<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\WebinarEventRegistration;
use App\Http\Resources\WebinarEventRegistrationResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Traits\ResponseTrait;

class WebinarEventRegistrationController extends Controller
{
    use ResponseTrait;

    public function register(Request $request, $eventId)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email',
            'company' => 'nullable|string|max:255',
            'reason_for_attending' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
        ]);
        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), 422);
        }
        $data = $validator->validated();
        $data['event_id'] = $eventId;
        $registration = WebinarEventRegistration::create($data);
        return $this->success(new WebinarEventRegistrationResource($registration), 'Registration successful', 201);
    }
}