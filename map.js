// https://github.com/Leaflet/Leaflet.heat/find/gh-pages
// https://github.com/mourner/simpleheat
//
// mappa del fondo
//
function getCoordinate() {
	//var vfondoid = location.search.split("&")[0].replace("?", "").split("=")[1];
	//var vschedaid = "136";
	//var formData = {fondoid: vfondoid};
	$.ajax({
		url: "./pianta/coordinate/read.php",
		type: "GET",
		//dataType:"json",
		//data: formData, //data to server
		success: function (data, textStatus, jqXHR) {
			//data response from server
			var out = data;
			//getCountAll();
			//getListaInterventi();
			//displayFondo(out);
			getMap(out);
			//window.alert(out.schedaid);
			//window.alert(out.qrcode);
		},
		error: function (er) {
			var status = er.status;
			var text = er.statustext;
			var message = status + ":" + text; -
				alert(message);
		}
	});

}; 
function getMap(out){
	var map = L.map('map').setView([46.0637, 11.1401], 9);
	var fondo = out;
	var i,x = "";
	L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
		attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
	}).addTo(map);

	// caricamento dinamico geo point

	for(i in fondo){
		L.marker([fondo[i].lat, fondo[i].lon]).addTo(map)
		.bindPopup('specie '+fondo[i].specie +'<br> altezza'+fondo[i].stima_altezza+' mt'+'<br>'+fondo[i].localita+'<br>'+ 'rilevatore ' + fondo[i].cognome, {closeOnClick: false, autoClose: true})
		.openPopup();
	};

	/** Caricamento statico
	L.marker([45.93885, 10.91519]).addTo(map)
		.bindPopup('Castagneto secolare Marcabruni'+'<br>'+'3,4h'+'<br>'+'200 piante')
		.openPopup();
	L.marker([46.064595 , 11.456113]).addTo(map)
			.bindPopup('Delugan-Mezzolombardo')
			//.openPopup();
	L.marker([46.226328 , 11.072280]).addTo(map)
			.bindPopup('Delugan-Mezzolombardo')
			//.openPopup();
**/
// Poligono fondo Marcabruni - Pianaura
var polygon = L.polygon([
	[45.94039, 10.91645], //pto01
	[45.93877, 10.91699],
	[45.93847, 10.91504],
	[45.93788, 10.91433],
	[45.93981, 10.91479],
],
{
	fillColor: '#f03',
	fillOpacity: 0.3
	}
	).addTo(map);  
  var polygon2 = L.polygon([
		[45.93787, 10.91424], //pto01  93998
		[45.93666, 10.91372],
		[45.93680, 10.91313],
		[45.93799, 10.91284],
		[45.9390, 10.91398],
		[45.93978, 10.91476],
	],{
	 fillColor: '#f03',
	fillOpacity: 0.3   
	}
).addTo(map); 
var polygon3 = L.polygon([
		[45.938488, 10.915617], //pto01
		[45.938255, 10.916195],
		[45.937920, 10.916195],
		[45.937573, 10.916349],
		[45.937506, 10.9162113],
	], {
			fillColor: '#f03',
			fillOpacity: 0.3
		}
	).addTo(map);		

//addressPoints = addressPoints.map(function (p) { return [p[0], p[1]]; });

//var heat = L.heatLayer(addressPoints).addTo(map);

var heat = L.heatLayer([
[45.93837, 10.91529, 0.9], // lat, lng, intensity
[45.938488, 10.915617, 0.5],
], {radius: 25}).addTo(map);
}
