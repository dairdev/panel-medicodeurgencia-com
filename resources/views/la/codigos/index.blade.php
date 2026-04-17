@extends("la.layouts.app")

@section("contentheader_title")
    <a @ajaxload href="{{ url(config('laraadmin.adminRoute') . '/codigos') }}">@lang('la_codigo.codigos')</a> :
@endsection
@section("contentheader_description", app('translator')->get('la_codigo.codigo_listing'))
@section("section", app('translator')->get('la_codigo.codigos'))
@section("sub_section", app('translator')->get('common.listing'))
@section("htmlheader_title", app('translator')->get('la_codigo.codigo_listing'))

@section("headerElems")
@la_access("Codigos", "create")
    <button class="btn btn-success btn-sm pull-right" data-toggle="modal" data-target="#AddModal">@lang('la_codigo.codigo_add')</button>
@endla_access
@endsection

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

<div class="box box-success">
    <!--<div class="box-header"></div>-->
    <div class="box-body">
        <table id="dt_codigos" class="table table-bordered">
        <thead>
        <tr class="success">
            @foreach( $listing_cols as $col )
            <th>{{ $module->fields[$col]['label'] ?? ucfirst($col) }}</th>
            @endforeach
            @if($show_actions)
            <th>Nombre Lector</th>
            <th>Estado</th>
            <th>@lang('common.actions')</th>
            @endif
        </tr>
        </thead>
        <tbody>

        </tbody>
        </table>
    </div>
</div>

