function autocomplete(inp, other, arr, type) { // prende l'input text e un array contenente gli elementi possibili
  var currentFocus;
  inp.addEventListener("input", function (e) { // mi metto in ascolto per input sulla text-area
    var a, b, i, val = this.value;
    other.value = ""; // svuoto l'altro campo
    closeAllLists(); //chiudo eventuali liste precedenti
    if (!val) { return false; } //se non ho contenuto termino
    currentFocus = -1;
    a = document.createElement("DIV"); //creo un div dove contenere la mia lista e ne imposto gli attributi del tag
    a.setAttribute("id", this.id + "autocomplete-list"); //gli assegno un id per poterlo richiamare in caso di uso di tastiera
    a.setAttribute("class", "autocomplete-items"); //assegno la classe per dargli lo stile
    /*append the DIV element as a child of the autocomplete container:*/
    this.parentNode.appendChild(a);

    for (i = 0; i < arr.length; i++) { //ciclo sugli elementi dell'array per creare la lista visibile
      if (arr[i][type].substr(0, val.length).toUpperCase() == val.toUpperCase()) { //controllo se la parte scritta conincide con l'inizio dell'occorrenza
        b = document.createElement("DIV"); //creo un div per l'occorrenza

        if (!type) {

          b.innerHTML = "<strong>" + arr[i][0].substr(0, val.length) + "</strong>"; //metto bold la parte che coincide
          b.innerHTML += arr[i][0].substr(val.length) + "-" + arr[i][1];

          b.innerHTML += "<input type='hidden' value='" + arr[i][0] + '&' + arr[i][1] + "'>"; //creo un campo input il cui valore sarà Titolo&Autore a cui farò riferimento per prelevarlo
        }
        else {

          b.innerHTML = "<strong>" + arr[i][1].substr(0, val.length) + "</strong>"; //metto bold la parte che coincide
          b.innerHTML += arr[i][1].substr(val.length) + "-" + arr[i][0];

          b.innerHTML += "<input type='hidden' value='" + arr[i][0] + '&' + arr[i][1] + "'>"; //creo un campo input il cui valore sarà Titolo&Autore a cui farò riferimento per prelevarlo


        }

        b.addEventListener("click", function (e) { //aggiungo il listener che al click su un elemento setto il value di entrambi i campi prelevandolo dal campo value di input

          var temp = this.getElementsByTagName("input")[0].value.split('&')

          if (!type) {
            inp.value = temp[0];
            other.value = temp[1];
          }
          else {
            inp.value = temp[1];
            other.value = temp[0];
          }

          closeAllLists(); //chiudo il container dell'autocomplete
        });
        a.appendChild(b); //aggiungo al div contenitore l'elemnto
      }
    }
  });

  inp.addEventListener("keydown", function (e) { //in caso di input da tastiera
    var x = document.getElementById(this.id + "autocomplete-list"); //prendo il contenitore degli elementi
    if (x) x = x.getElementsByTagName("div"); //metto in x tutti gli elementi 
    if (e.keyCode == 40) { //premuta freccia giù allora sposto il focus all'elemento di sotto
      currentFocus++;
      addActive(x); //rendo visibile l'elemento
    } else if (e.keyCode == 38) { //premuta freccia su, sposto il focus all'elemento di sopra
      currentFocus--;
      addActive(x);
    } else if (e.keyCode == 13) { //se invio è premuto blocco invio del form
      e.preventDefault();
      if (currentFocus > -1) { //se ho focus su un elemento allora simulo il click
        if (x) x[currentFocus].click();
      }
    }
  });

  function addActive(x) { //mi permette di rendere "attivo" uno degli elementi
    if (!x) return false;
    removeActive(x); //azzero per sicurezza
    if (currentFocus >= x.length) currentFocus = 0; //aggiusto il focus
    if (currentFocus < 0) currentFocus = (x.length - 1);

    x[currentFocus].classList.add("autocomplete-active"); //rendo la classe attiva
  }
  function removeActive(x) { //mi permette di rimuovere "attivo" dagli elementi
    for (var i = 0; i < x.length; i++) {
      x[i].classList.remove("autocomplete-active");
    }
  }
  function closeAllLists(elmnt) { //chiudo tutto eccetto quello passato come parametro
    var x = document.getElementsByClassName("autocomplete-items");
    for (var i = 0; i < x.length; i++) {
      if (elmnt != x[i] && elmnt != inp) {
        x[i].parentNode.removeChild(x[i]);
      }
    }
  }
  //cancello tutti gli elementi quando si clicca fuori dal blocco
  document.addEventListener("click", function (e) {
    closeAllLists(e.target);
  });
}