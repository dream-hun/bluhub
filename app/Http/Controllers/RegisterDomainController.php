<?php

namespace App\Http\Controllers;

use App\Http\Requests\DomainRegistrationRequest;
use App\Services\DomainRegistrationService;
use Exception;
use Illuminate\Support\Facades\Log;

class RegisterDomainController extends Controller
{
    public function index()
    {
        return view('domains.register');
    }

    public function store(DomainRegistrationRequest $request)
    {
        $registrationService = new DomainRegistrationService;
        try {
            $result = $registrationService->registerDomain(
                $request->domain_name,
                $request->extension,
                $request->nameservers, [
                    'name' => $request->contact_name,
                    'organization' => $request->organization,
                    'address' => $request->address,
                    'city' => $request->city,
                    'state' => $request->state,
                    'postal_code' => $request->postal_code,
                    'country_code' => $request->country_code,
                    'phone' => $request->phone,
                    'email' => $request->email,
                ],
                $request->period ?? 1);

            return response()->json($result);
        } catch (Exception $exception) {
            Log::error('Domain registration failed', [
                'error' => $exception->getMessage(),
                'trace' => $exception->getTraceAsString(),
            ]
            );

            return response()->json([
                'error' => 'Domain registration failed:'.$exception->getMessage(),
            ], 422);
        }

    }
}
