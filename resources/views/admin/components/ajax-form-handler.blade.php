<script>
$(document).ready(function() {
    $(document).on('submit', '.ajax-form', function(e) {
        e.preventDefault();
        var form = $(this);
        var submitBtn = form.find('button[type="submit"]');
        var originalHtml = submitBtn.html();
        submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> {{ __('Saving...') }}');

        var isMultipart = form.attr('enctype') === 'multipart/form-data';
        var ajaxOptions = {
            url: form.attr('action'),
            type: (form.attr('method') || 'POST').toUpperCase(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') || '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            success: function(response) {
                submitBtn.prop('disabled', false).html(originalHtml);
                var modal = form.closest('.modal');
                if (modal.length) {
                    var bsModal = bootstrap.Modal.getInstance(modal[0]);
                    if (bsModal) bsModal.hide();
                }

                Swal.fire({
                    icon: 'success',
                    title: @json(__('Success')),
                    text: response.message || @json(__('Operation successful!')),
                    confirmButtonColor: '#3085d6',
                }).then(function() {
                    var redirectUrl = response.redirect || form.data('redirect');
                    if (redirectUrl) {
                        window.location.href = redirectUrl;
                    } else if (window.erpActiveDataTable) {
                        window.erpActiveDataTable.ajax.reload(null, false);
                    } else {
                        location.reload();
                    }
                });
                form[0].reset();
            },
            error: function(xhr) {
                submitBtn.prop('disabled', false).html(originalHtml);
                let msg = @json(__('An error occurred.'));
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    msg = xhr.responseJSON.message;
                }
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    msg += '\n' + Object.values(xhr.responseJSON.errors).flat().join('\n');
                }
                Swal.fire({
                    icon: 'error',
                    title: @json(__('Error')),
                    text: msg,
                    confirmButtonColor: '#d33',
                });
            }
        };

        if (isMultipart) {
            ajaxOptions.data = new FormData(form[0]);
            ajaxOptions.processData = false;
            ajaxOptions.contentType = false;
        } else {
            ajaxOptions.data = form.serialize();
        }

        $.ajax(ajaxOptions);
    });
});
</script>
