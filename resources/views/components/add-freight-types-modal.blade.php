    <div class="modal fade" id="showFreightModal" tabindex="-1" aria-labelledby="showFreightModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="showFreightModalLabel">Add New Freight Type</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">                            
                    @include('components.add-freight-types-form')               
                </div>
            </div>
        </div>
    </div>
