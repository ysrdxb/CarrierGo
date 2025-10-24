<div class="modal fade" id="addMerchantModal" tabindex="-1" aria-labelledby="addMerchantModalLabel" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addMerchantLabel">Add New Merchant</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">                            
                @include('components.add-merchant-form')               
            </div>
        </div>
    </div>
</div>
