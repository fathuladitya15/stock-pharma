@extends('vMaster')
@section('title','Profile')
@section('content')
<div class="page-header">
    <div class="page-title">
        <h4>Profile</h4>
        <h6>User Profile</h6>
    </div>
</div>
<!-- /product list -->
<div class="card">
    <div class="card-header">
    <h4>Profile</h4>
    </div>
    <div class="card-body profile-body">
        <h5 class="mb-2"><i class="ti ti-user text-primary me-1"></i>Basic Information</h5>

        <form id="form_update">
            @csrf
            <div class="row">
                <input type="hidden" name="id" value="{{ auth()->user()->id }}">
                <div class="col-lg-6 col-sm-12">
                    <div class="mb-3">
                        <label class="form-label">Name<span class="text-danger ms-1">*</span></label>
                        <input type="text" class="form-control" value="{{ $user->name }}" name="name">
                    </div>
                </div>
                <div class="col-lg-6 col-sm-12">
                    <div class="mb-3">
                        <label>Email<span class="text-danger ms-1">*</span></label>
                        <input type="email" class="form-control" value="{{ $user->email }}" name="email">
                    </div>
                </div>
                <div class="col-lg-12 col-sm-12">
                    <div class="mb-3">
                        <label class="form-label">Password<span class="text-danger ms-1">*</span></label>
                        <div class="pass-group">
                            <input type="password" class="pass-input form-control" value="" name="password">
                            <i class="ti ti-eye-off toggle-password"></i>
                        </div>
                    </div>
                </div>
                <div class="col-12 d-flex justify-content-end">
                    <a href="javascript:void(0);" class="btn btn-secondary me-2 shadow-none">Cancel</a>
                    <button type="submit" class="btn btn-primary shadow-none">Save Changes</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- /product list -->
@endsection
@push('js')
<script>
    var url  = "{{ route('profile.update') }}";
</script>
<script>
    $(document).ready(function() {
        $("#form_update").submit(function(e) {
            e.preventDefault();
            $.ajax({
                url : url,
                type: "POST",
                data : $(this).serialize(),
                beforeSend: function() {
                    showLoadingAlert();
                },success: function(response) {
                    var message = response.message;
                    var title = response.title;
                    showAlert(message,title,'success');
                },error: function(xhr) {

                    console.log(xhr);
                    var message = xhr.responseJSON.message;
                    var title = xhr.responseJSON.title;
                    showAlert(message,title,'error');
                }
            })
        });
    });
</script>
@endpush
