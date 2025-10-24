<?php

namespace App\Livewire;

use App\Models\Destination;
use Livewire\Component;
use Illuminate\Support\Facades\Validator;
use Livewire\WithPagination;

class Destinations extends Component
{
    use WithPagination;

    public $destinations;
    public $editingDestination;

    protected $listeners = ['deleteConfirmed' => 'deleteDestination'];
    public $destinationId;

    public function mount()
    {
        $this->editingDestination = [];
        $this->destinations = Destination::all();
    } 

    public function render()
    {
        $data = Destination::paginate(12);
        return view('livewire.destinations.index', [
            'data' => $data
        ]);
    }

    public function create()
    {
        $newDestination = Destination::make();
        $newDestination->editing = false;
        $this->destinations->push($newDestination);
    }

    public function editDestination($destinationId = null)
    {
        if ($destinationId) {
            $destination = $this->destinations->where('id', $destinationId)->first();
            $destination->editing = true;
        
            if (empty($this->editingDestination)) {
                $this->editingDestination = [
                    'id' => $destination->id,
                    'name' => $destination->name,
                ];
            }
        } else {
            $newDestination = Destination::make();
            $newDestination->editing = true;
            $this->destinations->push($newDestination);
        
            $this->editingDestination = [
                'id' => '',
                'name' => '',
            ];
        }
    }
    
    public function saveDestination($destinationId = null)
    {
        $rules = [
            'name' => 'required',
        ];
    
        $validator = Validator::make($this->editingDestination, $rules);
    
        if ($validator->fails()) {
            session()->flash('errors', $validator->errors()->toArray());
            return;
        }
    
        try {
            $destination = $destinationId ? Destination::findOrFail($destinationId) : new Destination();
    
            $destination->fill([
                'name' => $this->editingDestination['name'],
            ]);
    
            if ($destinationId) {
                $destination->update();  
            } else {
                $destination->save();
            }
    
            $this->editingDestination = [];
            $this->destinations = Destination::get();
            session()->flash('success', 'Details saved successfully!');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to save the detail.'.$e);
        }
    }      

    public function cancelEdit($destinationId = null)
    {
        if ($destinationId) {
            $this->destinations->find($destinationId)->editing = false;
        } else {
            $newDestination = Destination::make();
            $newDestination->editing = false;
        }
    }

    public function confirmDelete($destinationId = null)
    {
        if (!$destinationId) {
            $new = Destination::make();
            $new->editing = false;
            return;
        }
        $this->destinationId = $destinationId;
        $this->dispatch('show-confirm-delete');
    }

    public function deleteDestination()
    {
        if (!$this->destinationId) {
            $new = Destination::make();
            $new->editing = false;
            return;
        }

        $destination = Destination::find($this->destination);
        if (!$destination) {
            session()->flash('error', 'Destination not found');
            return;
        }

        $destination->delete();
        session()->flash('success', 'Destination deleted successfully!');
        $this->destinations = Destination::get();
    }

}
