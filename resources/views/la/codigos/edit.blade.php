@extends("la.layouts.app")

@section("contentheader_title")
    <a @ajaxload href="{{ url(config('laraadmin.adminRoute') . '/codigos') }}">@lang('la_codigo.codigos')</a> :
@endsection
@section("contentheader_description", $codigo->$view_col)
@section("section", app('translator')->get('la_codigo.codigos'))
@section("section_url", url(config('laraadmin.adminRoute') . '/codigos'))
@section("sub_section", app('translator')->get('common.edit'))

@section("htmlheader_title", app('translator')->get('la_codigo.codigo_edit')." : ".$codigo->$view_col)

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
                {!! Form::model($codigo, ['route' => [config('laraadmin.adminRoute') . '.codigos.update', $codigo->id ], 'method'=>'PUT', 'id' => 'codigo-edit-form']) !!}
                    {{--@la_form($module)--}}
                    
                    
                  {{--  @la_input($module, 'codigo')
					@la_input($module, 'date_compra')
					@la_input($module, 'date_validez')
					@la_input($module, 'lectore_id')--}}
					@la_input($module, 'type')
					{{--@la_input($module, 'user_id')
					@la_input($module, 'renove')--}}
          <?php 
          if(auth()->user()->roles[0]->id == 3){
            $lectores = App\Models\Lectore::where('user_id', Auth::user()->id)->whereNull('deleted_at')->get();
          }else{
            $lectores = App\Models\Lectore::whereNull('deleted_at')->get();	
          }
        ?>
				
			<input type="hidden" name="codigo" value="{{ $codigo->codigo }}">
				
        <div class="form-group">
          <label for="lectore_id">Lector* :</label>
          <select class="form-control select2-hidden-accessible" required="1" data-placeholder="Enter Lector" rel="select2" name="lectore_id" tabindex="-1" aria-hidden="true" aria-required="true">
            @foreach($lectores as $lector)
              <option value="{{ $lector->id }}" {{ $codigo->lectore_id == $lector->id ? 'selected' : '' }}>{{ $lector->namo }} {{ $lector->surname }}</option>
            @endforeach
          </select>
        </div>
                    <br> 
                    <div class="form-group">
                        {!! Form::button( app('translator')->get('common.update'), ['class'=>'btn btn-success', 'type'=>'submit']) !!} <a @ajaxload href="{{ url(config('laraadmin.adminRoute') . '/codigos') }}" class="btn btn-default pull-right">@lang('common.cancel')</a>
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
    @la_access("Codigos", "edit")
    // Edit Codigo REST Request
    submitBtn = $('#codigo-edit-form button[type=submit]');
    formObj = $("#codigo-edit-form");

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
                        show_success("Codigo Update", data);
                    } else {
                        show_failure("Codigo Update", data);
                    }
                    submitBtn.html('Update');
                    submitBtn.prop('disabled', false);
                    if(isset(data.redirect)) {
                        window.location.href = data.redirect;
                    }
                },
                error: function( data ) {
                    show_failure("Codigo Update", data);
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
