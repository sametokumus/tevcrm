(function($) {
    "use strict";

	$(document).ready(function() {

        $(":input").inputmask();

        $('#offer_customer').on('change', function (e){
            e.preventDefault();
            let customer_id = document.getElementById('offer_customer').value;
            console.log(customer_id)
            if (customer_id == '0'){
                $('#offer_employee option').remove();
            }else{
                getEmployeesAddSelectId(customer_id, 'offer_employee');
            }
        });

        $('#offer_category').on('change', function (e){
            e.preventDefault();
            let category_id = document.getElementById('offer_category').value;
            console.log(category_id)
            if (category_id == 'Kategori Seçiniz'){
                $('#offer_test option').remove();
            }else{
                getTestsByCategoryAddSelectId(category_id, 'offer_test');
            }
        });

        $('#offer_test_btn').on('click', function (e){
            let test_id = document.getElementById('offer_test').value;
            addTestToOffer(test_id);
        });

        $('#offer_info_form').submit(function (e){
            e.preventDefault();
            updateOffer();
        });
	});

    $(window).on('load', async function () {

		checkLogin();
		checkRole();
        initOffer();
        getCategoriesAddSelectId('offer_category');
        await setCategoryOptions();
        initOfferTests();
	});

})(window.jQuery);

function checkRole(){
	return true;
}
async function updateOffer(){
    let offer_id = getPathVariable('add-offer-tests');

    let formData = JSON.stringify({
        "offer_id": offer_id,
        "customer": document.getElementById('offer_customer').value,
        "employee": document.getElementById('offer_employee').value,
        "manager": document.getElementById('offer_manager').value,
        "lab_manager": document.getElementById('offer_lab_manager').value,
        "description": document.getElementById('offer_description').value
    });
    console.log(formData);

    let data = await servicePostUpdateOffer(formData);
    // if (data.status == "success"){
    //     window.location = "add-offer-tests/" + data.object.offer_id;
    // }
}
async function initOffer(){
    let offer_id = getPathVariable('add-offer-tests');

    let data = await serviceGetOfferInfoById(offer_id);
    let offer = data.offer;

    await getCustomersAddSelectId('offer_customer');
    document.getElementById('offer_customer').value = offer.customer_id;
    await getAdminsAddSelectId('offer_manager');
    document.getElementById('offer_manager').value = offer.manager_id;
    await getAdminsAddSelectId('offer_lab_manager');
    document.getElementById('offer_lab_manager').value = offer.lab_manager_id;
    await getEmployeesAddSelectId(offer.customer_id, 'offer_employee');
    document.getElementById('offer_employee').value = offer.employee_id;

    document.getElementById('offer_description').value = offer.description;

}
async function addTestToOffer(test_id){
    let offer_id = getPathVariable('add-offer-tests');
    let returned = await servicePostAddTestToOffer(offer_id, test_id);
    if (returned){
        initOfferTests();
    }
}
async function removeTestItem(element, price){
    let total_price = document.getElementById('offer_price').value;
    total_price = parseFloat(total_price) - parseFloat(price);
    document.getElementById('offer_price').value = total_price;
    $('#view-offer-price').html(changeCommasToDecimal(parseFloat(total_price).toFixed(2)) + ' ₺');

    $(element).closest('.test-item').remove();
}


