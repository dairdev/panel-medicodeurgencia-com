<?php

ini_set('max_execution_time', 1000);
set_time_limit(0);
ini_set('memory_limit', '9999M');


//set_time_limit(0);
//ini_set("memory_limit", "-1"); 

require_once('../tcpdf/tcpdf.php');

// Conexión a la base de datos (modifica los datos según tu configuración)
$servername = "localhost";
$username = "libromdu";
$password = "Mr3sn17453";
$dbname = "panelmdu";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Consulta SQL para obtener los datos de la tabla (modifica la consulta según tus necesidades)
$sql = "SELECT * FROM employees where id = $_GET[id_empresa]";
$result = $conn->query($sql);
// Verificar si se obtuvieron resultados

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $nombre = $row['name'];
    }
}
// Crear nueva instancia de TCPDF
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Establecer información del documento
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('LaraAdmin');
$pdf->SetTitle('Listado de Lectores de Empresa - '.$nombre);
$pdf->SetSubject('Listado de Lectores de Empresa -'.$nombre);
$pdf->SetKeywords('PDF, Lista, Base de Datos, PHP');

// Establecer encabezado y pie de página
//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
$pdf->setFooterData(array(0,64,0), array(0,64,128));

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// Establecer márgenes
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// Establecer autoajuste de página
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// Establecer modo de fuente
$pdf->SetFont('helvetica', '', 12);

// Agregar una página
$pdf->AddPage();

$pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));



// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Consulta SQL para obtener los datos de la tabla (modifica la consulta según tus necesidades)
$sql = "SELECT l.namo,l.surname,l.email,c.codigo,c.date_validez FROM employees e
inner JOIN users u on e.id=u.context_id
inner JOIN lectores l ON u.context_id=l.user_id
inner JOIN codigos c on l.id=c.lectore_id
WHERE c.deleted_at IS NULL AND l.deleted_at IS NULL AND e.deleted_at IS null and e.id=$_GET[id_empresa]";
$result = $conn->query($sql);
// Verificar si se obtuvieron resultados
if ($result->num_rows > 0) {
    
    $html = <<<EOD
<h1>Listado de Lectores de Empresa - $nombre</h1><br>
EOD;
$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

$html = <<<EOD
<style>
      table {
         border:1px solid black;
         padding: 10px;
      }
      th {
         border:1px solid black;
         padding: 20px;
      }
      td{
        border:1px solid black;
        padding: 20px;
        font-size: 10px;
        white-space: nowrap;
     }

      </style>
<table border="1" style="width:100%; margin-left: -120px;" cellpadding="1" cellspacing="1" align="center">
<tbody>
<tr>
<th style="width:90px" class="tg-n97e">Nombre</th>
<th style="width:90px" class="tg-n97e">Apellidos</th>
<th style="width:90px" class="tg-n97e">Email</th>
<th style="width:120px" class="tg-n97e">Codigo</th>
<th style="width:70px">Vencimiento</th>
<th style="width:50px">Estado</th>
</tr>
EOD;


    // Mostrar los datos en el PDF
    while($row = $result->fetch_assoc()) {
        // Aquí puedes agregar los datos a tu PDF
        if(empty($row['date_validez'])){
            $color = 'gris';
            $vencido = 'No activado';
        }elseif($row['date_validez'] < date('Y-m-d')){
            $color = '#FF0000';
            $vencido = 'Caducado';
        }else{
            $color = '#04FF00';
            $vencido = 'Activo';
        }
        $estado = '<span class="'.$color.'"></span>';
        if(!empty($row['date_validez'])){
            $row['date_validez'] = date("d-m-Y", strtotime($row['date_validez']));
        }
        $html = $html. <<<EOD
        <tr>
        <td>$row[namo]</td>
        <td>$row[surname]</td>
        <td>$row[email]</td>
        <td>$row[codigo]</td>
        <td>$row[date_validez]</td>
        <td style="color:$color">$vencido</td>
        </tr>
        EOD;
      
    }
    $html = $html.<<<EOD
</tbody>
</table>
EOD;
$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

} else {
    $pdf->Cell(0, 10, 'No se encontraron resultados de Empresas - '.$nombre, 0, 1);
}

// Cerrar la conexión a la base de datos
$conn->close();

// Salida del PDF
$pdf->Output('Listado de Empresas - '.$nombre.'.pdf', 'D');

?>
