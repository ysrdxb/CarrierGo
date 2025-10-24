<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reference;
use App\Models\Freight;
use App\Models\FreightType;
use App\Models\ReferenceAdditionalFee;
use App\Models\ReferenceNumber;

use Auth;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        \Log::info('=== Dashboard Access ===');
        \Log::info('User: ' . (Auth::user() ? Auth::user()->email : 'NOT LOGGED IN'));
        \Log::info('Tenant ID: ' . (Auth::user()?->tenant_id ?? 'NULL'));
        \Log::info('App tenant_id: ' . (app()->has('tenant_id') ? app()->make('tenant_id') : 'NOT SET'));

        try {
            \Log::info('Checking if user is Admin...');
            if (!Auth::user()) {
                throw new \Exception('No authenticated user');
            }
            if (Auth::user()->hasRole('Admin')) {
                \Log::info('User is Admin');
                $reference_numbers = ReferenceNumber::all();
                $options = [];

            foreach ($reference_numbers as $reference_number) {
                $start = intval($reference_number->number_range);
                $lastUsedReferenceParts = explode('-', $reference_number->last_used_reference);
    
                if (count($lastUsedReferenceParts) != 2) {
                    continue;
                }
    
                $end = intval($lastUsedReferenceParts[0]);
                $year = intval($lastUsedReferenceParts[1]);
    
                $currentYear = date('Y');
    			$currentYear = substr($currentYear, -2);
                if ($year != $currentYear) {
                    $end = $start - 1;
                    $year = $currentYear;
                }
    
                for ($i = $start; $i <= $end; $i++) {
                    $optionLabel = $i;
                    $optionValue = $i . '-' . $year;
                    $options[$optionValue] = $optionLabel;
                }
            }
			    
            $query = Reference::query();

            $defaultMonth = date('m');
            $defaultYear = date('Y');            
    
            $filterByRefNo = $request->input('filterByRef');
            $filterByMonth = $request->input('month');
            $filterByYear = $request->input('year');

            $filterByMonth = $filterByMonth ? $filterByMonth : $defaultMonth;
            $filterByYear = $filterByYear ? $filterByYear : $defaultYear;            
    
            if ($filterByRefNo) {
                $query->where('reference_no', $filterByRefNo);
            }
    
            if ($filterByMonth) {
                $query->whereMonth('created_at', $filterByMonth);
            }
    
            if ($filterByYear) {
                $query->whereYear('created_at', $filterByYear);
            }
    
            $carrierTotalSum = $query->sum('carrier_fees');
            $extraFeesSum = $query->sum('extra_fees');
            $extraFeesSumEur = $query->sum('extra_fees_eur');
            
            $otherFeesSum = $query->withSum('additionalFees', 'amount')->get()->sum('additional_fees_sum_amount');
    
            $misFees = $extraFeesSumEur + $otherFeesSum;
            $agentFeesSum = $query->sum('agent_fees');
    
            $priceSum = $query->sum('price');
            $totalSum = $query->sum('price') - $carrierTotalSum - $agentFeesSum - $extraFeesSumEur - $otherFeesSum;
    
            return view('dashboard', compact('options', 'totalSum', 'carrierTotalSum', 'priceSum', 'agentFeesSum', 'misFees', 'otherFeesSum'));
        } else {
            return view('customer_dashboard');
            }
        } catch (\Exception $e) {
            \Log::error('=== DASHBOARD ERROR ===');
            \Log::error('Message: ' . $e->getMessage());
            \Log::error('Exception: ' . get_class($e));
            \Log::error('File: ' . $e->getFile() . ':' . $e->getLine());
            \Log::error('Trace: ' . $e->getTraceAsString());

            return view('error', ['error' => $e->getMessage()]);
        }
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
