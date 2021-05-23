<?php
   session_start();  
   $user_check = $_SESSION['login_user'];   
   include('../connect-db.php');
   $ses_sql = mysqli_query($mysqli,"SELECT * FROM Users WHERE username = '$user_check' ");  
   $row = mysqli_fetch_array($ses_sql,MYSQLI_ASSOC);  
   $login_session = $row['username'];   
   if(!isset($_SESSION['login_user'])){
      echo('<script type="text/javascript">window.location.href = "login.php?message=2";</script>');
   }
?>
<html>  
<head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
	<title>BookED ADMIN</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>
    <link href="../style.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="../font/flaticon.css" />
</head>
<body>
    <nav class="navbar navbar-expand-lg nav-on-scroll fixed-top position-fixed">
        <div class="container-fluid container">
            <h1><a class="navbar-brand" href="javascript:void();"><i class="flaticon-books"></i> BOOKed > Admin Panel</a></h1>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="../index.html">Torna alla HomePage</a>
                    </li>
                </ul>
                <div class="d-flex">
                    <!-- Lascio il div invece di solo a per farlo vedere meglio da mobile -->
                    <a href="logout.php" class="btn btn-light">Ciao <?php echo $login_session ?>, Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <section  class="container" id="prenota-page" style="margin-top: 15vh; padding-bottom: 5vh;">
        <div class="row mt-4">
            <h3>Control Panel</h3>
        </div>
        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="pills-libri-tab" data-bs-toggle="pill" data-bs-target="#pills-libri" type="button" role="tab" aria-controls="pills-libri" aria-selected="false">Libri Disponibili</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link " id="pills-prenotazioni-tab" data-bs-toggle="pill" data-bs-target="#pills-prenotazioni" type="button" role="tab" aria-controls="pills-prenotazioni" aria-selected="true">Riepilogo Prenotazioni</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="pills-logs-tab" data-bs-toggle="pill" data-bs-target="#pills-logs" type="button" role="tab" aria-controls="pills-logs" aria-selected="false">Console Logs</button>
            </li>
        </ul>
        <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade show active" id="pills-libri" role="tabpanel" aria-labelledby="pills-libri-tab">
                <div class="row">
                    <div class="col-12 col-md-8">
                        <div class="alert alert-info text-center">Ecco tutti i libri attualmente disponibili e prenotabili dagli utenti.</div>
                    </div>
                    <div class="col-12 col-md-4">                    
                        <button type="button" class="btn btn-success text-center full-width" style="margin-top: .5rem;" data-bs-toggle="modal" data-bs-target="#addnewbook">
                            Aggiungi nuovo libro
                        </button>
                    </div>
                </div>

                <!-- Nuovo Libro Aggiungi -->
                <div class="modal fade" id="addnewbook" tabindex="-1" aria-labelledby="addnewbookLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form method="POST" action="">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="addnewbookLabel">Aggiungi nuovo libro</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="input-group mb-2">
                                                <span class="input-group-text">Titolo:</span>
                                                <input type="text" class="form-control" id="nomelibro" name="nomelibro" required minlength="3">
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="input-group mb-2">
                                                <span class="input-group-text">Autore:</span>
                                                <input type="text" class="form-control" id="autorelibro" name="autorelibro" required minlength="3">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                                    <button type="submit" class="btn btn-success" name="submit_addbook" >Aggiungi libro!</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>


                <table class="table">
                    <thead>
                        <tr>
                            <th width="45%">Titolo</th>
                            <th width="45%">Autore</th>
                            <th width="10%">Rimuovi</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        $result = $mysqli->query("SELECT * FROM Libri");
                        $row_cnt = $result->num_rows;
                        if($row_cnt != 0){
                            while ($row = $result->fetch_assoc()) {
                    ?>
                                <tr>
                                    <td><?php echo $row['NomeLibro']; ?></td>
                                    <td><?php echo $row['AutoreLibro']; ?></td>
                                    <td>
                                        <form method="POST" style="margin: 0">
                                            <input type="hidden" value="<?php echo $row['ID']; ?>" name="id_delete" />
                                            <button class="btn btn-danger" type="submit_delete" name="submit_delete" style="padding: .1rem .5rem">Elimina</button>
                                        </form>    
                                    </td>
                                <tr>
                    <?php            
                            }
                        } else {
                    ?>
                        <tr>    
                            <td colspan="3">
                                <div class="alert alert-danger text-center">Nessun libro disponibile.</div>
                            </td>
                        </tr>
                    <?php            
                        }
                    ?>
                    </tbody>
                </table>
            </div>
            <div class="tab-pane fade" id="pills-prenotazioni" role="tabpanel" aria-labelledby="pills-prenotazioni-tab">    
                <form method="post" action="" autocomplete="nope" spellcheck="false">
                    <div class="row">
                        <div class="col-12">
                            <div class="alert alert-info">
                                Cerca tutte le prenotazioni attive per data.
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="input-group mb-2">
                                <span class="input-group-text">Dal:</span>
                                <input type="date" class="form-control" id="ritiro_dal" name="ritiro_dal" required min="<?php //echo date("Y-m-d");?>">
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="input-group mb-2">
                                <span class="input-group-text">Al:</span>
                                <input type="date" class="form-control" id="ritiro_al" name="ritiro_al" required>
                            </div>
                        </div>
                        <div class="col-12 text-center">
                            <button type="submit" class="btn btn-success" name="submit" id="submit"><i class='flaticon-successo'></i> Cerca prenotazioni</button>
                        </div>
                    </div>
                </form>
                <div class="row">
                    <div class="col-12">
                        <?php

                            if (isset($_POST['submit'])){
                        ?>
                                <script>
                                    $(document).ready(function() {
                                        $('div.tab-pane').removeClass('active');
                                        $('div.tab-pane').removeClass('show');
                                        $('div#pills-prenotazioni').addClass('show');
                                        $('div#pills-prenotazioni').addClass('active');
                                        $('button.nav-link').removeClass('active');
                                        $('button#pills-prenotazioni-tab').addClass('active');
                                    });
                                </script>
                        <?php
                                $ritiro_dal = $mysqli->real_escape_string(htmlspecialchars($_POST['ritiro_dal']));
                                $ritiro_al  = $mysqli->real_escape_string(htmlspecialchars($_POST['ritiro_al']));
                                $query = "SELECT * FROM Prenotazioni WHERE DataRitiro BETWEEN '$ritiro_dal' AND '$ritiro_al' ORDER BY DataRitiro ASC";
                                $result = $mysqli->query($query);
                                $row_cnt = $result->num_rows;
                                if($row_cnt != 0){ //Ce ne sono
                        ?>
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th colspan="4">
                                                    <div class="alert alert-success text-center">Ecco le prenotazioni dal <?php echo date_format(date_create($ritiro_dal), 'd/m/Y'); ?> al <?php echo date_format(date_create($ritiro_al), 'd/m/Y'); ?> (inclusi)</div>
                                                </th>
                                            </tr>
                                            <tr>
                                                <th width="15%">Data</th>
                                                <th width="45%">Luogo Ritiro</th>
                                                <th width="30%">Libro Ordinato</th>
                                                <th width="10%">Dettagli</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                        <?php
                                    while ($row = $result->fetch_assoc()) {
                        ?>
                                            <tr>
                                                <th><?php echo date_format(date_create($row['DataRitiro']), 'd/m/Y'); ?></th>
                                                <td><?php echo $row['NomeLuogoRitiro']; ?>, <?php echo $row['IndirizzoRitiro']; ?></td>
                                                <td><?php echo $row['NomeLibro']; ?> di <?php echo $row['AutoreLibro']; ?></td>
                                                <td><a href="../export.php?id=<?php echo $row['ID']; ?>" target="_blank">View</td>
                                            </tr>                                    
                        <?php 
                                    }
                        ?>
                                        </tbody>
                                    </table>
                        <?php
                                } else {
                        ?>
                                    <div class="alert alert-danger">Nessuna prenotazione trovata nell'intervallo di date selezionato</div>
                        <?php
                                }
                            }
                        ?>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="pills-logs" role="tabpanel" aria-labelledby="pills-logs-tab">
                <form action="" id="getlogs" name="getlogs">
                    <div class="row">
                        <div class="col-12">
                            <div class="alert alert-info">
                                Puoi cercare e visualizzare tutti i log relativi ad un singolo e specifico giorno
                            </div>
                        </div>
                        <div class="col-12 col-md-8">
                            <div class="input-group mb-2">
                                <span class="input-group-text">Giorno da visualizzare:</span>
                                <input type="date" class="form-control" id="datalog" name="datalog" required>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">                            
                            <button type="submit" class="btn btn-warning full-width" name="submit" id="submit"><i class='flaticon-successo'></i> Visualizza logs</button>
                        </div>
                    </div>
                </form>
                <div class="row">
                    <div id="result-log"></div>
                </div>
            </div>
        </div>
    </section>


