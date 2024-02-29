<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Contact;
use App\Models\Expense;
use App\Models\Sale;
use App\Models\SaleOffer;
use App\Models\SaleTransaction;
use App\Models\SaleTransactionPayment;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class AccountingDashboardController extends Controller
{
    public function getAccountingStats()
    {
        try {

            $stats = array();

            //Yapılan Ödemeler
            $total_payments = SaleTransactionPayment::query()
                ->leftJoin('sale_transactions', 'sale_transactions.transaction_id', '=', 'sale_transaction_payments.transaction_id')
                ->leftJoin('packing_lists', 'packing_lists.packing_list_id', '=', 'sale_transactions.packing_list_id')
                ->leftJoin('sales', 'sales.sale_id', '=', 'sale_transactions.sale_id')
                ->where('sales.active',1)
                ->where('packing_lists.active',1)
                ->where('sale_transaction_payments.active',1)
                ->where('sale_transaction_payments.payment_status_id',2)
                ->get();

            $total_try_price = 0;
            $total_usd_price = 0;
            $total_eur_price = 0;
            $total_gbp_price = 0;
            $total_payment_tax = 0;

            foreach ($total_payments as $item){
                $transaction = SaleTransaction::query()->where('transaction_id', $item->transaction_id)->first();
                $sale = Sale::query()->where('sale_id', $transaction->sale_id)->first();

                $payment_tax = $item->payment_tax;
                if ($payment_tax == null){
                    $payment_tax = 0;
                }

                if ($item->currency == 'TRY'){
                    $total_payment_tax += $payment_tax;
                    $total_try_price += $item->payment_price;
                    $total_usd_price += $item->payment_price / $sale->usd_rate;
                    $total_eur_price += $item->payment_price / $sale->eur_rate;
                    $total_gbp_price += $item->payment_price / $sale->gbp_rate;
                }else if ($item->currency == 'USD'){
                    $total_payment_tax += $payment_tax * $sale->usd_rate;
                    $total_usd_price += $item->payment_price;
                    $total_try_price += $item->payment_price * $sale->usd_rate;
                    $total_eur_price += $item->payment_price / $sale->eur_rate * $sale->usd_rate;
                    $total_gbp_price += $item->payment_price / $sale->gbp_rate * $sale->usd_rate;
                }else if ($item->currency == 'EUR'){
                    $total_payment_tax += $payment_tax * $sale->eur_rate;
                    $total_eur_price += $item->payment_price;
                    $total_try_price += $item->payment_price * $sale->eur_rate;
                    $total_usd_price += $item->payment_price / $sale->usd_rate * $sale->eur_rate;
                    $total_gbp_price += $item->payment_price / $sale->gbp_rate * $sale->eur_rate;
                }else if ($item->currency == 'GBP'){
                    $total_payment_tax += $payment_tax * $sale->gbp_rate;
                    $total_gbp_price += $item->payment_price;
                    $total_try_price += $item->payment_price * $sale->gbp_rate;
                    $total_eur_price += $item->payment_price / $sale->eur_rate * $sale->gbp_rate;
                    $total_usd_price += $item->payment_price / $sale->usd_rate * $sale->gbp_rate;
                }


            }

            $total = array();
            $total['try_sale'] = number_format($total_try_price, 2,".","");
            $total['usd_sale'] = number_format($total_usd_price, 2,".","");
            $total['eur_sale'] = number_format($total_eur_price, 2,".","");
            $total['gbp_sale'] = number_format($total_gbp_price, 2,".","");
            $total['payment_tax'] = number_format($total_payment_tax, 2,".","");
            $total['payment_total'] = number_format($total_try_price + $total_payment_tax, 2,".","");

            //Bekleyen Ödemeler
            $pending_payments = SaleTransactionPayment::query()
                ->leftJoin('sale_transactions', 'sale_transactions.transaction_id', '=', 'sale_transaction_payments.transaction_id')
                ->leftJoin('packing_lists', 'packing_lists.packing_list_id', '=', 'sale_transactions.packing_list_id')
                ->leftJoin('sales', 'sales.sale_id', '=', 'sale_transactions.sale_id')
                ->where('sales.active',1)
                ->where('packing_lists.active',1)
                ->where('sale_transaction_payments.active',1)
                ->where('sale_transaction_payments.payment_status_id',1)
                ->get();

            $pending_try_price = 0;
            $pending_usd_price = 0;
            $pending_eur_price = 0;
            $pending_gbp_price = 0;
            $pending_payment_tax = 0;

            foreach ($pending_payments as $item){
                $transaction = SaleTransaction::query()->where('transaction_id', $item->transaction_id)->first();
                $sale = Sale::query()->where('sale_id', $transaction->sale_id)->first();

                $payment_tax = $item->payment_tax;
                if ($payment_tax == null){
                    $payment_tax = 0;
                }

                if ($item->currency == 'TRY'){
                    $pending_payment_tax += $payment_tax;
                    $pending_try_price += $item->payment_price;
                    $pending_usd_price += $item->payment_price / $sale->usd_rate;
                    $pending_eur_price += $item->payment_price / $sale->eur_rate;
                    $pending_gbp_price += $item->payment_price / $sale->gbp_rate;
                }else if ($item->currency == 'USD'){
                    $pending_payment_tax += $payment_tax * $sale->usd_rate;
                    $pending_usd_price += $item->payment_price;
                    $pending_try_price += $item->payment_price * $sale->usd_rate;
                    $pending_eur_price += $item->payment_price / $sale->eur_rate * $sale->usd_rate;
                    $pending_gbp_price += $item->payment_price / $sale->gbp_rate * $sale->usd_rate;
                }else if ($item->currency == 'EUR'){
                    $pending_payment_tax += $payment_tax * $sale->eur_rate;
                    $pending_eur_price += $item->payment_price;
                    $pending_try_price += $item->payment_price * $sale->eur_rate;
                    $pending_usd_price += $item->payment_price / $sale->usd_rate * $sale->eur_rate;
                    $pending_gbp_price += $item->payment_price / $sale->gbp_rate * $sale->eur_rate;
                }else if ($item->currency == 'GBP'){
                    $pending_payment_tax += $payment_tax * $sale->gbp_rate;
                    $pending_gbp_price += $item->payment_price;
                    $pending_try_price += $item->payment_price * $sale->gbp_rate;
                    $pending_eur_price += $item->payment_price / $sale->eur_rate * $sale->gbp_rate;
                    $pending_usd_price += $item->payment_price / $sale->usd_rate * $sale->gbp_rate;
                }

            }

            $pending = array();
            $pending['try_sale'] = number_format($pending_try_price, 2,".","");
            $pending['usd_sale'] = number_format($pending_usd_price, 2,".","");
            $pending['eur_sale'] = number_format($pending_eur_price, 2,".","");
            $pending['gbp_sale'] = number_format($pending_gbp_price, 2,".","");
            $pending['payment_tax'] = number_format($pending_payment_tax, 2,".","");
            $pending['payment_total'] = number_format($pending_try_price + $pending_payment_tax, 2,".","");

            //Geciken Ödemeler
            $late_payments = SaleTransactionPayment::query()
                ->leftJoin('sale_transactions', 'sale_transactions.transaction_id', '=', 'sale_transaction_payments.transaction_id')
                ->leftJoin('packing_lists', 'packing_lists.packing_list_id', '=', 'sale_transactions.packing_list_id')
                ->leftJoin('sales', 'sales.sale_id', '=', 'sale_transactions.sale_id')
                ->where('sales.active',1)
                ->where('packing_lists.active',1)
                ->where('sale_transaction_payments.active',1)
                ->where('sale_transaction_payments.payment_status_id',1)
                ->where('sale_transaction_payments.due_date', '<', Carbon::now()->format('Y-m-d'))
                ->get();

            $late_try_price = 0;
            $late_usd_price = 0;
            $late_eur_price = 0;
            $late_gbp_price = 0;
            $late_payment_tax = 0;

            foreach ($late_payments as $item){
                $transaction = SaleTransaction::query()->where('transaction_id', $item->transaction_id)->first();
                $sale = Sale::query()->where('sale_id', $transaction->sale_id)->first();

                $payment_tax = $item->payment_tax;
                if ($payment_tax == null){
                    $payment_tax = 0;
                }

                if ($item->currency == 'TRY'){
                    $late_payment_tax += $payment_tax;
                    $late_try_price += $item->payment_price;
                    $late_usd_price += $item->payment_price / $sale->usd_rate;
                    $late_eur_price += $item->payment_price / $sale->eur_rate;
                    $late_gbp_price += $item->payment_price / $sale->gbp_rate;
                }else if ($item->currency == 'USD'){
                    $late_payment_tax += $payment_tax * $sale->usd_rate;
                    $late_usd_price += $item->payment_price;
                    $late_try_price += $item->payment_price * $sale->usd_rate;
                    $late_eur_price += $item->payment_price / $sale->eur_rate * $sale->usd_rate;
                    $late_gbp_price += $item->payment_price / $sale->gbp_rate * $sale->usd_rate;
                }else if ($item->currency == 'EUR'){
                    $late_payment_tax += $payment_tax * $sale->eur_rate;
                    $late_eur_price += $item->payment_price;
                    $late_try_price += $item->payment_price * $sale->eur_rate;
                    $late_usd_price += $item->payment_price / $sale->usd_rate * $sale->eur_rate;
                    $late_gbp_price += $item->payment_price / $sale->gbp_rate * $sale->eur_rate;
                }else if ($item->currency == 'GBP'){
                    $late_payment_tax += $payment_tax * $sale->gbp_rate;
                    $late_gbp_price += $item->payment_price;
                    $late_try_price += $item->payment_price * $sale->gbp_rate;
                    $late_eur_price += $item->payment_price / $sale->eur_rate * $sale->gbp_rate;
                    $late_usd_price += $item->payment_price / $sale->usd_rate * $sale->gbp_rate;
                }

            }

            $late = array();
            $late['try_sale'] = number_format($late_try_price, 2,".","");
            $late['usd_sale'] = number_format($late_usd_price, 2,".","");
            $late['eur_sale'] = number_format($late_eur_price, 2,".","");
            $late['gbp_sale'] = number_format($late_gbp_price, 2,".","");
            $late['payment_tax'] = number_format($late_payment_tax, 2,".","");
            $late['payment_total'] = number_format($late_try_price + $late_payment_tax, 2,".","");


            //Karlılık
            $sale_items = Sale::query()
                ->leftJoin('statuses', 'statuses.id', '=', 'sales.status_id')
                ->selectRaw('sales.*, statuses.period as period')
                ->where('sales.active',1)
                ->whereRaw("(statuses.period = 'completed' OR statuses.period = 'approved')")
                ->get();

            $sale_total = 0;
            $offer_total = 0;
            foreach ($sale_items as $sale){
                $sale_offers = SaleOffer::query()->where('sale_id', $sale->sale_id)->where('active', 1)->get();
                foreach ($sale_offers as $sale_offer){
                    if ($sale->currency == 'TRY'){
                        $sale_total += $sale_offer->sale_price;
                        $offer_total += $sale_offer->offer_price;
                    }else if ($sale->currency == 'USD'){
                        $sale_total += $sale_offer->sale_price * $sale->usd_rate;
                        $offer_total += $sale_offer->offer_price * $sale->usd_rate;
                    }else if ($sale->currency == 'EUR'){
                        $sale_total += $sale_offer->sale_price * $sale->eur_rate;
                        $offer_total += $sale_offer->offer_price * $sale->eur_rate;
                    }else if ($sale->currency == 'GBP'){
                        $sale_total += $sale_offer->sale_price * $sale->gbp_rate;
                        $offer_total += $sale_offer->offer_price * $sale->gbp_rate;
                    }
                }

                //ek giderler
                $expenses = Expense::query()->where('sale_id', $sale->sale_id)->where('active', 1)->get();
                foreach ($expenses as $expense){
                    if ($expense->currency == $sale->currency){
                        $sale_total += $expense->price;
                    }else{
                        if ($expense->currency == 'TRY') {
                            $sc = strtolower($sale->currency);
                            $expense_price = $expense->price / $sale->{$sc.'_rate'};
                        }else{
                            if ($sale->currency == 'TRY') {
                                $ec = strtolower($expense->currency);
                                $expense_price = $expense->price * $sale->{$ec.'_rate'};
                            }else{
                                $ec = strtolower($expense->currency);
                                $sc = strtolower($sale->currency);
                                if ($sale->{$sc.'_rate'} != 0) {
                                    $expense_price = $expense->price * $sale->{$ec . '_rate'} / $sale->{$sc . '_rate'};
                                }else{
                                    $expense_price = 0;
                                }
                            }
                        }
                        $sale_total += $expense_price;
                    }
                }


            }
            $profit_rate = 100 * ($offer_total - $sale_total) / $sale_total;


            $stats['total'] = $total;
            $stats['pending'] = $pending;
            $stats['late'] = $late;
            $stats['profit_rate'] = $profit_rate;

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['stats' => $stats]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }

    public function getCashFlows()
    {
        try {
            $months = SaleTransactionPayment::query()
                ->leftJoin('sale_transactions', 'sale_transactions.transaction_id', '=', 'sale_transaction_payments.transaction_id')
                ->leftJoin('packing_lists', 'packing_lists.packing_list_id', '=', 'sale_transactions.packing_list_id')
                ->leftJoin('sales', 'sales.sale_id', '=', 'sale_transactions.sale_id')
                ->where('sales.active',1)
                ->where('packing_lists.active',1)
                ->selectRaw('YEAR(due_date) AS year, MONTH(due_date) AS month')
                ->where('sale_transaction_payments.active',1)
                ->where('sale_transaction_payments.payment_status_id',1)
                ->groupByRaw('YEAR(due_date), MONTH(due_date)')
                ->orderByRaw('YEAR(due_date) ASC, MONTH(due_date) ASC')
                ->get();

            foreach ($months as $month){

                $payments = SaleTransactionPayment::query()
                    ->leftJoin('sale_transactions', 'sale_transactions.transaction_id', '=', 'sale_transaction_payments.transaction_id')
                    ->leftJoin('packing_lists', 'packing_lists.packing_list_id', '=', 'sale_transactions.packing_list_id')
                    ->leftJoin('sales', 'sales.sale_id', '=', 'sale_transactions.sale_id')
                    ->where('sales.active',1)
                    ->where('packing_lists.active',1)
                    ->where('sale_transaction_payments.active',1)
                    ->where('sale_transaction_payments.payment_status_id',1)
                    ->whereYear('sale_transaction_payments.due_date', $month->year)
                    ->whereMonth('sale_transaction_payments.due_date', $month->month)
                    ->selectRaw('sale_transaction_payments.*')
                    ->get();

                $try_price = 0;
                $usd_price = 0;
                $eur_price = 0;
                $gbp_price = 0;

                foreach ($payments as $item){
                    $transaction = SaleTransaction::query()->where('transaction_id', $item->transaction_id)->first();
                    $item['transaction'] = $transaction;
                    $sale = Sale::query()->where('sale_id', $transaction->sale_id)->first();
                    $sale['owner'] = Contact::query()->where('id', $sale->owner_id)->first();
                    $sale['customer'] = Company::query()->where('id', $sale->customer_id)->first();
                    $item['sale'] = $sale;

                    $item['date_status'] = true;
                    $date = Carbon::now()->format('Y-m-d');
                    if ($item->due_date < $date){
                        $item['date_status'] = false;
                    }

                    if ($item->currency == 'TRY'){
                        $try_price += $item->payment_price;
                        $usd_price += $item->payment_price / $sale->usd_rate;
                        $eur_price += $item->payment_price / $sale->eur_rate;
                        $gbp_price += $item->payment_price / $sale->gbp_rate;
                    }else if ($item->currency == 'USD'){
                        $usd_price += $item->payment_price;
                        $try_price += $item->payment_price * $sale->usd_rate;
                        $eur_price += $item->payment_price / $sale->eur_rate * $sale->usd_rate;
                        $gbp_price += $item->payment_price / $sale->gbp_rate * $sale->usd_rate;
                    }else if ($item->currency == 'EUR'){
                        $eur_price += $item->payment_price;
                        $try_price += $item->payment_price * $sale->eur_rate;
                        $usd_price += $item->payment_price / $sale->usd_rate * $sale->eur_rate;
                        $gbp_price += $item->payment_price / $sale->gbp_rate * $sale->eur_rate;
                    }else if ($item->currency == 'GBP'){
                        $gbp_price += $item->payment_price;
                        $try_price += $item->payment_price * $sale->gbp_rate;
                        $usd_price += $item->payment_price / $sale->usd_rate * $sale->gbp_rate;
                        $eur_price += $item->payment_price / $sale->eur_rate * $sale->gbp_rate;
                    }

                }

                $price = array();
                $price['try_sale'] = number_format($try_price, 2,".","");
                $price['usd_sale'] = number_format($usd_price, 2,".","");
                $price['eur_sale'] = number_format($eur_price, 2,".","");
                $price['gbp_sale'] = number_format($gbp_price, 2,".","");

                $month['prices'] = $price;
                $month['payments'] = $payments;

            }



            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['months' => $months]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }

    public function getCashFlowPayments()
    {
        try {
            $payments = SaleTransactionPayment::query()
                ->leftJoin('sale_transactions', 'sale_transactions.transaction_id', '=', 'sale_transaction_payments.transaction_id')
                ->leftJoin('packing_lists', 'packing_lists.packing_list_id', '=', 'sale_transactions.packing_list_id')
                ->leftJoin('payment_types', 'payment_types.id', '=', 'sale_transaction_payments.payment_type')
                ->leftJoin('payment_methods', 'payment_methods.id', '=', 'sale_transaction_payments.payment_method')
                ->leftJoin('sales', 'sales.sale_id', '=', 'sale_transactions.sale_id')
                ->where('sales.active',1)
                ->where('packing_lists.active',1)
                ->where('sale_transaction_payments.active',1)
                ->selectRaw('sale_transaction_payments.*, payment_methods.name as payment_method_name, payment_types.name as payment_type_name')
                ->get();



                foreach ($payments as $item){

                    $transaction = SaleTransaction::query()->where('transaction_id', $item->transaction_id)->first();
                    $item['transaction'] = $transaction;
                    $sale = Sale::query()->where('sale_id', $transaction->sale_id)->first();
                    $sale['owner'] = Contact::query()->where('id', $sale->owner_id)->first();
                    $sale['customer'] = Company::query()->where('id', $sale->customer_id)->first();
                    $item['sale'] = $sale;

                    $date = Carbon::now()->format('Y-m-d');

                    $item['date_status'] = true;
                    if ($item->due_date < $date){
                        $item['date_status'] = false;
                    }

                    $item['date_message'] = '';
                    if ($item->payment_status_id == 1) {
                        if ($item->due_date < $date) {
                            $date1 = Carbon::createFromFormat('Y-m-d', $date);
                            $date2 = Carbon::createFromFormat('Y-m-d', $item->due_date);
                            $differenceInDays = $date1->diffInDays($date2);
                            $item['date_message'] = 'Ödeme ' . $differenceInDays . ' gün gecikmede';
                        } else if ($item->due_date == $date) {
                            $item['date_message'] = 'Son ödeme günü';
                        } else if ($item->due_date > $date) {
                            $date1 = Carbon::createFromFormat('Y-m-d', $date);
                            $date2 = Carbon::createFromFormat('Y-m-d', $item->due_date);
                            $differenceInDays = $date1->diffInDays($date2);
                            $item['date_message'] = 'Son ödeme tarihine ' . $differenceInDays . ' gün kaldı';
                        }
                    }

                    if ($item->currency == 'TRY'){
                        $try_price = $item->payment_price;
                        $usd_price = $item->payment_price / $sale->usd_rate;
                        $eur_price = $item->payment_price / $sale->eur_rate;
                        $gbp_price = $item->payment_price / $sale->gbp_rate;
                    }else if ($item->currency == 'USD'){
                        $usd_price = $item->payment_price;
                        $try_price = $item->payment_price * $sale->usd_rate;
                        $eur_price = $item->payment_price / $sale->eur_rate * $sale->usd_rate;
                        $gbp_price = $item->payment_price / $sale->gbp_rate * $sale->usd_rate;
                    }else if ($item->currency == 'EUR'){
                        $eur_price = $item->payment_price;
                        $try_price = $item->payment_price * $sale->eur_rate;
                        $usd_price = $item->payment_price / $sale->usd_rate * $sale->eur_rate;
                        $gbp_price = $item->payment_price / $sale->gbp_rate * $sale->eur_rate;
                    }else if ($item->currency == 'GBP'){
                        $gbp_price = $item->payment_price;
                        $try_price = $item->payment_price * $sale->gbp_rate;
                        $usd_price = $item->payment_price / $sale->usd_rate * $sale->gbp_rate;
                        $eur_price = $item->payment_price / $sale->eur_rate * $sale->gbp_rate;
                    }

                    $price = array();
                    $price['try_sale'] = number_format($try_price, 2,".","");
                    $price['usd_sale'] = number_format($usd_price, 2,".","");
                    $price['eur_sale'] = number_format($eur_price, 2,".","");
                    $price['gbp_sale'] = number_format($gbp_price, 2,".","");
                    $item['prices'] = $price;

                }







            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['payments' => $payments]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }
}
