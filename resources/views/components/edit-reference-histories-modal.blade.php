<div class="modal fade" id="editHistoryModal" tabindex="-1" role="dialog" aria-labelledby="editHistoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editHistoryModalLabel">Edit History</h5>
                <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                @foreach($getHistories as $history)
                    <div>
                        <p>Edited at: {{ $history->created_at }}<br>by {{ $history->editor->firstname . ' ' .$history->editor->lastname }}</p>
                        <p>Action: {{ $history->details }}</p>
                    </div>
                    <hr class="bg-dark">
                @endforeach
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Close</button>
            </div>
        </div>
    </div>
</div>
