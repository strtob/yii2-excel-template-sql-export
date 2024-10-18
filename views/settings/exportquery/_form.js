$(document).ready(function() {
    /**
     * Extracts parameters enclosed in curly braces or preceded by a colon from a SQL string.
     *
     * @param {string} sql - The SQL string containing placeholders in the format {param} or :param.
     * @returns {Array} An array of unique extracted parameter names.
     */
    function extractParametersFromSql(sql) {
        // Use regular expressions to find all placeholders in the format {param} or :param
        const curlyBraceMatches = sql.match(/\{(\w+)\}/g) || [];
        const colonMatches = sql.match(/:(\w+)/g) || [];

        // Remove curly braces and colons from the matched parameters
        const cleanedCurlyMatches = curlyBraceMatches.map(match => match.replace(/[{}]/g, ''));
        const cleanedColonMatches = colonMatches.map(match => match.replace(/[:]/g, ''));

        // Merge and return unique parameter names
        return Array.from(new Set([...cleanedCurlyMatches, ...cleanedColonMatches]));
    }

    /**
     * Converts the extracted parameters into a JSON object where keys are parameter names and values
     * are in the format ":param", then sets it into the textarea with id 'exportsqlquery-parameter'.
     */
    function updateParameterTextarea() {
        // Get the SQL string from the textarea
        const sql = $('#exportsqlquery-query').val();

        // Extract parameters from the SQL string
        const parameters = extractParametersFromSql(sql);

        // Create a JSON object where the keys are parameter names and values are in the ":param" format
        const parameterObj = {};
        parameters.forEach(param => {
            parameterObj[param] = {
                parameter: ':' + param,  // Set the value as ":param"
                example: '1'  // Example value
            };  
        });

        // Convert the JSON object to a string and set it in the target textarea
        $('#exportsqlquery-parameter').val(JSON.stringify(parameterObj, null, 4));
    }

    // Monitor the input event on the SQL query textarea and update parameters as user types
    $('#exportsqlquery-query').on('input', updateParameterTextarea);
});
