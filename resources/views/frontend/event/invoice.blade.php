<!DOCTYPE html>
<html dir=" @if ($language->direction == 1) rtl @else ltl @endif">
@php

  $languageCode = $language->code;
  App::setLocale($languageCode);

@endphp

<head lang="{{ $language->code }}" @if ($language->direction == 1) dir="rtl" @endif>
  {{-- required meta tags --}}
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">

  {{-- title --}}
  <title>{{ 'Invoice | ' . config('app.name') }}</title>

  {{-- fav icon --}}
  <link rel="shortcut icon" type="image/png" href="{{ asset('assets/admin/img/' . $websiteInfo->favicon) }}">

  {{-- styles --}}
  <link rel="stylesheet" href="{{ asset('assets/admin/css/bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/admin/css/invoice.css') }}">
  @php
    $_15px = '15px';
    $_10px = '10px';
    $_12px = '12px';
    $b_color = '565656';
    $w_47 = '47%';
    $DejaVu_Sans = 'DejaVu Sans, serif';
  @endphp
  <style>
    * {
      font-family: {{ $DejaVu_Sans }} !important;
    }

    tr,
    td,
    th {
      font-family: {{ $DejaVu_Sans }} !important;
    }

    .h1,
    .h2,
    .h3,
    .h4,
    .h5,
    .h6 {
      font-family: {{ $DejaVu_Sans }} !important;
      font-weight: 700;
      line-height: 1.3;
      margin-bottom: 15px;
    }

    p,
    span {
      font-family: {{ $DejaVu_Sans }} !important;
    }

    .rtl {
      direction: rtl !important;
    }


    body {
      font-family: {{ $DejaVu_Sans }} !important;
      font-size: {{ $_15px }};
    }

    .border {
      border-color: #{{ $b_color }} !important;
    }

    .page-break {
      page-break-after: always;
    }

    .bg-primary {
      background: #{{ $event->ticket_background_color }} !important;
    }

    p {
      font-size: {{ $_12px }};
      margin-bottom: {{ $_10px }};
    }

    * {
      margin: 0;
      padding: 0;
      text-indent: 0;
    }

    .h1,
    .h2,
    .h3,
    .h4,
    .h5,
    .h6 {
      color: #000000;
      font-family: "Trebuchet MS", sans-serif;
      font-style: normal;
      font-weight: bold;
      text-decoration: none;
      margin-bottom: 0;
      display: block
    }

    .h1 {
      font-size: 22px;
    }

    .h2 {
      font-size: 14px;
    }

    .h3 {
      font-size: 12px;
    }

    .h4,
    .h5 {
      font-size: 12px;
    }

    .h6 {
      font-size: 10px;
    }

    .fw-0 {
      font-weight: normal
    }

    .fw-1 {
      font-weight: medium;
    }

    .fw-2 {
      font-weight: bold;
    }

    p {
      color: black;
      font-family: "Trebuchet MS", sans-serif;
      font-style: normal;
      font-weight: normal;
      text-decoration: none;
      font-size: 12px;
      margin: 0pt;
    }

    .a,
    a {
      color: #{{ $event->ticket_background_color }};
      font-family: "Trebuchet MS", sans-serif;
      font-style: normal;
      font-weight: bold;
      text-decoration: none;
    }

    .c-white {
      color: #FFFFFF;
    }

    .c-light {
      color: #afafaf
    }

    hr {
      margin-top: 0;
      margin-bottom: 0;
    }

    table,
    tbody {
      vertical-align: top;
      overflow: visible;
    }

    .img {
      max-width: 45px;
      margin-right: 10px;
    }

    .img::after {
      clear: both;
      content: ''
    }

    .rtl {
      text-align: right;
    }
  </style>
</head>

