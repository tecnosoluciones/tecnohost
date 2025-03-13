jQuery(document).ready(function($){
    var custom_fields;

    custom_fields = '<tr class="form-field form-required">';
    custom_fields += '<th scope="row">';
    custom_fields += '<label for="user_nit">Identificación Fiscal (NIT/CC/CE/RIF/EIN)</label>';
    custom_fields += '</th>';
    custom_fields += '<td>';
    custom_fields += '<input type="text" id="user_nit" name="billing_eu_vat_number">';
    custom_fields += '</td>';
    custom_fields += '</tr>';

    $('#createuser .form-table tbody').append(custom_fields);
});