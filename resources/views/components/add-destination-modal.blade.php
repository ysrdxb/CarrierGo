<div class="modal fade" id="showDestinationModal" tabindex="-1" aria-labelledby="showDestinationModalLabel" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="showDestinationModalLabel">Add New Destination</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">                            
                @include('components.add-destination-form')               
            </div>
        </div>
    </div>
</div>
