<?php

namespace App\Http\Controllers;

use App\Http\Requests\DomainTransferRequest;
use App\Models\DomainPricing;
use App\Services\DomainTransferService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class DomainTransferController extends Controller
{
    private DomainTransferService $transferService;

    public function __construct(DomainTransferService $transferService)
    {
        $this->transferService = $transferService;
    }

    public function index()
    {
        $extensions = DomainPricing::select(['tld', 'transfer_price','registration_price'])->get();
        return view('domains.transfer', ['extensions' => $extensions]);
    }

    public function initiateTransfer(DomainTransferRequest $request): JsonResponse
    {
        try {
            $result = $this->transferService->initiateDomainTransfer(
                $request->domain_name,
                $request->auth_code,
                [
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
                $request->period ?? 1
            );

            return response()->json($result);

        } catch (Exception $e) {
            Log::error('Domain transfer initiation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'error' => 'Domain transfer failed: '.$e->getMessage(),
            ], 422);
        }
    }

    public function checkStatus(string $domainName): JsonResponse
    {
        try {
            $result = $this->transferService->checkTransferStatus($domainName);

            return response()->json($result);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Status check failed: '.$e->getMessage(),
            ], 422);
        }
    }
}
