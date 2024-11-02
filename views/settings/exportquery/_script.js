$(document).ready(function () {
    $(document).on('click', '.executeExportQuery', function () {

        var name = $(this).data('name');
        var exportUrl = $(this).data('url');

        // Ensure the modal element exists before proceeding
        var modalElement = document.getElementById('genericModal');

        if (!modalElement) {
            console.error("Modal element not found");
            return;
        }

        var modal = new bootstrap.Modal(modalElement);

        // Update modal title and show loading message
        $('#genericModalLabel').text(lajax.t('Exporting Data'));
        $('#genericModal .modal-body')
            .html('<p><i class="fa-solid fa-download fa-fade me-2"></i>'
                + lajax.t('Generating file ' + name + ', please wait...') + '</p>');

        // Show the modal
        modal.show();

        // Append the name as a query parameter to the export URL
        exportUrl += (exportUrl.indexOf('?') === -1 ? '?' : '&') + 'name=' + encodeURIComponent(name);


        $.ajax({
            url: exportUrl,
            type: 'GET',
            xhrFields: {
                responseType: 'blob' // Expecting a Blob response for file download
            },
            success: function (data, status, xhr) {
                var filename = ''; // Default filename
                var disposition = xhr.getResponseHeader('Content-Disposition');
        
                // Check for the content-disposition header to extract the filename
                if (disposition && disposition.indexOf('attachment') !== -1) {
                    var matches = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/.exec(disposition);
                    if (matches != null && matches[1]) {
                        filename = matches[1].replace(/['"]/g, ''); // Clean up filename
                    }
                }
        
                // Create a blob URL and initiate a download
                var url = window.URL.createObjectURL(data); // Create a Blob URL from the response
                var a = document.createElement('a');
                a.href = url;
                a.download = filename || 'exported_data.xlsx'; // Provide a default filename if none is found
                document.body.appendChild(a);
                a.click();
                a.remove();
        
                // Optionally revoke the object URL after download
                setTimeout(function () {
                    window.URL.revokeObjectURL(url); // Free memory
                }, 100); // Revoke the URL after 100ms
        
                // Show success message or handle closing the modal
                setTimeout(function () {
                    modal.hide(); // Close the modal after 2 seconds
                }, 2000);
            },
            error: function (xhr, status, error) {
                // Handle the error as before
                let errorMessage = 'An unexpected error occurred. Please try again.';
                
                if (xhr.responseText) {
                    try {
                        const jsonResponse = JSON.parse(xhr.responseText);
                        errorMessage = jsonResponse.message || errorMessage;
                    } catch (e) {
                        errorMessage = xhr.responseText; // If parsing fails, use raw response
                    }
                }
        
                // Update the modal with the error message
                $('#genericModal .modal-body').html(
                    '<p><i class="fas fa-exclamation-triangle me-2"></i>' + errorMessage + '</p>' +
                    '<div class="scrollable-error" style="max-height: 200px; overflow-y: auto; border: 1px solid #ccc; padding: 10px; background: #f8d7da; color: #721c24;">' +
                    '<strong>Error Details:</strong><br>' +
                    errorMessage +
                    '</div>'
                );
        
                // Ensure modal footer with a "Close" button is shown
                $('#genericModal .modal-footer').html('<button type="button" class="btn btn-secondary" data-bs-dismiss="modal-small">Close</button>').show();
            }
        });
        
        

    });
});