</body>
</html>

<script>
    //Piccolo BugFix per la data ricerca prenotazioni
    $("input[name='ritiro_dal']").change(function() {
        $("input[name='ritiro_al']").val($(this).val());
        $("input[name='ritiro_al']").attr('min',$(this).val());
    });

    //Invio form dei log
    $('form#getlogs').submit(function(e) {
        e.preventDefault();
        var data_form    = $('input#datalog').val();
        var data_form_ok = data_form.split('-').reverse();
        if(data_form_ok[0].charAt(0) == 0){
            data_form_ok[0] = data_form_ok[0].charAt(1)
        }
        if(data_form_ok[1].charAt(0) == 0){
            data_form_ok[1] = data_form_ok[1].charAt(1)
        }
        data_form_ok = data_form_ok.join('/');

        $.ajax({    
            url: "https://localhost:3000/getLog?masterkey=password&data="+data_form_ok,
            cache: false,
            type: "GET",
            success: function(result) {
                var splitjoin1 = result.log;
                var splitjoin2 = splitjoin1.toString();
                var splitjoin3 = splitjoin2.split(';,');
                var splitjoin4 = splitjoin3.join('<br>');
                $('#result-log').html(splitjoin4);
            },
            error: function(error) {
                console.log(error);
                $('#result-log').html('Errore. Riprova...');
            }
        });
    });
