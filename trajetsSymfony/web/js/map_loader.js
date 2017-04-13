/**
 * Created by jeanc_000 on 11/04/2017.
 */
var map;
var markers = [];
var markersBoutiques = [];
var markersConsignes = [];

function initMap() {
    map = new google.maps.Map(document.getElementById('map'), {
        center: {lat: 48.1127671, lng: -1.6424174},
        zoom: 12
    });

    var largeInfoWindow = new google.maps.InfoWindow();

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
            populateInfoWindow(this, largeInfoWindow); // this : le marqueur qui a été cliqué
        });
    }

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
            populateInfoWindow(this, largeInfoWindow); // this : le marqueur qui a été cliqué
        });
    }

    // Evénements liés aux boutons
    document.getElementById('show_consignes').addEventListener('click', showConsignes);
    document.getElementById('hide_consignes').addEventListener('click', hideConsignes);
    document.getElementById('show_boutiques').addEventListener('click', showBoutiques);
    document.getElementById('hide_boutiques').addEventListener('click', hideBoutiques);
    document.getElementById('show_all').addEventListener('click', showAll);
    document.getElementById('hide_all').addEventListener('click', hideAll);
    document.getElementById('valider_adresse_depart').addEventListener('click', ajouterAdresse);

    function selectionnerDestination() {

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
                        var infoWindow = new google.maps.InfoWindow({
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

    function searchWithinTime(depart) {
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

    function ajouterAdresse() {
        var geocoder = new google.maps.Geocoder();
        var adresse = document.getElementById('adresse_depart').value;

        if(adresse == ''){
            window.alert('Le champ est vide.');
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
                    if(status == google.maps.GeocoderStatus.OK) {
                        map.setCenter(results[0].geometry.location);
                        var marker = new google.maps.Marker({
                            nom: adresse.toString(),
                            position: results[0].geometry.location,
                            label: 'D',
                            animation: google.maps.Animation.DROP
                        });
                        marker.setMap(map);
                        marker.addListener('click', function () {
                            populateInfoWindow(this, largeInfoWindow); // this : le marqueur qui a été cliqué
                        });
                        var lat = results[0].geometry.location.lat();
                        var long = results[0].geometry.location.lng();
                        var depart = {name: adresse.toString(), position: {lat: lat, lng: long}};
                        searchWithinTime(depart);
                    }
                    else {
                        window.alert('Erreur de géocodage : ' . status);
                    }
                }
            )
        }
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

    /**
     * Remplit l'infoWindow quand le marqueur est cliqué.
     * Un seul infoWindow à la fois
     * @param marker
     * @param infoWindow
     */
    function populateInfoWindow(marker, infoWindow) {
        // Un seul infoWindow à la fois
        if(infoWindow.marker != marker)
        {
            infoWindow.setContent('');
            infoWindow.marker = marker;

            // Si l'infoWindow est fermé, on on passe la propriété 'marker' à null
            infoWindow.addListener('closeclick', function () {
                infoWindow.marker = null;
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

            infoWindow.setContent('<div><h1>' + marker.nom + '</h1><p>Position : ' + marker.position + '</p></div>');
            infoWindow.open(map, marker);
        }
    }
}