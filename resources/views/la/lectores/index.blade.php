@extends("la.layouts.app")

@section("contentheader_title")
    <a @ajaxload href="{{ url(config('laraadmin.adminRoute') . '/lectores') }}">@lang('la_lectore.lectores')</a> :
@endsection
@section("contentheader_description", app('translator')->get('la_lectore.lectore_listing'))
@section("section", app('translator')->get('la_lectore.lectores'))
@section("sub_section", app('translator')->get('common.listing'))
@section("htmlheader_title", app('translator')->get('la_lectore.lectore_listing'))

@section("headerElems")
@la_access("Lectores", "create")
    <button class="btn btn-success btn-sm pull-right" data-toggle="modal" data-target="#AddModal">@lang('la_lectore.lectore_add')</button>
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
        <table id="dt_lectores" class="table table-bordered">
        <thead>
        <tr class="success">
            @foreach( $listing_cols as $col )
            <th>{{ $module->fields[$col]['label'] ?? ucfirst($col) }}</th>
            @endforeach
            @if($show_actions)
            <th>@lang('common.actions')</th>
            @endif
        </tr>
        </thead>
        <tbody>

        </tbody>
        </table>
    </div>
</div>

@la_access("Lectores", "create")
<div class="modal fade" id="AddModal" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">@lang('la_lectore.lectore_add')</h4>
            </div>
            {!! Form::open(['action' => 'App\Http\Controllers\LA\LectoresController@store', 'id' => 'lectore-add-form']) !!}
            <div class="modal-body">
                <div class="box-body">
                    {{--@la_form($module)    --}}

                    
                    @la_input($module, 'namo')
					@la_input($module, 'surname')
					@la_input($module, 'colegiado')
					@la_input($module, 'email')
					@la_input($module, 'phone')
          <div class="form-group">
						<label for="password">Contraseña :</label>
						<div class="input-group">
							<input class="form-control" placeholder="Enter Contraseña" data-rule-maxlength="256" id="password" name="password" type="password" value="">
							<div class="input-group-btn">
								<button class="btn btn-default" type="button" onclick="showPass()">
									<i class="fa fa-eye"></i>
								</button>
							</div>
						</div>
					</div>
					@if(Auth::user()->roles[0]->id != 3)
						<div class="form-group">
							<label for="password">Dias de validez</label>
							<input class="form-control" placeholder="duracion" id="type" name="type" type="text" value="0">
						</div>
					@endif
					{{--@la_input($module, 'password')--}}
					@la_input($module, 'notas')
                
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

@endsection

@push('styles')

@endpush

@push('scripts')
<script>
var dt_lectores = null;
var submitBtn = null;
var formObj = null;
function showPass() {
  		var x = document.getElementById("password");
  		if (x.type === "password") {
    		x.type = "text";
  		} else {
    		x.type = "password";
  		}
	}
$(function () {
    dt_lectores = $("#dt_lectores").DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ url(config('laraadmin.adminRoute') . '/lectore_dt_ajax') }}",
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

    @la_access("Lectores", "create")
    // Create New Lectore REST Request
    submitBtn = $('#lectore-add-form button[type=submit]');
    formObj = $("#lectore-add-form");

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
                        show_success("Lectore Create", data);
                        $('#AddModal').modal('hide')
                        if(isset(data.redirect)) {
                            window.location.href = data.redirect;
                        }
                    } else {
                        show_failure("Lectore Create", data);
                    }
                    submitBtn.html('Save');
                    submitBtn.prop('disabled', false);
                },
                error: function( data ) {
                    console.error(data);
                    show_failure("Lectore Create", data);
                    submitBtn.html('Save');
                    submitBtn.prop('disabled', false);
                }
            });
            return false;
        }
    });
    @endla_access

    @la_access("Lectores", "edit")
    // Section for Updating fields via X-editable
    dt_lectores.on('draw', function () {
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
                    url: "{{ url(config('laraadmin.adminRoute')) }}/lectores/"+id,
                    method: 'PUT',
                    contentType: 'json',
                    headers: { 'X-CSRF-Token': '{{ csrf_token() }}' },
                    data: JSON.stringify(formData),
                    success: function( data ) {
                        if(data.status == "success") {
                            show_success("Lectore Update", data);
                        } else {
                            show_failure("Lectore Update", data);
                        }
                        if(isset(data.redirect)) {
                            // window.location.href = data.redirect;
                        }
                    },
                    error: function( data ) {
                        show_failure("Lectore Update", data);
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
