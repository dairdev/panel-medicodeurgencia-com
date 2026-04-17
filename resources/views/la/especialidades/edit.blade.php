@extends("la.layouts.app")

@section("contentheader_title")
    <a @ajaxload href="{{ url(config('laraadmin.adminRoute') . '/especialidades') }}">@lang('la_especialidade.especialidades')</a> :
@endsection
@section("contentheader_description", $especialidade->$view_col)
@section("section", app('translator')->get('la_especialidade.especialidades'))
@section("section_url", url(config('laraadmin.adminRoute') . '/especialidades'))
@section("sub_section", app('translator')->get('common.edit'))

@section("htmlheader_title", app('translator')->get('la_especialidade.especialidade_edit')." : ".$especialidade->$view_col)

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
                {!! Form::model($especialidade, ['route' => [config('laraadmin.adminRoute') . '.especialidades.update', $especialidade->id ], 'method'=>'PUT', 'id' => 'especialidade-edit-form']) !!}
                    @la_form($module)
                    
                    {{--
                    @la_input($module, 'name')
					@la_input($module, 'image')
					@la_input($module, 'orden')
					@la_input($module, 'pdf')
                    --}}
                    <br>
                    <div class="form-group">
                        {!! Form::button( app('translator')->get('common.update'), ['class'=>'btn btn-success', 'type'=>'submit']) !!} <a @ajaxload href="{{ url(config('laraadmin.adminRoute') . '/especialidades') }}" class="btn btn-default pull-right">@lang('common.cancel')</a>
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
    @la_access("Especialidades", "edit")
    // Edit Especialidade REST Request
    submitBtn = $('#especialidade-edit-form button[type=submit]');
    formObj = $("#especialidade-edit-form");

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
                        show_success("Especialidade Update", data);
                    } else {
                        show_failure("Especialidade Update", data);
                    }
                    submitBtn.html('Update');
                    submitBtn.prop('disabled', false);
                    if(isset(data.redirect)) {
                        window.location.href = data.redirect;
                    }
                },
                error: function( data ) {
                    show_failure("Especialidade Update", data);
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