let editor;
let table;
// Activate an inline edit on click of a table cell
// $('#tests-table').on( 'click', 'tbody td.row-edit', function (e) {
//     editor.inline( table.cells(this.parentNode, '*').nodes(), {
//         submitTrigger: -1,
//         submitHtml: '<i class="bi bi-floppy"/>'
//     } );
// } );
let categoryOptions = [];
async function setCategoryOptions (){
    let data = await serviceGetCategories();
    console.log(data)
    let val = { value: "", label: "" };
    categoryOptions.push(val);
    $.each(data.categories, function(i, category){
        let val = { value: category.id, label: category.name };
        categoryOptions.push(val);
        $.each(category.sub_categories, function(i, category2){
            let val = { value: category2.id, label: category.name + " >>> " + category2.name };
            categoryOptions.push(val);
        });
    });

};
async function initOfferTests(){

    if ($.fn.DataTable.isDataTable("#tests-table")) {
        $("#tests-table").dataTable().fnDestroy();
    }

    let offer_id = getPathVariable('add-offer-tests');
    let data = await serviceGetOfferTestsById(offer_id);
    let offer_details = data.offer_details;

    editor = new $.fn.dataTable.Editor( {
        data: offer_details,
        table: "#tests-table",
        idSrc: "id",
        fields: [ {
            label: "ID",
            name: "id",
            type: "readonly",
            attr: {
                class: 'form-control'
            }
        },{
            label: "Test ID",
            name: "test_id",
            type: "readonly",
            attr: {
                class: 'form-control'
            }
        },{
            label: "Ürün Adı",
            name: "product_name",
            attr: {
                class: 'form-control'
            }
        },{
            label: "Kategori",
            name: "category_name",
            type: "readonly",
            attr: {
                class: 'form-control'
            },
            // type: "select",
            // options: categoryOptions
        },{
            label: "Test Adı",
            name: "name",
            type: "readonly",
            attr: {
                class: 'form-control'
            }
        },{
            label: "Numune Sayısı",
            name: "sample_count",
            attr: {
                class: 'form-control'
            }
        },{
            label: "Numune Açıklama",
            name: "sample_description",
            attr: {
                class: 'form-control'
            }
        }, {
            label: "Test Süresi (Gün)",
            name: "total_day",
            type: "readonly",
            attr: {
                class: 'form-control'
            }
        }, {
            label: "Test Bedeli (₺)",
            name: "price",
            type: "readonly",
            attr: {
                class: 'form-control'
            }
        }
        ]
    } );

    let deletedRowData = null;
    let originalRowData = null;

    editor.on('preEdit', function(e, data, node, config) {
        // Store the original data of the row being edited
        originalRowData = $.extend(true, {}, data);
    });

    editor.on('preSubmit', async function(e, data, action) {
        if (action === 'edit') {
            let item = data.data;
            const obj = Object.values(item)[0];
            let formData = JSON.stringify({
                "product_name": obj.product_name,
                "sample_count": obj.sample_count,
                "sample_description": obj.sample_description
            });
            let returned = await servicePostUpdateTestToOffer(formData, obj.id);
            if (returned) {
                showAlert('Düzenleme işlemi başarılı.');
                editor.submit();
            } else {
                showAlert('Düzenleme işlemi başarısız.');
                console.log('hata');
                // Revert the changes to the original row data
                if (originalRowData) {
                    data.data[0] = originalRowData;
                    originalRowData = null;
                }
            }
        }
        if (action === 'remove') {
            let item = data.data;
            const obj = Object.values(item)[0];

            deletedRowData = obj;
            let returned = await serviceGetDeleteTestToOffer(obj.id);
            if (returned){
                showAlert('Silme işlemi başarılı.');
                editor.submit();
            }else{
                showAlert('Bir hata oluştu.');
                if (deletedRowData) {
                    table.row.add(deletedRowData).draw();
                    deletedRowData = null;
                }
            }
        }
    });

    table = $('#tests-table').DataTable( {
        dom: "Bfrtip",
        data: offer_details,
        columns: [
            { data: "id", title:"ID", editable: false },
            { data: "test_id", title:"Test ID", editable: false },
            { data: "product_name",title: "Ürün Adı" , defaultContent: ""},
            { data: "category_name", title: "Test Kategori", editable: false },
            { data: "name", title: "Test Adı" , defaultContent: "", editable: false},
            { data: "sample_count", title: "Numune Sayısı" , defaultContent: ""},
            { data: "sample_description", title: "Numune Açıklama" , defaultContent: ""},
            { data: "total_day", title: "Test Süresi (Gün)", editable: false},
            { data: "price", title: "Test Bedeli (₺)", editable: false},
        ],
        select: {
            style: 'os',
            selector: 'tr'
        },
        sortable: false,
        scrollX: true,
        paging: false,
        buttons: [
            { extend: "edit",   editor: editor, text: "Düzenle", className: "btn btn-warning" },
            { extend: "remove", editor: editor, text: "Sil", className: "btn btn-danger" }
        ],
        language: {
            url: "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
        },
    } );
}

