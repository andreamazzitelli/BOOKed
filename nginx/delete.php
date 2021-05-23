<?php

include('connect-db.php');
 
if (isset($_GET['id']) && $_GET['id'] != ''){
    
    $id = $_GET['id'];
    $query = "SELECT ID FROM Prenotazioni WHERE ID = '$id'";
    $result = $mysqli->query($query);
    $row = $result->fetch_assoc();
    $row_cnt = $result->num_rows;
    if($row != '' && $row_cnt == 1){
        $sql_delete = "DELETE FROM Prenotazioni WHERE ID='$id'";
        mysqli_query($mysqli, $sql_delete);
        if(mysqli_error($mysqli) != '') {
        ?>
            <script type="text/javascript">
                alert("Oh no, c'è stato un errore, riprova...")
                window.location.href = "index.html"
            </script>
        <?php       
        } else {
        ?>
            <script type="text/javascript">
                alert("Prenotazione eliminata con successo.")
                window.location.href = "index.html"
            </script>
        <?php
        }
    } else {
    ?>
        <script type="text/javascript">
            alert("Nessuna prenotazione trovata con questo ID.")
            window.location.href = "index.html"
        </script>
    <?php
    }

} else {
?>
    <script type="text/javascript">
        alert("Errore! Devi selezionare un ID per potere eliminare qualcosa.")
        window.location.href = "index.html"
    </script>
<?php
}

$mysqli -> close();
?>