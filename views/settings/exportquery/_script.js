$(document).ready(function () {
    $(document).on('click', '.executeExportQuery', function () {
        var exportUrl = $(this).data('url');  // Base URL for export

        // Ensure the modal element exists before proceeding
        var modalElement = document.getElementById('genericModal');
        if (!modalElement) {
            console.error("Modal element not found");
            return;
        }

        var modal = new bootstrap.Modal(modalElement);

        // Update modal title and show loading message
        $('#genericModalLabel').text(lajax.t('Exporting Data'));
        $('#genericModal .modal-body').html('<p>' + lajax.t('Generating file, please wait...') + '</p>');

        // Show the modal
        modal.show();

        // Append the record ID to the export URL as a query parameter
        exportUrl += (exportUrl.indexOf('?') === -1 ? '?' : '');

        // AJAX request to generate the Excel file
        $.ajax({
            url: exportUrl,
            type: 'GET',
            xhrFields: {
                responseType: 'blob'  // Set the response type to 'blob'
            },
            success: function (data, status, xhr) {
                // Get the filename from the response headers
                var filename = ''; // Default filename
                var disposition = xhr.getResponseHeader('Content-Disposition');
                if (disposition && disposition.indexOf('attachment') !== -1) {
                    var matches = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/.exec(disposition);
                    if (matches != null && matches[1]) {
                        filename = matches[1].replace(/['"]/g, ''); // Clean up filename
                    }
                }
                
                // Create a blob URL and initiate a download
                var url = window.URL.createObjectURL(data);
                var a = document.createElement('a');
                a.href = url;
                a.download = filename || 'exported_data.xlsx'; // Provide a default filename if none is found
                document.body.appendChild(a);
                a.click();
                a.remove();

                // Wait for 2 seconds before closing the modal
                setTimeout(function() {
                    modal.hide(); // Close the modal after 2 seconds
                }, 2000); // 2000ms = 2 seconds
            },
            error: function (xhr, status, error) {
                // On unexpected errors
                $('#genericModal .modal-body').html(
                    '<p>' + lajax.t('An unexpected error occurred. Please try again.') + '</p>' +
                    '<div class="scrollable-error" style="max-height: 200px; overflow-y: auto; border: 1px solid #ccc; padding: 10px; background: #f8d7da; color: #721c24;">' +
                    '<strong>Error Details:</strong><br>' + 
                    (xhr.responseText || "No additional error details available.") + // Show detailed error response if available
                    '</div>'
                );
                $('#modal-footer').show(); // Show footer with Close button
            }
        });
    });
});
