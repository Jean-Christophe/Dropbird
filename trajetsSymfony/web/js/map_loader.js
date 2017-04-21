/**
 * Created by jeanc_000 on 11/04/2017.
 */
var map;
var markerDepart = null;
var markers = [];
var markersBoutiques = [];
var markersConsignes = [];
var largeInfoWindow;
var infoWindow;
var depart = {name: "Dépôt", position: {lat: 48.10926, lng: -1.63429}};
var etapes = [];
var destination;
var premierBlurDepart = true;

function initMap() {
    map = new google.maps.Map(document.getElementById('map'), {
        center: {lat: 48.1127671, lng: -1.6424174},
        zoom: 13
    });

    largeInfoWindow = new google.maps.InfoWindow();

    initMarkersConsignes();
    initMarkersBoutiques();
    initButtonsEvents();

    // Destination par défaut : le premier élément de la liste
    destination = getLocation(1);
}

function initMarkersConsignes() {
    // Créer un tableau de markers de consignes
    for(var i = 0; i < consignes.length; i++)
    {
        var idConsigne = consignes[i].id;
        var nomConsigne = consignes[i].nom;
        var positionConsigne = {lat: consignes[i].latitude, lng: consignes[i].longitude};

        var markerConsigne = new google.maps.Marker({
            nom: nomConsigne,
            position: positionConsigne,
            label: 'C',
            animation: google.maps.Animation.DROP,
            id: idConsigne
        });
        markersConsignes.push(markerConsigne);
        markers.push(markerConsigne);

        // Créer un événement pour ouvrir une infoWindow sur chaque marqueur
        markerConsigne.addListener('click', function () {
            populateLargeInfoWindow(this, largeInfoWindow); // this : le marqueur qui a été cliqué
        });
    }
}

function initMarkersBoutiques() {
    // Créer un tableau de markers de boutiques
    for(var i = 0; i < boutiques.length; i++)
    {
        var idBoutique = boutiques[i].id;
        var nomBoutique = boutiques[i].nom;
        var positionBoutique = {lat: boutiques[i].latitude, lng: boutiques[i].longitude};

        var markerBoutique = new google.maps.Marker({
            nom: nomBoutique,
            position: positionBoutique,
            label: 'B',
            animation: google.maps.Animation.DROP,
            id: idBoutique
        });
        markersBoutiques.push(markerBoutique);
        markers.push(markerBoutique);

        // Créer un événement pour ouvrir une infoWindow sur chaque marqueur
        markerBoutique.addListener('click', function () {
            populateLargeInfoWindow(this, largeInfoWindow); // this : le marqueur qui a été cliqué
        });
    }
}

function initButtonsEvents() {
    // Evénements liés aux boutons
    document.getElementById('show_consignes').addEventListener('click', showConsignes);
    document.getElementById('hide_consignes').addEventListener('click', hideConsignes);
    document.getElementById('show_boutiques').addEventListener('click', showBoutiques);
    document.getElementById('hide_boutiques').addEventListener('click', hideBoutiques);
    document.getElementById('show_all').addEventListener('click', showAll);
    document.getElementById('hide_all').addEventListener('click', hideAll);
    document.getElementById('adresse_depart').addEventListener('blur', selectDeparture);
    document.getElementById('duree_max').addEventListener('change', function() {
        searchWithinTime(depart);
    });
    document.getElementById('moyen_locomotion').addEventListener('change', function() {
        searchWithinTime(depart);
    });
    document.getElementById('ajouter_etape').addEventListener('click', function(){
        var select = document.getElementById('etapes');
        addStep(select.value);
    });
    document.getElementById('destination').addEventListener('change', function(){
        setDestination(this.value)
    });
    document.getElementById('calculer_trajet').addEventListener('click', function(){
        displayDirections();
    });
}

function tests(){
    var select = document.getElementById('etapes');
    console.log(select.value);
}

function showConsignes() {
    var bounds = new google.maps.LatLngBounds();
    // Etendre les frontières de la carte pour chaque marqueur
    // Et lier le marqueur à la carte
    for(var i = 0; i < markersConsignes.length; i++)
    {
        markersConsignes[i].setMap(map);
        bounds.extend(markersConsignes[i].position);
    }
    map.fitBounds(bounds);
}

function hideConsignes() {
    for(var i = 0; i < markersConsignes.length; i++)
    {
        markersConsignes[i].setMap(null);
    }
}

function showBoutiques() {
    var bounds = new google.maps.LatLngBounds();
    // Etendre les frontières de la carte pour chaque marqueur
    // Et lier le marqueur à la carte
    for(var i = 0; i < markersBoutiques.length; i++)
    {
        markersBoutiques[i].setMap(map);
        bounds.extend(markersBoutiques[i].position);
    }
    map.fitBounds(bounds);
}

