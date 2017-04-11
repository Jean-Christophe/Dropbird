var map;
var markers = [];

function initMap() {
    map = new google.maps.Map(document.getElementById('map'), {
        center: {lat: 48.1127671, lng: -1.6424174},
        zoom: 12
    });

    var lieux = [
        {nom: 'Gare de Rennes', localisation: {lat: 48.103497, lng: -1.672278}, type: 'C'},
        {nom: 'Métro République', localisation: {lat:  48.109684, lng: -1.679258}, type: 'C'},
        {nom: 'Forum du Livre', localisation: {lat: 48.114494, lng: -1.678114}, type: 'B'},
        {nom: 'Du Bruit dans la Cuisine', localisation: {lat: 48.082773, lng: -1.679678}, type: 'B'},
        {nom: 'Coffea', localisation: {lat:  48.104721, lng: -1.679605}, type: 'B'},
        {nom: 'Confidence des Vignobles', localisation: {lat: 48.10497, lng: -1.65055}, type: 'B'}
    ];

    var largeInfoWindow = new google.maps.InfoWindow();

    // Créer un tableau de marqueurs à partir du tableau de lieux
    for(var i = 0; i < lieux.length; i++)
    {
        var nom = lieux[i].nom;
        var position = lieux[i].localisation;

        // Créer un marqueur par lieu, et l'insérer dans le tableau de marqueurs
        var marker = new google.maps.Marker({
            // map: map,
            nom: nom,
            position: position,
            label: (i + 1).toString(),
            animation: google.maps.Animation.DROP,
            id: i
        });
        markers.push(marker);

        // Créer un événement pour ouvrir une infoWindow sur chaque marqueur
        marker.addListener('click', function () {
           populateInfoWindow(this, largeInfoWindow); // this : le marqueur qui a été cliqué
        });

        // Evénements liés aux boutons
        document.getElementById('show_consignes').addEventListener('click', showConsignes);
        document.getElementById('show_boutiques').addEventListener('click', showBoutiques);
        document.getElementById('show_all').addEventListener('click', showAll);
        document.getElementById('hide_all').addEventListener('click', hideAll);
        document.getElementById('valider_adresse').addEventListener('click', ajouterAdresse);
        /*document.getElementById('valider_adresse').addEventListener('click', function() {
            ajouterAdresse();
        });*/
    }

    function selectionnerDestination() {

    }

    function ajouterAdresse() {
        var geocoder = new google.maps.Geocoder();
        var adresse = document.getElementById('nouvelle_adresse').value;
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
                            label: 'N',
                            animation: google.maps.Animation.DROP
                            });
                        marker.setMap(map);
                        marker.addListener('click', function () {
                            populateInfoWindow(this, largeInfoWindow); // this : le marqueur qui a été cliqué
                        });
                        var lat = results[0].geometry.location.lat();
                        var long = results[0].geometry.location.lng();
                        console.log(lat);
                        console.log(long);
                    }
                    else {
                        window.alert('Erreur de géocodage : ' . status);
                    }
                }
            )
        }
    }

    /**
     * TODO
     */
    function showConsignes() {

    }

    /**
     * TODO
     */
    function showBoutiques() {

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