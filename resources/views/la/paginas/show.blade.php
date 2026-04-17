@extends('la.layouts.app')

@section('htmlheader_title')
    @lang('la_pagina.pagina_view')
@endsection

@section('main-content')
<div id="page-content" class="profile2">
    <div class="bg-primary clearfix">
        <div class="col-md-4">
            <div class="row">
                <div class="col-md-3">
                    <!--<img class="profile-image" src="{{ asset('la-assets/img/avatar5.png') }}" alt="">-->
                    <div class="profile-icon text-primary"><i class="fa {{ $module->fa_icon }}"></i></div>
                </div>
                <div class="col-md-9">
                    <h4 class="name">{{ $pagina->$view_col }}</h4>
                    
                </div>
            </div>
        </div>
        <div class="col-md-3">
            
        </div>
        <div class="col-md-4">
            <!--
            <div class="teamview">
                <a class="face" data-toggle="tooltip" data-placement="top" title="John Doe"><img src="{{ asset('la-assets/img/user1-128x128.jpg') }}" alt=""><i class="status-online"></i></a>
                <a class="face" data-toggle="tooltip" data-placement="top" title="John Doe"><img src="{{ asset('la-assets/img/user2-160x160.jpg') }}" alt=""></a>
                <a class="face" data-toggle="tooltip" data-placement="top" title="John Doe"><img src="{{ asset('la-assets/img/user3-128x128.jpg') }}" alt=""></a>
                <a class="face" data-toggle="tooltip" data-placement="top" title="John Doe"><img src="{{ asset('la-assets/img/user4-128x128.jpg') }}" alt=""><i class="status-online"></i></a>
                <a class="face" data-toggle="tooltip" data-placement="top" title="John Doe"><img src="{{ asset('la-assets/img/user5-128x128.jpg') }}" alt=""></a>
                <a class="face" data-toggle="tooltip" data-placement="top" title="John Doe"><img src="{{ asset('la-assets/img/user6-128x128.jpg') }}" alt=""></a>
                <a class="face" data-toggle="tooltip" data-placement="top" title="John Doe"><img src="{{ asset('la-assets/img/user7-128x128.jpg') }}" alt=""></a>
                <a class="face" data-toggle="tooltip" data-placement="top" title="John Doe"><img src="{{ asset('la-assets/img/user8-128x128.jpg') }}" alt=""></a>
                <a class="face" data-toggle="tooltip" data-placement="top" title="John Doe"><img src="{{ asset('la-assets/img/user5-128x128.jpg') }}" alt=""></a>
                <a class="face" data-toggle="tooltip" data-placement="top" title="John Doe"><img src="{{ asset('la-assets/img/user6-128x128.jpg') }}" alt=""><i class="status-online"></i></a>
                <a class="face" data-toggle="tooltip" data-placement="top" title="John Doe"><img src="{{ asset('la-assets/img/user7-128x128.jpg') }}" alt=""></a>
            </div>
            -->
            
        </div>
        <div class="col-md-1 actions">
            @la_access("Paginas", "edit")
                <a @ajaxload href="{{ url(config('laraadmin.adminRoute') . '/paginas/'.$pagina->id.'/edit') }}" class="btn btn-xs btn-edit btn-default"><i class="fa fa-pencil"></i></a><br>
            @endla_access
            
            @la_access("Paginas", "delete")
                {{ Form::open(['route' => [config('laraadmin.adminRoute') . '.paginas.destroy', $pagina->id], 'method' => 'delete', 'style'=>'display:inline']) }}
                    <button class="btn btn-default btn-delete btn-xs" type="submit"><i class="fa fa-times"></i></button>
                {{ Form::close() }}
            @endla_access
        </div>
    </div>

    <ul data-toggle="ajax-tab" class="nav nav-tabs profile" role="tablist">
        <li class=""><a @ajaxload href="{{ url(config('laraadmin.adminRoute') . '/paginas') }}" data-toggle="tooltip" data-placement="right" title="@lang('la_pagina.back_to_paginas')"><i class="fa fa-chevron-left"></i></a></li>
        <li class="active"><a role="tab" data-toggle="tab" class="active" href="#tab-info" data-target="#tab-info"><i class="fa fa-bars"></i> @lang('common.general_info')</a></li>
        <li class=""><a role="tab" data-toggle="tab" href="#tab-timeline" data-target="#tab-timeline"><i class="fa fa-clock-o"></i> @lang('common.timeline')</a></li>
    </ul>

    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active fade in" id="tab-info">
            <div class="tab-content">
                <div class="panel infolist">
                    <div class="panel-default panel-heading">
                        <h4>@lang('common.general_info')</h4>
                    </div>
                    <div class="panel-body">
                        @la_display($module, 'titulo')
						@la_display($module, 'contenido')
                    </div>
                </div>
            </div>
        </div>
        <div role="tabpanel" class="tab-pane fade in p20 bg-white" id="tab-timeline">
            @php
            $timeline = $pagina->timeline();
            @endphp
            @if(count($timeline) > 0)
                <ul class="timeline timeline-inverse">
                    @foreach($timeline as $log)
                        @php
                        $icon = "fa-clock-o";
                        $iconColor = "bg-blue";
                        if(str_contains($log->type, "CREATED")) {
                            $icon = "fa-plus";
                            $iconColor = "bg-green";
                        } else if(str_contains($log->type, "UPDATED")) {
                            $icon = "fa-pencil";
                            $iconColor = "bg-orange";
                        } else if(str_contains($log->type, "DELETED")) {
                            $icon = "fa-times";
                            $iconColor = "bg-red";
                        }

                        @endphp
                        <li class="time-label"><span class="{{ $iconColor }}">{{ date("d M, Y", strtotime($log->created_at)) }}</span></li>
                        <li>
                            <i class="fa {{ $icon }} {{ $iconColor }}"></i>
                            <div class="timeline-item">
                                <span class="time"><i class="fa fa-clock-o"></i> {{ date("g:ia", strtotime($log->created_at)) }}</span>
                                <h3 class="timeline-header no-border">{{ $log->title }}</h3>
                                @if(!str_contains($log->type, "DELETED"))
                                    <div class="timeline-body">
                                        @php
                                            $log_content = json_decode($log->content, true);
                                        @endphp
                                        <ul class="log-changes">
                                            @foreach ($log_content as $key => $logitem)
                                                @php
                                                    if($key == "created_at" || $key == "updated_at") {
                                                        if(str_contains($log->type, "UPDATED")) {
                                                            $logitem['old'] = LAHelper::dateFormat($logitem['old']);
                                                            $logitem['new'] = LAHelper::dateFormat($logitem['new']);
                                                        } else if(str_contains($log->type, "CREATED")) {
                                                            $logitem = LAHelper::dateFormat($logitem);
                                                        }
                                                    }
                                                @endphp
                                                @if(str_contains($log->type, "UPDATED"))
                                                    <li><span class="key"><a class="label label-warning">{{ $key }}</a></span> <span class="old">{{ $logitem['old'] }}</span> <i class="fa fa-arrow-right"></i> <span class="new">{{ $logitem['new'] }}</span></li>
                                                @elseif(str_contains($log->type, "CREATED"))
                                                    <li><span class="key"><a class="label label-success">{{ $key }}</a></span> <span class="old">{{ $logitem }}</span></li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                @if(isset($log->user->id))
                                    <div class="timeline-footer">
                                        @if(str_contains($log->type, "UPDATED"))
                                            <a href="{{ url(config('laraadmin.adminRoute') . '/users/'.$log->user->id) }}" class="btn btn-warning btn-xs">Updated by {{ $log->user->name }}</a>
                                        @elseif(str_contains($log->type, "CREATED"))
                                            <a href="{{ url(config('laraadmin.adminRoute') . '/users/'.$log->user->id) }}" class="btn btn-success btn-xs">Created by {{ $log->user->name }}</a>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </li>
                    @endforeach
                    <!-- END timeline item -->
                    <li><i class="fa fa-clock-o bg-gray"></i></li>
                </ul>
            @else
                <div class="text-center p30"><i class="fa fa-list-alt" style="font-size: 100px;"></i> <br> No logs to show</div>
            @endif
        </div>
    </div>
</div>
@endsection
