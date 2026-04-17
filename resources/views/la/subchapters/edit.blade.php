@extends("la.layouts.app")

@section("contentheader_title")
    <a @ajaxload href="{{ url(config('laraadmin.adminRoute') . '/subchapters') }}">@lang('la_subchapter.subchapters')</a> :
@endsection
@section("contentheader_description", $subchapter->$view_col)
@section("section", app('translator')->get('la_subchapter.subchapters'))
@section("section_url", url(config('laraadmin.adminRoute') . '/subchapters'))
@section("sub_section", app('translator')->get('common.edit'))

@section("htmlheader_title", app('translator')->get('la_subchapter.subchapter_edit')." : ".$subchapter->$view_col)

@section("main-content")

@if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="box">
    <div class="box-header">
        
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                {!! Form::model($subchapter, ['route' => [config('laraadmin.adminRoute') . '.subchapters.update', $subchapter->id ], 'method'=>'PUT', 'id' => 'subchapter-edit-form']) !!}
                    {{--@la_form($module)--}}
                    
                    
                    @la_input($module, 'name')
					@la_input($module, 'body')
					@la_input($module, 'orden')
					@la_input($module, 'chapter_id')
					@la_input($module, 'slug')
					@la_input($module, 'image')
					@la_input($module, 'video')
                    
                    <br>
                    <div class="form-group">
                        {!! Form::button( app('translator')->get('common.update'), ['class'=>'btn btn-success', 'type'=>'submit']) !!} <a @ajaxload href="{{ url(config('laraadmin.adminRoute') . '/subchapters') }}" class="btn btn-default pull-right">@lang('common.cancel')</a>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
var submitBtn = null;
var formObj = null;

