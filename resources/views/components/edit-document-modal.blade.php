<div class="modal fade" id="addDocumentModal" tabindex="-1" role="dialog" wire:ignore.self>
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Document: {{ $selectedDocument->file_name ?? '' }}</h5>
                <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form class="form-horizontal" enctype="multipart/form-data" wire:submit.prevent="uploadDocument({{ $selectedDocument->id ?? null }})">
                <div class="modal-body">
                    <div class="card-body">
                        @include('components.message')
                        @if (session('upload_message'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('upload_message') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><span class="fe fe-x"></span></button>
                        </div>
                        @endif
                        <div class="row mb-4">
                            <div class="form-group col-xl-12 mb-3">
                                <label for="">Upload</label>
                                <input wire:model="file" type="file" class="form-control @error('file') is-invalid @enderror">
                                @error('file') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>                            
                        </div>
                        <div class="form-group">
                            <input wire:model.defer="filename" class="form-control" type="text" placeholder="Specify filename*" required>
                            @error('filename') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group col-xl-6 mb-3">
                            <label for="document_type">Document type</label>
                            <select wire:model.defer="document_type" class="form-control select2-show-search form-select" data-placeholder="Choose one" required>
                                <option value="">Choose</option>
                                <option value="Dealer Invoice" {{ $document_type == 'Dealer Invoice' ? 'selected' : '' }}>Dealer invoice</option>
                                <option value="Car docs ( Schein)" {{ $document_type == 'Car docs ( Schein)' ? 'selected' : '' }}>Car docs ( Schein)</option>
                                <option value="Car docs ( Brief)" {{ $document_type == 'Car docs ( Brief)' ? 'selected' : '' }}>Car docs ( Brief)</option>
                                <option value="Bill of Lading" {{ $document_type == 'Bill of Lading' ? 'selected' : '' }}>Bill of Lading</option>
                                <option value="Carries invoice" {{ $document_type == 'Carries invoice' ? 'selected' : '' }}>Carries invoice</option>
                                <option value="Agent invoice" {{ $document_type == 'Agent invoice' ? 'selected' : '' }}>Agent invoice</option>
                                <option value="EUR1" {{ $document_type == 'EUR1' ? 'selected' : '' }}>EUR1</option>
                                <option value="Consignee data" {{ $document_type == 'Consignee data' ? 'selected' : '' }}>Consignee data</option>
                                <option value="Reciept" {{ $document_type == 'Reciept' ? 'selected' : '' }}>Reciept</option>
                                <option value="other shipping docs" {{ $document_type == 'other shipping docs' ? 'selected' : '' }}>other shipping docs</option>
                                <option value="others" {{ $document_type == 'others' ? 'selected' : '' }}>others</option>
                                                            
                            </select>
                            @error('document_type') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>                        
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>