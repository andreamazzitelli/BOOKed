<?php
function renderForm($nomeluogoritiro, $indirizzoritiro, $nomelibro, $autorelibro, $nome, $cognome, $email, $dataritiro, $error)
{
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
    <script src="autocomplete.js"></script>
    <style>
        
        /*the container must be positioned relative:*/
        /*.autocomplete {
            position: relative;
            display: inline-block;
        }*/

        .autocomplete-items {
            position: absolute;
            border: 1px solid #d4d4d4;
            border-bottom: none;
            border-top: none;
            z-index: 99;
            /*position the autocomplete items to be the same width as the container:*/
            top: 100%;
            left: 0;
            right: 0;
        }

        .autocomplete-items div {
            padding: 10px;
            cursor: pointer;
            background-color: #fff;
            border-bottom: 1px solid #d4d4d4;
        }

        /*evidenzio il div quando ci posso sopra con il mouse */
        .autocomplete-items div:hover {
            background-color: #e9e9e9;
        }

        /*evidenzio il div quando mi ci muovo con la freccia*/
        .autocomplete-active {
            background-color: DodgerBlue !important;
            color: #ffffff;
        }
    </style>
</head>

<body>
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
        <h3>Prenotazione</h3>
            <div class="col-12">
                <div class="alert alert-warning">Tutti i campi sono obbligatori e devono essere compilati. Il ritiro può avvenire a partire da domani in poi.</div>
            </div>
            <?php
                // se ci sono errori vengono visualizzati
                if ($error != ''){
                    echo '<div class="col-12"><div class="alert alert-danger">'.$error.'</div></div>';
                }
            ?>  
        </div>
        <form method="post" action="" autocomplete="nope" spellcheck="false">
            <div class="row mt-4">
                <div class="col-12 col-md-6">
                    <div class="input-group mb-2">
                        <span class="input-group-text">Luogo del ritiro:</span>
                        <input type="text" class="form-control" id="nomeluogoritiro" name="nomeluogoritiro" value="<?php echo $nomeluogoritiro; ?>" required readonly >
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="input-group mb-2">
                        <span class="input-group-text">Indirizzo del ritiro:</span>
                        <input type="text" class="form-control" id="indirizzoritiro" name="indirizzoritiro" value="<?php echo $indirizzoritiro; ?>" required readonly>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="input-group mb-2">
                        <span class="input-group-text">Nome del libro:</span>
                        <input type="text" class="form-control" id="nomelibro" name="nomelibro" required minlength="3" value="<?php echo $nomelibro; ?>" autocomplete="off">
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="input-group mb-2">
                        <span class="input-group-text">Autore:</span>
                        <input type="text" class="form-control" id="autorelibro" name="autorelibro" required minlength="3" value="<?php echo $autorelibro; ?>" autocomplete="off">
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="input-group mb-2">
                        <span class="input-group-text">Il tuo nome:</span>
                        <input type="text" class="form-control" id="nome" name="nome" required minlength="3" value="<?php echo $nome; ?>">
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="input-group mb-2">
                        <span class="input-group-text">Il tuo cognome:</span>
                        <input type="text" class="form-control" id="cognome" name="cognome" required minlength="3" value="<?php echo $cognome; ?>">
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="input-group mb-2">
                        <span class="input-group-text">La tua email:</span>
                        <input type="email" class="form-control" id="email" name="email" required minlength="3" value="<?php echo $email; ?>">
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="input-group mb-2">
                        <span class="input-group-text">Data del ritiro:</span>
                        <input type="date" class="form-control" id="dataritiro" name="dataritiro" required min="<?php echo date("Y-m-d", strtotime('tomorrow'));?>" value="<?php echo $dataritiro; ?>">
                    </div>
                </div>
                <div class="col-12 col-md-6">                
                    <button type="submit" class="btn btn-success full-width" name="submit" id="submit"><i class='flaticon-successo'></i> Procedi con la prenotazione!</button>
                </div>
                <div class="col-12 col-md-6">
                    <button type="reset" class="btn btn-danger full-width" name="reset" id="reset" onclick="window.location.replace('index.html')" ><i class='flaticon-annulla'></i> Annulla e riprova dall'inizio...</button>
                </div>
            </div>
        </form>
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
}
 
include('connect-db.php');

