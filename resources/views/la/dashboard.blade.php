@extends('la.layouts.app')

@section('htmlheader_title') @lang('dashboard.dashboard') @endsection
@section('contentheader_title') @lang('dashboard.dashboard') @endsection
@section('contentheader_description') @lang('dashboard.organization_overview') @endsection

@section('main-content')
<!-- Main content -->
<section class="content">
  <!-- Small boxes (Stat box) -->
  @if(auth()->user()->roles[0]->id != 3)
    <div class="row">
      <div class="col-lg-4 col-xs-6">
        <!-- small box -->
        <a href="{{ url(config('laraadmin.adminRoute') . '/chapters') }}" class="small-box text-white">
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3>{{ $capitulos }}</h3>
              <p>Capítulos</p>
            </div>
            <div class="icon">
              <i class="ion ion-android-list"></i>
            </div>
            <span class="small-box-footer">Ver todos <i class="fa fa-arrow-circle-right"></i></span>
          </div>
        </a>
      </div><!-- ./col -->
      <div class="col-lg-4 col-xs-6">
        <!-- small box -->
        <a href="{{ url(config('laraadmin.adminRoute') . '/specialties') }}" class="small-box text-white">
          <div class="small-box bg-green">
            <div class="inner">
              <h3>{{ $especialidades }}</h3>
              <p>Especialidades</p>
            </div>
            <div class="icon">
              <i class="ion ion-medkit"></i>
            </div>
            <span class="small-box-footer">Ver todas <i class="fa fa-arrow-circle-right"></i></span>
          </div>
        </a>
      </div><!-- ./col -->
      <div class="col-lg-4 col-xs-6">
        <!-- small box -->
        <a href="{{ url(config('laraadmin.adminRoute') . '/paginas') }}" class="small-box text-white">
          <div class="small-box bg-yellow">
            <div class="inner">
              <h3>{{ $paginas }}</h3>
              <p>Páginas</p>
            </div>
            <div class="icon">
              <i class="ion ion-document"></i>
            </div>
            <span class="small-box-footer">Ver todas <i class="fa fa-arrow-circle-right"></i></span>
          </div>
        </a>
      </div><!-- ./col -->
      <div class="col-lg-4 col-xs-6">
        <!-- small box -->
        <a href="{{ url(config('laraadmin.adminRoute') . '/authores') }}" class="small-box text-white">
          <div class="small-box bg-red">
            <div class="inner">
              <h3>{{ $autores }}</h3>
              <p>Autores</p>
            </div>
            <div class="icon">
              <i class="ion ion-person"></i>
            </div>
            <span class="small-box-footer">Ver todos <i class="fa fa-arrow-circle-right"></i></span>
          </div>
        </a>
      </div><!-- ./col -->
      <div class="col-lg-4 col-xs-6">
        <!-- small box -->
        <a href="{{ url(config('laraadmin.adminRoute') . '/employees') }}" class="small-box text-white">
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3>{{ $empresas }}</h3>
              <p>Empresas</p>
            </div>
            <div class="icon">
              <i class="ion ion-podium"></i>
            </div>
            <span class="small-box-footer">Ver todos <i class="fa fa-arrow-circle-right"></i></span>
          </div>
        </a>
      </div><!-- ./col -->
      <div class="col-lg-4 col-xs-6">
        <!-- small box -->
        <a href="{{ url(config('laraadmin.adminRoute') . '/subchapters') }}" class="small-box text-white">
          <div class="small-box bg-green">
            <div class="inner">
              <h3>{{ $subcapitulos }}</h3>
              <p>Subcapitulos</p>
            </div>
            <div class="icon">
              <i class="ion ion-android-list"></i>
            </div>
            <span class="small-box-footer">Ver todos <i class="fa fa-arrow-circle-right"></i></span>
          </div>
        </a>
      </div><!-- ./col -->
    </div>
  @else
    <div class="col-lg-3 col-xs-6">
      <!-- small box -->
      <a href="{{ url(config('laraadmin.adminRoute') . '/lectores') }}" class="small-box text-white">
        <div class="small-box bg-aqua">
          <div class="inner">
            <h3>{{ $lectores }}</h3>
            <p>Lectores</p>
          </div>
          <div class="icon">
            <i class="ion ion-person"></i>
          </div>
          <span class="small-box-footer">Ver todos <i class="fa fa-arrow-circle-right"></i></span>
        </div>
      </a>
    </div>
    <div class="col-lg-3 col-xs-6">
      <!-- small box -->
      <a href="{{ url(config('laraadmin.adminRoute') . '/codigos') }}" class="small-box text-white">
        <div class="small-box bg-red">
          <div class="inner">
            <h3>{{ $codigos }}</h3>
            <p>Códigos</p>
          </div>
          <div class="icon">
            <i class="ion ion-person"></i>
          </div>
          <span class="small-box-footer">Ver todos <i class="fa fa-arrow-circle-right"></i></span>
        </div>
      </a>
    </div>
  @endif
  <!-- Main row -->
