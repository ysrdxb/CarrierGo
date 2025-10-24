<?php

namespace App\Livewire;

use App\Models\Language;
use Livewire\Component;
use Livewire\WithPagination;
use Log;

class Languages extends Component
{
    use WithPagination;

    public $languages;
    public $editingLanguage;
    protected $listeners = ['deleteConfirmed' => 'delete'];
    public $languageId;

    public function mount()
    {
        $this->editingLanguage = [];
        $this->languages = Language::all();
    }

    public function render()
    {
        $data = Language::paginate(12);
        return view('livewire.languages.index', compact('data'));
    }

    public function addNewLanguage()
    {
        $newLanguage = Language::make();
        $newLanguage->editing = false;
        $this->languages->push($newLanguage);
    }

    public function editLanguage($languageId = null)
    {
        if ($languageId) {
            $language = Language::where('id', $languageId)->first();
            $language->editing = true;

            if (empty($this->editingLanguage)) {
                $this->editingLanguage = [
                    'id' => $language->id,
                    'name' => $language->name,
                    'code' => $language->code,
                ];
            }
        } else {
            $newLanguage = Language::make();
            $newLanguage->editing = true;
            $this->languages->push($newLanguage);

            $this->editingLanguage = [
                'id' => '',
                'name' => '',
                'code' => '',
            ];
        }
    }

    public function saveLanguage($languageId = null)
    {
        $rules = [
            'editingLanguage.name' => 'required|max:250',
            'editingLanguage.code' => 'required|max:2',
        ];

        $this->validate($rules);

        try {
            $language = $languageId ? Language::findOrFail($languageId) : new Language();

            $language->fill([
                'name' => $this->editingLanguage['name'],
                'code' => $this->editingLanguage['code'],
            ]);

            if ($languageId) {
                $language->update();
            } else {
                $language->save();
            }

            $this->editingLanguage = [];
            $this->languages = Language::all();
            session()->flash('success', 'Language information saved successfully!');
        } catch (\Exception $e) {
            session()->flash('error', $e);
        }
    }

    public function cancelEdit($languageId = null)
    {
        if ($languageId) {
            $this->languages->find($languageId)->editing = false;
        } else {
            $newLanguage = Language::make();
            $newLanguage->editing = false;
        }
    }

    public function confirmDelete($languageId = null)
    {
        if (!$languageId) {
            $newLanguage = Language::make();
            $newLanguage->editing = false;
            return;
        }
        $this->languageId = $languageId;
        $this->dispatch('show-confirm-delete');
    }

    public function delete()
    {
        if (!$this->languageId && !Auth::user()->hasRole('Admin')) {
            $newLanguage = Language::make();
            $newLanguage->editing = false;
            return false;
        }

        $language = Language::find($this->languageId);
        if (!$language) {
            session()->flash('error', 'Language not found');
            return;
        }

        $language->delete();
        session()->flash('success', 'Language deleted successfully!');

        $this->languages = Language::all();
    }
}
