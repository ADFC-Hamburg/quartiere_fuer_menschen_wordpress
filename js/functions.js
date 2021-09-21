// Main JS functions for Quartiere fuer Menschen WordPress theme

var $ = jQuery, qfmMap, marker = [];
var iconWidth = 26, iconHeight = 32, locationMarker = '', currentLat, currentLon, hasCLickedInside;

$(document).ready(function(){
	
	kuulaSettings();	
	initMap();
			
});

function initMap() {
	if($('.qfm-map').length) {
		
		// add OSM layer
		var mapnikLayer = L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
			attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a>-Mitwirkende, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>',
			maxZoom: 19
		});
		
		// prepare marker layers, separated by location types
		var markerLayer = [];
		if(typeof(locationTypes)!='undefined') {
			for(var i in locationTypes) markerLayer[locationTypes[i][0]] = new L.LayerGroup();
		}
		
		var styleProjektGebiet = {
			'color': '#ee7f00',
			'weight': 2,
			'dashArray': '5,5',
			'opacity': 1,
			'fillOpacity': 0,
			'fillColor': '#ffffff'
		};
		
			
		var projektGebietLayer = L.geoJson(geojsonData.projektGebietLayer,{style:styleProjektGebiet});
		
		// set map bounds
		bounds = new Array();
		if(mapAtts.bounds) {
			var southWest = L.latLng(mapAtts.bounds[0], mapAtts.bounds[1]);
			var northEast = L.latLng(mapAtts.bounds[2], mapAtts.bounds[3]);
			bounds = L.latLngBounds(southWest, northEast);
		}
		
		// set initial zoom level values depending on screen size
		var defaultZoom = mapAtts.zoom;
		var minZoom = mapAtts.minZoom;
		if($(window).innerWidth()<600) {
			defaultZoom = mapAtts.zoom - 1;
		}
		
		// create the list of layers to be shown in the map as array
		var layerList = [];
		layerList.push(mapnikLayer);
		layerList.push(projektGebietLayer);
		for(var i in markerLayer) layerList.push(markerLayer[i]);
		
		// create the map
		qfmMap = L.map('qfm-map', {scrollWheelZoom: false, layers: layerList ,maxBounds: bounds, maxBoundsViscosity: 1}).setView([mapAtts.lat,mapAtts.lon], defaultZoom);
		
		// add click event to default map view button
		if(mapAtts.currentID) {
			$('.qfm-map-default-view').addClass('init');
		}
		
		var defaultViewClicked = false;
		$('.qfm-map-default-view a').on('click',function(e){
			e.preventDefault();
			if(mapAtts.currentID) {
				qfmMap.setView([currentLat,currentLon],mapAtts.singleZoom);
			}
			else {
				qfmMap.setView([mapAtts.lat,mapAtts.lon], defaultZoom);
			}
			$('.qfm-map-default-view').slideUp();
			defaultViewClicked = true;
			setTimeout(function(){
				defaultViewClicked = false;
			},1000);
		});
		
		qfmMap.on('dragend zoomend',function(e){
			if(!defaultViewClicked) $('.qfm-map-default-view:not(.init)').slideDown();
		});
		
		// handle ADFC/community filter
		$('.filter-adfc-or-community .show-adfc').on('click',function(e){
			e.preventDefault();
			$('.leaflet-marker-icon.is-adfc').toggleClass('hidden-marker');
			$(this).toggleClass('inactive');
			$(this).blur();
		});
		$('.filter-adfc-or-community .show-community').on('click',function(e){
			e.preventDefault();
			$('.leaflet-marker-icon.is-community').toggleClass('hidden-marker');
			$(this).toggleClass('inactive');
			$(this).blur();
		});

		// set initial zoom levels
		if(mapAtts.maxZoom) qfmMap.options.maxZoom = mapAtts.maxZoom;
		if(mapAtts.minZoom) qfmMap.options.minZoom = minZoom;
		
		// add scale control		
		L.control.scale().addTo(qfmMap);
		
		if(!mapAtts.setMarker) {
			
			// layer switcher
			if(!mapAtts.currentID) {
				var layerSwitcherHTML = '<div class="qfm-map-layer-switcher-wrapper"><div class="qfm-map-layer-switcher-toggle">Filter/Legende</div><ul class="qfm-map-layer-switcher">';
				for(var i in locationTypes) {
					var el = $('<span>'+locationTypes[i][3]+'</span>');
					var infoUrl = el.find('a').first().attr('href');
					var infoOutput = '';
					if(infoUrl) infoOutput = '<a target="_blank" href="'+infoUrl+'" class="info" title="Infos zu dieser Ortskategorie">Info</a>';
					layerSwitcherHTML += '<li><a href="#" class="activated cat cat-'+locationTypes[i][0]+'" data-name="'+locationTypes[i][0]+'"><span class="icon"></span> '+locationTypes[i][1]+'</a> '+infoOutput+'</li>';
				}
				layerSwitcherHTML += '</ul></div>';
				$('.qfm-map').append(layerSwitcherHTML);
				$('.qfm-map-layer-switcher-toggle').on('click',function(){
					$(this).closest('.qfm-map-layer-switcher-wrapper').find('ul').slideToggle();
					$(this).toggleClass('toggled');
				});
				$('.qfm-map .qfm-map-layer-switcher a.cat').on('click',function(e){
					e.preventDefault();
					$(this).blur();
					$(this).toggleClass('activated');
					markerLayer[$(this).attr('data-name')].eachLayer(function(layer) {
						$(layer._icon).toggleClass('hidden-layer-marker');
					});
				});
			}
				
			var params = '';
			if(mapAtts.currentID) {
				if(params) params += '&currentid='+mapAtts.currentID;
				else params += '?currentid='+mapAtts.currentID;
			}
			
			// get all locations via AJAX
			$.getJSON(mapAtts.themeDir+'/locations.php'+params,function(data){
				$.each(data,function(key,objData) {
					var currentIconWidth = iconWidth, currentIconHeight = iconHeight;
					var popupContent = '';
					var markerIcon = L.icon({iconUrl: objData.markerUrl, iconSize: [currentIconWidth, currentIconHeight], iconAnchor: [(currentIconWidth/2), currentIconHeight], popupAnchor: [0, (currentIconHeight+3)*-1]});
					
					// add icon to layer switcher
					if(!mapAtts.currentID) {
						if($('.qfm-map-layer-switcher').find('a.cat-'+objData.term+' .icon').html()=='') {
							if(objData.markerUrl2 && objData.markerUrl2 != objData.markerUrl) $('.qfm-map-layer-switcher').find('a.cat-'+objData.term+' .icon').append('<img src="'+objData.markerUrl2+'" alt="Icon2" />');
							else $('.qfm-map-layer-switcher').find('a.cat-'+objData.term+' .icon').append('<img src="'+objData.markerUrl+'" alt="Icon" />');
						}
					}
					
					// popup settings
					if(!mapAtts.currentID) {
						popupContent += '<h2 id="popup-'+objData.id+'" class="cat-'+objData.term+'">'+objData.title+'</h2>';
						if(objData.imgurl) popupContent += '<img src="'+objData.imgurl+'" alt="marker image" /><br />';
						popupContent += '<a href="'+objData.permalink+'">'+mapTextData.viewDetails+'<a/><br />';
						if($('body.logged-in').length) {
							if(objData.editEntryLink) popupContent += '<br /><a href="'+objData.editEntryLink+'"><em>'+mapTextData.editLocation+'</em></a>';
						}
						popupContent += '</p>';
					}
					if(markerLayer[objData.term]) {
						marker.push(L.marker([objData.lat,objData.lon], {icon: markerIcon, id: objData.id }).addTo(markerLayer[objData.term]));
					}
					
					// bind popup to marker
					if(!mapAtts.currentID) {
						if(marker[marker.length-1]) marker[marker.length-1].bindPopup(popupContent);
					}
					else {
						currentLat = objData.lat;
						currentLon = objData.lon;
						qfmMap.setView([objData.lat,objData.lon],mapAtts.singleZoom);
						qfmMap.on('moveend', function(e){
							$('.qfm-map-default-view').removeClass('init');
						})
					}
					
					// add community flag to marker
					if(objData.isCommunity) {
						$('.leaflet-marker-icon').last().addClass('is-community');
					}
					else {
						$('.leaflet-marker-icon').last().addClass('is-adfc');
					}
										
				});
				// remove empty layer switcher items
				$('.qfm-map-layer-switcher li .icon').each(function(){
					if(!$(this).html()) $(this).closest('li').remove();
				});
			});
			
		}
		else {
			
			// Add marker at respective location on click on map within allowed area and save lat/lon values to html form fields.
			if(mapAtts.noOutsideClicks) {
				projektGebietLayer.on('singleclick',function(e){
					L.DomEvent.stopPropagation(e);
					setMarker(e.latlng.lat,e.latlng.lng);
				});
				
				qfmMap.on('singleclick', function(e){
					alert(mapTextData.clickedOutside);
				});
			}
			
			else {
				qfmMap.on('singleclick',function(e){
					L.DomEvent.stopPropagation(e);
					setMarker(e.latlng.lat,e.latlng.lng);
				});
			}
				
			
			if($('#acf-location-longitude').val() && $('#acf-location-latitude').val()) {
				setMarker($('#acf-location-latitude').val(),$('#acf-location-longitude').val())
			}
			function setMarker(lat,lon) {
				//if(qfmMap.getZoom() >= mapAtts.minZoomSetmarker) {
					var firstType = [];
					if(typeof(locationTypes)!='undefined') firstType = locationTypes[0];
					var markerIcon = L.icon({iconUrl: (defaultIconUrl ? defaultIconUrl : locationTypes[0][2]), iconSize: [iconWidth, iconHeight], iconAnchor: [(iconWidth/2), iconHeight], popupAnchor: [0, (iconHeight + 3)*-1]});
					if(locationMarker == '') locationMarker = new L.marker([lat,lon],{draggable: false, icon: markerIcon}).addTo(markerLayer[firstType[0]]);
					else locationMarker.setLatLng([lat,lon]);
					qfmMap.setView([lat,lon]);
					$('.bsg-karte-view-marker').show();
					$('#acf-location-longitude').val(lon);
					$('#acf-location-latitude').val(lat);
					$('#acf-location-longitude').trigger('change');
					$('#acf-location-latitude').trigger('change');
				//}
				//else alert(mapTextData.zoomToSetPosition);
			}
			
			projektGebietLayer.on('singleclick', function(e) {
				hasClickedInside = true;
			});
		}
	}
}

function kuulaSettings() {
	$('.entry-content iframe.ku-embed').each(function(){
		$(this).closest('p').addClass('kuula-box');
	});
}