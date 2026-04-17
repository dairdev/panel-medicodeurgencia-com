@extends("la.layouts.app")

@section("contentheader_title")
	<a href="{{ url(config('laraadmin.adminRoute') . '/paginas') }}">Pagina</a> :
@endsection
@section("contentheader_description", $pagina->$view_col)
@section("section", "Paginas")
@section("section_url", url(config('laraadmin.adminRoute') . '/paginas'))
@section("sub_section", "Editar")

@section("htmlheader_title", "Editar Pagina : ".$pagina->$view_col)

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
				{!! Form::model($pagina, ['route' => [config('laraadmin.adminRoute') . '.paginas.update', $pagina->id ], 'method'=>'PUT', 'id' => 'pagina-edit-form']) !!}
					@la_form($module)
					
					{{--
					@la_input($module, 'titulo')
					@la_input($module, 'contenido')
					--}}
                    <br>
					<div class="form-group">
						{!! Form::submit( 'Actualizar', ['class'=>'btn btn-success']) !!} <button class="btn btn-default pull-right"><a href="{{ url(config('laraadmin.adminRoute') . '/paginas') }}">Cancelar</a></button>
					</div>
				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>

@endsection

@push('scripts')
	<script>
		$(document).ready(function() {
			$("[name='contenido']").summernote({
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
			data.append("chapter_id", $('#id_chapter').val());
			$.ajax({
				url: '../admin/uploadimagepage',
				cache: false,
				contentType: false,
				processData: false,
				data: data,
				type: "post",
				success: function(url) {
					var image = $('<img class="img-fluid img-thumbnail">').attr('src', url);
					$("[name='contenido']").summernote("insertNode", image[0]);
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

	});

	$(function () {
				$("#pagina-edit-form").validate({ });
		});
	</script>
@endpush
