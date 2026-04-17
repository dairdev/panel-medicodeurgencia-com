Hola {{ $lector->namo }} {{ $lector->surname }},<br><br>

A continuación tienes los datos de tu cuenta<br><br>

Nombre: {{ $lector->namo }} {{ $lector->surname }}<br>
Email: {{ $lector->email }}<br>
Códigos/Fecha de validez: <br>
@foreach($codigos as $code)
   {{ $code->codigo}} / {{ $code->date_validez }}<br>
@endforeach
Gracias
