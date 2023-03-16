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
    $('#parent_category option').remove();
    $('#parent_category').append('<option value="0">2. Seviye Alt Grup Olarak Ekle</option>');
    let data = await serviceGetCategoriesByParentId(parent_id);
    $.each(data.categories, function (i, category) {
        let categoryItem = '<option value="'+ category.id +'">'+ category.name +'</option>';

        $('#sub_category').append(categoryItem);
    });
}
async function initCategoryView(){
    $('#category_view > li').remove();
    let data = await serviceGetCategories();
    $.each(data.categories, function (i, category) {
        if ((category.sub_categories.length == 0)){
            let categoryItem = '<li>'+ category.name +
                '	<a href="javascript:void(0)" class="btn btn-danger btn-sm float-end" onclick="deleteCategory(\''+ category.id +'\')">Sil</a>' +
                '	<a href="javascript:void(0)" class="btn btn-theme btn-sm float-end mx-2" onclick="openCategoryModal(\''+ category.id +'\')">Güncelle</a>' +
                '</li>';
            $('#category_view').append(categoryItem);
        }else{
            let categoryItem = '<li>'+ category.name +'\n' +
                '	<a href="javascript:void(0)" class="btn btn-danger btn-sm float-end" onclick="deleteCategory(\''+ category.id +'\')">Sil</a>' +
                '	<a href="javascript:void(0)" class="btn btn-theme btn-sm float-end mx-2" onclick="openCategoryModal(\''+ category.id +'\')">Güncelle</a>' +
                '               	<ul>';
            $.each(category.sub_categories, function (i, sub_category) {
                categoryItem = categoryItem + '<li>'+ sub_category.name +
                    '	<a href="javascript:void(0)" class="btn btn-danger btn-sm float-end" onclick="deleteCategory(\''+ sub_category.id +'\')">Sil</a>' +
                    '	<a href="javascript:void(0)" class="btn btn-theme btn-sm float-end mx-2" onclick="openCategoryModal(\''+ sub_category.id +'\')">Güncelle</a>' +
                    '</li>';
            });
            categoryItem = categoryItem + '</ul>\n' +
                '       </li>';
            $('#category_view').append(categoryItem);
        }
    });
    // $('#category_view').treed();
}
async function initCategoryModal(category_id){
    let data = await serviceGetCategoryById(category_id);
    let category = data.category;
    document.getElementById('update_parent_category').value = category.parent_id;
    document.getElementById('update_category_id').value = category.id;
    document.getElementById('update_category_name').value = category.name;
}



async function addCategory(){
    let parent_id = document.getElementById('parent_category').value;
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
    let parent_id = document.getElementById('update_parent_category').value;
    let category_id = document.getElementById('update_category_id').value;
    let category_name = document.getElementById('update_category_name').value;
    let formData = JSON.stringify({
        "parent_id": parent_id,
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
