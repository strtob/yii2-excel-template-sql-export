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
        

        // AJAX request to generate the Excel file
        $.ajax({
            url: exportUrl,
            type: 'GET',
            xhrFields: {
                responseType: 'blob' 
            },
            success: function (data, status, xhr) {
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
                setTimeout(function () {
                    modal.hide(); // Close the modal after 2 seconds
                }, 2000); // 2000ms = 2 seconds
            },
            error: function (xhr, status, error) {
                // Check if the response is a Blob object
                if (xhr.response instanceof Blob) {
                    var reader = new FileReader();
                    reader.onload = function (event) {
                        var result = event.target.result;
                        try {
                            // Try to parse the response as JSON
                            var jsonResponse = JSON.parse(result);
                            var errorMessage = jsonResponse.message || lajax.t('No additional error details available.');
                        } catch (e) {
                            var errorMessage = result; // If parsing fails, treat the response as plain text
                        }

                        // Update the modal with the error message
                        $('#genericModal .modal-body').html(
                            '<p><i class="fas fa-exclamation-triangle me-2"></i>' + lajax.t('An unexpected error occurred. Please try again.') + '</p>' +
                            '<div class="scrollable-error" style="max-height: 200px; overflow-y: auto; border: 1px solid #ccc; padding: 10px; background: #f8d7da; color: #721c24;">' +
                            '<strong>' + lajax.t('Error Details:') + '</strong><br>' +
                            errorMessage +  // Show the parsed or raw error message
                            '</div>'
                        );
                    };
                    reader.readAsText(xhr.response); // Read the Blob content as text
                } else {
                    // Fallback for non-Blob errors
                    $('#genericModal .modal-body').html(
                        '<p><i class="fas fa-exclamation-triangle me-2"></i>' + lajax.t('An unexpected error occurred. Please try again.') + '</p>' +
                        '<div class="scrollable-error" style="max-height: 200px; overflow-y: auto; border: 1px solid #ccc; padding: 10px; background: #f8d7da; color: #721c24;">' +
                        '<strong>' + lajax.t('Error Details:') + '</strong><br>' +
                        (xhr.responseText || lajax.t('No additional error details available.')) +
                        '</div>'
                    );
                }

                // Ensure modal footer with a "Close" button is shown
                $('#genericModal .modal-footer').html('<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">' + lajax.t('Close') + '</button>').show();
            }
        });

    });
});