</section><!-- /.content -->
@endsection

@push('styles')
<!-- Morris chart -->
<link rel="stylesheet" href="{{ asset('la-assets/plugins/morris/morris.css') }}">
<!-- jvectormap -->
<link rel="stylesheet" href="{{ asset('la-assets/plugins/jvectormap/jquery-jvectormap-1.2.2.css') }}">
<!-- Date Picker -->
<link rel="stylesheet" href="{{ asset('la-assets/plugins/datepicker/datepicker3.css') }}">
<!-- Daterange picker -->
<link rel="stylesheet" href="{{ asset('la-assets/plugins/daterangepicker/daterangepicker.css') }}">
<!-- bootstrap wysihtml5 - text editor -->
<link rel="stylesheet" href="{{ asset('la-assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css') }}">

<style>
.small-box.small-box-bg {
    background-size: cover;
}
.small-box.small-box-bg > .inner {
    background: rgba(0, 0, 0, 0.3);
}
.small-box.small-box-bg > .small-box-footer {
    transition: all 0.2s linear;
    background: rgba(0, 0, 0, 0.5);
    font-size: 14px;
    color: #DDD;
}
.small-box.small-box-bg > .small-box-footer:hover {
    font-size: 14.5px;
    color: #FFF;
}
.small-box.small-box-bg .icon {
    opacity: 0;
    font-size: 80px;
    margin-top: 6px;
    margin-right: 6px;
    color: rgba(255, 255, 255, 0.25);
}
.small-box.small-box-bg:hover .icon {
    opacity: 1;
    font-size: 85px;
    margin-top: 4px;
}
.small-box.blog_post {
    background-image: url('{{ asset('la-assets/img/bg_blog.jpg') }}')
}
.small-box.customers {
    background-image: url('{{ asset('la-assets/img/bg_customers.jpg') }}')
}
.small-box.employees {
    background-image: url('{{ asset('la-assets/img/bg_employees.jpg') }}')
}
.small-box.uploads {
    background-image: url('{{ asset('la-assets/img/bg_uploads.jpg') }}')
}
</style>
@endpush


@push('scripts')
<!-- jQuery UI 1.11.4 -->
<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Morris.js charts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<script src="{{ asset('la-assets/plugins/morris/morris.min.js') }}"></script>
<!-- Sparkline -->
<script src="{{ asset('la-assets/plugins/sparkline/jquery.sparkline.min.js') }}"></script>
<!-- jvectormap -->
<script src="{{ asset('la-assets/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js') }}"></script>
<script src="{{ asset('la-assets/plugins/jvectormap/jquery-jvectormap-world-mill-en.js') }}"></script>
<!-- jQuery Knob Chart -->
<script src="{{ asset('la-assets/plugins/knob/jquery.knob.js') }}"></script>
<!-- daterangepicker -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
<script src="{{ asset('la-assets/plugins/daterangepicker/daterangepicker.js') }}"></script>
<!-- datepicker -->
<script src="{{ asset('la-assets/plugins/datepicker/bootstrap-datepicker.js') }}"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="{{ asset('la-assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js') }}"></script>
<!-- FastClick -->
<script src="{{ asset('la-assets/plugins/fastclick/fastclick.js') }}"></script>
<!-- dashboard -->
<script src="{{ asset('la-assets/js/pages/dashboard.js') }}"></script>
@endpush

@push('scripts')
<script>
(function($) {
    if(document.URL.indexOf("#") == -1)
    {
        // Set the URL to whatever it was plus "#".
        url = document.URL+"#";
        location = "#";
        //Reload the page
        location.reload(true);
    }

	$('body').pgNotification({
		style: 'circle',
		title: 'Medico de Urgencias',
		message: "Bienvenido...",
		position: "top-right",
		timeout: 0,
		type: "success",
		thumbnail: '<img width="40" height="40" style="display: inline-block;" src="{{ Auth::user()->context()->profileImageUrl() }}" data-src="assets/img/profiles/avatar.jpg" data-src-retina="assets/img/profiles/avatar2x.jpg" alt="">'
	}).show();
})(window.jQuery);
</script>
@endpush