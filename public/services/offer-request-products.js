(function($) {
    "use strict";

	$(document).ready(function() {

		$('#update_offer_request_form').submit(function (e){
			e.preventDefault();
            updateOfferRequest();
		});

        $('#update_offer_request_product_button').click(function (e){
            e.preventDefault();
            let refcode = document.getElementById('update_offer_request_product_refcode').value;
            let product_name = document.getElementById('update_offer_request_product_name').value;
            let quantity = document.getElementById('update_offer_request_product_quantity').value;
            let measurement = document.getElementById('update_offer_request_product_measurement').value;
            let brand = document.getElementById('update_offer_request_brand').value;
            let category1 = document.getElementById('update_offer_request_product_category_1').value;
            let category2 = document.getElementById('update_offer_request_product_category_2').value;
            let category3 = document.getElementById('update_offer_request_product_category_3').value;
            let cust_stock = document.getElementById('update_offer_request_customer_stock_code').value;
            let own_stock = document.getElementById('update_offer_request_owner_stock_code').value;
            let note = document.getElementById('update_offer_request_note').value;
            if (quantity == "0"){
                alert('Formu Doldurunuz');
                return false;
            }
            console.log(refcode, product_name, quantity, measurement, brand, category1, category2, category3, cust_stock, own_stock, note);
            addProductToTable(refcode, product_name, quantity, measurement, brand, category1, category2, category3, cust_stock, own_stock, note);
        });
	});

	$(window).load(async function() {

		checkLogin();
		checkRole();
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

async function initSecondCategory(){
    let parent_id = document.getElementById('update_offer_request_product_category_1').value;
    $('#update_offer_request_product_category_2 option').remove();
    $('#update_offer_request_product_category_3 option').remove();

    let data = await serviceGetCategoriesByParentId(parent_id);
    $('#update_offer_request_product_category_2').append('<option value="0">Kategori Seçiniz</option>');
    $.each(data.categories, function(i, category){
        let optionRow = '<option value="'+category.id+'">'+category.name+'</option>';
        $('#update_offer_request_product_category_2').append(optionRow);
    });
}

async function initThirdCategory(){
    let parent_id = document.getElementById('update_offer_request_product_category_2').value;
    $('#update_offer_request_product_category_3 option').remove();

    let data = await serviceGetCategoriesByParentId(parent_id);
    $('#update_offer_request_product_category_3').append('<option value="0">Kategori Seçiniz</option>');
    $.each(data.categories, function(i, category){
        let optionRow = '<option value="'+category.id+'">'+category.name+'</option>';
        $('#update_offer_request_product_category_3').append(optionRow);
    });
}

async function addProductToTable(refcode, product_name, quantity, measurement, brand, category1, category2, category3, cust_stock, own_stock, note){

    let formData = JSON.stringify({
        "owner_stock_code": own_stock,
        "customer_stock_code": cust_stock,
        "ref_code": refcode,
        "product_name": product_name,
        "quantity": quantity,
        "measurement": measurement,
        "brand": brand,
        "category": category3,
        "note": note
    });
    console.log(formData)
    let request_id = getPathVariable('offer-request');

    let returned = await servicePostAddProductToOfferRequest(request_id, formData);
    if (returned){

        let count = document.getElementById('update_offer_request_product_count').value;
        count = parseInt(count) + 1;
        document.getElementById('update_offer_request_product_count').value = count;
        let item = '<tr id="productRow'+ count +'">\n' +
            '           <td>'+ count +'</td>\n' +
            '           <td>'+ own_stock +'</td>\n' +
            '           <td>'+ cust_stock +'</td>\n' +
            '           <td>'+ refcode +'</td>\n' +
            '           <td>'+ product_name +'</td>\n' +
            '           <td>'+ quantity +'</td>\n' +
            '           <td>'+ measurement +'</td>\n' +
            '           <td>'+ brand +'</td>\n' +
            '           <td>'+ category3 +'</td>\n' +
            '           <td>'+ note +'</td>\n' +
            '           <td>\n' +
            '               <div class="btn-list">\n' +
            '                   <button type="button" class="btn btn-sm btn-outline-theme" onclick="updateProductRow('+ count +');"><span class="fe fe-trash-2"> Düzenle</span></button>\n' +
            '                   <button type="button" class="btn btn-sm btn-outline-theme" onclick="deleteProductRow('+ count +');"><span class="fe fe-trash-2"> Sil</span></button>\n' +
            '               </div>\n' +
            '           </td>\n' +
            '       </tr>';
        $('#offer-request-products tbody').append(item);
        document.getElementById('update_offer_request_product_refcode').value = "";
        document.getElementById('update_offer_request_product_name').value = "";
        document.getElementById('update_offer_request_product_quantity').value = "1";
        document.getElementById('update_offer_request_product_measurement').value = "adet";
        document.getElementById('update_offer_request_owner_stock_code').value = "";
        document.getElementById('update_offer_request_customer_stock_code').value = "";
        document.getElementById('update_offer_request_customer_brand').value = "";
        document.getElementById('update_offer_request_note').value = "";

    }else{
        alert("Hata Oluştu");
    }

}

async function deleteProductRow(item_id){
    let returned = serviceGetDeleteProductToOfferRequest(item_id);
    if (returned) {
        $('#productRow' + item_id).remove();
    }else{
        alert("Ürün silinemedi");
    }
}
async function updateProductRow(item_id){
    let returned = serviceGetDeleteProductToOfferRequest(item_id);
    if (returned) {
        const table = document.getElementById("offer-request-products");
        const row = table.querySelector("#productRow"+item_id);
        const cells = row.cells;
        const cellData = [];
        for (let i = 0; i < cells.length; i++) {
            cellData.push(cells[i].textContent);
        }

        document.getElementById('update_offer_request_product_refcode').value = cellData[3];
        document.getElementById('update_offer_request_product_name').value = cellData[4];
        document.getElementById('update_offer_request_product_quantity').value = cellData[5];
        document.getElementById('update_offer_request_product_measurement').value = cellData[6];
        document.getElementById('update_offer_request_brand').value = cellData[7];
        document.getElementById('update_offer_request_customer_stock_code').value = cellData[2];
        document.getElementById('update_offer_request_owner_stock_code').value = cellData[1];
        document.getElementById('update_offer_request_note').value = cellData[9];
        $('#productRow' + item_id).remove();
    }else{
        alert("Ürün silinemedi");
    }
}




async function initOfferRequest(){
    let request_id = getPathVariable('offer-request-products');
    let data = await serviceGetOfferRequestById(request_id);
    let offer_request = data.offer_request;
    console.log(offer_request)

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
(async function() {
    let data = await serviceGetMeasurements();
    measurementOptions = data.measurements.map(function(item) {
        return { value: item.name_tr, label: item.name_tr };
    });
})();
let categoryOptions = [];
(async function (){
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

})();

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
                { data: "customer_stock_code", title: "Müşteri Stok Kodu" },
                { data: "ref_code", title: "Ref. Code" },
                { data: "product_name", title: "Ürün Adı" },
                { data: "quantity", title: "Miktar" },
                { data: "measurement_name_tr", title: "Birim" },
                { data: "product.brand_name", title: "Marka", editable: false },
                { data: "product.category_id", title: "Ürün Grubu", editable: false },
                { data: "note", title: "Satın Alma Notu" },
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
                    className: "btn btn-theme"
                },
                { extend: "edit",   editor: editor, text: "Düzenle", className: "btn btn-warning" },
                { extend: "remove", editor: editor, text: "Sil", className: "btn btn-danger" }
            ],
            language: {
                url: "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
            },
        } );
}
