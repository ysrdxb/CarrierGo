<div class="row">
    <div class="col-xl-12 col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Shipment status:</h4>
            </div>
            <div class="card-body">
 
                <div id="smartwizard-3" class="sw-main sw-theme-dots">
                    <ul class="nav nav-tabs step-anchor">
                        <li class="nav-item {{ $reference->status == 'New' ? 'active' : '' }}"><a href="#step-10" class="nav-link">New</a></li>
                        <li class="nav-item {{ $reference->status == 'Booked' ? 'active' : '' }}"><a href="#step-11" class="nav-link">Booked</a></li>
                        <li class="nav-item {{ $reference->status == 'Pickup scheduled' ? 'active' : '' }}"><a href="#step-12" class="nav-link">Pickup scheduled</a></li>
                        <li class="nav-item {{ $reference->status == 'Self Delivery' ? 'active' : '' }}"><a href="#step-10" class="nav-link" wire:click.prevent="changeStatus('markAsSelfDelivery')">Self Delivery</a></li>						
                        <li class="nav-item {{ $reference->status == 'Picked up' ? 'active' : '' }}"><a href="#step-10" class="nav-link" wire:click.prevent="changeStatus('markAsPickedUp')">Picked up</a></li>
                        <li class="nav-item {{ $reference->status == 'Port delivered' ? 'active' : '' }}"><a href="#step-11" class="nav-link" wire:click.prevent="changeStatus('markAsPortDelivered')">Port delivered</a></li>
                        <li class="nav-item {{ $reference->status == 'Ready to ship' ? 'active' : '' }}"><a href="#step-12" class="nav-link" wire:click.prevent="changeStatus('markAsReadyToShip')">Ready to Ship</a></li>
                        <li class="nav-item {{ $reference->status == 'Shipped' ? 'active' : '' }}"><a href="#step-10" class="nav-link" wire:click.prevent="changeStatus('markAsShipped')">Shipped</a></li>
                        <li class="nav-item {{ $reference->status == 'Arrived' ? 'active' : '' }}"><a href="#step-11" class="nav-link" wire:click.prevent="changeStatus('markAsArrived')">Arrived</a></li>
                        <li class="nav-item {{ $reference->status == 'Paid' ? 'active' : '' }}"><a href="#step-12" class="nav-link" wire:click.prevent="changeStatus('markAsPaid')">Paid</a></li>
                        <li class="nav-item {{ $reference->status == 'Released' ? 'active' : '' }}"><a href="#step-10" class="nav-link" wire:click.prevent="changeStatus('markAsReleased')">Released</a></li>

                    </ul>
                    <div class="sw-container tab-content" style="min-height: 30px;">
                        <div id="step-10" class="tab-pane step-content" style="display: block;">
                            
                        </div>
                        <div id="step-11" class="tab-pane step-content">
                            
                        </div>
                        <div id="step-12" class="tab-pane step-content">
                            
                        </div>
                    </div>
                </div>
                <form wire:submit.prevent='updateShipment'>
                    <div class="form-row">
                    
                        <div class="form-group col-md-4 mb-0">
                            <div class="form-group">
                                <label class="form-label">Vesselname</label>
                                <input type="text" class="form-control" wire:model='vessel_name' id="text" placeholder="MSC Marie">
                                @error('vessel_name') <span class="text-danger">{{ $message }} </span>  @enderror
                            </div>
                        </div>
                        <div class="form-group col-md-4 mb-0">
                            <div class="form-group">
                                <label class="form-label">ETS</label>
                                <div class="input-group">
                            <div class="input-group-text">
                                <i class="fa fa-calendar tx-16 lh-0 op-6"></i>
                            </div>
                            <input class="form-control fc-datepicker" wire:model='estimated_time_shipment' placeholder="MM/DD/YYYY" type="date">
                        </div>
                        @error('estimated_time_shipment') <span class="text-danger">{{ $message }} </span>  @enderror

                            </div>
                        </div>
                        <div class="form-group col-md-4 mb-0">
                            <div class="form-group">
                                <label class="form-label">ETA</label>
                                <div class="input-group">
                                <div class="input-group-text">
                                    <i class="fa fa-calendar tx-16 lh-0 op-6"></i>
                                </div>
                                <input class="form-control fc-datepicker" wire:model='estimated_time_arrival' placeholder="MM/DD/YYYY" type="date">
                                </div>
                                @error('estimated_time_arrival') <span class="text-danger">{{ $message }} </span>  @enderror

                            </div>
                        </div>
                    </div>
                    <div class="form-footer mt-2">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

</div>