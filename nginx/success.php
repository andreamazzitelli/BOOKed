<?php
include('connect-db.php');

//TEST b338923f818d077af7f6302b41821e26

//Controllo che l'utente venga effettivamente dalla prenotazione e non da altre fonti.
if (isset($_GET['id']) && $_GET['id'] != ''){
    $id_pren = $_GET['id'];
    $query = "SELECT * FROM Prenotazioni WHERE ID = '$id_pren'";
    $result = $mysqli->query($query);
    $row = $result->fetch_assoc();
    $row_cnt = $result->num_rows;
    if($row != '' && $row_cnt == 1){ //Prenotazione con id valido: esiste!

?>

<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BOOKed ~ Prenota, vai, ritira.</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>
    <link href="style.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="font/flaticon.css" />
</head>

<body>
    <script>
        //Scriptino per l'invio delle email
        $(document).ready(function(){
            $.ajax({
                url: "https://localhost:3000/sendmail",
                cache: false,
                type: "POST",
                data: {
                    id: "<?php echo $row['ID']; ?>",
                    data: "<?php echo $row['DataRitiro']; ?>",
                    indirizzo: "<?php echo $row['IndirizzoRitiro']; ?>",
                    autore: "<?php echo $row['AutoreLibro']; ?>",
                    nomeLuogo: "<?php echo $row['NomeLuogoRitiro']; ?>",
                    nomeLibro: "<?php echo $row['NomeLibro']; ?>",
                    email: "<?php echo $row['Email']; ?>",
                },
                dataType: 'json',
                success: function(result) { 
                    if (result == '{"status": "OK"}') {
                        $('div#status-email').addClass('alert-success');
                        $('div#status-email').text('Riceverai a breve una email con tutti i dati della prenotazione.');
                    } else if (result == '{"status": "FAILED"}') {
                        $('div#status-email').addClass('alert-warning');
                        $('div#status-email').text('Purtroppo non è stato possibile inviarti una email con tutti i dati della prenotazione. Puoi comunque salvare la ricevuta tramite questa pagina.');
                    }
                },
                error: function(error) {
                    console.log(error);
                }
            });
        });
    </script>
    <nav class="navbar navbar-expand-lg nav-on-scroll fixed-top position-fixed">
        <div class="container-fluid container">
            <h1><a class="navbar-brand" href="javascript:void();"><i class="flaticon-books"></i> BOOKed > Prenota</a></h1>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="index.html">Torna alla HomePage</a>
                    </li>
                </ul>
                <div class="d-flex mb-2 mb-md-0" style="margin-right: 1rem !important">
                    <a href="apidoc/" class="btn btn-light"><i class="flaticon-settings"></i> API SERVICES</a>
                </div>
                <div class="d-flex">
                    <a href="admin/index.php" class="btn btn-light"><i class="flaticon-settings"></i> ADMIN</a>
                </div>
            </div>
        </div>
    </nav>

    <section class="container" id="prenota-page" style="margin-top: 15vh; padding-bottom: 5vh;">
        <div class="row mt-4">
        <h3>Riepilogo prenotazione</h3>
            <div class="col-12">
            </div> 
            <div class="col-12">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col" class="text-center" colspan="2">               
                                <div class="alert alert-success text-center">Prenotazione avvenuta con successo!!<br>Se vuoi puoi stampare la ricevuta in PDF e/o salvare l'evento sul tuo G-Calendar.</div>
                                <div id="status-email" class="alert text-center"></div>
                                    Ecco un riepilogo dei dati della tua prenotazione 
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row">ID Prenotazione</th>
                            <td><?php echo $row['ID']; ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Nome</th>
                            <td><?php echo $row['Nome']; ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Cognome</th>
                            <td><?php echo $row['Cognome']; ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Email</th>
                            <td><?php echo $row['Email']; ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Data del ritiro</th>
                            <td><?php echo date_format(date_create($row['DataRitiro']), 'd/m/Y'); ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Luogo del ritiro</th>
                            <td><?php echo $row['NomeLuogoRitiro']; ?> in <?php echo $row['IndirizzoRitiro']; ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Libro richiesto</th>
                            <td><?php echo $row['NomeLibro']; ?> di <?php echo $row['AutoreLibro']; ?></td>
                        </tr>
                    </tbody>
                </table>
            </div> 
            <div class="col-12 col-md-6 success-page mt-2">
                <a href="export.php?id=<?php echo $row['ID']; ?>" class="btn btn-outline-primary full-width" target="_blank"><i class="flaticon-pdf"></i>&nbsp;&nbsp;Salva e stampa il PDF</a>
            </div>
            <div class="col-12 col-md-6 success-page mt-2 mb-4">
                <a href="https://localhost:3000/gcalendar/login?id=<?php echo $row['ID']; ?>&data=<?php echo $row['DataRitiro']; ?>&indirizzo=<?php echo $row['IndirizzoRitiro']; ?>&nomeLuogo=<?php echo $row['NomeLuogoRitiro']; ?>&nomeLibro=<?php echo $row['NomeLibro']; ?>&autore=<?php echo $row['AutoreLibro']; ?>" class="btn btn-outline-primary full-width" target="_blank"><i class="flaticon-googlecal"></i>&nbsp;&nbsp;<i class="flaticon-calendar"></i>&nbsp;&nbsp;Salva su Google Calendar </a>
            </div>
        </div>
    </section>

    <footer class="mt-4" id="credits">
        <div class="row text-center">
            <div class="col-12 mb-3">
                <i class="flaticon-books"></i> BOOKed - Ideato e realizzato da Andrea Mazzitelli e Daniele Petrucci
            </div>
            <div class="col-12">
                <a href="mailto:mazzitelli.1835022@studenti.uniroma1.it"><i class="flaticon-email"></i> mazzitelli.1835022@studenti.uniroma1.it</a>
                <br>
                <a href="mailto:petrucci.1840410@studenti.uniroma1.it"><i class="flaticon-email"></i> petrucci.1840410@studenti.uniroma1.it</a>
            </div>
            <div class="col-12 mt-3">
                Si rigranzia <a href="https://www.flaticon.com" target="_blank">Flaticon.com</a> per il font delle icone personalizzate.
            </div>
            <div class="col-12 mt-3">
                Tecnologie utilizzate: HTML5, CSS3, Bootstrap, JavaScript, jQuery, PHP, MySQL, NodeJS, CouchDB
            </div>
        </div>
    </footer>
</body>
</html>

<?php
    }else{ //Come caBBo sei arrivato fin qui? VIA! Pt. 1
    ?>
        <script type="text/javascript">
            window.location.href = 'index.html';
        </script>
    <?php
    }
} else {
    //Come caBBo sei arrivato fin qui? VIA! Pt. 2
?>
    <script type="text/javascript">
        window.location.href = 'index.html';
    </script>
<?php
}
$mysqli -> close();
?> 


<script language="javascript" type="text/javascript">
    function popitup() {
        var url = "localhost:3000/gcalendar/login?id=<?php echo $row['ID']; ?>&data=<?php echo $row['DataRitiro']; ?>&indirizzo=<?php echo $row['IndirizzoRitiro']; ?>&nomeLuogo=<?php echo $row['NomeLuogoRitiro']; ?>&nomeLibro=<?php echo $row['NomeLibro']; ?>&autore=<?php echo $row['AutoreLibro']; ?>";
	    newwindow=window.open(url,'Google Calendar - Salva Eventp','height=500,width=400');
	    if (window.focus) {
            newwindow.focus()
        }
	    return false;
    }
</script>



