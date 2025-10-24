<?php

namespace App\Livewire;

use App\Models\FreightType;
use Livewire\Component;
use Illuminate\Support\Facades\Validator;
use Livewire\WithPagination;

class FreightTypes extends Component
{
    use WithPagination;

    public $freightTypes;
    public $editingFreightType;

    protected $listeners = ['deleteConfirmed' => 'deleteFreightType'];
    public $freightTypeId;

    public function mount()
    {
        $this->editingFreightType = [];
        $this->freightTypes = FreightType::all();
    } 

    public function render()
    {
        $data = FreightType::paginate(12);
        return view('livewire.freights.freight_types', [
            'data' => $data
        ]);
    }

    public function addNewFreightType()
    {
        $newFreightType = FreightType::make();
        $newFreightType->editing = false;
        $this->freightTypes->push($newFreightType);
    }

    public function editFreightType($freightTypeId = null)
    {
        if ($freightTypeId) {
            $freightType = $this->freightTypes->where('id', $freightTypeId)->first();
            $freightType->editing = true;
        
            if (empty($this->editingFreightType)) {
                $this->editingFreightType = [
                    'id' => $freightType->id,
                    'name' => $freightType->name,
                ];
            }
        } else {
            $newFreightType = FreightType::make();
            $newFreightType->editing = true;
            $this->freightTypes->push($newFreightType);
        
            $this->editingFreightType = [
                'id' => '',
                'name' => '',
            ];
        }
    }
    
    public function saveFreightType($freightTypeId = null)
    {
        $rules = [
            'name' => 'required',
        ];
    
        $validator = Validator::make($this->editingFreightType, $rules);
    
        if ($validator->fails()) {
            session()->flash('errors', $validator->errors()->toArray());
            return;
        }
    
        try {
            $freightType = $freightTypeId ? FreightType::findOrFail($freightTypeId) : new FreightType();
    
            $freightType->fill([
                'name' => $this->editingFreightType['name'],
            ]);
    
            if ($freightTypeId) {
                $freightType->update();  
            } else {
                $freightType->save();
            }
    
            $this->editingFreightType = [];
            $this->freightTypes = FreightType::get();
            session()->flash('success', 'Details saved successfully!');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to save the detail.'.$e);
        }
    }      

    public function cancelEdit($freightTypeId = null)
    {
        if ($freightTypeId) {
            $this->freightTypes->find($freightTypeId)->editing = false;
        } else {
            $newFreightType = FreightType::make();
            $newFreightType->editing = false;
        }
    }

    public function confirmDelete($freightTypeId = null)
    {
        if (!$freightTypeId) {
            $newFreightType = FreightType::make();
            $newFreightType->editing = false;
            return;
        }
        $this->freightTypeId = $freightTypeId;
        $this->dispatch('show-confirm-delete');
    }

    public function deleteFreightType()
    {
        if (!$this->freightTypeId) {
            $newFreightType = FreightType::make();
            $newFreightType->editing = false;
            return;
        }

        $freightType = FreightType::find($this->freightTypeId);
        if (!$freightType) {
            session()->flash('error', 'Freight Type not found');
            return;
        }

        $freightType->delete();
        session()->flash('success', 'Freight Type deleted successfully!');
        $this->freightTypes = FreightType::get();
    }

}
