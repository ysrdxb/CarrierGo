<?php

namespace App\Services;

use App\Models\ReferenceNumber;
use Auth;

class ReferenceNumberService
{
    public function getUserReference()
    {
        $currentYear = date('Y');
        $currentYear = substr($currentYear, -2);
        $referenceNumber = ReferenceNumber::where('user_id', Auth::user()->id)
            ->where('year', $currentYear)
            ->orderBy('id', 'desc')
            ->first();
    
        return $referenceNumber;
    }

    public function getLastUsedReference()
    {
        $currentYear = date('Y');
        $currentYear = substr($currentYear, -2);
        $referenceNumber = ReferenceNumber::where('user_id', Auth::user()->id)
            ->where('year', $currentYear)
            ->orderBy('id', 'desc')
            ->first();
    
        return $referenceNumber ? $referenceNumber->last_used_reference : null;
    }

    public function getNextReferenceNumber()
    {
        $currentYear = date('Y');
        $currentYear = substr($currentYear, -2);
        $referenceNumber = ReferenceNumber::where('user_id', Auth::user()->id)
            ->where('year', $currentYear)
            ->orderBy('id', 'desc')
            ->first();
    
        if ($referenceNumber) {
            $lastUsedReference = $referenceNumber->last_used_reference;
    
            if ($lastUsedReference > 0) {
                $lastReference = explode('-', $lastUsedReference);
                $nextReference = intval($lastReference[0]) + 1;
            } else {
                $numberRange = explode('-', $referenceNumber->number_range);
                $nextReference = intval($numberRange[0]);
            }
    
            $nextReferenceNumber = $nextReference . '-' . $currentYear;
        } else {
            $nextReferenceNumber = 0;
        }
    
        return $nextReferenceNumber;
    }
    
    public function saveNextReferenceNumber()
    {
        $nextReferenceNumber = $this->getNextReferenceNumber();

        $currentYear = date('Y');
        $currentYear = substr($currentYear, -2);
        $referenceNumber = ReferenceNumber::updateOrCreate(
            ['year' => $currentYear, 'user_id' => Auth::user()->id],
            ['last_used_reference' => $nextReferenceNumber]
        );

        return $referenceNumber;
    }            
}
