<?php

namespace App\Http\Controllers\FrontEnd\PaymentGateway;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FrontEnd\Event\BookingController;
use App\Models\BasicSettings\Basic;
use App\Models\CheckoutData;
use App\Models\Earning;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class AsaasController extends Controller
{

  public function bookingProcess(Request $request, $eventId)
  {
    $rules = [
      'fname' => 'required',
      'lname' => 'required',
      'email' => 'required',
      'cpf' => 'required',
      'phone' => 'required',
      'country' => 'required',
      'address' => 'required',
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator)->withInput();
    }

    $currencyInfo = $this->getCurrencyInfo();
    $total = Session::get('grand_total');
    $quantity = Session::get('quantity');
    $discount = Session::get('discount');
    $total_early_bird_dicount = Session::get('total_early_bird_dicount');
    $basicSetting = Basic::select('commission')->first();

    $tax_amount = Session::get('tax');
    $commission_amount = ($total * $basicSetting->commission) / 100;

    if ($currencyInfo->base_currency_text !== 'USD') {
      $rate = floatval($currencyInfo->base_currency_rate);
      $convertedTotal = round(((Session::get('grand_total') + $tax_amount) / $rate), 2);
    }

    $stripeTotal = $currencyInfo->base_currency_text === 'USD' ? ($total + $tax_amount) : $convertedTotal;

    try {
//      $baseUrl = 'https://api.asaas.com/v3';
      $baseUrl = 'https://sandbox.asaas.com/api/v3';

      $response = Http::withHeaders([
        'access_token' => '$aact_YTU5YTE0M2M2N2I4MTliNzk0YTI5N2U5MzdjNWZmNDQ6OjAwMDAwMDAwMDAwMDAwOTQ1ODk6OiRhYWNoX2JkZDdiMGM1LTI3M2YtNDY0ZS04ODhkLTA2NzFhY2VlOWQyYg=='
      ])
        ->get("$baseUrl/customers", [
          'email' => $request->email
        ])
        ->json();

      if (count($response['data']) > 0) {
        $customer_id = $response['data'][0]['id'];

        Http::withHeaders([
          'access_token' => '$aact_YTU5YTE0M2M2N2I4MTliNzk0YTI5N2U5MzdjNWZmNDQ6OjAwMDAwMDAwMDAwMDAwOTQ1ODk6OiRhYWNoX2JkZDdiMGM1LTI3M2YtNDY0ZS04ODhkLTA2NzFhY2VlOWQyYg=='
        ])
          ->put("$baseUrl/customers/$customer_id", [
            'name' => $request->fname . ' ' . $request->lname,
            'email' => $request->email,
            'phone' => $request->phone,
            'cpfCnpj' => preg_replace('/\D/', '', $request->cpf),
            'mobilePhone' => $request->phone,
            'address' => $request->address,
            'province' => $request->state,
            'postalCode' => $request->zip_code,
          ])
          ->json();

      } else {

        $response = Http::withHeaders([
          'access_token' => '$aact_YTU5YTE0M2M2N2I4MTliNzk0YTI5N2U5MzdjNWZmNDQ6OjAwMDAwMDAwMDAwMDAwOTQ1ODk6OiRhYWNoX2JkZDdiMGM1LTI3M2YtNDY0ZS04ODhkLTA2NzFhY2VlOWQyYg=='
        ])->post("$baseUrl/customers", [
          'name' => $request->fname . ' ' . $request->lname,
          'email' => $request->email,
          'phone' => $request->phone,
          'cpfCnpj' => preg_replace('/\D/', '', $request->cpf),
          'mobilePhone' => $request->phone,
          'address' => $request->address,
          'province' => $request->state,
          'postalCode' => $request->zip_code,
        ])->json();

        $customer_id = $response['id'];
      }

      try {
        try {
          $charge = Http::withHeaders([
            'access_token' => '$aact_YTU5YTE0M2M2N2I4MTliNzk0YTI5N2U5MzdjNWZmNDQ6OjAwMDAwMDAwMDAwMDAwOTQ1ODk6OiRhYWNoX2JkZDdiMGM1LTI3M2YtNDY0ZS04ODhkLTA2NzFhY2VlOWQyYg=='
          ])->post("$baseUrl/payments", [
            'customer' => $customer_id,
            'billingType' => 'UNDEFINED',
            'dueDate' => date('Y-m-d', strtotime('+2 day')),
            'value' => $stripeTotal,
            'description' => 'Payment for event booking',
          ])->json();

        } catch (\Exception $th) {
          Session::flash('error', $th->getMessage());
          return redirect()->route('check-out');
        }

        $arrData = array(
          'event_id' => $eventId,
          'charge_id' => $charge['id'],
          'price' => $total,
          'tax' => $tax_amount,
          'commission' => $commission_amount,
          'quantity' => $quantity,
          'discount' => $discount,
          'total_early_bird_dicount' => $total_early_bird_dicount,
          'currencyText' => $currencyInfo->base_currency_text,
          'currencyTextPosition' => $currencyInfo->base_currency_text_position,
          'currencySymbol' => $currencyInfo->base_currency_symbol,
          'currencySymbolPosition' => $currencyInfo->base_currency_symbol_position,
          'fname' => $request->fname,
          'lname' => $request->lname,
          'email' => $request->email,
          'phone' => $request->phone,
          'country' => $request->country,
          'state' => $request->state,
          'city' => $request->city,
          'zip_code' => $request->city,
          'address' => $request->address,
          'paymentMethod' => 'Asaas',
          'gatewayType' => 'online',
          'paymentStatus' => 'incomplete',
        );

        $checkoutData = new CheckoutData($arrData);
        $checkoutData->save();

        return redirect()->to($charge['invoiceUrl']);

      } catch (Exception $e) {
        Session::flash('error', $e->getMessage());

        return redirect()->route('event_booking.cancel', ['id' => $eventId]);
      }
    } catch (Exception $e) {
      Session::flash('error', $e->getMessage());

      return redirect()->route('event_booking.cancel', ['id' => $eventId]);
    }
  }
}
