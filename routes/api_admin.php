<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\Admin\AuthController;
use App\Http\Controllers\Api\Admin\AdminRoleController;
use App\Http\Controllers\Api\Admin\AdminPermissionController;
use App\Http\Controllers\Api\Admin\CountryController;
use App\Http\Controllers\Api\Admin\StateController;
use App\Http\Controllers\Api\Admin\CityController;
use App\Http\Controllers\Api\Admin\CompanyController;
use App\Http\Controllers\Api\Admin\EmployeeController;
use App\Http\Controllers\Api\Admin\NoteController;
use App\Http\Controllers\Api\Admin\ContactController;
use App\Http\Controllers\Api\Admin\SaleController;
use App\Http\Controllers\Api\Admin\StatusController;
use App\Http\Controllers\Api\Admin\OwnerController;
use App\Http\Controllers\Api\Admin\LanguageController;
use App\Http\Controllers\Api\Admin\CategoryController;
use App\Http\Controllers\Api\Admin\ProductController;
use App\Http\Controllers\Api\Admin\SettingController;
use App\Http\Controllers\Api\Admin\DashboardController;
use App\Http\Controllers\Api\Admin\PdfController;
use App\Http\Controllers\Api\Admin\ChatController;
use App\Http\Controllers\Api\Admin\SocketsController;
use App\Http\Controllers\Api\Admin\NotifyController;
use App\Http\Controllers\Api\Admin\TestController;
use App\Http\Controllers\Api\Admin\OfferController;

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

Route::get('sale/getLiveCurrencyLog', [SaleController::class, 'getLiveCurrencyLog']);
Route::get('notify/getCheckSystemNotifies', [NotifyController::class, 'getCheckSystemNotifies']);



