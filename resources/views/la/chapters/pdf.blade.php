<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        
        <style type="text/css">
            html {
                margin-left: 10mm;
                margin-right: 10mm;
                line-height: 1.5;
                font-size: 15px;
                font-weight: 400;
            }

            body {
                font-family: sans-serif !important;
                font-style: normal;
                font-weight: normal;
                font-size: 20px !important;
                line-height: 1.25 !important;
                color: black;
            }

            p {
                hyphens: auto;
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

            ul li {
                padding-left: 10px;
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
                margin-top: 100px;
                margin-left: 0px;
                margin-right: 0px;
                margin-bottom: 0px;
            }

            main p {
                text-align: justify;
                word-break: break-word;
                word-spacing: 1px;
            }

            main table p {
                text-align: left;
                word-break: break-word;
                word-spacing: 1px;
                margin-bottom: 0;
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
                font-size: 26px;
                text-align: center;
                margin-bottom: .2rem;
            }

            h1, h2 {
                page-break-before: always;
            }

            h2 {
                font-family: sans-serif !important;
                font-size: 20px;
                margin-top: 5px;
                text-decoration: underline;
                font-weight: bold;
                text-align: center;
            }
            
            h3 {
                font-family: sans-serif !important;
                font-size: 20px;
                font-weight: bold;
                text-align: left;
                margin-bottom: .2rem;
                margin-top: 3rem;
            }

            h4 {
                font-family: sans-serif !important;
                text-align: center;
                font-size: 19px;
                font-style: italic;
                margin-bottom: .5rem;
            }

            /*h4:last-of-type {
                margin-bottom: 32px;
            }*/

            h4 + h3 {
                margin-top: 32px;
            }

            /*h5 {
                page-break-before: always;
            }*/

            h6 {
                font-family: sans-serif !important;
                font-size: 20px;
                text-decoration: underline;
                margin-bottom: .5rem;
            }

            h5 {
                color: black !important;
                font-weight: bold;
                text-transform: capitalize;
                font-size: 17px;
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
                empty-cells: hide;
                text-align: left;
                width: 100%;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
                -ms-overflow-style: -ms-autohiding-scrollbar;
                font-size: 14px;
                table-layout: fixed;
                text-align: center;
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

            table td {
                text-align: left;
                word-break: break-word;
                word-spacing: 1px;
                margin-bottom: 0;
                padding: 5px 0px;
            }

            table thead {
                background-color: #c3b088; 
            }
            
            table thead { 
                display: table-header-group; 
                page-break-after: avoid;
            }
            table tr { page-break-inside: avoid; }

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

            .table td {
                padding: .25rem;
            }

            th, td {
                vertical-align: middle !important;
                text-align: left;
                word-break: break-word;
                word-spacing: 1px;
            }

            .table-responsive {
                display: table;
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

            @media (min-width: 768px) {
                .container {
                    /*max-width: 94%;*/
                    max-width: 100%;
                }
            }
        </style>
        <title>Guía del médico de urgencias: {{ $title }}</title>
    </head>
    <body>
        <main role="main" class="container">
            <div class="starter-template">
                {!! $content !!}
                @if(!empty($video))
                    <h3>VIDEO</h3>
                    <div>
                        {{$video}}
                    </div>
                @endif
            </div>
        </main>
    </body>
</html>
