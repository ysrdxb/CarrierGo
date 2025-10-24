<div>
    <div class="page">
        <div class="">
           
            <div class="col col-login mx-auto mt-7">
                <div class="text-center">
                    <a href="{{ route('dashboard') }}"><img style="max-width: 120px" src="{{ asset('admin/images/brand/logo-main.png') }}" class="header-brand-img" alt="{{ $settings->company_name }}"></a>
                </div>
            </div>

            <div class="container-login100">
                <div class="wrap-login100 p-6">
                    <form wire:submit.prevent="track" class="login100-form validate-form">
                        <span class="login100-form-title pb-5">
                            Tracking
                        </span>
                        @if(!$freights)
                            <div class="panel panel-primary">
                                <div class="tab-menu-heading">
                                    @include('components.message') 
                                </div>
                                <div class="panel-body tabs-menu-body p-0 pt-5">
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="tab5">
                                            <div class="form-group">
                                                <input wire:model.defer="fin" class="form-control" type="text" placeholder="Vehicle Fin No.">
                                            </div>
            
                                            <div class="form-group">
                                                <select wire:model.defer="destination" class="form-control select2" data-placeholder="Choose one" tabindex="-1" aria-hidden="true">
                                                    <option value="">Choose Destination</option>
                                                    @foreach($destinations as $dest)
                                                    <option value="{{ $dest->id }}">{{ $dest->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
            
                                            <div class="container-login100-form-btn">
                                                <button type="submit" class="login100-form-btn btn-primary">GO</button>
                                            </div>    
                                                                                        
                                        </div>                                    
                                        
                                        <br><hr>
                                            <div class="col-md-12 col-sm-12 text-center">
                                                Powered by <a href="https://carriergo.de" target="_blank">  CarrierGo.de </a>
                                            </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="panel panel-primary">
                                <div class="tab-menu-heading">
                                    
                                </div>
                                <div class="panel-body tabs-menu-body p-0 pt-5">
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="tab5">
                                            <div class="wrap-input100 validate-input input-group" data-bs-validate="Valid email is required: ex@abc.xyz">
                                            
                                            </div>
                                            @foreach($freights as $freight)
                                            <div class="wrap-input100 validate-input input-group" data-bs-validate="Valid email is required: ex@abc.xyz">
                                                Modell: {{ $freight->vehicle_model ?? '' }}<br>
                                                FIN No.: {{ $freight->vehicle_fin ?? '' }}<br><br>
                                                Freight Typ: {{ $freight->freightType->name ?? '' }}<br>
                                                Status: {{ $freight->reference->status ?? ''}}<br><br>
                                                Destination: {{ $freight->destination->name ?? '' }}<br><br>
                                                Vessel: {{ $freight->reference->vessel_name ?? '' }}<br><br>
                                                ETS: {{ $freight->reference->estimated_time_shipment ?? '' }}<br>
                                                ETA: {{ $freight->reference->estimated_time_arrival ?? '' }}<br>
                                            </div>
                                            @endforeach
                                                                                        
                                            <div class="container-login100-form-btn">
                                                <a href="#" wire:click.prevent='search' class="text-primary">Back To Search</a>
                                            </div>
                                            
                                        </div>
                                       
                                        <br><hr>
                                            <div class="col-md-12 col-sm-12 text-center">
                                                Powered by <a href="https://carriergo.de" target="_blank">  CarrierGo.de </a>
                                            </div>
                                    </div>
                                </div>
                            </div>                        
                        @endif
                    </form>
                </div>
            </div>
            <!-- CONTAINER CLOSED -->
        </div>
    </div>    
</div>