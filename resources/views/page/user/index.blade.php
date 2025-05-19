@extends('vMaster')
@section('title','User')
@section('content')
<div class="page-header">
    <div class="add-item d-flex">
        <div class="page-title">
            <h4 class="fw-bold">Users List</h4>
            <h6>Manage your Users</h6>
        </div>
    </div>
    <ul class="table-top-head">
        <li>
            <a data-bs-toggle="tooltip" data-bs-placement="top" title="Refresh"><i class="ti ti-refresh"></i></a>
        </li>
    </ul>
    <div class="page-btn">
        <a href="#" class="btn btn-primary add_button" id="add_button"><i class="ti ti-circle-plus me-1"></i>Add Users</a>
    </div>
</div>

<!-- /product list -->
<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between flex-wrap row-gap-3">
        <div class="search-set">
            <div class="search-input">
                <span class="btn-searchset"><i class="ti ti-search fs-14 feather-search"></i></span>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table" id="table">
                <thead class="thead-light">
                    <tr>
                        <th>No </th>
                        <th>Name</th>
                        <th>Role</th>
                        <th>Email</th>
                        <th class="no-sort"></th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- /product list -->

<!-- add modal -->
<div class="modal fade" id="add-modal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div class="page-title">
                    <h4>Add Users</h4>
                </div>
                <button type="button" class="close bg-danger text-white fs-16" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('users.save') }}" id="form_add">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Name<span class="text-danger ms-1">*</span></label>
                        <input type="text" class="form-control" name="name" required placeholder="Name users">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Role<span class="text-danger ms-1">*</span></label>
                        <select name="role" class="form-control" required>
                            <option value="">-- Select Role --</option>
                            <option value="admin">Admin</option>
                            <option value="manager">Manager</option>
                            <option value="staff_gudang">Staff Gudang</option>
                            <option value="supplier">Supplier</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email<span class="text-danger ms-1">*</span></label>
                        <input type="email" class="form-control" name="email" required placeholder="mail@example.com">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password<span class="text-danger ms-1">*</span></label>
                        <input type="password" class="form-control" name="password" required placeholder="*********">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn me-2 btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add users</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- edit modal -->
<div class="modal fade" id="edit-modal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div class="page-title">
                    <h4>Edit User</h4>
                </div>
                <button type="button" class="close bg-danger text-white fs-16" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form_edit">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Name<span class="text-danger ms-1">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" required placeholder="Name users">
                    </div>
                   <div class="mb-3">
                        <label class="form-label">Role<span class="text-danger ms-1">*</span></label>
                        <select name="role" id="role" class="form-control" required>
                            <option value="">-- Select Role --</option>
                            <option value="admin">Admin</option>
                            <option value="manager">Manager</option>
                            <option value="staff_gudang">Staff Gudang</option>
                            <option value="supplier">Supplier</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email<span class="text-danger ms-1">*</span></label>
                        <input type="email" class="form-control" id="email" name="email" required placeholder="mail@example.com">
                    </div>
                     <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" class="form-control" name="password"  placeholder="*********">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn me-2 btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update users</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    var url            = "{{ route('users.data') }}";
    var url_show       = "{{ route('users.show',':id') }}";
    var url_update     = "{{ route('users.update',':id') }}";
    var url_delete     = "{{ route('users.delete',':id') }}";
</script>
<script>
    $(document).ready(function() {
        var table = $("#table").dataTable({
            ...defaultDatatableSettings,
            processing: true,
            serverSide: true,
            ajax: url,
            columns: [{
                    data: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'name',
                    name: 'name',
                },
                {
                    data: 'role',
                    name: 'role',
                },
                {
                    data: 'email',
                    name: 'email',
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    render: function (data, type, row, meta) {

                        return `
                            <div class="edit-delete-action">
                                <a class="me-2 edit-icon  p-2 show_data" href="javascript:void(0)" data-id="${row.id}">
                                    <i data-feather="eye" class="feather-eye"></i>
                                </a>
                                <a class="p-2 delete" data-id="${row.id}" href="javascript:void(0);">
                                    <i data-feather="trash-2" class="feather-trash-2"></i>
                                </a>
                            </div>
                        `;
                    }
                },
            ],
            columnDefs: [
                {
                    targets: 4,
                    className: 'action-table-data',
                }
            ]
        });

        $(document).on('click','.add_button',function() {
            $("#add-modal").modal('show');
        });

        $("#form_add").submit(function(e) {
            e.preventDefault();
            var url  = $(this).attr('action');
            var data = $(this).serialize();
            $.ajax({
                url : url,
                data: data,
                type: "POST",
                beforeSend: function() {
                    showLoadingAlert();
                },
                success: function(response) {
                    var message = response.message;
                    var title   = response.title;
                    showAlert(message,title,'success');
                    $("#add-modal").modal('hide');
                    $("#form_add").trigger('reset');
                },
                error: function(xhr) {
                    var message = xhr.responseJSON.message;
                    var title = xhr.responseJSON.title;
                    showAlert(message,title,'error');
                },
                complete: function() {
                    table.DataTable().ajax.reload();
                }
            })
        });

        $(document).on('click','.show_data',function() {
            var id      = $(this).data('id');
            var url     = url_show.replace(":id",id);
            var update  = url_update.replace(":id",id);

            $.ajax({
                url : url,
                type : "GET",
                beforeSend: function() {
                    showLoadingAlert();
                },success: function(response) {
                    var data = response.data;
                    swal.close();
                    $("#edit-modal").modal("show");
                    $("#name").val(data.name);
                    $("#role").val(data.role);
                    $("#email").val(data.email);
                    $("#form_edit").attr('action',update);
                },error : function(xhr) {
                    var message = xhr.responseJSON.message;
                    var title   = xhr.responseJSON.title;
                    showAlert(message,title,'error');
                }
            })
        });

        $("#form_edit").submit(function(e) {
            e.preventDefault();
            var url = $(this).attr('action');
            $.ajax({
                url : url,
                type : "PUT",
                data : $(this).serialize(),
                beforeSend: function() {
                    showLoadingAlert();
                },success: function(response) {
                    var message = response.message;
                    var title   = response.title;
                    showAlert(message,title,'success');
                },error : function(xhr) {
                    var message = xhr.responseJSON.message;
                    var title   = xhr.responseJSON.title;
                    showAlert(message,title,'error');
                },complete : function() {
                    table.DataTable().ajax.reload();
                    $("#edit-modal").modal('hide');
                    $("#form_edit").trigger('reset');
                }
            })
        });

        $(document).on('click','.delete', function() {
            var id = $(this).data('id');
            var url = url_delete.replace(":id",id);
            Swal.fire({
                title: "Delete User?",
                text: "You won't be able to revert this!",
                icon: "warning",
                width: '300px', // default-nya 500px
                customClass: {
                  popup: 'custom-swal-size'
                },

                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!",
                reverseButtons: true
              }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url : url,
                        type: "DELETE",
                        beforeSend: function() {
                            showLoadingAlert();
                        },success: function(response) {
                            var message = response.message;
                            var title  = response.title;
                            showAlert(message,title,'success');
                        },error : function(xhr) {
                            var messege  = xhr.responseJSON.message;
                            var title    = xhr.responseJSON.title;
                            showAlert(messege,title,'error');
                        },complete: function() {
                            table.DataTable().ajax.reload();
                        }
                    });
                }
              });
        });
    });
</script>
@endpush
