<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\FreightType;
use App\Models\Freight;
use Livewire\Attributes\On; 
use App\Services\SettingService;
use App\Models\Destination;

class Shipments extends Component
{
    public $reference_id;
    public $fin;
    public $destination;
    public $settings;
    public $destinations;
    public $freights = null;
    
    protected $rules = [
        'fin' => 'required|string|max:200',
        'destination' => 'required|numeric'
    ];   

    public function mount()
    {
        // Get settings, use a default object if none exist
        $this->settings = SettingService::getSettings() ?? (object)[
            'company_name' => 'CarrierGo',
            'address' => '',
            'zip_code' => '',
            'city' => '',
            'currency' => 'EUR',
        ];

        // Get all destinations
        $this->destinations = Destination::all();
    }

    public function render()
    {
        return view('livewire.shipments.tracking')
            ->layout('components.layouts.guest');
    }

    public function track()
    {
        $this->validate();
        $freights = Freight::where('vehicle_fin', $this->fin)
                          ->where('destination_id', $this->destination)
                          ->get();
        
        if ($freights->isNotEmpty()) {
           $this->freights = $freights;
        } else {
            session()->flash('error', 'Freight not found for the provided details.');
        }
    }

    public function search()
    {
        $this->freights = null;
    }

}
