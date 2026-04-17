@extends("la.layouts.app")

@section("contentheader_title")
    <a @ajaxload href="{{ url(config('laraadmin.adminRoute') . '/lectores') }}">@lang('la_lectore.lectores')</a> :
@endsection
@section("contentheader_description", $lectore->$view_col)
@section("section", app('translator')->get('la_lectore.lectores'))
@section("section_url", url(config('laraadmin.adminRoute') . '/lectores'))
@section("sub_section", app('translator')->get('common.edit'))

@section("htmlheader_title", app('translator')->get('la_lectore.lectore_edit')." : ".$lectore->$view_col)

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
                {!! Form::model($lectore, ['route' => [config('laraadmin.adminRoute') . '.lectores.update', $lectore->id ], 'method'=>'PUT', 'id' => 'lectore-edit-form']) !!}
                    {{--@la_form($module)--}}
                    
                    
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
					{{--@la_input($module, 'password')--}}
					@la_input($module, 'notas')
                    
                    <br>
                    <div class="form-group">
                        {!! Form::button( app('translator')->get('common.update'), ['class'=>'btn btn-success', 'type'=>'submit']) !!} <a @ajaxload href="{{ url(config('laraadmin.adminRoute') . '/lectores') }}" class="btn btn-default pull-right">@lang('common.cancel')</a>
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
  function showPass() {
  		var x = document.getElementById("password");
  		if (x.type === "password") {
    		x.type = "text";
  		} else {
    		x.type = "password";
  		}
	}
    @la_access("Lectores", "edit")
    // Edit Lectore REST Request
    submitBtn = $('#lectore-edit-form button[type=submit]');
    formObj = $("#lectore-edit-form");

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
                        show_success("Lectore Update", data);
                    } else {
                        show_failure("Lectore Update", data);
                    }
                    submitBtn.html('Update');
                    submitBtn.prop('disabled', false);
                    if(isset(data.redirect)) {
                        window.location.href = data.redirect;
                    }
                },
                error: function( data ) {
                    show_failure("Lectore Update", data);
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
</script>
@endpush
