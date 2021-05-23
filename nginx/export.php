<?php
require_once('class_pdf.php');


if (isset($_GET['id']) && $_GET['id'] != ''){

    $id = $_GET['id'];
    $mysqli = new mysqli('mysql', 'user', 'password', 'PRENOTAZIONI');
    if ($mysqli->connect_errno) {
        die('Connessione al db fallita: '. $mysqli->connect_error); //RIP + F
    }
    $result = $mysqli->query("SELECT * FROM Prenotazioni WHERE ID = '$id'");
    if(mysqli_num_rows($result) != 1){
?>
        <script type="text/javascript">
            window.location.href = 'index.html';
        </script>
<?php
    }
    
    $row = $result->fetch_assoc();
    
    $pdf = new PDF();
	$pdf->AliasNbPages();
	$pdf->AddPage();
    $pdf->SetTextColor(0); 
	$pdf->SetFont('Arial','',13); 
    $pdf->Cell(0,0,'Ciao '.utf8_decode(html_entity_decode($row['Nome'])).' '.utf8_decode(html_entity_decode($row['Cognome'])).',');
    $pdf->Ln(6);
    //$pdf->SetTextColor(16,65,37); verde success boostrap in rgb, me lo salvo qui
    $pdf->Cell(0,0,'la tua prenotazione '.utf8_decode(html_entity_decode('è')).' avvenuta con successo. Ecco di seguito i dati per il tuo ritiro.');
    $pdf->Ln(6);
    
    //Dati
	$pdf->SetFont('Arial','B',13);
    $pdf->Cell(50,10,'ID Prenotazione:',0,0,'L');
	$pdf->SetFont('Arial','',13);
    $pdf->Cell(0,10,utf8_decode(html_entity_decode($row['ID'])),0,0,'L');
    $pdf->Ln(7);
	$pdf->SetFont('Arial','B',13);
    $pdf->Cell(50,10,'Data del Ritiro:',0,0,'L');
	$pdf->SetFont('Arial','',13);
    $pdf->Cell(0,10,utf8_decode(html_entity_decode(date_format(date_create($row['DataRitiro']), 'd/m/Y'))),0,0,'L');
    $pdf->Ln(7);
    $pdf->SetFont('Arial','B',13);
    $pdf->Cell(50,10,'Titolo del Libro:',0,0,'L');
	$pdf->SetFont('Arial','',13);
    $pdf->Cell(0,10,utf8_decode(html_entity_decode($row['NomeLibro'])),0,0,'L');
    $pdf->Ln(7);
	$pdf->SetFont('Arial','B',13);
    $pdf->Cell(50,10,'Autore:',0,0,'L');
	$pdf->SetFont('Arial','',13);
    $pdf->Cell(0,10,utf8_decode(html_entity_decode($row['AutoreLibro'])),0,0,'L');
    $pdf->Ln(7);
	$pdf->SetFont('Arial','B',13);
    $pdf->Cell(50,10,'Luogo del Ritiro:',0,0,'L');
	$pdf->SetFont('Arial','',13);
    $pdf->MultiCell(0,10,utf8_decode(html_entity_decode($row['NomeLuogoRitiro'])).' - '.utf8_decode(html_entity_decode($row['IndirizzoRitiro'])).'',0,'L');

    //Conclusioni
    $pdf->Ln(10);
	$pdf->SetFont('Arial','',13);
    $pdf->Cell(0,0,"Recati nel luogo del ritiro e mostra questa ricevuta all'operatore.",0,1,'L');
    $pdf->Ln(7);
	$pdf->SetFont('Arial','',13);
    $pdf->Cell(0,0,"Grazie per aver scelto i nostri servizi, ciao!",0,1,'L');
    $pdf->Ln(7);
	$pdf->SetFont('Arial','',11);
    $pdf->MultiCell(0,5,'Nota: Se vuoi cancellare la tua prenotazione recati nuovamente sul nostro sito e visita nella sezione "Cancella Prenotazione". Segui poi i passaggi sullo schermo.',0,'L');

    
    $pdf->Output('I','RicevutaPrenotazione.pdf',false);

}else{
?>
    <script type="text/javascript">
        window.location.href = 'index.html';
    </script>
<?php
}

$mysqli -> close();
?>
