<?php
require_once('fpdf/fpdf.php');


class PDF extends FPDF{
	// Header
    // Le dimensioni sono IN MM. Foglio A4 = 210x297
	function Header(){
    	$id = $_GET['id'];
    	//$barcode = "https://bwipjs-api.metafloor.com/?bcid=qrcode&text=".$id."#.png";
		$barcode = "https://api.qrserver.com/v1/create-qr-code/?data=".$id."#.png";
    	// Logo
    	$this->Image('imgs/BOOKed.jpg',6.5,10,65);
    	$this->Image($barcode,164,10,35);
        // Intestazione
    	$this->SetFont('Arial','B',19);  
    	$this->Cell(0,30,'',0,1);
    	$this->Cell(0,0,'Ricevuta di conferma prenotazione',0,1,'L');
    	$this->Ln(16.5);
	}

	// Footer
	function Footer() {
    	$this->SetY(-15);
    	$this->SetFont('Arial','I',10); 
    	$this->Cell(0,10,'Pagina '.$this->PageNo().'/{nb}',0,0,'C');
	}

}
?>