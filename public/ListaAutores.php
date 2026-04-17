<?php
require_once('../tcpdf/tcpdf.php');

// Crear nueva instancia de TCPDF
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Establecer información del documento
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('LaraAdmin');
$pdf->SetTitle('Listado de Autores');
$pdf->SetSubject('Listado de Autores');
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
$sql = "SELECT * FROM authores where deleted_at is null";
$result = $conn->query($sql);
// Verificar si se obtuvieron resultados
if ($result->num_rows > 0) {
    
    $html = <<<EOD
<h1>Listado de Autores</h1><br>
EOD;
$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

$html = <<<EOD
<style type="text/css">
.tg  {border-collapse:collapse;border-spacing:0;}
.tg td{border-color:black;border-style:solid;border-width:1px;font-family:Arial, sans-serif;font-size:14px;
  overflow:hidden;padding:10px 5px;word-break:normal;}
.tg th{border-color:black;border-style:solid;border-width:1px;font-family:Arial, sans-serif;font-size:14px;
  font-weight:normal;overflow:hidden;padding:10px 5px;word-break:normal;}
.tg .tg-8d8h{background-color:#67fd9a;color:#000000;font-family:"Arial Black", Gadget, sans-serif !important;font-size:18px;
  text-align:left;vertical-align:top}
.tg .tg-ltad{font-size:14px;text-align:left;vertical-align:top}
</style>
<table class="tg">
<tbody>
<tr>
<th class="tg-n97e">Id</th>
<th class="tg-n97e">Nombre</th>
<th class="tg-n97e">Cargo</th>
<th class="tg-n97e">Lugar</th>
</tr>
EOD;


    // Mostrar los datos en el PDF
    while($row = $result->fetch_assoc()) {
        // Aquí puedes agregar los datos a tu PDF

        $html = $html. <<<EOD
        <tr>
        <td class="tg-ltad">$row[id]</td>
        <td class="tg-ltad">$row[name]</td>
        <td class="tg-ltad">$row[cargo]</td>
        <td class="tg-ltad">$row[lugar]</td>
        </tr>
        EOD;
      
    }
    $html = $html.<<<EOD
</tbody>
</table>
EOD;
$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

} else {
    $pdf->Cell(0, 10, 'No se encontraron resultados de Autores', 0, 1);
}

// Cerrar la conexión a la base de datos
$conn->close();

// Salida del PDF
$pdf->Output('Listado de Autores.pdf', 'D');

?>
