(function($) {
    "use strict";

	$(document).ready(function() {

		$('#update_offer_request_form').submit(function (e){
			e.preventDefault();
            updateOfferRequest();
		});
        $('#import_data_form').submit(function (e){
            e.preventDefault();

            var formData = new FormData(this);

            // Use SheetJS to read the uploaded Excel file
            let reader = new FileReader();
            reader.onload = function (e) {
                let data = e.target.result;
                let workbook = XLSX.read(data, { type: 'binary' });
                let sheet_name_list = workbook.SheetNames;
                let sheet_name = sheet_name_list[0]; // assume the first sheet is the one we want
                let worksheet = workbook.Sheets[sheet_name];

                // Convert the worksheet data to JSON
                let json_data = XLSX.utils.sheet_to_json(worksheet, { header: 1 });
                console.log(json_data)
                // Remove the header row from the data (optional)
                json_data.splice(0, 1);

                json_data.forEach(function(obj) {
                    console.log(obj)
                    table.row.add({
                        "id": "",
                        "product.stock_code": obj[0],
                        "customer_stock_code": obj[1],
                        "ref_code": obj[2],
                        "product_name": obj[3],
                        "quantity": obj[4],
                        "measurement_name_tr": obj[5],
                        "product.brand_name": "",
                        "product.category_id": "",
                        "note": obj[6]
                    }).draw();

                });

            };
            reader.readAsBinaryString(formData.get('import_file'));
        });


	});

	$(window).load(async function() {

		checkLogin();
		checkRole();
        await setCategoryOptions();
        await setMeasurementOptions();
        await initOfferRequestProducts();
        await initPage();
        await initOfferRequest();

	});

})(window.jQuery);

function checkRole(){
	return true;
}

async function initPage(){
    await getOwnersAddSelectId('update_offer_request_owner');
    await getAdminsAddSelectId('update_offer_request_authorized_personnel');
    await getAdminsAddSelectId('update_offer_request_purchasing_staff');
    await getCustomersAndPotentialsAddSelectId('update_offer_request_company');
    await getMeasurementsAddSelectId('update_offer_request_product_measurement');
    await getParentCategoriesAddSelectId('update_offer_request_product_category_1');
    let data1 = await serviceGetBrands();
    let brands = [];
    $.each(data1.brands, function (i, brand) {
        let item = {
            "id": brand.id,
            "name": brand.name
        }
        brands.push(item);
    });
    $('#update_offer_request_brand').typeahead({
        source: brands,
        autoSelect: true
    });

}

async function initEmployeeSelect(){
    let company_id = document.getElementById('update_offer_request_company').value;
    await getEmployeesAddSelectId(company_id, 'update_offer_request_company_employee');
}


let sale_global_id;
async function initOfferRequest(){
    let request_id = getPathVariable('offer-request-products');
    let data = await serviceGetOfferRequestById(request_id);
    let offer_request = data.offer_request;
    console.log(offer_request)
    sale_global_id = offer_request.owner.short_code + "-" + offer_request.global_id;
    $('#title_sale_global_id').text(sale_global_id);

    document.getElementById('update_offer_request_id').value = request_id;
    document.getElementById('update_offer_request_owner').value = offer_request.owner_id;
    document.getElementById('update_offer_request_authorized_personnel').value = checkNull(offer_request.authorized_personnel_id);
    document.getElementById('update_offer_request_purchasing_staff').value = checkNull(offer_request.purchasing_staff_id);
    document.getElementById('update_offer_request_company').value = offer_request.company_id;
    document.getElementById('update_offer_request_company_employee').value = offer_request.company_employee_id;
    document.getElementById('update_offer_request_company_request_code').value = offer_request.company_request_code;
    document.getElementById('update_offer_request_product_count').value = offer_request.products.length;

    if (offer_request.company_id != null && offer_request.company_id != 0) {
        await initEmployeeSelect();
        document.getElementById('update_offer_request_company_employee').value = offer_request.company_employee_id;
    }


}

