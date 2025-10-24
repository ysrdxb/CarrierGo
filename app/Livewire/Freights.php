<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\FreightType;
use App\Models\Freight;
use App\Models\Destination;
use Livewire\Attributes\On; 

class Freights extends Component
{
    public $freightTypes;
    public $name;

    protected $rules = [
        'name' => 'required|string|max:200',
    ];   

    public function render()
    {
        return view('livewire.freights.index');
    }

    public function saveEntry()
    {
        $this->validate();

        $data = [
            'name' => $this->name ?? '',
        ];

        $data = FreightType::create($data);
        $this->dispatch('freightTypeCreated', ['id' => $data->id, 'name' => $data->name]);
        $this->name = '';
        session()->flash('success', 'Freight Type added successfully!');

    }

    public function saveDestination()
    {
        $this->validate();

        $data = [
            'name' => $this->name ?? '',
        ];

        $data = Destination::create($data);
        $this->dispatch('destinationCreated', ['id' => $data->id, 'name' => $data->name]);
        $this->name = '';
        session()->flash('success', 'Destination added successfully!');

    }    

    #[On('refresh-entriess')]
    public function test()
    {
        //dd('test from Freight');
    }

}
