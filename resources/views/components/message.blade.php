<div>
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><span class="fe fe-x"></span></button>
        </div>
    @endif

    @if (session('status'))
        <div class="alert alert-primary alert-dismissible fade show" role="alert">
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><span class="fe fe-x"></span></button>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><span class="fe fe-x"></span></button>
        </div>
    @endif

    @if (session()->has('errors'))
        <div class="alert alert-danger">
            <ul>
                @foreach(session('errors') as $errorMessages)
                    @foreach($errorMessages as $errorMessage)
                        <li>
                            {{ $errorMessage }}                            
                        </li>
                    @endforeach
                @endforeach
            </ul>
        </div>
    @endif

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif    

    
</div>