async function updateOfferRequest(){
    let user_id = localStorage.getItem('userId');
    let request_id = getPathVariable('offer-request-products');

    let owner = document.getElementById('update_offer_request_owner').value;
    if (owner == 0){owner = null;}
    let personnel = document.getElementById('update_offer_request_authorized_personnel').value;
    if (personnel == 0){personnel = null;}
    let purchasing = document.getElementById('update_offer_request_purchasing_staff').value;
    if (purchasing == 0){purchasing = null;}
    let company = document.getElementById('update_offer_request_company').value;
    if (company == 0){company = null;}
    let employee = document.getElementById('update_offer_request_company_employee').value;
    if (employee == 0){employee = null;}
    let request_code = document.getElementById('update_offer_request_company_request_code').value;

    let formData = JSON.stringify({
        "user_id": parseInt(user_id),
        "authorized_personnel_id": personnel,
        "purchasing_staff_id": purchasing,
        "company_id": company,
        "company_employee_id": employee,
        "company_request_code": request_code,
        "owner_id": owner
    });

    console.log(request_id)
    let returned = await servicePostUpdateOfferRequest(request_id, formData);
    if (returned){
        // window.location.reload();
    }else{
        alert("Hata Oluştu");
    }
}





let editor;
let table;
// Activate an inline edit on click of a table cell
$('#offer-request-products').on( 'click', 'tbody td.row-edit', function (e) {
    editor.inline( table.cells(this.parentNode, '*').nodes(), {
        submitTrigger: -1,
        submitHtml: '<i class="fas fa-lg fa-fw me-2 fa-save"/>'
    } );
} );
$('#offer-request-products').on('draw.dt', function() {
    table.rows().every(function(rowIdx, tableLoop, rowLoop) {
        var data = this.data();
        data.auto_increment = rowIdx + 1;
        $(this.node()).find('td:eq(0)').html(data.auto_increment);
    });
});

let measurementOptions = [];
async function setMeasurementOptions() {
    let data = await serviceGetMeasurements();
    measurementOptions = data.measurements.map(function(item) {
        return { value: item.name_tr, label: item.name_tr };
    });
};
let categoryOptions = [];
async function setCategoryOptions (){
    let data = await serviceGetCategories();
    console.log(data)

    $.each(data.categories, function(i, category){
        $.each(category.sub_categories, function(i, category2){
            $.each(category2.sub_categories, function(i, category3){
                let val = { value: category3.id, label: category.name + "--->>>" + category2.name + "--->>>" + category3.name };
                categoryOptions.push(val);
            });
        });
    });

};