//Controllo che l'utente venga effettivamente dalla mappa e non da altre fonti.
if (isset($_GET['luogo']) && isset($_GET['indirizzo']) && $_GET['luogo'] != '' && $_GET['indirizzo'] != ''){
    // verifica se il modulo è stato inviato. Se lo è, inizia a elaborare il modulo e lo salva nel database
    if (isset($_POST['submit'])){
        $nomeluogoritiro = $mysqli->real_escape_string(htmlspecialchars($_POST['nomeluogoritiro']));
        $indirizzoritiro = $mysqli->real_escape_string(htmlspecialchars($_POST['indirizzoritiro']));
        $nomelibro       = $mysqli->real_escape_string(htmlspecialchars($_POST['nomelibro']));
        $autorelibro     = $mysqli->real_escape_string(htmlspecialchars($_POST['autorelibro']));
        $nome            = $mysqli->real_escape_string(htmlspecialchars($_POST['nome']));
        $cognome         = $mysqli->real_escape_string(htmlspecialchars($_POST['cognome']));
        $email           = $mysqli->real_escape_string(htmlspecialchars($_POST['email']));
        $dataritiro      = $mysqli->real_escape_string(htmlspecialchars($_POST['dataritiro']));
        $str_per_id      = $email.$dataritiro.$nomelibro;
        $id              = md5($str_per_id); 

        //Controllo campi compilati
        if ($nomeluogoritiro == '' || $indirizzoritiro == '' || $nomelibro == '' || $autorelibro == '' || $nome == '' || $cognome == '' || $email == '' || $dataritiro == ''){
            // genera messaggio di errore
            $error = 'ERRORE: tutti i campi devono essere compilati. Quante vorte te lo devo da dì???';
            // se uno dei campi è vuoto, visualizzo di nuovo il modulo
            renderForm($nomeluogoritiro, $indirizzoritiro, $nomelibro, $autorelibro, $nome, $cognome, $email, $dataritiro, $error);
        } else {
            // salva i dati nel database
            $sql = "INSERT INTO Prenotazioni (ID, Email, DataRitiro, NomeLuogoRitiro, IndirizzoRitiro, NomeLibro, AutoreLibro, Nome, Cognome) VALUES ('$id', '$email', '$dataritiro', '$nomeluogoritiro', '$indirizzoritiro', '$nomelibro', '$autorelibro', '$nome', '$cognome')";
            mysqli_query($mysqli, $sql);
            if(mysqli_error($mysqli) != ''){// Errore mysql or die(mysqli_error($mysqli));
                echo '<div style="text-align: center; width: 100%; font-size: 1.25rem;"><div>Oh no... qualcosa è andato storto! :/</div>';
                echo mysqli_error($mysqli);
                echo '<div>Redirect alla homepage in automatico tra 5 secondi... Riprova...</div></div>';
            ?>
                <script type="text/javascript">
                setTimeout(function() {
                    window.location.href = 'index.html';                  
                }, 5000);
                </script>
            <?php
            // DIscordo su telegram riguardo all'ELSE
            } else {// Il salvataggio nel database è invece andato bene, redirect alla pagina di successo!  
            ?>
                <script type="text/javascript">
                    window.location.href = 'success.php?id=<?php echo $id ?>';        
                </script>
            <?php
            }
        }
    } else {
        renderForm($_GET['luogo'],$_GET['indirizzo'],'','','','','','','');
    }
} else {
    //Come caBBo sei arrivato fin qui? VIA!
?>
    <script type="text/javascript">
        window.location.href = 'index.html';
    </script>
<?php
}

?> 

<script>
    //array che contiene la coppia ["Titolo", "Autore"]
    var book_list = [
    <?php
        // ottiene i risultati dal database
        $result = $mysqli -> query('SELECT * FROM Libri');
        if (mysqli_num_rows($result) > 0) {
            while ($row = $result -> fetch_assoc()) {
                echo '["'.$row['NomeLibro'].'", "'.$row['AutoreLibro'].'"],';

            }
        } else {
            echo '["",""]'; //Array vuoto
        }
    ?>
    ];
    //attivo l'autocomplete sull'input 
    autocomplete(document.getElementById("nomelibro"), document.getElementById("autorelibro"), book_list, 0);
    autocomplete(document.getElementById("autorelibro"), document.getElementById("nomelibro"), book_list, 1);
        
</script>

<?php
    $mysqli -> close();
?>