function hideBoutiques() {
    for(var i = 0; i < markersBoutiques.length; i++)
    {
        markersBoutiques[i].setMap(null);
    }
}

/**
 * Affiche tous les marqueurs sur une carte mise à une échelle qui peut tous les afficher
 */
function showAll() {
    var bounds = new google.maps.LatLngBounds();
    // Etendre les frontières de la carte pour chaque marqueur
    // Et lier le marqueur à la carte
    for(var i = 0; i < markers.length; i++)
    {
        markers[i].setMap(map);
        bounds.extend(markers[i].position);
    }
    map.fitBounds(bounds);
}

function hideAll() {
    for(var i = 0; i < markers.length; i++)
    {
        markers[i].setMap(null);
    }
}

function selectDeparture() {
    // Si c'est la première fois que le focus quitte l'input Départ et si le champ n'est pas vide,
    // on affiche le filtrage des lieux par distance et par moyen de locomation, ainsi que
    // l'ajout d'étapes et de la destination
    if(premierBlurDepart && document.getElementById('adresse_depart').value !== '')
    {
        document.getElementById('afficher_lieux').style.display = 'block';
        document.getElementById('editer_trajet').style.display = 'block';
        premierBlurDepart = false;
    }
    hideAll();
    var geocoder = new google.maps.Geocoder();
    var adresse = document.getElementById('adresse_depart').value;

    if(adresse === ''){
        window.alert('Le champ "Départ" est vide.');
    }
    else{
        geocoder.geocode(
            {
                address: adresse,
                componentRestrictions:
                    {
                        country: 'France'
                    }
            },
            function(results, status) {
                if(status === google.maps.GeocoderStatus.OK) {
                    if(markerDepart !== null) {
                        markerDepart.setMap(null);
                    }
                    map.setCenter(results[0].geometry.location);
                    markerDepart = new google.maps.Marker({
                        nom: adresse.toString(),
                        position: results[0].geometry.location,
                        label: 'D',
                        animation: google.maps.Animation.DROP
                    });
                    markerDepart.setMap(map);
                    markerDepart.addListener('click', function () {
                        populateLargeInfoWindow(this, largeInfoWindow); // this : le marqueur qui a été cliqué
                    });
                    var lat = results[0].geometry.location.lat();
                    var long = results[0].geometry.location.lng();
                    depart = {name: adresse.toString(), position: {lat: lat, lng: long}};
                }
                else {
                    window.alert('Erreur de géocodage : ' . status);
                }
            }
        )
    }
}

/**
 * Afficher des lieux en fonction du lieu de départ, du mode de transport et de la durée maximale de trajet
 * entrée par l'utilisateur
 * @param depart
 */
function searchWithinTime(depart) {
    console.log('Entrée dans searchWithinTime');
    var distanceMatrixService = new google.maps.DistanceMatrixService;
    // On efface tous les markers pour n'afficher que ceux qui entrent dans la durée indiquée
    hideAll();
    var destinations = [];
    for(var i = 0; i < markers.length; i++)
    {
        destinations[i] = markers[i].position;
    }
    var mode = document.getElementById('moyen_locomotion').value;
    distanceMatrixService.getDistanceMatrix({
        origins: [depart.position],
        destinations: destinations,
        travelMode: google.maps.TravelMode[mode],
    }, function(response, status) {
        if(status !== google.maps.DistanceMatrixStatus.OK) {
            window.alert('Erreur : ' + status);
        }
        else {
            displayMarkersWithinTime(response);
        }
    })
}

/**
 * Parcourt la réponse et, si la distance est inférieure à la valeur entrée par l'utilisateur,
 * affiche le lieu sur la carte
 * @param response
 */
function displayMarkersWithinTime(response) {
    var origins = response.originAddresses;
    var destinations = response.destinationAddresses;
    var maxDuration = document.getElementById('duree_max').value;
    var atLeastOne = false;

    for(var i = 0; i < origins.length; i++)
    {
        var results = response.rows[i].elements;
        for(var j = 0; j < results.length; j++)
        {
            var element = results[j];
            if(element.status === 'OK')
            {
                var distance = element.distance.value;
                var distanceText = element.distance.text;
                var duration = element.duration.value / 60;
                var durationText = element.duration.text;

                if(duration <= maxDuration)
                {
                    markers[j].setMap(map);
                    atLeastOne = true;
                    infoWindow = new google.maps.InfoWindow({
                        content: durationText + ' (' + distanceText + ')'
                    });
                    markers[j].infowindow = infoWindow;
                    markers[j].addListener('mouseover', function() {
                        this.infowindow.open(map, this);
                    });
                    markers[j].addListener('mouseout', function() {
                        this.infowindow.close();
                    });
                }
            }
        }
    }
}

/**
 * Retourne une étape en fonction d'un id
 * @param id
 * @returns {*}
 */
