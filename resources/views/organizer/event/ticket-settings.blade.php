@extends('organizer.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Edit Ticket Settings') }}</h4>
    <ul class="breadcrumbs">
      <li class="nav-home">
        <a href="{{ route('organizer.dashboard') }}">
          <i class="flaticon-home"></i>
        </a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Event Management') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a
          href="{{ route('admin.event_management.event', ['language' => $defaultLang->code]) }}">{{ __('All Events') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>

      @php
        $event_title = DB::table('event_contents')
            ->where('language_id', $defaultLang->id)
            ->where('event_id', $event->id)
            ->select('title')
            ->first();

      @endphp
      <li class="nav-item">
        <a href="#">
          {{ strlen($event_title->title) > 35 ? mb_substr($event_title->title, 0, 35, 'UTF-8') . '...' : $event_title->title }}
        </a>

      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>

      <li class="nav-item">
        <a href="#">{{ __('Edit Ticket Settings') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="card-title d-inline-block">{{ __('Edit Ticket Settings') }}</div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-lg-8 offset-lg-2">
              <form id="ajaxEditForm" action="{{ route('organizer.event_management.update_ticket_setting') }}"
                method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="event_id" value="{{ $event->id }}">
                <!--------- cover & map image-->
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="">{{ __('Ticket Image') }} </label>
                      <br>
                      <div class="thumb-preview">
                        <img
                          src="{{ $event->ticket_image ? asset('assets/admin/img/event_ticket/' . $event->ticket_image) : asset('assets/admin/img/noimage.jpg') }}"
                          alt="..." class="uploaded-img2">
                      </div>
                      <div class="mt-3">
                        <div role="button" class="btn btn-primary btn-sm upload-btn">
                          {{ __('Choose Image') }}
                          <input type="file" class="img-input2" name="ticket_image">
                        </div>
                      </div>
                      <p id="editErr_ticket_image" class="mt-1 mb-0 text-danger em"></p>
                      <p class="mt-2 mb-0 text-warning">{{ __('Best Size 255x390') }}</p>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="">{{ __('Ticket Logo') }} </label>
                      <br>
                      <div class="thumb-preview">
                        <img
                          src="{{ $event->ticket_logo ? asset('assets/admin/img/event_ticket_logo/' . $event->ticket_logo) : asset('assets/admin/img/noimage.jpg') }}"
                          alt="..." class="uploaded-img">
                      </div>
                      <div class="mt-3">
                        <div role="button" class="btn btn-primary btn-sm upload-btn">
                          {{ __('Choose Image') }}
                          <input type="file" class="img-input" name="ticket_logo">
                        </div>
                      </div>
                      <p id="editErr_ticket_logo" class="mt-1 mb-0 text-danger em"></p>
                      <p class="mt-2 mb-0 text-warning">{{ __('Best Size 200*50') }}</p>
                    </div>
                  </div>

                  <div class="col-md-12">
                    <div class="form-group">
                      <label>{{ __('Instructions') }}</label>
                      <textarea id="descriptionTmce" class="form-control summernote" name="instructions"
                        placeholder="{{ __('Enter Instructions') }}" data-height="300"> {{ $event->instructions }}</textarea>
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>

        <div class="card-footer">
          <div class="row">
            <div class="col-12 text-center">
              <button type="submit" id="updateBtn" class="btn btn-primary">
                {{ __('Update') }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('script')
  @php
    $languages = App\Models\Language::get();
  @endphp
  <script>
    let languages = "{{ $languages }}";
  </script>
  <script type="text/javascript" src="{{ asset('assets/admin/js/admin-partial.js') }}"></script>
  <script src="{{ asset('assets/admin/js/admin_dropzone.js') }}"></script>
  <script>
    $(document).ready(function() {
      $('.js-example-basic-single').select2();
    });
  </script>
@endsection

@section('variables')
  <script>
    "use strict";
    var storeUrl = "{{ route('admin.event.imagesstore') }}";
    var removeUrl = "{{ route('admin.event.imagermv') }}";

    var rmvdbUrl = "{{ route('admin.event.imgdbrmv') }}";
    var loadImgs = "{{ route('admin.event.images', $event->id) }}";
  </script>
@endsection
