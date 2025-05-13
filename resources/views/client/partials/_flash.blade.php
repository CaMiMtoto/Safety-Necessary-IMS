@if(session()->has('success'))
    <div class="alert alert-success mt-5 alert-dismissible fade show" role="alert">
        <strong>Success!</strong> {{ session()->get('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@elseif(session()->has('error'))
    <div class="alert alert-danger mt-5 alert-dismissible fade show" role="alert">
        <strong>Error!</strong> {{ session()->get('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@elseif(session()->has('warning'))
    <div class="alert alert-warning mt-5 alert-dismissible fade show" role="alert">
        <strong>Warning!</strong> {{ session()->get('warning') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@elseif(session()->has('info'))
    <div class="alert alert-info mt-5 alert-dismissible fade show" role="alert">
        <strong>Info!</strong> {{ session()->get('info') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>

@elseif(session()->has('status'))
    <div class="alert alert-info mt-5 alert-dismissible fade show" role="alert">
        <strong>Info!</strong> {{ session()->get('status') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
