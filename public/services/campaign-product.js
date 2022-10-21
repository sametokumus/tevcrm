(function($) {
    "use strict";
	
	$(document).ready(function() {
		$('#add_campaign_product_form').submit(function (e){
			e.preventDefault();
			addCampaignProduct();
		});
	});

	$(window).load( function() {

		checkLogin();
		checkRole();
		initCampaignProducts();

	});

})(window.jQuery);

function checkRole(){
	return true;
}

async function initCampaignProducts(){
	$("#product-datatable").dataTable().fnDestroy();
	$('#product-datatable tbody > tr').remove();

	let data = await serviceGetAllCampaignProducts();
	console.log(data.products)
	$.each(data.products, function (i, product) {
		let productItem = '<tr>\n' +
			'              <td>'+ product.order +'</td>\n' +
			'              <td>'+ product.id +'</td>\n' +
			'              <td>'+ product.sku +'</td>\n' +
			'              <td>'+ product.name +'</td>\n' +
			'              <td>'+ product.brand_name +'</td>\n' +
			'              <td>'+ product.type_name +'</td>\n' +
			'              <td>\n' +
			'                  <div class="btn-list">\n' +
			'                      <a href="update-product.php?id='+ product.id +'" class="btn btn-sm btn-primary"><span class="fe fe-edit"> </span></a>\n' +
			'                      <button id="bDel" type="button" class="btn  btn-sm btn-danger" onclick="deleteCampaignProduct(\''+ product.id +'\')">\n' +
			'                          <span class="fe fe-trash-2"> </span>\n' +
			'                      </button>\n' +
			'                  </div>\n' +
			'              </td>\n' +
			'          </tr>';
		$('#product-datatable tbody').append(productItem);
	});
	let dataTable = $('#product-datatable').DataTable({
		responsive: true,
		columnDefs: [
			{ responsivePriority: 1, targets: 0 },
			{ responsivePriority: 2, targets: -1 }
		],
		dom: 'Bfrtip',
		buttons: ['excel', 'pdf'],
		pageLength : -1,
		rowReorder: {
			selector: 'tr'
		},
		language: {
			url: "services/Turkish.json"
		}
	});

	// dataTable.on( 'row-reorder', function ( e, diff, edit ) {
	// 	// let data = dataTable.rows();
	// 	// console.log(data)
	// 	for ( var i=0, ien=diff.length ; i<ien ; i++ ) {
	// 		// get id row
	// 		let idQ = diff[i].node.id;
	// 		let idNewQ = idQ.replace("row_", "");
	// 		// get position
	// 		let position = diff[i].newPosition+1;
	// 		//call funnction to update data
	// 		console.log("a: "+idQ)
	// 		console.log("b: "+idNewQ)
	// 		console.log("c: "+position)
	// 		// updateOrder(idNewQ, position);
	// 	}
	// } );

	dataTable.on( 'row-reorder', function ( e, diff, edit ) {
		var result = 'Reorder started on row: '+edit.triggerRow.data()[1]+'<br>';

		for ( var i=0, ien=diff.length ; i<ien ; i++ ) {
			var rowData = dataTable.row( diff[i].node ).data();

			result += rowData[1]+' updated to be in position '+
				diff[i].newData+' (was '+diff[i].oldData+')<br>';
		}

		console.log( 'Event result:<br>'+result );
	} );

}
async function deleteCampaignProduct(product_id){
	let returned = await serviceGetDeleteCampaignProduct(product_id);
	if(returned){
		initCampaignProducts();
	}
}
async function addCampaignProduct(){
	let sku = document.getElementById('add_campaign_product_sku').value;
	let formData = JSON.stringify({
		"product_sku": sku,
		"order": 1
	});
	let returned = await servicePostAddCampaignProduct(formData);
	if(returned){
		initCampaignProducts();
	}
}