</script>




<?php

    $redirect = 'index.php';

    //AGGIUNGI LIBRO
    if (isset($_POST['submit_addbook'])){
        $nomelibro       = $mysqli->real_escape_string(htmlspecialchars($_POST['nomelibro']));
        $autorelibro     = $mysqli->real_escape_string(htmlspecialchars($_POST['autorelibro']));

        //Controllo campi compilati
        if ($nomelibro == '' || $autorelibro == ''){
            //Errore: campi vuoti
            echo '
                <script type="text/javascript">
                    alert("Devi compilare entrambi i campi! Riprova...");
                    window.location.href = "'.$redirect.'"
                </script>';
        } else {
            // salva i dati nel database
            $sql = "INSERT INTO Libri (NomeLibro, AutoreLibro) VALUES ('$nomelibro', '$autorelibro')";
            mysqli_query($mysqli, $sql);
            if(mysqli_error($mysqli) != ''){
                echo '
                    <script type="text/javascript">
                        alert("Ops... qualcosa è andato storto!");
                        window.location.href = "'.$redirect.'"
                    </script>';
            } else {  
                echo '
                    <script type="text/javascript">
                        alert("Libro aggiunto con successo!");
                        window.location.href = "'.$redirect.'"
                    </script>';
            }
        }
    }

    //DELETE LIBRO
    if (isset($_POST['submit_delete'])){
        $id = $_POST['id_delete'];
 
        $sql = "DELETE FROM Libri WHERE ID=$id";
        mysqli_query($mysqli, $sql);
        if(mysqli_error($mysqli) != ''){
            echo '
                <script type="text/javascript">
                    alert("Ops... qualcosa è andato storto!");
                    window.location.href = "'.$redirect.'"
                </script>';
        } else {
            echo '
                <script type="text/javascript">
                    alert("Libro eliminato con successo!");
                    window.location.href = "'.$redirect.'"
                </script>';
        }
    }
 
    mysqli_close($mysqli);
?>