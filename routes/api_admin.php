<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\Admin\AuthController;
use App\Http\Controllers\Api\Admin\AdminRoleController;
use App\Http\Controllers\Api\Admin\AdminPermissionController;
use App\Http\Controllers\Api\Admin\OfferRequestController;
use App\Http\Controllers\Api\Admin\OfferController;
use App\Http\Controllers\Api\Admin\ImportController;
use App\Http\Controllers\Api\Admin\CountryController;
use App\Http\Controllers\Api\Admin\StateController;
use App\Http\Controllers\Api\Admin\CityController;
use App\Http\Controllers\Api\Admin\CompanyController;
use App\Http\Controllers\Api\Admin\EmployeeController;
use App\Http\Controllers\Api\Admin\ActivityController;
use App\Http\Controllers\Api\Admin\NoteController;
use App\Http\Controllers\Api\Admin\ContactController;
use App\Http\Controllers\Api\Admin\SaleController;
use App\Http\Controllers\Api\Admin\StatusController;
use App\Http\Controllers\Api\Admin\OwnerController;
use App\Http\Controllers\Api\Admin\NewsFeedController;
use App\Http\Controllers\Api\Admin\MeasurementController;
use App\Http\Controllers\Api\Admin\LanguageController;
use App\Http\Controllers\Api\Admin\BrandController;
use App\Http\Controllers\Api\Admin\CategoryController;
use App\Http\Controllers\Api\Admin\ProductController;
use App\Http\Controllers\Api\Admin\SettingController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//
Route::get('language/changeLanguage/{language_key}',[LanguageController::class,'language']);
//Route::get('',[LanguageController::class,'emptyLanguage'])->name('front.emptyLanguage');

Route::post('login', [AuthController::class, 'login'])->name('admin.login');


