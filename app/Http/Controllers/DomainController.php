<?php

namespace App\Http\Controllers;

use App\Http\Requests\DomainSearchRequest;
use App\Models\DomainPricing;
use App\Services\EppService;
use Exception;
use Illuminate\Support\Facades\Log;

class DomainController extends Controller
{
    protected EppService $eppService;

    public function __construct(EppService $eppService)
    {
        $this->eppService = $eppService;
    }

    public function index()
    {
        $domains = DomainPricing::select(['tld', 'registration_price', 'renewal_price'])->get();

        return view('domains.index', ['domains' => $domains]);
    }

    public function search(DomainSearchRequest $request)
    {
        try {
            $domainText = $request->input('domain');
            $extension = $request->input('extension');

            $domainResults = $this->eppService->checkDomainAvailability($domainText, $extension);

            if ($request->ajax()) {
                return response()->json([
                    'status' => 'success',
                    'data' => $domainResults,
                ]);
            }

            return view('domains.index', [
                'searchResults' => $domainResults,
                'domains' => DomainPricing::orderBy('tld')->get(),
                'popularDomains' => DomainPricing::inRandomOrder()->limit(5)->get(),
                'searchedDomain' => $domainText,
                'searchedExtension' => $extension,
            ]);

        } catch (Exception $e) {
            Log::error('EPP Error: '.$e->getMessage());

            if ($request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to process domain check',
                    'error' => $e->getMessage(),
                ], 500);
            }

            return back()->withInput()
                ->with('error', 'Failed to process domain check: '.$e->getMessage());
        }
    }
}