Route::middleware(['auth:sanctum', 'type.admin'])->group(function (){

    Route::get('logout', [AuthController::class, 'logout'])->name('admin.logout');
    Route::post('register', [AuthController::class, 'register'])->name('admin.register');

    Route::get('adminRole/getAdmins', [AdminRoleController::class, 'getAdmins']);
    Route::get('adminRole/getAdminById/{id}', [AdminRoleController::class, 'getAdminById']);
    Route::post('adminRole/addAdmin', [AdminRoleController::class, 'addAdmin']);
    Route::post('adminRole/updateAdmin/{id}', [AdminRoleController::class, 'updateAdmin']);
    Route::post('adminRole/updateUser/{id}', [AdminRoleController::class, 'updateUser']);
    Route::get('adminRole/deleteAdmin/{id}', [AdminRoleController::class, 'deleteAdmin']);
    Route::get('adminRole/getMailSignaturesByAdminId/{id}', [AdminRoleController::class, 'getMailSignaturesByAdminId']);
    Route::post('adminRole/updateMailSignature', [AdminRoleController::class, 'updateMailSignature']);

    Route::get('adminRole/getAdminRoles', [AdminRoleController::class, 'getAdminRoles']);
    Route::get('adminRole/getAdminRoleById/{id}', [AdminRoleController::class, 'getAdminRoleById']);
    Route::post('adminRole/addAdminRole', [AdminRoleController::class, 'addAdminRole']);
    Route::post('adminRole/updateAdminRole/{role_id}', [AdminRoleController::class, 'updateAdminRole']);
    Route::get('adminRole/deleteAdminRole/{role_id}', [AdminRoleController::class, 'deleteAdminRole']);

    Route::get('adminRole/getAdminRolePermissions/{role_id}', [AdminRoleController::class, 'getAdminRolePermissions']);
    Route::get('adminRole/addAdminRolePermission/{role_id}/{permission_id}', [AdminRoleController::class, 'addAdminRolePermission']);
    Route::get('adminRole/deleteAdminRolePermission/{role_id}/{permission_id}', [AdminRoleController::class, 'deleteAdminRolePermission']);
    Route::get('adminRole/getCheckAdminRolePermission/{admin_id}/{permission_id}', [AdminRoleController::class, 'getCheckAdminRolePermission']);

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



    //Company
    Route::get('customer/getCustomers', [CompanyController::class, 'getCustomers']);
    Route::get('customer/getCustomerById/{customer_id}', [CompanyController::class, 'getCustomerById']);
    Route::post('customer/addCustomer', [CompanyController::class, 'addCustomer']);
    Route::post('customer/updateCustomer/{company_id}', [CompanyController::class, 'updateCustomer']);
    Route::get('customer/deleteCustomer/{company_id}', [CompanyController::class, 'deleteCustomer']);

    //Employee
    Route::get('employee/getEmployees', [EmployeeController::class, 'getEmployees']);
    Route::get('employee/getEmployeesByCompanyId/{company_id}', [EmployeeController::class, 'getEmployeesByCompanyId']);
    Route::get('employee/getEmployeeById/{employee_id}', [EmployeeController::class, 'getEmployeeById']);
    Route::post('employee/addEmployee', [EmployeeController::class, 'addEmployee']);
    Route::post('employee/updateEmployee/{employee_id}', [EmployeeController::class, 'updateEmployee']);
    Route::get('employee/deleteEmployee/{employee_id}', [EmployeeController::class, 'deleteEmployee']);

    //Category
    Route::get('category/getCategory', [CategoryController::class, 'getCategory']);
    Route::get('category/getCategoryById/{id}', [CategoryController::class, 'getCategoryById']);
    Route::get('category/getCategoryByParentId/{parent_id}', [CategoryController::class, 'getCategoryByParentId']);
    Route::post('category/addCategory', [CategoryController::class, 'addCategory']);
    Route::post('category/updateCategory/{id}', [CategoryController::class, 'updateCategory']);
    Route::get('category/deleteCategory/{id}', [CategoryController::class, 'deleteCategory']);


    //Test
    Route::get('test/getTests', [TestController::class, 'getTests']);
    Route::get('test/getTestsByCategoryId/{category_id}', [TestController::class, 'getTestsByCategoryId']);
    Route::get('test/getTestById/{test_id}', [TestController::class, 'getTestById']);
    Route::post('test/addTest', [TestController::class, 'addTest']);
    Route::post('test/updateTest/{test_id}', [TestController::class, 'updateTest']);
    Route::get('test/deleteTest/{test_id}', [TestController::class, 'deleteTest']);


    //Offer
    Route::post('offer/addOffer', [OfferController::class, 'addOffer']);
    Route::post('offer/updateOffer', [OfferController::class, 'updateOffer']);
    Route::get('offer/getOffers', [OfferController::class, 'getOffers']);
    Route::get('offer/getOfferById/{offer_id}', [OfferController::class, 'getOfferById']);
    Route::get('offer/getOfferInfoById/{offer_id}', [OfferController::class, 'getOfferInfoById']);
    Route::get('offer/getOfferTestsById/{offer_id}', [OfferController::class, 'getOfferTestsById']);
    Route::get('offer/addTestToOffer/{offer_id}/{test_id}', [OfferController::class, 'addTestToOffer']);
    Route::post('offer/updateTestToOffer/{offer_detail_id}', [OfferController::class, 'updateTestToOffer']);
    Route::get('offer/deleteTestToOffer/{offer_detail_id}', [OfferController::class, 'deleteTestToOffer']);
    Route::get('offer/getOfferSummaryById/{offer_id}', [OfferController::class, 'getOfferSummaryById']);
    Route::post('offer/updateOfferSummary/{offer_id}', [OfferController::class, 'updateOfferSummary']);

    Route::get('offer/getOffersByRequestId/{request_id}', [OfferController::class, 'getOffersByRequestId']);
    Route::get('offer/getNewOffersByRequestId/{request_id}', [OfferController::class, 'getNewOffersByRequestId']);
    Route::get('offer/getOfferProductById/{offer_id}/{product_id}', [OfferController::class, 'getOfferProductById']);
    Route::post('offer/addOfferProduct/{offer_id}', [OfferController::class, 'addOfferProduct']);
    Route::post('offer/updateOfferProduct/{offer_id}/{product_id}', [OfferController::class, 'updateOfferProduct']);
    Route::get('offer/deleteOffer/{offer_id}', [OfferController::class, 'deleteOffer']);














    //Sale
    Route::get('sale/getSales', [SaleController::class, 'getSales']);
    Route::get('sale/getSalesByCompanyId/{company_id}', [SaleController::class, 'getSalesByCompanyId']);
    Route::get('sale/getActiveSales/{user_id}', [SaleController::class, 'getActiveSales']);
    Route::get('sale/getApprovedSales/{user_id}', [SaleController::class, 'getApprovedSales']);
    Route::get('sale/getCancelledSales/{user_id}', [SaleController::class, 'getCancelledSales']);
    Route::post('sale/getFilteredSales/{user_id}', [SaleController::class, 'getFilteredSales']);
    Route::get('sale/getSaleById/{sale_id}', [SaleController::class, 'getSaleById']);
    Route::get('sale/getSaleConfirmationById/{sale_id}', [SaleController::class, 'getSaleConfirmationById']);
    Route::get('sale/getPackingListSaleById/{sale_id}', [SaleController::class, 'getPackingListSaleById']);
    Route::get('sale/getApproveOfferBySaleId/{sale_id}/{user_id}/{revize}', [SaleController::class, 'getApproveOfferBySaleId']);
    Route::get('sale/getRejectOfferBySaleId/{sale_id}/{user_id}/{revize}', [SaleController::class, 'getRejectOfferBySaleId']);
    Route::post('sale/addSale', [SaleController::class, 'addSale']);
    Route::get('sale/deleteSale/{sale_id}', [SaleController::class, 'deleteSale']);
    Route::post('sale/updateSaleStatus', [SaleController::class, 'updateSaleStatus']);
    Route::post('sale/updatePackingListStatus', [SaleController::class, 'updatePackingListStatus']);
    Route::post('sale/addPackingDeliveryAddress', [SaleController::class, 'addPackingDeliveryAddress']);
    Route::post('sale/updatePackingDeliveryAddress', [SaleController::class, 'updatePackingDeliveryAddress']);
    Route::get('sale/getSaleOfferById/{offer_product_id}', [SaleController::class, 'getSaleOfferById']);
    Route::get('sale/getSaleOffersByOfferId/{offer_id}', [SaleController::class, 'getSaleOffersByOfferId']);
    Route::post('sale/addSaleOfferPrice', [SaleController::class, 'addSaleOfferPrice']);
    Route::post('sale/updateSaleOfferPrice', [SaleController::class, 'updateSaleOfferPrice']);
    Route::get('sale/getQuoteBySaleId/{sale_id}', [SaleController::class, 'getQuoteBySaleId']);
    Route::post('sale/updateQuote', [SaleController::class, 'updateQuote']);
    Route::get('sale/updateQuoteTerms', [SaleController::class, 'updateQuoteTerms']);
    Route::post('sale/updateShippingPrice', [SaleController::class, 'updateShippingPrice']);
    Route::post('sale/addCancelSaleNote', [SaleController::class, 'addCancelSaleNote']);
    Route::get('sale/getRfqDetailById/{offer_id}', [SaleController::class, 'getRfqDetailById']);
    Route::post('sale/updateRfqDetail', [SaleController::class, 'updateRfqDetail']);

    Route::get('sale/getSaleTypes', [SaleController::class, 'getSaleTypes']);

    Route::get('sale/removeCancelledSales', [SaleController::class, 'removeCancelledSales']);

    Route::get('sale/getPackingableProductsBySaleId/{sale_id}', [SaleController::class, 'getPackingableProductsBySaleId']);
    Route::get('sale/getPackingListsBySaleId/{sale_id}', [SaleController::class, 'getPackingListsBySaleId']);
    Route::get('sale/getPackingListProductsById/{packing_list_id}', [SaleController::class, 'getPackingListProductsById']);
    Route::post('sale/addPackingList', [SaleController::class, 'addPackingList']);
    Route::get('sale/deletePackingList/{packing_list_id}', [SaleController::class, 'deletePackingList']);
    Route::post('sale/updatePackingListNote', [SaleController::class, 'updatePackingListNote']);


    Route::get('sale/getSaleNotes/{sale_id}', [SaleController::class, 'getSaleNotes']);
    Route::post('sale/addSaleNote', [SaleController::class, 'addSaleNote']);

    Route::get('sale/getLastCurrencyLog', [SaleController::class, 'getLastCurrencyLog']);
    Route::get('sale/getCheckSaleCurrencyLog/{request_id}', [SaleController::class, 'getCheckSaleCurrencyLog']);
    Route::post('sale/addSaleCurrencyLog/{request_id}', [SaleController::class, 'addSaleCurrencyLog']);
    Route::post('sale/updateSaleCurrencyLogOnPI/{request_id}', [SaleController::class, 'updateSaleCurrencyLogOnPI']);
    Route::get('sale/getSaleByRequestId/{request_id}', [SaleController::class, 'getSaleByRequestId']);
    Route::get('sale/getCurrencyLogs', [SaleController::class, 'getCurrencyLogs']);
    Route::get('sale/updateOldCurrencies', [SaleController::class, 'updateOldCurrencies']);
    Route::post('sale/addCurrencyLog', [SaleController::class, 'addCurrencyLog']);
    Route::get('sale/deleteCurrencyLog/{log_id}', [SaleController::class, 'deleteCurrencyLog']);

    Route::get('sale/getSellingProcess/{sale_id}', [SaleController::class, 'getSellingProcess']);

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
    Route::get('sale/getSaleSummary/{sale_id}', [SaleController::class, 'getSaleSummary']);

    //Documents
    Route::get('sale/getDocuments/{sale_id}', [SaleController::class, 'getDocuments']);

    //Pinned
    Route::get('sale/addSalePin/{sale_id}', [SaleController::class, 'addSalePin']);
    Route::get('sale/deleteSalePin/{sale_id}', [SaleController::class, 'deleteSalePin']);

    //Contact
    Route::get('contact/getContacts', [ContactController::class, 'getContacts']);
    Route::get('contact/getContactById/{contact_id}', [ContactController::class, 'getContactById']);
    Route::post('contact/updateContact', [ContactController::class, 'updateContact']);
    Route::get('contact/deleteContact/{contact_id}', [ContactController::class, 'deleteContact']);


    //Status
    Route::get('status/getStatuses', [StatusController::class, 'getStatuses']);
    Route::get('status/getChangeableStatuses', [StatusController::class, 'getChangeableStatuses']);
    Route::get('status/getPackingStatuses', [StatusController::class, 'getPackingStatuses']);
    Route::get('status/getAuthorizeStatuses/{user_id}', [StatusController::class, 'getAuthorizeStatuses']);

    //Product
    Route::get('product/getProducts', [ProductController::class, 'getProducts']);
    Route::get('product/getProductById/{id}', [ProductController::class, 'getProductById']);
    Route::post('product/addImportedProducts', [ProductController::class, 'addImportedProducts']);
    Route::post('product/addProduct', [ProductController::class, 'addProduct']);
    Route::post('product/updateProduct/{id}', [ProductController::class, 'updateProduct']);
    Route::get('product/deleteProduct/{id}', [ProductController::class, 'deleteProduct']);
    Route::post('product/updateProductName/{id}', [ProductController::class, 'updateProductName']);


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

    //Dashboard
    Route::get('dashboard/getTotalSales/{owner_id}', [DashboardController::class, 'getTotalSales']);
    Route::get('dashboard/getLastMonthSales/{owner_id}', [DashboardController::class, 'getLastMonthSales']);
    Route::get('dashboard/getLastMonthSalesByAdmin/{admin_id}', [DashboardController::class, 'getLastMonthSalesByAdmin']);

    Route::get('dashboard/getMonthlySales', [DashboardController::class, 'getMonthlySales']);
    Route::get('dashboard/getApprovedSales/{owner_id}', [DashboardController::class, 'getApprovedSales']);
    Route::get('dashboard/getPotentialSales/{owner_id}', [DashboardController::class, 'getPotentialSales']);
    Route::get('dashboard/getCancelledSales/{owner_id}', [DashboardController::class, 'getCancelledSales']);
    Route::get('dashboard/getCompletedSales/{owner_id}', [DashboardController::class, 'getCompletedSales']);

    Route::get('dashboard/getMonthlySalesLastTwelveMonths', [DashboardController::class, 'getMonthlySalesLastTwelveMonths']);
    Route::get('dashboard/getMonthlyApprovedSalesLastTwelveMonths/{owner_id}', [DashboardController::class, 'getMonthlyApprovedSalesLastTwelveMonths']);
    Route::get('dashboard/getMonthlyApprovedSalesLastTwelveMonthsByAdmin/{admin_id}', [DashboardController::class, 'getMonthlyApprovedSalesLastTwelveMonthsByAdmin']);
    Route::get('dashboard/getMonthlyCompletedSalesLastTwelveMonths/{owner_id}', [DashboardController::class, 'getMonthlyCompletedSalesLastTwelveMonths']);
    Route::get('dashboard/getMonthlyCompletedSalesLastTwelveMonthsByAdmin/{admin_id}', [DashboardController::class, 'getMonthlyCompletedSalesLastTwelveMonthsByAdmin']);
    Route::get('dashboard/getMonthlyPotentialSalesLastTwelveMonths/{owner_id}', [DashboardController::class, 'getMonthlyPotentialSalesLastTwelveMonths']);
    Route::get('dashboard/getMonthlyPotentialSalesLastTwelveMonthsByAdmin/{admin_id}', [DashboardController::class, 'getMonthlyPotentialSalesLastTwelveMonthsByAdmin']);
    Route::get('dashboard/getMonthlyCancelledSalesLastTwelveMonths/{owner_id}', [DashboardController::class, 'getMonthlyCancelledSalesLastTwelveMonths']);
    Route::get('dashboard/getMonthlyCancelledSalesLastTwelveMonthsByAdmin/{admin_id}', [DashboardController::class, 'getMonthlyCancelledSalesLastTwelveMonthsByAdmin']);

    Route::get('dashboard/getMonthlyApprovedSalesLastTwelveMonthsByAdmins/{owner_id}', [DashboardController::class, 'getMonthlyApprovedSalesLastTwelveMonthsByAdmins']);
    Route::get('dashboard/getMonthlyApprovedSalesLastTwelveMonthsByAdminId/{admin_id}', [DashboardController::class, 'getMonthlyApprovedSalesLastTwelveMonthsByAdminId']);


    Route::get('dashboard/getDashboardStats/{owner_id}', [DashboardController::class, 'getDashboardStats']);
    Route::get('dashboard/getMostValuableCustomers', [DashboardController::class, 'getMostValuableCustomers']);
    Route::get('dashboard/getCustomerByNotSaleLongTimes', [DashboardController::class, 'getCustomerByNotSaleLongTimes']);
    Route::get('dashboard/getCustomerByNotSale', [DashboardController::class, 'getCustomerByNotSale']);
    Route::get('dashboard/getTotalProfitRate/{owner_id}', [DashboardController::class, 'getTotalProfitRate']);
    Route::get('dashboard/getMonthlyProfitRates/{owner_id}', [DashboardController::class, 'getMonthlyProfitRates']);
    Route::get('dashboard/getMonthlyTurningRates/{owner_id}', [DashboardController::class, 'getMonthlyTurningRates']);
    Route::get('dashboard/getMonthlyProfitRatesLastTwelveMonths/{owner_id}', [DashboardController::class, 'getMonthlyProfitRatesLastTwelveMonths']);
    Route::get('dashboard/getBestSalesLastNinetyDays/{owner_id}', [DashboardController::class, 'getBestSalesLastNinetyDays']);


    Route::get('pdf/getGeneratePDF/{owner_id}/{sale_id}', [PdfController::class, 'getGeneratePDF']);
    Route::get('pdf/getGenerateQuatotionPDF/{lang}/{owner_id}/{sale_id}', [PdfController::class, 'getGenerateQuatotionPDF']);
    Route::get('pdf/getGenerateOrderConfirmationPDF/{lang}/{owner_id}/{sale_id}/{bank_id}', [PdfController::class, 'getGenerateOrderConfirmationPDF']);
    Route::get('pdf/getGenerateProformaInvoicePDF/{lang}/{owner_id}/{sale_id}/{bank_id}/{target}', [PdfController::class, 'getGenerateProformaInvoicePDF']);
    Route::get('pdf/getGenerateInvoicePDF/{lang}/{owner_id}/{sale_id}/{bank_id}', [PdfController::class, 'getGenerateInvoicePDF']);
    Route::get('pdf/getGeneratePackingListInvoicePDF/{lang}/{owner_id}/{packing_list_id}/{bank_id}', [PdfController::class, 'getGeneratePackingListInvoicePDF']);
    Route::get('pdf/getGeneratePurchasingOrderPDF/{lang}/{owner_id}/{sale_id}/{offer_id}', [PdfController::class, 'getGeneratePurchasingOrderPDF']);
    Route::get('pdf/getGenerateRfqPDF/{lang}/{owner_id}/{offer_id}', [PdfController::class, 'getGenerateRfqPDF']);
    Route::get('pdf/getGeneratePackingListPDF/{lang}/{owner_id}/{packing_list_id}', [PdfController::class, 'getGeneratePackingListPDF']);


    Route::get('pdf/getGenerateSaleSummaryPDF/{sale_id}', [PdfController::class, 'getGenerateSaleSummaryPDF']);



    //Notify
    Route::post('notify/addNotifySetting', [NotifyController::class, 'addNotifySetting']);
    Route::post('notify/updateNotifySetting', [NotifyController::class, 'updateNotifySetting']);
    Route::get('notify/deleteNotifySetting/{setting_id}', [NotifyController::class, 'deleteNotifySetting']);
    Route::get('notify/getNotifySettings', [NotifyController::class, 'getNotifySettings']);
    Route::get('notify/getNotifySettingById/{setting_id}', [NotifyController::class, 'getNotifySettingById']);
    Route::get('notify/getReadNotifyById/{notify_id}', [NotifyController::class, 'getReadNotifyById']);
    Route::get('notify/getReadAllNotifyByUserId/{user_id}', [NotifyController::class, 'getReadAllNotifyByUserId']);
    Route::get('notify/getNotReadNotifyCountByUserId/{user_id}', [NotifyController::class, 'getNotReadNotifyCountByUserId']);
    Route::get('notify/getNotifiesByUserId/{user_id}', [NotifyController::class, 'getNotifiesByUserId']);


    //Chat
    Route::post("/sockets/connect", [SocketsController::class, "connect"]);

    Route::post('/companyChat/sendMessage', [ChatController::class, 'sendPublicChatMessage']);
    Route::get('/companyChat/getPublicChatMessages/{page}', [ChatController::class, 'getPublicChatMessages']);




});