Route::middleware(['auth:sanctum', 'type.admin'])->group(function (){

    Route::get('logout', [AuthController::class, 'logout'])->name('admin.logout');
    Route::post('register', [AuthController::class, 'register'])->name('admin.register');

    Route::get('adminRole/getAdmins', [AdminRoleController::class, 'getAdmins']);
    Route::get('adminRole/getAdminById/{id}', [AdminRoleController::class, 'getAdminById']);
    Route::post('adminRole/addAdmin', [AdminRoleController::class, 'addAdmin']);
    Route::post('adminRole/updateAdmin/{id}', [AdminRoleController::class, 'updateAdmin']);
    Route::post('adminRole/updateUser/{id}', [AdminRoleController::class, 'updateUser']);
    Route::get('adminRole/deleteAdmin/{id}', [AdminRoleController::class, 'deleteAdmin']);

    Route::get('adminRole/getAdminRoles', [AdminRoleController::class, 'getAdminRoles']);
    Route::get('adminRole/getAdminRoleById/{id}', [AdminRoleController::class, 'getAdminRoleById']);
    Route::post('adminRole/addAdminRole', [AdminRoleController::class, 'addAdminRole']);
    Route::post('adminRole/updateAdminRole/{role_id}', [AdminRoleController::class, 'updateAdminRole']);
    Route::get('adminRole/deleteAdminRole/{role_id}', [AdminRoleController::class, 'deleteAdminRole']);

    Route::get('adminRole/getAdminRolePermissions/{role_id}', [AdminRoleController::class, 'getAdminRolePermissions']);
    Route::get('adminRole/addAdminRolePermission/{role_id}/{permission_id}', [AdminRoleController::class, 'addAdminRolePermission']);
    Route::get('adminRole/deleteAdminRolePermission/{role_id}/{permission_id}', [AdminRoleController::class, 'deleteAdminRolePermission']);

    Route::get('adminRole/getAdminRoleStatuses/{role_id}', [AdminRoleController::class, 'getAdminRoleStatuses']);
    Route::get('adminRole/addAdminRoleStatus/{role_id}/{status_id}', [AdminRoleController::class, 'addAdminRoleStatus']);
    Route::get('adminRole/deleteAdminRoleStatus/{role_id}/{status_id}', [AdminRoleController::class, 'deleteAdminRoleStatus']);

    Route::get('adminPermission/getAdminPermissions', [AdminPermissionController::class, 'getAdminPermissions']);
    Route::post('adminPermission/addAdminPermission', [AdminPermissionController::class, 'addAdminPermission']);
    Route::post('adminPermission/updateAdminPermission/{id}', [AdminPermissionController::class, 'updateAdminPermission']);
    Route::get('adminPermission/deleteAdminPermission/{id}', [AdminPermissionController::class, 'deleteAdminPermission']);




    Route::get('countries/getCountries', [CountryController::class, 'getCountries']);
    Route::get('states/getStatesByCountryId/{country_id}', [StateController::class, 'getStatesByCountryId']);
    Route::get('cities/getCitiesByStateId/{state_id}', [CityController::class, 'getCitiesByStateId']);


    Route::post('excel/productExcelImport', [ImportController::class, 'productExcelImport']);
    Route::post('excel/priceExcelImport', [ImportController::class, 'priceExcelImport']);
    Route::get('excel/addAllProduct', [ImportController::class, 'addAllProduct']);
    Route::get('excel/addProductPrice', [ImportController::class, 'addProductPrice']);
    Route::get('excel/productVariationUpdate', [ImportController::class, 'productVariationUpdate']);
    Route::get('excel/setProductCategory', [ImportController::class, 'setProductCategory']);
    Route::post('excel/newProduct', [ImportController::class, 'newProduct']);
    Route::post('excel/postNewProducts', [ImportController::class, 'postNewProducts']);
    Route::get('excel/updateProductNew', [ImportController::class, 'updateProductNew']);



    //Company
    Route::get('company/getCompanies', [CompanyController::class, 'getCompanies']);
    Route::get('company/getPotentialCustomers', [CompanyController::class, 'getPotentialCustomers']);
    Route::get('company/getCustomers', [CompanyController::class, 'getCustomers']);
    Route::get('company/getSuppliers', [CompanyController::class, 'getSuppliers']);
    Route::get('company/getCompanyById/{company_id}', [CompanyController::class, 'getCompanyById']);
    Route::post('company/addCompany', [CompanyController::class, 'addCompany']);
    Route::post('company/updateCompany/{company_id}', [CompanyController::class, 'updateCompany']);
    Route::get('company/deleteCompany/{company_id}', [CompanyController::class, 'deleteCompany']);

    //Employee
    Route::get('employee/getEmployees', [EmployeeController::class, 'getEmployees']);
    Route::get('employee/getEmployeesByCompanyId/{company_id}', [EmployeeController::class, 'getEmployeesByCompanyId']);
    Route::get('employee/getEmployeeById/{employee_id}', [EmployeeController::class, 'getEmployeeById']);
    Route::post('employee/addEmployee', [EmployeeController::class, 'addEmployee']);
    Route::post('employee/updateEmployee/{employee_id}', [EmployeeController::class, 'updateEmployee']);
    Route::get('employee/deleteEmployee/{employee_id}', [EmployeeController::class, 'deleteEmployee']);

    //Activity
    Route::get('activity/getActivities', [ActivityController::class, 'getActivities']);
    Route::get('activity/getActivitiesByCompanyId/{company_id}', [ActivityController::class, 'getActivitiesByCompanyId']);
    Route::get('activity/getActivityById/{activity_id}', [ActivityController::class, 'getActivityById']);
    Route::post('activity/addActivity', [ActivityController::class, 'addActivity']);
    Route::post('activity/updateActivity/{activity_id}', [ActivityController::class, 'updateActivity']);
    Route::get('activity/deleteActivity/{activity_id}', [ActivityController::class, 'deleteActivity']);
    //Activity Tasks
    Route::get('activity/getActivityTasksByCompanyId/{company_id}', [ActivityController::class, 'getActivityTasksByCompanyId']);
    Route::get('activity/getActivityTaskById/{task_id}', [ActivityController::class, 'getActivityTaskById']);
    Route::post('activity/addActivityTask', [ActivityController::class, 'addActivityTask']);
    Route::post('activity/updateActivityTask/{task_id}', [ActivityController::class, 'updateActivityTask']);
    Route::get('activity/deleteActivityTask/{task_id}', [ActivityController::class, 'deleteActivityTask']);
    Route::get('activity/completeActivityTask/{task_id}', [ActivityController::class, 'completeActivityTask']);
    Route::get('activity/unCompleteActivityTask/{task_id}', [ActivityController::class, 'unCompleteActivityTask']);
    //Activity Types
    Route::get('activity/getActivityTypes', [ActivityController::class, 'getActivityTypes']);
    Route::get('activity/getActivityTypeById/{type_id}', [ActivityController::class, 'getActivityTypeById']);
    Route::post('activity/addActivityType', [ActivityController::class, 'addActivityType']);
    Route::post('activity/updateActivityType/{type_id}', [ActivityController::class, 'updateActivityType']);
    Route::get('activity/deleteActivityType/{type_id}', [ActivityController::class, 'deleteActivityType']);

    //Note
    Route::get('note/getNotes', [NoteController::class, 'getNotes']);
    Route::get('note/getNotesByCompanyId/{company_id}', [NoteController::class, 'getNotesByCompanyId']);
    Route::get('note/getNoteById/{note_id}', [NoteController::class, 'getNoteById']);
    Route::post('note/addNote', [NoteController::class, 'addNote']);
    Route::post('note/updateNote/{note_id}', [NoteController::class, 'updateNote']);
    Route::get('note/deleteNote/{note_id}', [NoteController::class, 'deleteNote']);


    //OfferRequest
    Route::get('offerRequest/getOfferRequests', [OfferRequestController::class, 'getOfferRequests']);
    Route::get('offerRequest/getOfferRequestById/{offer_request_id}', [OfferRequestController::class, 'getOfferRequestById']);
    Route::get('offerRequest/getOfferRequestProductsById/{offer_request_id}', [OfferRequestController::class, 'getOfferRequestProductsById']);
    Route::post('offerRequest/offerRequestProducts/{request_id}', [OfferRequestController::class, 'offerRequestProducts']);
    Route::post('offerRequest/createOfferRequest', [OfferRequestController::class, 'createOfferRequest']);
    Route::post('offerRequest/addOfferRequest', [OfferRequestController::class, 'addOfferRequest']);
    Route::post('offerRequest/updateOfferRequest/{request_id}', [OfferRequestController::class, 'updateOfferRequest']);
    Route::post('offerRequest/addProductToOfferRequest/{request_id}', [OfferRequestController::class, 'addProductToOfferRequest']);
    Route::get('offerRequest/deleteProductToOfferRequest/{request_product_id}', [OfferRequestController::class, 'deleteProductToOfferRequest']);
    Route::get('offerRequest/getOfferRequestsByCompanyId/{company_id}', [OfferRequestController::class, 'getOfferRequestsByCompanyId']);


    //Offer
    Route::get('offer/getOffersByRequestId/{request_id}', [OfferController::class, 'getOffersByRequestId']);
    Route::get('offer/getOfferById/{offer_id}', [OfferController::class, 'getOfferById']);
    Route::post('offer/addOffer', [OfferController::class, 'addOffer']);
    Route::get('offer/getOfferProductById/{offer_id}/{product_id}', [OfferController::class, 'getOfferProductById']);
    Route::post('offer/addOfferProduct/{offer_id}', [OfferController::class, 'addOfferProduct']);
    Route::post('offer/updateOfferProduct/{offer_id}/{product_id}', [OfferController::class, 'updateOfferProduct']);


    //Sale
    Route::get('sale/getSales', [SaleController::class, 'getSales']);
    Route::get('sale/getActiveSales/{user_id}', [SaleController::class, 'getActiveSales']);
    Route::post('sale/getFilteredSales/{user_id}', [SaleController::class, 'getFilteredSales']);
    Route::get('sale/getSaleById/{sale_id}', [SaleController::class, 'getSaleById']);
    Route::get('sale/getApproveOfferBySaleId/{sale_id}/{user_id}/{revize}', [SaleController::class, 'getApproveOfferBySaleId']);
    Route::get('sale/getRejectOfferBySaleId/{sale_id}/{user_id}/{revize}', [SaleController::class, 'getRejectOfferBySaleId']);
    Route::post('sale/addSale', [SaleController::class, 'addSale']);
    Route::post('sale/updateSaleStatus', [SaleController::class, 'updateSaleStatus']);
    Route::get('sale/getSaleOfferById/{offer_product_id}', [SaleController::class, 'getSaleOfferById']);
    Route::get('sale/getSaleOffersByOfferId/{offer_id}', [SaleController::class, 'getSaleOffersByOfferId']);
    Route::post('sale/addSaleOfferPrice', [SaleController::class, 'addSaleOfferPrice']);
    Route::post('sale/updateSaleOfferPrice', [SaleController::class, 'updateSaleOfferPrice']);
    Route::get('sale/getQuoteBySaleId/{sale_id}', [SaleController::class, 'getQuoteBySaleId']);
    Route::post('sale/updateQuote', [SaleController::class, 'updateQuote']);
    Route::post('sale/updateShippingPrice', [SaleController::class, 'updateShippingPrice']);
    Route::post('sale/addCancelSaleNote', [SaleController::class, 'addCancelSaleNote']);
    Route::get('sale/getRfqDetailById/{offer_id}', [SaleController::class, 'getRfqDetailById']);
    Route::post('sale/updateRfqDetail', [SaleController::class, 'updateRfqDetail']);

    Route::get('sale/removeCancelledSales', [SaleController::class, 'removeCancelledSales']);


    Route::get('sale/getSaleNotes/{sale_id}', [SaleController::class, 'getSaleNotes']);
    Route::post('sale/addSaleNote', [SaleController::class, 'addSaleNote']);

    Route::get('sale/getLastCurrencyLog', [SaleController::class, 'getLastCurrencyLog']);
    Route::get('sale/getCheckSaleCurrencyLog/{request_id}', [SaleController::class, 'getCheckSaleCurrencyLog']);
    Route::post('sale/addSaleCurrencyLog/{request_id}', [SaleController::class, 'addSaleCurrencyLog']);
    Route::get('sale/getSaleByRequestId/{request_id}', [SaleController::class, 'getSaleByRequestId']);

    //Owner
    Route::get('owner/getBankInfos', [OwnerController::class, 'getBankInfos']);
    Route::get('owner/getBankInfoById/{info_id}', [OwnerController::class, 'getBankInfoById']);
    Route::post('owner/addBankInfo', [OwnerController::class, 'addBankInfo']);
    Route::post('owner/updateBankInfo', [OwnerController::class, 'updateBankInfo']);
    Route::get('owner/deleteBankInfo/{info_id}', [OwnerController::class, 'deleteBankInfo']);

    //Purchasing Order Detail
    Route::get('sale/getPurchasingOrderDetailById/{offer_id}', [SaleController::class, 'getPurchasingOrderDetailById']);
    Route::post('sale/addPurchasingOrderDetail', [SaleController::class, 'addPurchasingOrderDetail']);
    Route::post('sale/updatePurchasingOrderDetail', [SaleController::class, 'updatePurchasingOrderDetail']);

    //Order Confirmation Detail
    Route::get('sale/getOrderConfirmationDetailById/{sale_id}', [SaleController::class, 'getOrderConfirmationDetailById']);
    Route::post('sale/addOrderConfirmationDetail', [SaleController::class, 'addOrderConfirmationDetail']);
    Route::post('sale/updateOrderConfirmationDetail', [SaleController::class, 'updateOrderConfirmationDetail']);

    //Proforma Invoice Detail
    Route::get('sale/getProformaInvoiceDetailById/{sale_id}', [SaleController::class, 'getProformaInvoiceDetailById']);
    Route::post('sale/updateProformaInvoiceDetail', [SaleController::class, 'updateProformaInvoiceDetail']);

    //Sale Detail
    Route::get('sale/getSaleDetailInfo/{sale_id}', [SaleController::class, 'getSaleDetailInfo']);
    Route::get('sale/getSaleStatusHistory/{sale_id}', [SaleController::class, 'getSaleStatusHistory']);
    Route::get('sale/getSaleSuppliers/{sale_id}', [SaleController::class, 'getSaleSuppliers']);


    //Contact
    Route::get('contact/getContacts', [ContactController::class, 'getContacts']);
    Route::get('contact/getContactById/{contact_id}', [ContactController::class, 'getContactById']);
    Route::post('contact/updateContact', [ContactController::class, 'updateContact']);
    Route::get('contact/deleteContact/{contact_id}', [ContactController::class, 'deleteContact']);


    //Status
    Route::get('status/getStatuses', [StatusController::class, 'getStatuses']);
    Route::get('status/getChangeableStatuses', [StatusController::class, 'getChangeableStatuses']);
    Route::get('status/getAuthorizeStatuses/{user_id}', [StatusController::class, 'getAuthorizeStatuses']);

    //Measurement
    Route::get('measurement/getMeasurements', [MeasurementController::class, 'getMeasurements']);
    Route::get('measurement/getMeasurementById/{id}', [MeasurementController::class, 'getMeasurementById']);


    //News Feed
    Route::get('newsFeed/getSaleHistoryActions', [NewsFeedController::class, 'getSaleHistoryActions']);
    Route::get('newsFeed/getTopRequestedProducts', [NewsFeedController::class, 'getTopRequestedProducts']);
    Route::get('newsFeed/getSaleStats', [NewsFeedController::class, 'getSaleStats']);

    //Product
    Route::get('product/getProducts', [ProductController::class, 'getProducts']);
    Route::get('product/getProductById/{id}', [ProductController::class, 'getProductById']);
    Route::post('product/addProduct', [ProductController::class, 'addProduct']);
    Route::post('product/updateProduct/{id}', [ProductController::class, 'updateProduct']);
    Route::get('product/deleteProduct/{id}', [ProductController::class, 'deleteProduct']);

    //Brand
    Route::get('brand/getBrands', [BrandController::class, 'getBrands']);
    Route::get('brand/getBrandById/{id}', [BrandController::class, 'getBrandById']);
    Route::post('brand/addBrand', [BrandController::class, 'addBrand']);
    Route::post('brand/updateBrand/{id}', [BrandController::class, 'updateBrand']);
    Route::get('brand/deleteBrand/{id}', [BrandController::class, 'deleteBrand']);

    //Category
    Route::get('category/getCategory', [CategoryController::class, 'getCategory']);
    Route::get('category/getCategoryById/{id}', [CategoryController::class, 'getCategoryById']);
    Route::get('category/getCategoryByParentId/{parent_id}', [CategoryController::class, 'getCategoryByParentId']);
    Route::post('category/addCategory', [CategoryController::class, 'addCategory']);
    Route::post('category/updateCategory/{id}', [CategoryController::class, 'updateCategory']);
    Route::get('category/deleteCategory/{id}', [CategoryController::class, 'deleteCategory']);


    //Payment Terms
    Route::get('setting/getPaymentTerms', [SettingController::class, 'getPaymentTerms']);
    Route::get('setting/getPaymentTermById/{type_id}', [SettingController::class, 'getPaymentTermById']);
    Route::post('setting/addPaymentTerm', [SettingController::class, 'addPaymentTerm']);
    Route::post('setting/updatePaymentTerm/{type_id}', [SettingController::class, 'updatePaymentTerm']);
    Route::get('setting/deletePaymentTerm/{type_id}', [SettingController::class, 'deletePaymentTerm']);


    //Delivery Terms
    Route::get('setting/getDeliveryTerms', [SettingController::class, 'getDeliveryTerms']);
    Route::get('setting/getDeliveryTermById/{type_id}', [SettingController::class, 'getDeliveryTermById']);
    Route::post('setting/addDeliveryTerm', [SettingController::class, 'addDeliveryTerm']);
    Route::post('setting/updateDeliveryTerm/{type_id}', [SettingController::class, 'updateDeliveryTerm']);
    Route::get('setting/deleteDeliveryTerm/{type_id}', [SettingController::class, 'deleteDeliveryTerm']);
});

