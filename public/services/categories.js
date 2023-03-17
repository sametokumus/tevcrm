(function($) {
    "use strict";

    $(document).ready(function() {

        $('#add_category_form').submit(function (e){
            e.preventDefault();
            addCategory();
        });
        $('#update_category_form').submit(function (e){
            e.preventDefault();
            updateCategory();
        });

    });

    $(window).load( function() {

        checkLogin();
        checkRole();
        initParentCategoryList();
        initCategoryView();

    });

})(window.jQuery);

function checkRole(){
    return true;
}

function openCategoryModal(category_id){
    $('#updateCategoryModal').modal('show');
    initCategoryModal(category_id);
}
async function initParentCategoryList(){
    $('.sub_category').addClass('d-none');
    $('#parent_category option').remove();
    $('#parent_category').append('<option value="0">Ana Grup Olarak Ekle</option>');
    let data = await serviceGetCategoriesByParentId(0);
    $.each(data.categories, function (i, category) {
        let categoryItem = '<option value="'+ category.id +'">'+ category.name +'</option>';

        $('#parent_category').append(categoryItem);
    });
}
async function changeParentCategory(){
    let parent_id = document.getElementById('parent_category').value;
    console.log(parent_id)
    if (parent_id == 0){
        $('.sub_category').addClass('d-none');
    }else{
        $('.sub_category').removeClass('d-none');
        await initSubCategoryList(parent_id);
    }
}
async function initSubCategoryList(parent_id){
    $('#sub_category option').remove();
    $('#sub_category').append('<option value="0">2. Seviye Alt Grup Olarak Ekle</option>');
    let data = await serviceGetCategoriesByParentId(parent_id);
    $.each(data.categories, function (i, category) {
        let categoryItem = '<option value="'+ category.id +'">'+ category.name +'</option>';

        $('#sub_category').append(categoryItem);
    });
}
async function initCategoryView(){

    let data = await serviceGetCategories();
    $("#category-datatable").dataTable().fnDestroy();
    $('#category-datatable tbody > tr').remove();

    $.each(data.categories, function (i, category) {
        let typeItem = '<tr class="bg-gray-700">\n' +
            '              <td>'+ (i+1) +'</td>\n' +
            '              <td>'+ category.name +'</td>\n' +
            '              <td>\n' +
            '                  <div class="btn-list">\n' +
            '                      <button id="bEdit" type="button" class="btn btn-sm btn-theme" onclick="openCategoryModal(\''+ category.id +'\')">\n' +
            '                          <span class="fe fe-edit"> </span> Düzenle\n' +
            '                      </button>\n' +
            '                      <button id="bDel" type="button" class="btn  btn-sm btn-danger" onclick="deleteCategory(\''+ category.id +'\')">\n' +
            '                          <span class="fe fe-trash-2"> </span> Sil\n' +
            '                      </button>\n' +
            '                  </div>\n' +
            '              </td>\n' +
            '          </tr>';

        $.each(category.sub_categories, function (j, sub_category) {
            typeItem += '<tr class="bg-gray-600">\n' +
                '              <td>--->>> '+ (i+1)+'.'+(j+1) +'</td>\n' +
                '              <td>'+ sub_category.name +'</td>\n' +
                '              <td>\n' +
                '                  <div class="btn-list">\n' +
                '                      <button id="bEdit" type="button" class="btn btn-sm btn-theme" onclick="openCategoryModal(\''+ sub_category.id +'\')">\n' +
                '                          <span class="fe fe-edit"> </span> Düzenle\n' +
                '                      </button>\n' +
                '                      <button id="bDel" type="button" class="btn  btn-sm btn-danger" onclick="deleteCategory(\''+ sub_category.id +'\')">\n' +
                '                          <span class="fe fe-trash-2"> </span> Sil\n' +
                '                      </button>\n' +
                '                  </div>\n' +
                '              </td>\n' +
                '          </tr>';

            $.each(sub_category.sub_categories, function (k, thr_sub_category) {
                typeItem += '<tr>\n' +
                    '              <td>------>>>>>> '+ (i+1)+'.'+(j+1)+'.'+(k+1) +'</td>\n' +
                    '              <td>'+ thr_sub_category.name +'</td>\n' +
                    '              <td>\n' +
                    '                  <div class="btn-list">\n' +
                    '                      <button id="bEdit" type="button" class="btn btn-sm btn-theme" onclick="openCategoryModal(\''+ thr_sub_category.id +'\')">\n' +
                    '                          <span class="fe fe-edit"> </span> Düzenle\n' +
                    '                      </button>\n' +
                    '                      <button id="bDel" type="button" class="btn  btn-sm btn-danger" onclick="deleteCategory(\''+ thr_sub_category.id +'\')">\n' +
                    '                          <span class="fe fe-trash-2"> </span> Sil\n' +
                    '                      </button>\n' +
                    '                  </div>\n' +
                    '              </td>\n' +
                    '          </tr>';
            });

        });

        $('#category-datatable tbody').append(typeItem);
    });

    $('#category-datatable').DataTable({
        responsive: true,
        columnDefs: [
            { responsivePriority: 1, targets: 0 },
            { responsivePriority: 2, targets: -1 }
        ],
        dom: 'Bfrtip',
        buttons: ['excel', 'pdf'],
        pageLength : 20,
        language: {
            url: "services/Turkish.json"
        },
        order: false
    });
}
async function initCategoryModal(category_id){
    let data = await serviceGetCategoryById(category_id);
    let category = data.category;
    document.getElementById('update_category_id').value = category.id;
    document.getElementById('update_category_name').value = category.name;
}



async function addCategory(){
    let parent_id = 0;
    if (document.getElementById('parent_category').value != 0){
        if (document.getElementById('sub_category').value != 0){
            parent_id = document.getElementById('sub_category').value;
        }else{
            parent_id = document.getElementById('parent_category').value;
        }
    }
    let category_name = document.getElementById('category_name').value;
    let formData = JSON.stringify({
        "parent_id": parent_id,
        "name": category_name
    });
    let returned = await servicePostAddCategory(formData);
    if(returned){
        $("#add_category_form").trigger("reset");
        initParentCategoryList();
        initCategoryView();
    }
}
async function updateCategory(){
    let category_id = document.getElementById('update_category_id').value;
    let category_name = document.getElementById('update_category_name').value;
    let formData = JSON.stringify({
        "name": category_name
    });
    console.log(formData)
    let returned = await servicePostUpdateCategory(formData, category_id);
    console.log(returned)
    if(returned){
        $("#update_category_form").trigger("reset");
        $('#updateCategoryModal').modal('hide');
        initParentCategoryList();
        initCategoryView();
    }
}
async function deleteCategory(categoryId){
    let returned = await serviceGetDeleteCategory(categoryId);
    if(returned){
        initParentCategoryList();
        initCategoryView();
    }
}