<body class="p-3">

  @php
    $position = $bookingInfo->currencyTextPosition;
    $currency = $bookingInfo->currencyText;
    $bg_color = '#' . $websiteInfo->primary_color;
  @endphp

  @if ($bookingInfo->variation != null)
    @php
      $variations = json_decode($bookingInfo->variation, true);
    @endphp
    @foreach ($variations as $variation)
      @if (!empty($event->ticket_logo))
        <p class="text-center">
          <span>
            <img class="img-fluid" alt="image"
              src="{{ asset('assets/admin/img/event_ticket_logo/' . $event->ticket_logo) }}" />
          </span>
        </p>
      @endif

      <table class="mt-2" border="0" cellspacing="0" cellpadding="0" width="100%">
        <tbody style="border: 2px solid {{ $bg_color }}">
          <tr>
            @if ($language->direction == 1)
              <td class="right p-3" style="width:16.666667%;">
                <p class="text-center">
                  <img width="111" height="111" alt="image"
                    src="{{ asset('assets/admin/qrcodes/' . $bookingInfo->booking_id . '__' . $variation['unique_id'] . '.svg') }}"
                    alt="">
                </p>
              </td>
            @else
              <td class="left"
                style="width:33.333333%; @if (empty($event->ticket_image)) background-color:{{ $bg_color }}; @endif">
                @if (!empty($event->ticket_image))
                  <img alt="image" width="100%"
                    src="{{ asset('assets/admin/img/event_ticket/' . $event->ticket_image) }}" />
                @endif
              </td>
            @endif

            <td class="center p-3" style="width:50%;">
              <p class="h2 mb-2 {{ $language->direction == 1 ? 'rtl' : '' }}">
                {{ @$eventInfo->title }}
              </p>
              <hr>
              <p class="h3 mb-2 {{ $language->direction == 1 ? 'rtl' : '' }}">
                {{ $bookingInfo->city }},
                {{ is_null($bookingInfo->state) ? '' : $bookingInfo->state . ',' }}
                {{ $bookingInfo->country }}
              </p>
              <p class="mb-2 {{ $language->direction == 1 ? 'rtl' : '' }}">
                {{ FullDateTimeInvoice($bookingInfo->event_date) }}
              </p>
              <hr>
              @php
                $ticket_content = App\Models\Event\TicketContent::where([
                    ['ticket_id', $variation['ticket_id']],
                    ['language_id', $language->id],
                ])->first();

                $ticket = App\Models\Event\Ticket::where('id', $variation['ticket_id'])
                    ->select('pricing_type')
                    ->first();
              @endphp
              <p class="h2 fw-0 mt-2 mb-2 {{ $language->direction == 1 ? 'rtl' : '' }}">
                @if ($ticket_content && $ticket->pricing_type == 'variation')
                  {{ @$ticket_content->title }}
                @endif

              </p>
              @if ($ticket_content && $ticket->pricing_type == 'variation')
                <hr>
              @endif
              <p class="h2 fw-0 mt-2 mb-2 {{ $language->direction == 1 ? 'rtl' : '' }}">
                @if ($ticket_content && $ticket->pricing_type == 'variation')
                  {{ @$ticket_content->title }} -
                @endif
                {{ $variation['name'] }}
              </p>
              <hr>
              <p class="c-light h6 mb-1 {{ $language->direction == 1 ? 'rtl' : '' }}">
                {{ __('Billing Address') }}
              </p>
              <p class="h4 fw-0 mb-2 {{ $language->direction == 1 ? 'rtl' : '' }}">
                {{ $bookingInfo->address }}
              </p>
              </p>
              <hr>
              <table border="0" cellspacing="0" cellpadding="0" width="100%">
                <tr>
                  <td class="{{ $language->direction == 1 ? 'rtl' : '' }}" style="width: 33%">
                    <p class="mt-2">
                    <p class="c-light h6 mb-1  ">
                      {{ __('BOOKING DATE') }}
                    </p>
                    <p class="h4 fw-0 mb-2">
                      {{ date_format($bookingInfo->created_at, 'M d, Y') }}
                    </p>
                    </p>
                  </td>
                  <td class="{{ $language->direction == 1 ? 'rtl' : '' }}" style="width: 33%">
                    <p class="mt-2">
                    <p class="c-light h6 mb-1 ">
                      {{ __('DURATION') }}
                    </p>
                    <p class="h4 fw-0 mb-2">
                      {{ @$bookingInfo->evnt->duration }}
                    </p>
                    </p>
                  </td>
                  <td class="{{ $language->direction == 1 ? 'rtl' : '' }}" style="width: 33%">
                    <p class="mt-2">
                    <p class="c-light h6 mb-1 ">
                      {{ __('BOOKING ID') }}
                    </p>
                    <p class="h4 fw-0 mb-2">
                      {{ '#' . $bookingInfo->booking_id }}
                    </p>
                    </p>
                  </td>
                </tr>
              </table>
              <hr>
              <table border="0" cellspacing="0" cellpadding="0" width="100%">
                <tr>
                  <td class="{{ $language->direction == 1 ? 'rtl' : '' }}" style="width: 33%">
                    <p class="mt-2">
                    <p class="c-light h6 mb-1">
                      {{ __('TAX') }}
                    </p>
                    <p class="h4 fw-0 mb-2">
                      {{ $position == 'left' ? $currency . ' ' : '' }}{{ is_null($bookingInfo->tax) ? '0.00' : $bookingInfo->tax }}{{ $position == 'right' ? ' ' . $currency : '' }}
                    </p>
                    </p>
                  </td>
                  <td class="{{ $language->direction == 1 ? 'rtl' : '' }}" style="width: 33%">
                    <p class="mt-2">
                    <p class="c-light h6 mb-1">
                      {{ __('EARLY BIRD') }}
                    </p>
                    <p class="h4 fw-0 mb-2">
                      {{ $position == 'left' ? $currency . ' ' : '' }}{{ is_null($bookingInfo->early_bird_discount) ? '0.00' : $bookingInfo->early_bird_discount }}{{ $position == 'right' ? ' ' . $currency : '' }}
                    </p>
                    </p>
                  </td>
                  <td class="{{ $language->direction == 1 ? 'rtl' : '' }}" style="width: 33%">
                    <p class="mt-2">
                    <p class="c-light h6 mb-1">
                      {{ __('COUPON') }}
                    </p>
                    <p class="h4 fw-0 mb-2">
                      {{ $position == 'left' ? $currency . ' ' : '' }}{{ is_null($bookingInfo->discount) ? '0.00' : $bookingInfo->discount }}{{ $position == 'right' ? ' ' . $currency : '' }}
                    </p>
                    </p>
                  </td>
                </tr>
              </table>
              <hr>
              <table border="0" cellspacing="0" cellpadding="0" width="100%">
                <tr>
                  <td class="{{ $language->direction == 1 ? 'rtl' : '' }}" style="width: 33%">
                    <p class="mt-2">
                      <span class="c-light h6 mb-1">
                        {{ __('TOTAL PAID') }}
                      </span>
                      <span class="h4 fw-0 mb-2">
                        {{ $position == 'left' ? $currency . ' ' : '' }}{{ $bookingInfo->price + $bookingInfo->tax }}{{ $position == 'right' ? ' ' . $currency : '' }}
                      </span>
                    </p>
                  </td>
                  <td class="{{ $language->direction == 1 ? 'rtl' : '' }}" style="width: 33%">
                    <p class="mt-2">
                      <span class="c-light h6 mb-1">
                        {{ __('PAYMENT METHOD') }}
                      </span>
                      <span class="h4 fw-0 mb-2">
                        {{ is_null($bookingInfo->paymentMethod) ? '-' : $bookingInfo->paymentMethod }}
                      </span>
                    </p>
                  </td>
                  <td class="{{ $language->direction == 1 ? 'rtl' : '' }}" style="width: 33%">
                    <p class="mt-2">
                      <span class="c-light h6 mb-1">
                        {{ __('PAYMENT STATUS') }}
                      </span>
                      <span class="h4 fw-0 mb-2">
                        @if ($bookingInfo->paymentStatus == 'completed')
                          {{ __('Completed') }}
                        @elseif ($bookingInfo->paymentStatus == 'pending')
                          {{ __('Pending') }}
                        @elseif ($bookingInfo->paymentStatus == 'rejected')
                          {{ __('Rejected') }}
                        @else
                          -
                        @endif
                      </span>
                    </p>
                  </td>
                </tr>
              </table>
              <div class="mt-4">
              </div>
            </td>
            @if ($language->direction == 1)
              <td class="left"
                style="width:33.333333%; @if (empty($event->ticket_image)) background-color:{{ $bg_color }}; @endif">
                @if (!empty($event->ticket_image))
                  <img alt="image" width="100%"
                    src="{{ asset('assets/admin/img/event_ticket/' . $event->ticket_image) }}" />
                @endif
              </td>
            @else
              <td class="right p-3" style="width:16.666667%;">
                <p class="text-center">
                  <img width="111" height="111" alt="image"
                    src="{{ asset('assets/admin/qrcodes/' . $bookingInfo->booking_id . '__' . $variation['unique_id'] . '.svg') }}"
                    alt="">
                </p>
              </td>
            @endif
          </tr>
        </tbody>
      </table>

      {{-- Information --}}
      @if (!empty($event->instructions))
        <div class="info p-3 mt-2 {{ $language->direction == 1 ? 'rtl' : '' }}"
          style="border: 2px solid {{ $bg_color }}; border-radius: 5px">
          {!! $event->instructions !!}
        </div>
      @endif
      <div class="mt-4"></div>
      @if (!$loop->last)
        <div class="page-break"></div>
      @endif
    @endforeach
  @else
    @for ($i = 1; $i <= $bookingInfo->quantity; $i++)
      @if (!empty($event->ticket_logo))
        <p class="text-center">
          <span>
            <img class="img-fluid" alt="image"
              src="{{ asset('assets/admin/img/event_ticket_logo/' . $event->ticket_logo) }}" />
          </span>
        </p>
      @endif
      <table class="mt-2" border="0" cellspacing="0" cellpadding="0" width="100%">
        <tbody style="border: 2px solid {{ $bg_color }}">
          <tr>

            @if ($language->direction == 1)
              <td class="right p-3" style="width:16.666667%;">
                <p class="text-center">
                  <img width="111" height="111" alt="image"
                    src="{{ asset('assets/admin/qrcodes/' . $bookingInfo->booking_id . '__' . $i . '.svg') }}">
                </p>
              </td>
            @else
              <td class="left"
                style="width:33.333333%; @if (empty($event->ticket_image)) background-color:{{ $bg_color }}; @endif">
                @if (!empty($event->ticket_image))
                  <img alt="image" width="100%"
                    src="{{ asset('assets/admin/img/event_ticket/' . $event->ticket_image) }}" />
                @endif
              </td>
            @endif

            <td class="center p-3" style="width:50%;">
              <p class="h2 mb-2 {{ $language->direction == 1 ? 'rtl' : '' }}">
                {{ @$eventInfo->title }}
              </p>
              <hr>
              <p class="h3 mb-2 {{ $language->direction == 1 ? 'rtl' : '' }}">
                {{ $bookingInfo->city }},
                {{ is_null($bookingInfo->state) ? '' : $bookingInfo->state . ',' }}
                {{ $bookingInfo->country }}
              </p>
              <p class="mb-2 {{ $language->direction == 1 ? 'rtl' : '' }}">
                {{ FullDateTimeInvoice($bookingInfo->event_date) }}
              </p>
              <hr>

              <hr>
              <p class="c-light h6 mb-1 {{ $language->direction == 1 ? 'rtl' : '' }}">
                {{ __('Billing Address') }}
              </p>
              <p class="h4 fw-0 mb-2 {{ $language->direction == 1 ? 'rtl' : '' }}">
                {{ $bookingInfo->address }}
              </p>
              <hr>
              <table border="0" cellspacing="0" cellpadding="0" width="100%">
                <tr>
                  <td class="{{ $language->direction == 1 ? 'rtl' : '' }}" style="width: 33%">
                    <p class="mt-2">
                    <p class="c-light h6 mb-1">
                      {{ __('BOOKING DATE') }}
                    </p>
                    <p class="h4 fw-0 mb-2">
                      {{ date_format($bookingInfo->created_at, 'M d, Y') }}
                    </p>
                    </p>
                  </td>
                  <td class="{{ $language->direction == 1 ? 'rtl' : '' }}" style="width: 33%">
                    <p class="mt-2">
                    <p class="c-light h6 mb-1">
                      {{ __('DURATION') }}
                    </p>
                    <p class="h4 fw-0 mb-2">
                      {{ @$bookingInfo->evnt->duration }}
                    </p>
                    </p>
                  </td>
                  <td class="{{ $language->direction == 1 ? 'rtl' : '' }}" style="width: 33%">
                    <p class="mt-2">
                    <p class="c-light h6 mb-1">
                      {{ __('BOOKING ID') }}
                    </p>
                    <p class="h4 fw-0 mb-2">
                      {{ '#' . $bookingInfo->booking_id }}
                    </p>
                    </p>
                  </td>
                </tr>
              </table>
              <hr>
              <table border="0" cellspacing="0" cellpadding="0" width="100%">
                <tr>
                  <td class="{{ $language->direction == 1 ? 'rtl' : '' }}" style="width: 33%">
                    <p class="mt-2">
                    <p class="c-light h6 mb-1">
                      {{ __('TAX') }}
                    </p>
                    <p class="h4 fw-0 mb-2">
                      {{ $position == 'left' ? $currency . ' ' : '' }}{{ is_null($bookingInfo->tax) ? '0.00' : $bookingInfo->tax }}{{ $position == 'right' ? ' ' . $currency : '' }}
                    </p>
                    </p>
                  </td>
                  <td class="{{ $language->direction == 1 ? 'rtl' : '' }}" style="width: 33%">
                    <p class="mt-2">
                    <p class="c-light h6 mb-1">
                      {{ __('EARLY BIRD') }}
                    </p>
                    <p class="h4 fw-0 mb-2">
                      {{ $position == 'left' ? $currency . ' ' : '' }}{{ is_null($bookingInfo->early_bird_discount) ? '0.00' : $bookingInfo->early_bird_discount }}{{ $position == 'right' ? ' ' . $currency : '' }}
                    </p>
                    </p>
                  </td>
                  <td class="{{ $language->direction == 1 ? 'rtl' : '' }}" style="width: 33%">
                    <p class="mt-2">
                    <p class="c-light h6 mb-1">
                      {{ __('COUPON') }}
                    </p>
                    <p class="h4 fw-0 mb-2">
                      {{ $position == 'left' ? $currency . ' ' : '' }}{{ is_null($bookingInfo->discount) ? '0.00' : $bookingInfo->discount }}{{ $position == 'right' ? ' ' . $currency : '' }}
                    </p>
                    </p>
                  </td>
                </tr>
              </table>
              <hr>
              <table border="0" cellspacing="0" cellpadding="0" width="100%">
                <tr>
                  <td class="{{ $language->direction == 1 ? 'rtl' : '' }}" style="width: 33%">
                    <p class="mt-2">
                      <span class="c-light h6 mb-1">
                        {{ __('TOTAL PAID') }}
                      </span>
                      <span class="h4 fw-0 mb-2">
                        {{ $position == 'left' ? $currency . ' ' : '' }}{{ $bookingInfo->price + $bookingInfo->tax }}{{ $position == 'right' ? ' ' . $currency : '' }}
                      </span>
                    </p>
                  </td>
                  <td class="{{ $language->direction == 1 ? 'rtl' : '' }}" style="width: 33%">
                    <p class="mt-2">
                      <span class="c-light h6 mb-1">
                        {{ __('PAYMENT METHOD') }}
                      </span>
                      <span class="h4 fw-0 mb-2">
                        {{ is_null($bookingInfo->paymentMethod) ? '-' : $bookingInfo->paymentMethod }}
                      </span>
                    </p>
                  </td>
                  <td class="{{ $language->direction == 1 ? 'rtl' : '' }}" style="width: 33%">
                    <p class="mt-2">
                      <span class="c-light h6 mb-1">
                        {{ __('PAYMENT STATUS') }}
                      </span>
                      <span class="h4 fw-0 mb-2">
                        @if ($bookingInfo->paymentStatus == 'completed')
                          {{ __('Completed') }}
                        @elseif ($bookingInfo->paymentStatus == 'pending')
                          {{ __('Pending') }}
                        @elseif ($bookingInfo->paymentStatus == 'rejected')
                          {{ __('Rejected') }}
                        @else
                          -
                        @endif
                      </span>
                    </p>
                  </td>
                </tr>
              </table>
              <div class="mt-4">
              </div>
            </td>

            @if ($language->direction == 1)
              <td class="left"
                style="width:33.333333%; @if (empty($event->ticket_image)) background-color:{{ $bg_color }}; @endif">
                @if (!empty($event->ticket_image))
                  <img alt="image" width="100%"
                    src="{{ asset('assets/admin/img/event_ticket/' . $event->ticket_image) }}" />
                @endif
              </td>
            @else
              <td class="right p-3" style="width:16.666667%;">
                <p class="text-center">
                  <img width="111" height="111" alt="image"
                    src="{{ asset('assets/admin/qrcodes/' . $bookingInfo->booking_id . '__' . $i . '.svg') }}">
                </p>
              </td>
            @endif
          </tr>
        </tbody>
      </table>

      {{-- Information --}}
      @if (!empty($event->instructions))
        <div class="info p-3 mt-2{{ $language->direction == 1 ? 'rtl' : '' }}"
          style="border: 2px solid {{ $bg_color }}; border-radius: 5px">
          {!! $event->instructions !!}
        </div>
      @endif
      <div class="mt-4"></div>
      @if ($i < $bookingInfo->quantity)
        <div class="page-break"></div>
      @endif
    @endfor
  @endif

</body>

</html>
