<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        
        <style type="text/css">
            html {
                line-height: 1.5;
                font-size: 12px;
                font-weight: 400;
            }

            body {
                font-family: sans-serif !important;
                font-style: normal;
                font-weight: normal;
                font-size: 12px !important;
                line-height: 1.25 !important;
                color: black;
                word-break: break-word;
            }

            table {
                word-break: normal;   
            }

            ul, ol {
                text-align: justify;
            }
            
            ul {
                padding-inline-start: 24px;
                list-style-type: disc;
                list-style-position: outside;
            }

            ol {
                list-style-type: disc;
            }

            li ol {
                border: none;
                padding-top: 0;
                padding-bottom: 0;
            }

            /*em {
                display: block;
                margin-left: auto;
                margin-right: auto;
                width: 90%;
                font-size: 70%;
                font-weight: 400;
                color: rgb(141,105,30) !important;
                padding: 10px;
            }*/

            blockquote {
                display: block;
                margin-left: auto;
                margin-right: auto;
                width: 60%;
                font-size: 70%;
                font-weight: 400;
                color: rgb(141,105,30) !important;
                padding: 10px; 
            }

            blockquote p {
                text-align: center !important;
                margin-bottom: 0.25rem;
            }

            main {
                margin-left: 0px;
                margin-right: 0px;
                margin-bottom: 0px;
            }

            main p {
                text-align: justify;
                word-spacing: 1px;
                font-size: 12px !important;
            }

            main p span{
                font-size: 12px !important;
            }

            li p {
                margin-bottom: 0.3rem;
            }

            h1, h2, h3 {
                color: rgb(141,105,30) !important;
                text-transform: uppercase;
            }

            h6 {
                color: rgb(141,105,30) !important;
            }

            h1 {
                font-family: sans-serif !important;
                font-weight: bold;
                font-size: 16px;
                text-align: center;
                margin-bottom: .2rem;
            }

            h1, h2 {
                page-break-before: always;
            }

            h2 {
                font-family: sans-serif !important;
                font-size: 13px;
                margin-top: 5px;
                text-decoration: underline;
                font-weight: bold;
                text-align: center;
            }
            
            h3 {
                font-family: sans-serif !important;
                font-size: 13px;
                font-weight: bold;
                text-align: left;
                margin-bottom: .2rem;
            }

            h4 {
                font-family: sans-serif !important;
                text-align: center;
                font-size: 12px;
                font-style: italic;
                margin-bottom: .5rem;
            }

            /*h4:last-of-type {
                margin-bottom: 32px;
            }*/

            h4 + h3 {
                margin-top: 12px;
            }

            /*h5 {
                page-break-before: always;
            }*/

            h6 {
                font-family: sans-serif !important;
                font-size: 12px;
                text-decoration: underline;
                margin-bottom: .5rem;
            }

            h5 {
                color: black !important;
                font-weight: bold;
                font-size: 12px;
            }

            .img-fluid {
                display: block;
                margin-left: auto;
                margin-right: auto;
                max-width: 60%;
            }

            h2 + p > img {
                max-width: 100% !important;   
            }

            .img-thumbnail {
                padding: 0;
                border: none;
                border-radius: 0;
            }

            td img.img-fluid {
                max-width: 100%;
            }

            table {
                margin-bottom: 10px;
                text-align: center;
                width: 100%;
                overflow-x: auto;
                display: inherit;
                -webkit-overflow-scrolling: touch;
                -ms-overflow-style: -ms-autohiding-scrollbar;
                font-size: 12px;
                table-layout: auto;
            }   

            table td p {
                margin-bottom: 0.25rem;
                text-align: left;
            }

            table.center {
                text-align: center;
            }   

            td img {
                display: block;
                margin-left: auto;
                margin-right: auto;
            }

            img {
                margin-bottom: 15px;
            }

            table td {
                text-align: left;
                margin-bottom: 0;
                padding: 5px 0px;
            }

            thead {
                background-color: #c3b088;
            }

            /*
            tr td:first-child {
                background-color: #c3b088;
            }

            tr th:first-child {
                border: none !important;
                background-color: white;
            }*/

            tr th {
                font-family: sans-serif !important;
            }

            .table-striped tbody tr:nth-of-type(odd) {
                background-color: rgba(0,0,0,.1) !important;
            }

            .table-bordered {
                /*border: 1px solid rgb(141,105,30) !important;*/
                border: none !important;
            }

            .table-bordered td, .table-bordered th {
                border: 1px solid rgb(141,105,30) !important;
            }

            .td-fill {
                background-color: rgb(227, 194, 125);
            }

            .table-responsive {
                display: table;
            }

            .table td {
                padding: .25rem .75rem;
            }

            th, td {
                vertical-align: middle !important;
                text-align: left;
            }

            ol {
                border: 1px solid rgb(141,105,30);
                padding-top: 12px;
                padding-bottom: 12px;
                padding-right: 12px;
                padding-inline-start: 30px;
                background-color: rgb(228, 221, 206);
            }

            b, strong {
                font-family: sans-serif !important;
            }

            .container {
                max-width: 100%;
            }

            @media (min-width: 768px) {
                .container {
                    /*max-width: 94%;*/
                    max-width: 100%;
                }
            }

            @media (max-width: 767px) {
                .img-fluid {
                    max-width: 80%;
                }

                td img.img-fluid {
                    max-width: 100%;
                }

                blockquote {
                    width: 80%;
                }
            }
        </style>
        <style>
            #youtube-player {position: relative;padding-bottom:56.23%;height:0;overflow:hidden;max-width:100%;background:#000;margin:8px 0 8px 0;}
            #youtube-player iframe {position:absolute;top: 0;left:0;width:100%;height:100%;z-index:100;background: transparent;}

            .video-responsive {
                height: 0;
                overflow: hidden;
                padding-bottom: 56.25%;
                padding-top: 30px;
                position: relative;
            }
            .video-responsive iframe, .video-responsive object, .video-responsive embed {
                height: 100%;
                left: 0;
                position: absolute;
                top: 0;
                width: 100%;
            }
        </style>
        <title>Guía del médico de urgencias: {{ $title }}</title>
    </head>
    <body>
        <main role="main" class="container">
            <div class="starter-template">
                {!! $content !!}
                <h3>VIDEOS</h3>
                <br>
                @if(!empty($video))
                    @php $videos = explode("\n",$video); @endphp
                    @foreach($videos as $video)
                        @php preg_match("#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+(?=\?)|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#", $video, $matches); @endphp
                        @if(!empty($matches[0]))
                            <div id="youtube-player-id" class="video-responsive" data-id="{{ $matches[0] }}">
                                <iframe src="https://www.youtube.com/embed/{{ $matches[0] }}" frameborder="0" allowfullscreen="1" width="100%" height="100%"></iframe>
                            </div>
                            <br>
			@else
                            <div class="video-responsive" >
                                <iframe src="{{ $video }}" frameborder="0" allowfullscreen="1" width="100%" height="100%"></iframe>
                            </div>
                            <br>
                        @endif
                    @endforeach
                @else
                    <p>No hay videos en este capítulo</p>
                @endif

                @if($user_id)
                    <div id="bottom" style="display: flex;flex-direction: column; align-items: center;">
                        <form action="/admin/comment/store" method="post" style="width: 100%;">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <h3 style="text-align: left; width: 100%;">
                                Notas 
                                <button type="button" class="btn btn-warning" style="color: #FFF;background-color: #8e6a12; border-color: #8e6a12;font-size: 14px; width: 20px;height: 20px;padding: 0px;line-height: 0;" onclick="toggle()">+</button>
                            </h3>
                            <div id="bloque_notas" style="display:none">
                                <textarea name="notes" style="width: 100%;" rows=10>{{ $note ? $note->note : '' }}</textarea>
                                <input type="hidden" name="user_id" value="{{ $user_id}}">
                                <input type="hidden" name="type" value="{{ $type }}">
                                <input type="hidden" name="c_id" value="{{ $c_id }}">
                                <input type="hidden" name="code" value="{{ $code }}">
                                <input type="hidden" name="id" value="{{ $note ? $note->id : '' }}">

                                <button type="submit" class="btn btn-warning" style="color: #FFF;background-color: #8e6a12; border-color: #8e6a12;
        font-size: 14px;">Salvar notas</button>
                            </div>
                        </form>
                    </div>
                @endif
                <br>
            </div>
        </main>

        <script>
            function toggle() {
                var x = document.getElementById("bloque_notas");
                if (x.style.display === "none") {
                    x.style.display = "block";
                } else {
                    x.style.display = "none";
                }
                window.scrollTo(0, document.body.scrollHeight);
            }
        </script
    </body>
</html>
