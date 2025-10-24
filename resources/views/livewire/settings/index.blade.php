<div>
    <div class="main-content mt-0 hor-content">
        <div class="side-app">
            <div class="main-container container-fluid" style="max-width:85% !important;">
                <div class="page-header">
                    <h1 class="page-title">Company Settings</h1>
                </div>
                <div class="row">
                    @include('components.message')                              
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-body">
                                <form wire:submit.prevent="saveSettings" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group">
                                        <label for="company_name">Company Name</label>
                                        <input type="text" class="form-control" id="company_name" wire:model="company_name">
                                        @error('company_name') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="address">Address</label>
                                        <input type="text" class="form-control" id="address" wire:model="address">
                                        @error('address') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="city">City</label>
                                        <input type="text" class="form-control" id="city" wire:model="city">
                                        @error('city') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="zip_code">ZIP Code</label>
                                        <input type="text" class="form-control" id="zip_code" wire:model="zip_code">
                                        @error('zip_code') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>                                    
                                    <div class="form-group">
                                        <label for="currency">Currency</label>
                                        <input type="text" class="form-control" id="currency" wire:model="currency">
                                        @error('currency') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="logo">Logo</label>
                                        <input type="file" class="form-control" id="logo" wire:model="logo">
                                        @if($logo)
                                            <img src="{{ asset('storage/' . $logo) }}" alt="Logo Preview" style="max-width: 100px; margin-top: 10px;">
                                        @endif
                                        @error('logo') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="favicon">Favicon</label>
                                        <input type="file" class="form-control" id="favicon" wire:model="favicon">
                                        @if ($favicon)
                                            <img src="{{ asset('storage/' . $favicon) }}" alt="Favicon Preview" style="max-width: 50px; margin-top: 10px;">
                                        @endif
                                        @error('favicon') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-body">
                                <form wire:submit.prevent="saveSettings">
                                    @csrf
                                    <div class="form-group">
                                        <label for="smtp_host">Mail From Name</label>
                                        <input type="text" class="form-control" id="smtp_host" wire:model="mail_from_name">
                                        @error('mail_from_name') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="mail_from_address">Mail From Address</label>
                                        <input type="text" class="form-control" id="smtp_host" wire:model="mail_from_address">
                                        @error('mail_from_address') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>                                                                        
                                    <div class="form-group">
                                        <label for="smtp_host">SMTP Host</label>
                                        <input type="text" class="form-control" id="smtp_host" wire:model="smtp_host">
                                        @error('smtp_host') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="smtp_port">SMTP Port</label>
                                        <input type="text" class="form-control" id="smtp_port" wire:model="smtp_port">
                                        @error('smtp_port') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="smtp_username">SMTP Username</label>
                                        <input type="text" class="form-control" id="smtp_username" wire:model="smtp_username">
                                        @error('smtp_username') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="smtp_password">SMTP Password</label>
                                        <input type="password" class="form-control" id="smtp_password" wire:model="smtp_password">
                                        @error('smtp_password') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <button type="submit" class="btn btn-primary">Save Settings</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
