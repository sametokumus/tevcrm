<?php
include('header.php');
$extra_js='
<script src="services/coupons.js"></script>
';
?>



            <!--app-content open-->
            <div class="main-content app-content mt-0">
                <div class="side-app">

                    <!-- CONTAINER -->
                    <div class="main-container container-fluid">

                        <!-- PAGE-HEADER -->
                        <div class="page-header">
                            <h1 class="page-title">Kuponlar</h1>
                            <div>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Kuponlar</li>
                                </ol>
                            </div>
                        </div>
                        <!-- PAGE-HEADER END -->

                        <form method="post" action="#" id="add_coupon_form">

                            <!-- ROW-1 OPEN -->
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <div class="card-title">Kupon Ekle</div>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="row mb-4">
                                                        <label class="col-md-5 form-label">Kupon Kodu :</label>
                                                        <div class="col-md-7">
                                                            <input type="text" class="form-control" id="coupon_code" required>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-4">
                                                        <label class="col-md-5 form-label">İndirim Türü :</label>
                                                        <div class="col-md-7">
                                                            <select name="type" class="form-control form-select" id="coupon_type" required>
                                                                <option>Tür Seçiniz</option>
                                                                <option value="1">Tam İndirim</option>
                                                                <option value="2">Yüzdelik İndirim</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-4">
                                                        <label class="col-md-5 form-label">İndirim Miktarı :</label>
                                                        <div class="col-md-7">
                                                            <input type="text" class="form-control" id="coupon_discount" required>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-4">
                                                        <label class="col-md-5 form-label">Başlangıç Tarihi :</label>
                                                        <div class="col-md-7">
                                                            <div class="input-group">
                                                                <div class="input-group-text">
                                                                    <i class="fa fa-calendar tx-14 lh-0 op-6"></i>
                                                                </div>
                                                                <input class="form-control fc-datepicker" id="coupon_start_date" type="text">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-4">
                                                        <label class="col-md-5 form-label">Bitiş Tarihi :</label>
                                                        <div class="col-md-7">
                                                            <div class="input-group">
                                                                <div class="input-group-text">
                                                                    <i class="fa fa-calendar tx-14 lh-0 op-6"></i>
                                                                </div>
                                                                <input class="form-control fc-datepicker" id="coupon_end_date" type="text">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="row mb-4">
                                                        <label class="col-md-5 form-label">Toplam Adet :</label>
                                                        <div class="col-md-7">
                                                            <input type="text" class="form-control" id="coupon_count_of_uses" required>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-4">
                                                        <label class="col-md-5 form-label">Kullanılan Adet :</label>
                                                        <div class="col-md-7">
                                                            <input type="text" class="form-control" id="coupon_count_of_used" required>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-4">
                                                        <label class="col-md-5 form-label">Kupon Kullanıcı Türü :</label>
                                                        <div class="col-md-7">
                                                            <select name="coupon_user_type" class="form-control form-select" id="coupon_user_type" required>
                                                                <option value="1">Kullanıcı</option>
                                                                <option value="2">Kullanıcı Grubu</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-4">
                                                        <label class="col-md-5 form-label">Kullanıcı Id (0->Tüm Kullanıcılar) :</label>
                                                        <div class="col-md-7">
                                                            <input type="text" class="form-control" id="coupon_user_id" value="0" required>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-4">
                                                        <label class="col-md-5 form-label">Kullanıcı Grubu :</label>
                                                        <div class="col-md-7">
                                                            <select name="coupon_user_group" class="form-control form-select" id="coupon_user_group" required>
                                                                <option value="0">Grup Seçiniz</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="card-footer">
                                            <!--Row-->
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <button type="submit" class="btn btn-primary">Ekle</button>
                                                </div>
                                            </div>
                                            <!--End Row-->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /ROW-1 CLOSED -->

                        </form>

                        <!-- Row -->
                        <div class="row row-sm">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Mevcut Kuponlar</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table id="coupon-datatable" class="table table-bordered text-nowrap key-buttons border-bottom">
                                                <thead>
                                                <tr>
                                                    <th class="border-bottom-0" data-priority="1">Kod</th>
                                                    <th class="border-bottom-0">İndirim Türü</th>
                                                    <th class="border-bottom-0">İndirim Miktarı</th>
                                                    <th class="border-bottom-0">Başlangıç T.</th>
                                                    <th class="border-bottom-0">Bitiş T.</th>
                                                    <th class="border-bottom-0">Toplam Adet</th>
                                                    <th class="border-bottom-0">Kullanılan Adet</th>
                                                    <th class="border-bottom-0">Kupon Kullanıcı Türü</th>
                                                    <th class="border-bottom-0">Kullanıcı</th>
                                                    <th class="border-bottom-0">Kullanıcı Grubu</th>
                                                    <th class="border-bottom-0" data-priority="2">İşlemler</th>
                                                </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Row -->

                    </div>
                    <!-- CONTAINER END -->
                </div>
            </div>
            <!--app-content close-->

    <div class="modal fade" id="updateCouponModal" tabindex="-1" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">

                <form method="post" action="#" id="update_coupon_form">
                    <div class="modal-header">
                        <h5 class="modal-title">Kupon Güncelle</h5>
                        <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="row mb-4">
                            <label class="col-md-5 form-label">Kupon Kodu :</label>
                            <div class="col-md-7">
                                <input type="text" class="form-control" id="update_coupon_code" required>
                                <input type="hidden" class="form-control" id="update_coupon_id" required>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <label class="col-md-5 form-label">İndirim Türü :</label>
                            <div class="col-md-7">
                                <select name="type" class="form-control form-select" id="update_coupon_type" required>
                                    <option>Tür Seçiniz</option>
                                    <option value="1">Tam İndirim</option>
                                    <option value="2">Yüzdelik İndirim</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <label class="col-md-5 form-label">İndirim Miktarı :</label>
                            <div class="col-md-7">
                                <input type="text" class="form-control" id="update_coupon_discount" required>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <label class="col-md-5 form-label">Başlangıç Tarihi :</label>
                            <div class="col-md-7">
                                <div class="input-group">
                                    <div class="input-group-text">
                                        <i class="fa fa-calendar tx-14 lh-0 op-6"></i>
                                    </div>
                                    <input class="form-control fc-datepicker" id="update_coupon_start_date" type="text">
                                </div>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <label class="col-md-5 form-label">Bitiş Tarihi :</label>
                            <div class="col-md-7">
                                <div class="input-group">
                                    <div class="input-group-text">
                                        <i class="fa fa-calendar tx-14 lh-0 op-6"></i>
                                    </div>
                                    <input class="form-control fc-datepicker" id="update_coupon_end_date" type="text">
                                </div>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <label class="col-md-5 form-label">Toplam Adet :</label>
                            <div class="col-md-7">
                                <input type="text" class="form-control" id="update_coupon_count_of_uses" required>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <label class="col-md-5 form-label">Kullanılan Adet :</label>
                            <div class="col-md-7">
                                <input type="text" class="form-control" id="update_coupon_count_of_used" required>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <label class="col-md-5 form-label">Kupon Kullanıcı Türü :</label>
                            <div class="col-md-7">
                                <select name="update_coupon_user_type" class="form-control form-select" id="update_coupon_user_type" required>
                                    <option value="1">Kullanıcı</option>
                                    <option value="2">Kullanıcı Grubu</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <label class="col-md-5 form-label">Kullanıcı Id :</label>
                            <div class="col-md-7">
                                <input type="text" class="form-control" id="update_coupon_user_id" required>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <label class="col-md-5 form-label">Kullanıcı Grubu :</label>
                            <div class="col-md-7">
                                <select name="update_coupon_user_group" class="form-control form-select" id="update_coupon_user_group" required>
                                    <option value="0">Grup Seçiniz</option>
                                </select>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Vazgeç</button>
                        <button type="submit" class="btn btn-primary">Kaydet</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

<?php include('footer.php'); ?>