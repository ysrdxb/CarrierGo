<?php 

namespace App\Livewire;

use Livewire\Component;
use App\Models\Setting;
use App\Services\SettingService;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class Settings extends Component
{
    use WithFileUploads;
 
    public $company_name;
    public $address;
    public $zip_code;
    public $city;
    public $logo;
    public $favicon;
    public $currency;
    public $smtp_host;
    public $smtp_port;
    public $smtp_username;
    public $smtp_password;
    public $mail_from_name;
    public $mail_from_address;

    protected $rules = [
        'company_name' => 'required',
        'address' => 'required',
        'zip_code' => 'required',
        'city' => 'required',
        'currency' => 'required',
        'smtp_host' => 'required',
        'smtp_port' => 'required',
        'smtp_username' => 'required',
        'smtp_password' => 'required',
        'logo' => 'nullable|max:2048',
        'favicon' => 'nullable|max:2048',        
    ];

    public function mount()
    {
        $settings = Setting::first();
        if ($settings) {
            $this->company_name = $settings->company_name;
            $this->address = $settings->address;
            $this->zip_code = $settings->zip_code;
            $this->city = $settings->city;
            $this->logo = $settings->logo;
            $this->favicon = $settings->favicon;
            $this->currency = $settings->currency;
            $this->mail_from_name = $settings->mail_from_name;
            $this->mail_from_address = $settings->mail_from_address;
            $this->smtp_host = $settings->smtp_host;
            $this->smtp_port = $settings->smtp_port;
            $this->smtp_username = $settings->smtp_username;
            $this->smtp_password = $settings->smtp_password;
        }
    }


    public function saveSettings()
    {
        $this->validate();
    
        $settings = Setting::firstOrNew();
    
        if ($this->logo instanceof \Illuminate\Http\UploadedFile && $settings->logo && Storage::disk('public')->exists($settings->logo)) {
            Storage::disk('public')->delete($settings->logo);
        }
    
        if ($this->favicon instanceof \Illuminate\Http\UploadedFile && $settings->favicon && Storage::disk('public')->exists($settings->favicon)) {
            Storage::disk('public')->delete($settings->favicon);
        }
    
        $settings->company_name = $this->company_name;
        $settings->address = $this->address;
        $settings->zip_code = $this->zip_code;
        $settings->city = $this->city;
        $settings->currency = $this->currency;
        $settings->mail_from_name = $this->mail_from_name;
        $settings->mail_from_address = $this->mail_from_address;
        $settings->smtp_host = $this->smtp_host;
        $settings->smtp_port = $this->smtp_port;
        $settings->smtp_username = $this->smtp_username;
        $settings->smtp_password = $this->smtp_password;
    
        if ($this->logo instanceof \Illuminate\Http\UploadedFile) {
            $logoPath = $this->logo->store('logos', 'public');
            $settings->logo = $logoPath;
        }
    
        if ($this->favicon instanceof \Illuminate\Http\UploadedFile) {
            $faviconPath = $this->favicon->store('favicon', 'public');
            $settings->favicon = $faviconPath;
        }
    
        $settings->save();
    
        SettingService::fillSession();
    
        session()->flash('success', 'Settings saved successfully.');
    }
    
    

    public function render()
    {
        return view('livewire.settings.index');
    }
}
