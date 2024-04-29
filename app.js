// dev app

// fetch form structure
function fetchFormStructure(formId) {
    $.ajax({
        url: `/api.php?form_id=${formId}`,
        method: 'GET',
        success: function(data) {
            renderForm(JSON.parse(data));
        },
        error: function(error) {
            console.error('Error fetching form:', error);
        }
    });
}

// render form
function renderForm(data) {
    const formContainer = $('#form-container');
    formContainer.empty(); 

    data.fields.forEach(field => {
        let formField;
        switch (field.type) {
            case 'input':
                formField = `<input type="text" name="${field.name}" placeholder="${field.label}" required>`;
                break;
            case 'textarea':
                formField = `<textarea name="${field.name}" placeholder="${field.label}" required></textarea>`;
                break;
            case 'select':
                formField = `<select name="${field.name}">`;
                field.options.forEach(option => {
                    formField += `<option value="${option.value}">${option.label}</option>`;
                });
                formField += `</select>`;
                break;
            case 'radio':
                formField = '';
                field.options.forEach(option => {
                    formField += `<label><input type="radio" name="${field.name}" value="${option.value}" required>${option.label}</label>`;
                });
                break;
            case 'checkbox':
                formField = '';
                field.options.forEach(option => {
                    formField += `<label><input type="checkbox" name="${field.name}" value="${option.value}" required>${option.label}</label>`;
                });
                break;
        }
        formContainer.append(formField);
    });
}

// handle submission
$('#submit-btn').click(function() {
    const formContainer = $('#form-container');
    const formData = {};

    formContainer.find('input, textarea, select').each(function() {
        formData[$(this).attr('name')] = $(this).val();
    });

    $.ajax({
        url: `/api.php?form_id=1`, // form_id
        method: 'POST',
        contentType: 'application/json',
        data: JSON.stringify(formData),
        success: function(response) {
            console.log(response);
        },
        error: function(error) {
            console.error('Error submitting form:', error);
        }
    });
});

// usage
const formId = 1; 
fetchFormStructure(formId);
