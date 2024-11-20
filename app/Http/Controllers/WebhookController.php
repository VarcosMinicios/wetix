<?php

namespace App\Http\Controllers;

use App\Http\Controllers\FrontEnd\Event\BookingController;
use App\Models\CheckoutData;
use App\Models\Earning;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    public function handleAsaas(Request $request)
    {
        $payload = $request->all();
        $event = $payload['event'];

        if ($event === 'PAYMENT_CONFIRMED') {
          if ($payload['payment']['status'] === 'CONFIRMED') {
            $checkoutData = CheckoutData::where('payment_id', $payload['payment']['id'])->first();
            $checkoutData->status = 'completed';

            $bookingController = new BookingController();
            $bookingInfo = $bookingController->storeData($checkoutData->toArray());

            $invoice = $bookingController->generateInvoice($bookingInfo, $checkoutData->event_id);
            //unlink qr code
            @unlink(public_path('assets/admin/qrcodes/') . $bookingInfo->booking_id . '.svg');
            //end unlink qr code

            // then, update the invoice field info in database
            $bookingInfo->update(['invoice' => $invoice]);

            //add blance to admin revinue
            $earning = Earning::first();
            $earning->total_revenue = $earning->total_revenue + $checkoutData->price + $bookingInfo->tax;
            if ($bookingInfo['organizer_id'] != null) {
              $earning->total_earning = $earning->total_earning + ($bookingInfo->tax + $bookingInfo->commission);
            } else {
              $earning->total_earning = $earning->total_earning + $checkoutData->price + $bookingInfo->tax;
            }
            $earning->save();

            //storeTransaction
            $bookingInfo['paymentStatus'] = 1;
            $bookingInfo['transcation_type'] = 1;

            storeTranscation($bookingInfo);

            //store amount to organizer
            $organizerData['organizer_id'] = $bookingInfo['organizer_id'];
            $organizerData['price'] = $checkoutData->price;
            $organizerData['tax'] = $bookingInfo->tax;
            $organizerData['commission'] = $bookingInfo->commission;
            storeOrganizer($organizerData);

            // send a mail to the customer with the invoice
            $bookingController->sendMail($bookingInfo);


            return redirect()->route('event_booking.complete', [
              'id' => $checkoutData->event_id, 'booking_id' => $bookingInfo->id
            ]);
          }
        }

        return response()->json(['message' => 'Webhook received']);
    }
}
