$(document).ready(function() {

    //Bottone che saltella
    $.when(
        $('#start-button').animate({
            marginLeft: '+=150vw'
        }, 2000)
    ).then(function() {
        setTimeout(function() {
            for (var i = 0; i < 7; i++) {
                $('#start-button').animate({
                        marginTop: '-=' + '.65rem'
                    }, 300)
                    .animate({
                        marginTop: '+=' + '.65rem'
                    }, 300);
            }
        }, 250);
    });

    //Funzione per la progress bar
    var progress_bar_array = [
        ['25', '1000'],
        ['50', '2500'],
        ['75', '4000'],
        ['100', '5500'],
    ];

    function progressbar(i) {
        setTimeout(function() {
            $('.progress .progress-bar').css('width', progress_bar_array[i][0] + '%');
            $('.progress .progress-bar').text(progress_bar_array[i][0] + '%');
            $('ol#progress-ol li#progress-' + progress_bar_array[i][0]).addClass('d-flex');
            $('ol#progress-ol li#progress-' + progress_bar_array[i][0]).fadeIn();
        }, progress_bar_array[i][1]);
    }

    //OnSroll Functions
    $(document).on('scroll', function() {
        //Navbar cambia colore allo scrool
        $('nav').addClass('nav-on-scroll');
        if ($(document).scrollTop() == 0) {
            $('nav').removeClass('nav-on-scroll');
        }
        //Caricamento barra 
        if ($(document).scrollTop() >= $('#appiglio').offset().top && $('.progress .progress-bar').text() == '0%') {
            for (var i = 0; i < 4; i++) {
                progressbar(i);
            }
        }
        //Sblocco scroll page se ricarica in una posizione diversa da inizio. Solo per desktop & tablet
        if ($(document).scrollTop() != 0) {
            $('body').css('overflow-y', 'scroll');
        }

    });

    //Bottone "Iniziamo!"
    $('#start-button').click(function() {
        $('html, body').animate({
            scrollTop: $('#appiglio').offset().top
        }, 'slow');
        //Lo eseguo solo la prima volta
        if ($('.progress .progress-bar').text() == '0%') {
            for (var i = 0; i < 4; i++) {
                progressbar(i);
            }
        }
    });

    //Funzione per scroll elementi nella pagina cliccando dalle voci del menu
    $('li.nav-item a.nav-link').not('#areusure-a').click(function(e) {
        var sezione = $(this).attr('href');
        $('li.nav-item a.nav-link').removeClass('active');
        //$(this).addClass('active');
        $('html, body').animate({
            scrollTop: $(sezione).offset().top
        }, 'slow');
    });


    //IMPOSTAZIONI MAPPA LEAFLET
    var biblioteche = new L.MarkerClusterGroup();
    var current_position;
    var prenmap = L.map('prenmap').setView([41.917999, 12.462100], 10);
    var strade = new L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png?{foo}', {
        attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a>',
        foo: 'bar',
        label: 'Strade',
        maxZoom: 18,
        minZoom: 6
    }).addTo(prenmap);
    var sat = new L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
        attribution: 'Map data &copy; <a href=https://www.openstreetmap.org/>OpenStreetMap</a>',
        label: 'Satellite'
    });
    var stradesat = new L.tileLayer('https://{s}.google.com/vt/lyrs=s,h&x={x}&y={y}&z={z}', {
        subdomains: ['mt0', 'mt1', 'mt2', 'mt3'],
        attribution: 'Map data &copy; <a href=https://www.openstreetmap.org/>OpenStreetMap</a>',
        label: 'Strade + Satellite'
    });
    biblioteche.addTo(prenmap);
    /* per evitare di farla caricare a cubetti grigi che non mi sembra il caso :) */
    setInterval(function() {
        prenmap.invalidateSize();
    }, 100);
    //Filtro visualizzazione
    var overlays = {
        "Strade": strade,
        "Satellite": sat,
        "Strade + Satellite": stradesat
    };
    var layers_map = {
        "Punti di ritiro": biblioteche,
    };
    L.control.layers(overlays, layers_map, {
        collapsed: true
    }).addTo(prenmap);
    //Centra Mappa su posizione utente
    function centra_mappa(lat_user, lng_user) {
        var centra_utente = L.Control.extend({
            options: {
                position: 'topleft'
            },
            onAdd: function(prenmap) {
                var container = L.DomUtil.create('div', 'leaflet-bar leaflet-control leaflet-control-custom');
                container.id = 'centra_utente';
                container.onclick = function() {
                    prenmap.setView([lat_user, lng_user], 12);
                }
                return container;
            }
        });
        return prenmap.addControl(new centra_utente());
    }

    //INVIO FORM ED ELABORAZIONE DATI BACKEND NodeJS per return della mappa a video
    $('form#location-form').submit(function(e) {
        e.preventDefault();
        var location_form = $('input#address').val();
        var citta_form = $('select#citta').val();
        var vehicle_form = $('input[name="vehicle"]:checked').val();
        $.ajax({
            url: "https://localhost:3000/specific",
            cache: false,
            type: "POST",
            data: {
                address: location_form,
                vehicle: vehicle_form,
                citta: citta_form
            },
            dataType: 'json',
            success: function(result) {
                if (result.service_status == 'OK') {
                    success_map(result, vehicle_form);
                    setLocal()
                } else if (result.service_status == 'FAIL_TO_LOCATE') {
                    error_map('Errore: impossibile localizzare la posizione inserita... Riprova!');
                } else {
                    error_map('Errore: [generico]... Riprova!');
                }
            },
            error: function(error) {
                error_map(error);
            }
        });
    });
    //Bottone reset del form oltre a cancellare il form fa anche altro di cui di seguito
    $('#reset').click(function() {
        $('div#map_box').fadeOut();
        $('div#error_box').fadeOut();
    });




    //Funzioni per AJAX
    //Pulisci mappa
    function clear_map() {
        biblioteche.clearLayers();
        if (prenmap.hasLayer(current_position)) {
            prenmap.removeLayer(current_position);
        }
        if ($('div#centra_utente').length != 0) {
            prenmap.removeControl(centra_utente);
        }
    }
    //Success
    function success_map(result, vehicle_form) {
        clear_map();
        $('div#error_box').fadeOut();
        $('div#map_box').fadeIn();
        $('html, body').animate({
            scrollTop: $('div#map_box').offset().top,
            duration: 2000
        }, 'slow');
        var centra_mappa_fn = centra_mappa(result.start[0], result.start[1]);
        prenmap.setView([result.start[0], result.start[1]], 13);

        //User Position
        var gpsicon = new L.Icon({
            iconSize: [28, 28],
            iconAnchor: [14, 14],
            iconUrl: "imgs/user_placeholder.svg",
        });
        current_position = new L.marker([result.start[0], result.start[1]], {
            icon: gpsicon
        }).bindPopup("<span id='contenuti'>Ciao, questo è l'indirizzo che hai inserito. Non è questo? :/ Riprova ad inserirlo correttamente nel form qui sopra...</span>", {
            maxWidth: 320
        }).addTo(prenmap);


        //Aggiungo i marker biblioteche ottenuti dalla query ajax
        for (var i = 0; i < result.destinations.length; i++) {
            var lat_biblio = result.destinations[i][0][0];
            var lng_biblio = result.destinations[i][0][1];

            //Finestra al click su marker
            var infowincontent = document.createElement('div');
            var nome_distacc = document.createElement('strong');
            nome_distacc.id = 'event';
            nome_distacc.textContent = result.destinations[i][3]
            infowincontent.appendChild(nome_distacc);
            infowincontent.appendChild(document.createElement('br'));
            var indirizzo = document.createElement('i');
            indirizzo.textContent = result.destinations[i][4]
            indirizzo.id = 'contenuti';
            infowincontent.appendChild(indirizzo);
            infowincontent.appendChild(document.createElement('br'));
            var info_time_vec = document.createElement('i');
            info_time_vec.className = 'flaticon-distance';
            infowincontent.appendChild(info_time_vec);
            var info_time_vec_text = document.createElement('span');
            info_time_vec_text.textContent = ' Distanza: ' + result.destinations[i][1] + 'm';
            info_time_vec_text.id = 'contenuti_con_icon';
            infowincontent.appendChild(info_time_vec_text);
            infowincontent.appendChild(document.createElement('br'));
            var info_time = document.createElement('i');
            switch (vehicle_form) {
                case 'car':
                    info_time.className = 'flaticon-car';
                    break;
                case 'foot':
                    info_time.className = 'flaticon-foot';
                    break;
                case 'bike':
                    info_time.className = 'flaticon-bike';
                    break;
            }
            infowincontent.appendChild(info_time);
            var info_time_text = document.createElement('span');
            info_time_text.textContent = ' Percorrenza: ' + Math.round((result.destinations[i][2] / 60) * 10) / 10 + 'min'
            info_time_text.id = 'contenuti_con_icon';
            infowincontent.appendChild(info_time_text);
            infowincontent.appendChild(document.createElement('br'));
            var prenota_link = document.createElement('a');
            prenota_link.textContent = 'Prenota qui!';
            prenota_link.id = 'prenota_link';
            prenota_link.className = 'btn btn-primary mt-2';
            prenota_link.href = 'prenota.php?luogo=' + (result.destinations[i][3]).split(' ').join('+') + '&indirizzo=' + (result.destinations[i][4]).split(' ').join('+');
            infowincontent.appendChild(prenota_link);
            infowincontent.appendChild(document.createElement('br'));

            var marker_biblio = new L.marker([lat_biblio, lng_biblio]).bindPopup(infowincontent, {
                maxWidth: 320 //Dimensione testata negli anni con noti per non farla uscire fuori dallo schermo mobile
            });
            biblioteche.addLayer(marker_biblio);
        }
    }
    //Error
    function error_map(errore_da_scrivere) {
        clear_map();
        $('div#map_box').fadeOut();
        $('div#error_box').fadeIn();
        $('div#error_box div').text(errore_da_scrivere);
        $('html, body').animate({
            scrollTop: $('div#error_box').offset().top,
            duration: 2000
        }, 'slow');
    }


    //SetLocalStorage
    showDiv() 
    $('#ok').click(function() {
        const element = document.querySelector('.animatebutton');
        element.classList.add('animated', 'pulse');
        element.classList.add('btn-success')
        setTimeout(function() {
            element.classList.remove('pulse');

            setOrigin();
            $('#hidden_card').fadeOut(400);

        }, 1000);

    });
    $('#ls-no').click(function() {
        $('#hidden_card').fadeOut(400);
    });

    function setLocal() {//funzione che setta il localStorage
        if (document.getElementById('address').value != '') {
            localStorage['last_origin'] = document.getElementById('address').value;
        }
    }

    function setOrigin() { //funzione che fa apparire il contenuto nel box
        if (localStorage['last_origin'] != null) {
            document.getElementById('address').setAttribute('value', localStorage['last_origin']);
        }
    }

    function showDiv() {
        if (localStorage['last_origin'] != null) {
            document.getElementById('paragraph').innerHTML = localStorage['last_origin']
            document.getElementById('hidden_card').style.display = 'block' 
        }
    }


});