@la_access("Codigos", "create")
<div class="modal fade" id="AddModal" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">@lang('la_codigo.codigo_add')</h4>
            </div>
            {!! Form::open(['action' => 'App\Http\Controllers\LA\CodigosController@store', 'id' => 'codigo-add-form']) !!}
            <div class="modal-body">
              <div class="box-body">
                {{--@la_input($module, 'date_compra')--}}
                
                @if(auth()->user()->roles[0]->id == 3)
                  <input id="type" name="type" type="hidden" value="0">
                @else
                  <div class="form-group">
                    <label for="password">Duración Codigo</label>
                    <input class="form-control" placeholder="duracion" id="type" name="type" type="text" value="0">
                  </div>
                @endif
      
                <?php 
                  if(auth()->user()->roles[0]->id == 3){
                    $lectores = App\Models\Lectore::where('user_id', Auth::user()->id)->whereNull('deleted_at')->get();
                  }else{
                    $lectores = App\Models\Lectore::whereNull('deleted_at')->get();	
                  }
                ?>
                <div class="form-group">
                  <label for="lectore_id">Lector* :</label>
                  <select class="form-control select2-hidden-accessible" required="1" data-placeholder="Enter Lector" rel="select2" name="lectore_id" tabindex="-1" aria-hidden="true" aria-required="true">
                    @foreach($lectores as $lector)
                      <option value="{{ $lector->id }}">{{ $lector->namo }} {{ $lector->surname }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">@lang('common.close')</button>
                {!! Form::button( app('translator')->get('common.save'), ['class'=>'btn btn-success', 'type'=>'submit']) !!}
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@endla_access
<div class="modal fade" id="SendModal" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Añadir Codigo</h4>
			</div>
			{!! Form::open(['action' => 'App\Http\Controllers\LA\CodigosController@email', 'id' => 'codigo-send-form']) !!}
			<div class="modal-body">
				<div class="box-body">
                    <div class="form-group">
                    	<label for="codigo">Email de envio* :</label>
                    	<input class="form-control" placeholder="Email de envio" id="email_code" name="email" type="email">
                    	<label for="codigo">Texto opcional :</label>
                    	<textarea class="form-control" id="texto_code" name="texto_code" rows="5"></textarea>
                    	<input class="form-control" name="code_id" id="code_id" type="hidden">
                    	<input class="form-control" name="lectore_id2" id="lectore_id2" type="hidden">
                    </div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
				<button type="button" class="btn btn-success" id="btn-send-email">Enviar</button>
			</div>
			{!! Form::close() !!}
		</div>
	</div>
</div>
@endsection

@push('styles')

@endpush

@push('scripts')
<script>
var dt_codigos = null;
var submitBtn = null;
var formObj = null;

$(function () {
    dt_codigos = $("#dt_codigos").DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ url(config('laraadmin.adminRoute') . '/codigo_dt_ajax') }}",
        language: {
            lengthMenu: "_MENU_",
            search: "_INPUT_",
            searchPlaceholder: '@lang("common.search")'
        },
        columns: [
            @foreach ($listing_cols as $col)
                {
                    data: '{{ $col }}',
                    name: '{{ $col }}'
                },
            @endforeach
            @if ($show_actions)
                {
                    data: 'NombreLector',
                    name: 'NombreLector',
                },
                {
                    data: 'Estado',
                    name: 'Estado',
                },
                {
                    data: 'dt_action',
                    name: 'dt_action',
                },
                
            @endif
        ],
        @if ($show_actions)
            columnDefs: [{
                orderable: false,
                targets: [-1]
            }],
        @endif
    });
    $('#SendModal').on('shown.bs.modal', function (e) {
		var ele = $(e.relatedTarget);
  		$('#email_code').val(ele.data('email'));
  		$('#code_id').val(ele.data('code'));
  		$('#lectore_id2').val(ele.data('lectore'));
	});

	$('#btn-send-email').click(function(){
		$.ajax({
            url: $('#codigo-send-form').attr('action'),
            data: $('#codigo-send-form').serialize(),
            dataType:'json',
            type:'GET',
            success:function(response){
            	$('#SendModal').modal('hide');
                alert('Email con código enviado');
            },
        });
	})
    @la_access("Codigos", "create")
    // Create New Codigo REST Request
    submitBtn = $('#codigo-add-form button[type=submit]');
    formObj = $("#codigo-add-form");

    formObj.validate({
        submitHandler: function(form, event) {
            event.preventDefault();
            $.ajax({
                url: formObj.attr('action'),
                method: 'POST',
                contentType: 'json',
                headers: { 'X-CSRF-Token': '{{ csrf_token() }}' },
                data: getFormDataJSON(formObj),
                beforeSend: function() {
                    submitBtn.html('<i class="fa fa-refresh fa-spin mr5"></i> Creating...');
                    submitBtn.prop('disabled', true);
                },
                success: function( data ) {
                    console.log(data);
                    if(data.status == "success") {
                        show_success("Codigo Create", data);
                        $('#AddModal').modal('hide')
                        if(isset(data.redirect)) {
                            window.location.href = data.redirect;
                        }
                    } else {
                        show_failure("Codigo Create", data);
                    }
                    submitBtn.html('Save');
                    submitBtn.prop('disabled', false);
                },
                error: function( data ) {
                    console.error(data);
                    show_failure("Codigo Create", data);
                    submitBtn.html('Save');
                    submitBtn.prop('disabled', false);
                }
            });
            return false;
        }
    });
    @endla_access

    @la_access("Codigos", "edit")
    // Section for Updating fields via X-editable
    dt_codigos.on('draw', function () {
        $('.update_field').editable({
            container: 'body',
            validate: function(value) {
                var id = $(this).attr('id');
                var field_name = $(this).attr('field_name');
                // Make your validations here
                if ($.trim(value) == '') {
                    return 'This field is required';
                }
                var formData = {};
                formData[field_name] = value;
                $.ajax({
                    url: "{{ url(config('laraadmin.adminRoute')) }}/codigos/"+id,
                    method: 'PUT',
                    contentType: 'json',
                    headers: { 'X-CSRF-Token': '{{ csrf_token() }}' },
                    data: JSON.stringify(formData),
                    success: function( data ) {
                        if(data.status == "success") {
                            show_success("Codigo Update", data);
                        } else {
                            show_failure("Codigo Update", data);
                        }
                        if(isset(data.redirect)) {
                            // window.location.href = data.redirect;
                        }
                    },
                    error: function( data ) {
                        show_failure("Codigo Update", data);
                        if(isset(data.redirect)) {
                            window.location.href = data.redirect;
                        }
                    }
                });
            }
        });
    });
    @endla_access
   
});
</script>
@endpush