function getLocation(id){
    var etape = null;
    for(var i = 0; i < lieux.length; i++)
    {
        if(lieux[i].id === parseInt(id))
        {
            etape = lieux[i];
        }
    }
    if(etape === null)
    {
        console.log('Label incorrect');
    }
    return etape;
}

function addStep(id) {
    // Crée une étape avec un id
    var etape = getLocation(id);

    addEtape(etape);
}

function addEtape(etape) {
    var coordEtape = new google.maps.LatLng(etape.latitude, etape.longitude);
    var wayPoint = {location: coordEtape, stopover: true};
    //var positionEtape = {lat: etape.latitude, lng: etape.longitude};
    if(destination === null){
        destination = etape;
        setDestination(etape.id);
    }
    else{
        etapes.push(wayPoint);
    }
}

function setDestination(id) {
    var select = document.getElementById('destination');
    select.value = id;


    // Crée une destination avec un id
    destination = getLocation(id);
}

/**
 * Affiche la route vers la destination sur la carte
 */
function displayDirections(){
    hideAll();
    var positionDestination = {lat: destination.latitude, lng: destination.longitude};
    var directionsService = new google.maps.DirectionsService;
    var mode = document.getElementById('moyen_locomotion').value;
    directionsService.route({
        origin: depart.position,
        destination: positionDestination,
        waypoints: etapes,
        optimizeWaypoints: true,
        travelMode: google.maps.TravelMode[mode]
    }, function(response, status) {
        if(status === google.maps.DirectionsStatus.OK){
            var directionsDisplay = new google.maps.DirectionsRenderer({
                map: map,
                directions: response,
                draggable: true,
                polylineOptions: {
                    strokeColor: 'blue'
                }
            });
            // Afficher l'itinéraire écrit
            console.log(response);
            var route = response.routes[0];
            var resume = document.getElementById('itineraire');
            resume.innerHTML = '';
            for(var i = 0; i < route.legs.length; i++){
                var routeSegment = i + 1;
                resume.innerHTML += '<hr /><hr /><b>Segment : ' + routeSegment + '</b><br />';
                resume.innerHTML += route.legs[i].start_address + ' à ';
                resume.innerHTML += route.legs[i].end_address + '<br />';
                resume.innerHTML += route.legs[i].distance.text + '<br />';
                resume.innerHTML += route.legs[i].duration.text + '<br /><hr />';
                for(var j = 0; j < route.legs[i].steps.length; j++) {
                    resume.innerHTML += route.legs[i].steps[j].instructions + '<br /><br />';
                }
            }
        } else{
            window.alert('Erreur : ' + status);
        }
    });
}

/**
 * Remplit l'infoWindow quand le marqueur est cliqué.
 * Un seul infoWindow à la fois
 * @param marker
 * @param largeInfoWindow
 */
function populateLargeInfoWindow(marker, largeInfoWindow){

    // Un seul infoWindow à la fois
    if(largeInfoWindow.marker !== marker)
    {
        largeInfoWindow.setContent('');
        largeInfoWindow.marker = marker;

        // Si l'infoWindow est fermé, on on passe la propriété 'marker' à null
        largeInfoWindow.addListener('closeclick', function () {
            largeInfoWindow.marker = null;
        });

        /* Streetwiew
         var streetViewService = new google.maps.StreetViewService();
         var radius = 50; // Cherche l'image streetView la plus proche du lieu désigné, dans un rayon de 50 mètres

         function getStreetView(data, status) {
         if(status == google.maps.StreetViewStatus.OK) {
         var nearStreetViewLocation = data.location.latLng;
         var heading = google.maps.geometry.spherical.computeHeading(
         nearStreetViewLocation, marker.position);
         infoWindow.setContent('<div><h1>' + marker.nom + '</h1><p>Position : ' + marker.position + '</p></div><div id="pano"></div>');
         var panoramaOptions = {
         position: nearStreetViewLocation,
         pov: {
         heading: heading,
         pitch: 30
         }
         };
         var panorama = new google.maps.StreetViewPanorama(document.getElementById('pano'), panoramaOptions);
         }
         else
         {
         infoWindow.setContent('<div><h1>' + marker.nom + '</h1><p>Position : ' + marker.position + '</p></div>');
         }
         }
         streetViewService.getPanoramaByLocation(marker.position, radius, getStreetView);
         */

        // Ajout de boutons à l'InfoWindow pour ajouter une étape ou une destination au trajet
        largeInfoWindow.setContent('<div><h1>' + marker.nom + '</h1><p>Position : ' + marker.position + '</p></div>' +
            '<div class="marker_editer_trajet"><input type="button" value="Ajouter une étape" onclick="addStep(\'' + marker.id + '\')" /></div>' +
            '<div class="marker_editer_trajet"><input type="button" value="Définir comme destination" onclick="setDestination(\'' + marker.id + '\')" /></div>'
        );
        largeInfoWindow.open(map, marker);
    }
}