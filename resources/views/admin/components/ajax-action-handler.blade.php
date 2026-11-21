<script>
$(document).ready(function() {
    // Auto-init client-side DataTables on tables with .erp-datatable
    if ($.fn.DataTable) {
        $('.erp-datatable').each(function() {
            if (!$.fn.DataTable.isDataTable(this)) {
                $(this).DataTable({
                    pageLength: 25,
                    lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
                    order: []
                });
            }
        });
    }

    // Universal AJAX Delete Handler with SweetAlert2 Confirmation
    $(document).on('submit', '.ajax-delete-form', function(e) {
        e.preventDefault();
        var form = $(this);
        var title = form.data('title') || 'Are you sure?';
        var text = form.data('text') || 'This record will be deleted!';

        Swal.fire({
            title: title,
            text: text,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: form.attr('action'),
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') || '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    data: form.serialize(),
                    success: function(response) {
                        Swal.fire('Deleted!', response.message || 'Record deleted successfully.', 'success').then(function() {
                            if (window.erpActiveDataTable) {
                                window.erpActiveDataTable.ajax.reload(null, false);
                            } else {
                                location.reload();
                            }
                        });
                    },
                    error: function(xhr) {
                        let msg = xhr.responseJSON?.message || 'Unable to delete record.';
                        Swal.fire('Error', msg, 'error');
                    }
                });
            }
        });
    });

    // Universal AJAX Action Handler (Confirm Order, Issue Invoice, Post Entry, Complete Payment, Toggle Flag)
    $(document).on('submit', '.ajax-action-form', function(e) {
        e.preventDefault();
        var form = $(this);
        var title = form.data('title') || 'Confirm Action';
        var text = form.data('text') || 'Do you want to proceed with this action?';
        var confirmText = form.data('confirm-text') || 'Yes, proceed!';

        Swal.fire({
            title: title,
            text: text,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#0d6efd',
            cancelButtonColor: '#6c757d',
            confirmButtonText: confirmText
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: form.attr('action'),
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') || '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    data: form.serialize(),
                    success: function(response) {
                        Swal.fire('Done!', response.message || 'Action completed successfully.', 'success').then(function() {
                            if (window.erpActiveDataTable) {
                                window.erpActiveDataTable.ajax.reload(null, false);
                            } else {
                                location.reload();
                            }
                        });
                    },
                    error: function(xhr) {
                        let msg = xhr.responseJSON?.message || 'Action failed.';
                        Swal.fire('Error', msg, 'error');
                    }
                });
            }
        });
    });
});
</script>
