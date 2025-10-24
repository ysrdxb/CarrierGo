<div class="modal fade" id="addConsigneeModal" tabindex="-1" aria-labelledby="addConsigneeModalLabel" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addConsigneeLabel">Add New Consignee</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">                            
                @include('components.add-consignee-form')               
            </div>
        </div>
    </div>
</div>