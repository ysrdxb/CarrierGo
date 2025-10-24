<div class="modal fade" id="addCarrierModal" tabindex="-1" aria-labelledby="addCarrierModalLabel" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addClientModalLabel">Add New Carrier</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">                            
                @include('components.add-carrier-form')               
            </div>
        </div>
    </div>
</div>