$(function () {
    @la_access("Subchapters", "edit")
    // Edit Subchapter REST Request
    submitBtn = $('#subchapter-edit-form button[type=submit]');
    formObj = $("#subchapter-edit-form");

    formObj.validate({
        submitHandler: function(form, event) {
            event.preventDefault();
            $.ajax({
                url: formObj.attr('action'),
                method: 'PUT',
                contentType: 'json',
                headers: { 'X-CSRF-Token': '{{ csrf_token() }}' },
                data: getFormDataJSON(formObj),
                beforeSend: function() {
                    submitBtn.html('<i class="fa fa-refresh fa-spin mr5"></i> Updating...');
                    submitBtn.prop('disabled', true);
                },
                success: function( data ) {
                    if(data.status == "success") {
                        show_success("Subchapter Update", data);
                    } else {
                        show_failure("Subchapter Update", data);
                    }
                    submitBtn.html('Update');
                    submitBtn.prop('disabled', false);
                    if(isset(data.redirect)) {
                        window.location.href = data.redirect;
                    }
                },
                error: function( data ) {
                    show_failure("Subchapter Update", data);
                    submitBtn.html('Update');
                    submitBtn.prop('disabled', false);
                    if(isset(data.redirect)) {
                        window.location.href = data.redirect;
                    }
                }
            });
            return false;
        }
    });
    @endla_access
});
$(document).ready(function() {
	$("[name='body']").summernote({
    	toolbar: [
			['style', ['style','bold']],
			['para', ['ul', 'ol']],
			['table', ['table']],
			['options', ['fullscreen']],
			['insert', ['picture','link']],
			['view', ['codeview']]
		],
		popover: {
			table: [
				['add', ['addRowDown', 'addRowUp', 'addColLeft', 'addColRight']],
				['delete', ['deleteRow', 'deleteCol', 'deleteTable']],
				['custom', ['tableHeaders']]
			],
		},
		styleTags: ['normal', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'blockquote'],
		callbacks: {
			onImageUpload: function(image) {
				uploadImage(image[0]);
			}
		}
	});

	function uploadImage(image) {
        var data = new FormData();
        data.append("image", image);
        data.append("_token", "{{ csrf_token() }}");
        data.append("subchapter_id", $('#id_subchapter').val());
        $.ajax({
            url: '/admin/uploadimagesub',
            cache: false,
            contentType: false,
            processData: false,
            data: data,
            type: "post",
            success: function(url) {
            	var image = $('<img class="img-fluid img-thumbnail">').attr('src', url);
        		$("[name='body']").summernote("insertNode", image[0]);
            },
            error: function(data) {
                console.log(data);
            }
        });
    }

	$(".note-btn-group").find(`[data-value='blockquote']`).html('<h4>Pie de imagen</h4>');
	$(".note-btn-group").find(`[data-value='normal']`).html('<h4>Normal</h4>');
	$(".note-btn-group").find(`[data-value='h1']`).html('<h4>Titulo Capitulo</h4>');
	$(".note-btn-group").find(`[data-value='h2']`).html('<h4>Salto pagina</h4>');
	$(".note-btn-group").find(`[data-value='h3']`).html('<h4>Titulo</h4>');
	$(".note-btn-group").find(`[data-value='h4']`).html('<h4>Autores</h4>');
	$(".note-btn-group").find(`[data-value='h5']`).html('<h4>Subtitulo</h4>');
	$(".note-btn-group").find(`[data-value='h6']`).html('<h4>Seccion</h4>');

	//$(".note-btn-group.note-para button.note-btn:first").attr('aria-label','Lista');
});
</script>
<style type="text/css">
  .note-editable {
      font-family: sans-serif !important;
      font-style: normal;
      font-weight: normal;
      font-size: 16px !important;
      line-height: 1.25 !important;
      color: black;
  }

  .note-editable p {
      hyphens: auto;
  }

  .note-editable ul, .note-editable ol {
      text-align: justify;
  }
  
  .note-editable ul {
      padding-inline-start: 24px;
      list-style-type: disc;
      list-style-position: outside;
  }

  .note-editable ol {
      list-style-type: disc;
  }

  .note-editable li ol {
      border: none;
      padding-top: 0;
      padding-bottom: 0;
  }

  .note-editable blockquote {
      display: block;
      margin-left: auto;
      margin-right: auto;
      width: 60%;
      font-size: 70%;
      font-weight: 400;
      color: rgb(141,105,30) !important;
      padding: 10px; 
      border: none !important;
  }

  .note-editable blockquote p {
      text-align: center !important;
      margin-bottom: 0.25rem;
  }

  .note-editable main {
      margin-left: 0px;
      margin-right: 0px;
      margin-bottom: 0px;
  }

  .note-editable main p {
      text-align: justify;
      word-break: break-word;
      word-spacing: 1px;
  }

  .note-editable li p {
      margin-bottom: 0.3rem;
  }

  .note-editable h1, .note-editable h2, .note-editable h3 {
      color: rgb(141,105,30) !important;
      text-transform: uppercase;
  }

  .note-editable h6 {
      color: rgb(141,105,30) !important;
  }

  .note-editable h1 {
      font-family: sans-serif !important;
      font-weight: bold;
      font-size: 26px;
      text-align: center;
      margin-bottom: .2rem;
  }

  .note-editable h1, h2 {
      page-break-before: always;
  }

  .note-editable h2 {
      font-family: sans-serif !important;
      font-size: 20px;
      margin-top: 5px;
      text-decoration: underline;
      font-weight: bold;
      text-align: center;
  }
  
  .note-editable h3 {
      font-family: sans-serif !important;
      font-size: 20px;
      font-weight: bold;
      text-align: left;
      margin-bottom: .2rem;
  }

  .note-editable h4 {
      font-family: sans-serif !important;
      text-align: center;
      font-size: 19px;
      font-style: italic;
      margin-bottom: .5rem;
  }

  .note-editable h4 + h3 {
      margin-top: 32px;
  }

  .note-editable h6 {
      font-family: sans-serif !important;
      font-size: 20px;
      text-decoration: underline;
      margin-bottom: .5rem;
  }

  .note-editable h5 {
      color: black !important;
      font-weight: bold;
      font-size: 17px;
  }

  .note-editable .img-fluid {
      display: block;
      margin-left: auto;
      margin-right: auto;
      max-width: 60%;
  }

  .note-editable h2 + p > img {
      max-width: 100% !important;   
  }

  .note-editable .img-thumbnail {
      padding: 0;
      border: none;
      border-radius: 0;
  }

  .note-editable td img.img-fluid {
      max-width: 100%;
  }

  .note-editable table {
      margin-bottom: 10px;
      text-align: center;
      width: 100%;
      overflow-x: auto;
      -webkit-overflow-scrolling: touch;
      -ms-overflow-style: -ms-autohiding-scrollbar;
      font-size: 16px;
      table-layout: auto;
  }   

  .note-editable table td p {
      margin-bottom: 0.25rem;
      text-align: left;
  }

  .note-editable table.center {
      text-align: center;
  }   

  .note-editable td img {
      display: block;
      margin-left: auto;
      margin-right: auto;
  }

  .note-editable img {
      margin-bottom: 15px;
  }

  .note-editable table td {
      text-align: left;
      word-break: break-word;
      word-spacing: 1px;
      margin-bottom: 0;
      padding: 5px 0px;
  }

  .note-editable thead {
      background-color: #c3b088;
  }

  .note-editable tr th {
      font-family: sans-serif !important;
  }

  .note-editable .table-striped tbody tr:nth-of-type(odd) {
      background-color: rgba(0,0,0,.1) !important;
  }

  .note-editable .table-bordered {
      /*border: 1px solid rgb(141,105,30) !important;*/
      border: none !important;
  }

  .note-editable .table-bordered td, .note-editable .table-bordered th {
      border: 1px solid rgb(141,105,30) !important;
  }

  .note-editable .td-fill {
      background-color: rgb(227, 194, 125);
  }

  .note-editable .table-responsive {
      display: table;
  }

  .note-editable .table td {
      padding: .25rem .75rem;
  }

  .note-editable th, .note-editable td {
      vertical-align: middle !important;
      text-align: left;
      word-break: break-word;
      word-spacing: 1px;
  }

  .note-editable ol {
      border: 1px solid rgb(141,105,30);
      padding-top: 12px;
      padding-bottom: 12px;
      padding-right: 12px;
      padding-inline-start: 30px;
      background-color: rgb(228, 221, 206);
  }

  .note-editable b, .note-editable strong {
      font-family: sans-serif !important;
  }

  .note-editable .container {
      max-width: 100%;
  }

  @media (min-width: 768px) {
      .note-editable .container {
          /*max-width: 94%;*/
          max-width: 100%;
      }
  }

  @media (max-width: 767px) {
      .note-editable .img-fluid {
          max-width: 80%;
      }

      .note-editable td img.img-fluid {
          max-width: 100%;
      }

      .note-editable blockquote {
          width: 80%;
      }
  }
</style>
@endpush