async function initOfferRequestProducts(){

    let request_id = getPathVariable('offer-request-products');
    let data = await serviceGetOfferRequestProductsById(request_id);
    let products = data.offer_request_products;

        editor = new $.fn.dataTable.Editor( {
            data: products,
            table: "#offer-request-products",
            idSrc: "id",
            fields: [ {
                label: "ID",
                name: "id",
                type: "readonly",
                attr: {
                    class: 'form-control'
                }
            },{
                label: "Firma Stok Kodu",
                name: "product.stock_code",
                attr: {
                    class: 'form-control'
                }
            },{
                label: "Müşteri Stok Kodu",
                name: "customer_stock_code",
                attr: {
                    class: 'form-control'
                }
            },{
                label: "Ref. Code",
                name: "ref_code",
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
                label: "Adet",
                name: "quantity",
                attr: {
                    class: 'form-control'
                }
            }, {
                label: "Birim",
                name: "measurement_name_tr",
                attr: {
                    class: 'form-control'
                },
                type: "select",
                options: measurementOptions
            }, {
                label: "Marka",
                name: "product.brand_name",
                attr: {
                    class: 'form-control'
                }
            }, {
                label: "Ürün Grubu",
                name: "product.category_id",
                attr: {
                    class: 'form-control'
                },
                type: "select",
                options: categoryOptions
            }, {
                label: "Satın Alma Notu",
                name: "note",
                attr: {
                    class: 'form-control'
                }
            }
            ]
        } );

        editor.on('preSubmit', async function(e, data, action) {
            if (action !== 'remove') {
                // let id = editor.field('id').val();
                // let quantity = editor.field('quantity').val();
                // let pcs_price = editor.field('pcs_price').val();
                // let total_price = editor.field('total_price').val();
                // let discount_rate = editor.field('discount_rate').val();
                // let discounted_price = editor.field('discounted_price').val();
                // let currency = editor.field('currency').val();
                // let lead_time = editor.field('lead_time').val();
                //
                // let formData = JSON.stringify({
                //     "id": id,
                //     "date_code": "",
                //     "package_type": "",
                //     "quantity": quantity,
                //     "pcs_price": changePriceToDecimal(pcs_price),
                //     "total_price": changePriceToDecimal(total_price),
                //     "discount_rate": changePriceToDecimal(discount_rate),
                //     "discounted_price": changePriceToDecimal(discounted_price),
                //     "vat_rate": "",
                //     "currency": currency,
                //     "lead_time": lead_time
                // });
                //
                // let offer_id = document.getElementById('offer-detail-modal-offer-id').value;
                // let returned = await servicePostUpdateOfferProduct(formData, offer_id, id);
                // if (returned){
                //     await initOfferDetailModal(offer_id);
                //     await initOffers();
                // }else{
                //     alert("Hata Oluştu");
                // }

                // Submit the edited row data
                editor.submit();
            }
        });

        table = $('#offer-request-products').DataTable( {
            dom: "Bfrtip",
            data: products,
            columns: [
                { data: null, title:"N#", editable: false },
                { data: "id", title:"ID", editable: false },
                { data: "product.stock_code",title: "Firma Stok Kodu", className:  "row-edit" },
                { data: "customer_stock_code", title: "Müşteri Stok Kodu", className:  "row-edit" },
                { data: "ref_code", title: "Ref. Code", className:  "row-edit" },
                { data: "product_name", title: "Ürün Adı", className:  "row-edit" },
                { data: "quantity", title: "Miktar", className:  "row-edit" },
                { data: "measurement_name_tr", title: "Birim", className:  "row-edit" },
                { data: "product.brand_name", title: "Marka", editable: false },
                { data: "product.category_id", title: "Ürün Grubu", editable: false },
                { data: "note", title: "Satın Alma Notu", className:  "row-edit" },
                {
                    data: null,
                    title: "",
                    defaultContent: '<i class="fas fa-lg fa-fw me-2 fa-edit"/>',
                    className: 'row-edit dt-center',
                    orderable: false
                },
            ],
            createdRow: function(row, data, dataIndex) {
                $(row).find('td:eq(0)').html(dataIndex+1);
            },
            select: {
                style: 'os',
                selector: 'td:first-child'
            },
            sortable: false,
            scrollX: true,
            paging: false,
            buttons: [
                {
                    extend: "create",
                    editor: editor,
                    text: "Yeni Ürün Ekle",
                    className: "btn btn-yellow"
                },
                { extend: "edit",   editor: editor, text: "Düzenle", className: "btn btn-warning" },
                { extend: "remove", editor: editor, text: "Sil", className: "btn btn-danger" },
                {
                    text: 'Ürünleri Kaydet',
                    className: "btn btn-theme",
                    action: function ( e, dt, node, config ) {
                        if (table.rows().count() === 0){
                            alert("Öncelikle ürün girmeniz gerekmektedir.")
                        }else {
                            addRequestProducts();
                        }
                    }
                },
                {
                    extend: 'excelHtml5',
                    text: 'Excel olarak kaydet',
                    title: function() {
                        return 'REQUEST-' + sale_global_id;
                    },
                    exportOptions: {
                        columns: [ 1, 2, 3, 4, 5, 6, 7, 8, 9, 10 ]
                    }
                },
                {
                    text: 'Ürünleri Excel\'den aktar',
                    action: function(){
                        var fileSelector = document.getElementById('import_file');
                        fileSelector.click();
                        return false;
                    }
                }
            ],
            language: {
                url: "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
            },
        } );
}

async function addRequestProducts(){
    let request_id = getPathVariable('offer-request-products');
    let products = [];
    let table = document.getElementById("offer-request-products-body");
    for (let i = 0, row; row = table.rows[i]; i++) {
        console.log(row)
        let item = {
            "sequence": row.cells[0].innerText,
            "id": row.cells[1].innerText,
            "owner_stock_code": row.cells[2].innerText,
            "customer_stock_code": row.cells[3].innerText,
            "ref_code": row.cells[4].innerText,
            "product_name": row.cells[5].innerText,
            "quantity": parseInt(row.cells[6].innerText),
            "measurement": row.cells[7].innerText,
            "brand": row.cells[8].innerText,
            "category": row.cells[9].innerText,
            "note": row.cells[10].innerText
        }
        products.push(item);
    }

    let formData = JSON.stringify({
        "products": products
    });

    console.log(formData);

    let returned = await servicePostOfferRequestProducts(formData, request_id);
    if (returned){
        $("#offer-request-products").dataTable().fnDestroy();
        $('#offer-request-products tbody > tr').remove();
        await initOfferRequestProducts();
    }else{
        alert("Hata Oluştu");
    }
}
