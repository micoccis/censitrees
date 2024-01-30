//Get list of plantes from db
function getPlants() {
    var vspecie= document.getElementById("specie").value;
    var vtipoluogo = document.getElementById("tipoluogo").value;
    var vluogo = document.getElementById("luogo").value;
    var formData= {
        specie: vspecie,
        tipoluogo: vtipoluogo,
        luogo:vluogo
    };
    $.ajax({
        url: "./pianta/read.php",
        type: "GET",
        //dataType:"json",
        data: formData, //data to server
        success: function (data, textStatus, jqXHR) {
            //data response from server
            var out = data;
            displayPlants(out);
            //window.alert(out.records[0].Titolo);
            //window.alert(out.qrcode);
        },
        error: function (er) {
            var status = er.status;
            var text = er.statustext;
            var message = status + ":" + text; -
                alert(message);
        }
    });
    
    // window.location.assign("./schedadisplay.html?qrcode="+vqrcode+"&"+tipoLuogo+"="+vtipoLuogo+"&"+tipoPianta+"="+vtipoPianta)
    };
    //Dsiplay plants list
    function displayPlants(out){
        var r = out;
            var i, x = "";
            var row = "";
            //document.getElementById("proprietario").innerHTML="<b>Prorietario:</b> " +fondo[0].nome + " "+ fondo[0].cognome + "<br>";
            var tbody = document.getElementById("myTbody");
            tbody.innerHTML = row;
            for (i in r) {
            var row = "<tr><td><b>" +
                    r[i].localita +  "</b></td><td>" +
                    r[i].specie +  "</td><td>" + 
                    
                    //r[i].stima_altezza +  "</td><td>" + 
                    //r[i].stima_larghezza +  "</td><td>" + 
                    // r[i].diametro +  "</td><td>" + 
                    //r[i].ecotipo +  "</td><td>" + 
                    //r[i].stato_fito + "</td><td>" + 
                    //r[i].associazione + "</td><td>" +
                   //"<a href= \"/" + r[i].url_file +  "\">"+r[i].url_file + "</a></td>" +

                    /* "<td><div class=\"btn-group-vertical\"><a class=\"btn btn-outline-primary btn-sm\" href=\"#\" role=\"button\">dettagli</a>" +
                    "<a class=\"btn btn-outline-info btn-sm\" href=\"#\" role=\"button\">modifica</a>" +
                    "<a class=\"btn btn-outline-danger btn-sm\" href=\"#\" role=\"button\">cancella</a></div></td>"+ 

                    "<td>" + */
                    "<div class=\"btn-group\" role=\"group\">"+
    "<button id=\"btnGroupDrop1\" type=\"button\" class=\"btn btn-info btn-sm dropdown-toggle\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">"+
     "azione" +
    "</button>"+ 
    "<div class=\"dropdown-menu\" aria-labelledby=\"btnGroupDrop1\">"+
      "<a class=\"dropdown-item btn-secondary btn-sm\" href=\"./dettagli.html?id_pianta=" + r[i].id_pianta +"\">dettagli</a>"+
      "<a class=\"dropdown-item btn-info btn-sm\" href=\"#\">modifica </a>"+
      "<a class=\"dropdown-item btn-danger btn-sm\" href=\"#\">cancella</a>"+
    "</div></div> </td></tr>";
           
                   
            //var row2 = "<tr><td>" + "Provincia" + "</td><td>" + fondo[i].provincia + "</td></tr>";
            //var row3= "<tr><td>" + "Comune" + "</td><td>" + fondo[i].comune + "</td></tr>";
            // var row3 = "<tr><td>" + "Numero interventi" + "</td><td>" + intervento[i].NumInterventi + "</td></tr>";
            // var row4 = "<tr><td>" + "Data interventi" + "</td><td>" + intervento[i].DataIntervento + "</td></tr>";
            // var row2 = "<tr><td>" + "Numero piante interessate" + "</td><td>" + intervento[i].NumPianteIntervento + "</td></tr>";
            // var row5 = "<tr><td>" + "Fondi interessati" + "</td><td>" + intervento[i].InterventiFondo + "</td></tr>";
            
    
            //var rows = row0+acc1+acc3+acc4+acc5+row4;
            //window.alert(row);
            //var rows = row                                                                                                                              ;
            tbody.innerHTML += row;
                    }
    };
    //Get plant position
    function getMyLocation() {
        var displayLat = document.getElementById("lat");
        var displayLng = document.getElementById("lon");
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function (position) {
                var lat = position.coords.latitude;
                var lon = position.coords.longitude;
                displayLat.value = lat;
                displayLng.value = lon;
            }, function () {
                handleLocationError(true, infoWindow, map.getCenter());
            });
        } else {
            // Browser doesn't support Geolocation
            handleLocationError(false, infoWindow, map.getCenter());
        }
    };
    //Create new plant from
    function  createPlants(){
        var vspecie = document.getElementById("specie").value;
        var vstima_altezza= document.getElementById("stima_altezza").value; 
        var vstima_larghezza= document.getElementById("stima_larghezza").value; 
        var vdiametro= document.getElementById("diametro").value; 
        var vecotipo= document.getElementById("ecotipo").value; 
        var vstato_fito= document.getElementById("stato_fito").value; 
        var vassociazione= document.getElementById("associazione").value;     
        
        var vlat= document.getElementById("lat").value;     
        var vlon= document.getElementById("lon").value;     
        var vlocalita= document.getElementById("localita").value;     
        var vcomune= document.getElementById("comune").value;     
        var vprovincia= document.getElementById("provincia").value;     
        var vnome= document.getElementById("nome").value;     
        var vcognome= document.getElementById("cognome").value;     
        var vurl_file= document.getElementById("url_file").value; 
        var vnota= document.getElementById("nota").value; 

   // var vurl_file= vdata_magazine + "_" + vtipomagazine + "_" + vcategoria + "_" + vstato + "_"+ vcitta;

    var formData ={
        specie: vspecie,
        stima_altezza: vstima_altezza,
        stima_larghezza: vstima_larghezza,
        diametro: vdiametro,
        ecotipo: vecotipo,
        stato_fito: vstato_fito,
        associazione: vassociazione,
        lat: vlat,
        lon: vlon,
        localita: vlocalita,
        comune: vcomune,
        provincia: vprovincia,
        nome: vnome,
        cognome: vcognome,
        url_file: vurl_file,
        nota: vnota

    };
$.ajax({
url: "./pianta/create.php",
type: "GET",
//dataType:"json",
//data: formData, //data to server
data: formData, //data to server
success: function (data, textStatus, jqXHR) {
    //data response from server
    var out = data;
    //window.location.assign("./nolog.html");
    //displayArticoli(out);
    //window.alert(out.records[0].Titolo);
    window.alert(out.message);
    //document.getElementById("alert").innerHTML= out.message;
    window.location.assign("./crea.html");

},
error: function (er) {
    var status = er.status;
    var text = er.statustext;
    var message = status + ":" + text; -
        alert(message);
}
});
    };
    //Get and display counts
    function getCounts(){
        $.ajax({
            url: "./pianta/counts/read.php",
            type: "GET",
            //dataType:"json",
            //data: formData, //data to server
            success: function (data, textStatus, jqXHR) {
                //data response from server
                var out = data;
                //displayCounts(out);
                //window.alert(out.records[0].Titolo);
                //window.alert(out.qrcode);
                var r = out;
            var i, x = "";
            var row = "";
            //document.getElementById("proprietario").innerHTML="<b>Prorietario:</b> " +fondo[0].nome + " "+ fondo[0].cognome + "<br>";
            var tbody = document.getElementById("myTbody");
            tbody.innerHTML = row;
            for (i in r) {
            var row = "<tr><td><b>" +
                    r[i].localita +  "</b></td><td>" +
                    r[i].ecotipo +  "</b></td><td>" +
                    r[i].numero_ecotipo +  "</td><td>"                                                                                                                         ;
            tbody.innerHTML += row;
                    }
            },
            error: function (er) {
                var status = er.status;
                var text = er.statustext;
                var message = status + ":" + text; -
                    alert(message);
            }
        });

    };
    // Get plat details
    function getDetails(){
        var url = location.href;
         //var url = "http://foo? k1=v1&k2=v2&k3=v3";
        var kid_pianta = url.split("?")[1].split("&")[0].split("=")[0]; //solo chiave
        var vid_pianta = url.split("?")[1].split("&")[0].split("=")[1]; //solo valore
        var formData= {
            id_pianta: vid_pianta
        };
        $.ajax({
            url: "./pianta/id/read.php",
            type: "GET",
            //dataType:"json",
            data: formData, //data to server
            success: function (data, textStatus, jqXHR) {
                //data response from server
                var out = data;
                //displayPlants(out);
                //window.alert(out.records[0].Titolo);
                //window.alert(out.qrcode);
                var i, x = "";
                //document.getElementById("proprietario").innerHTML="<b>Prorietario:</b> " +fondo[0].nome + " "+ fondo[0].cognome + "<br>";
                
                var tbody = document.getElementById("myTbody");
                for (i in out) {
                
                // var acc1 = "<tr><td><a class= \"btn btn-info\" data-toggle=\"collapse\" href=\"#collapse" + i + "\" role=\"button\"aria-expanded=\"false\" aria-controls=\"collapse" + i + "\">" + "Fondo " + fondo[i].fondo + "</a></td>";
                // var acc3 = "</a></td><td><div class=\"collapse\" id=\"collapse" + i + "\"><div class=\"card card-body\">";
                // var acc4 = "Fondo: " + fondo[i].fondo + "<br>" + "Località: " + fondo[i].localita + "<br>" + "Comune: " + fondo[i].comune + "<br>" + "Provincia: " + fondo[i].provincia;
                // var acc5 = "</div></div></td></tr>";

                var row0 = "<thead><tr><th>Nome</th><th>Valore</th></tr></thead >";
                var row1 = "<tr><td><b>" + "Comune" + "</b></td><td><b>" + out[i].comune + "</b></td></tr>";
                var row2 = "<tr><td>" + "Provincia" + "</td><td>" + out[i].provincia + "</td></tr>";
                var row3= "<tr><td>" + "Nome cognome rilevatore" + "</td><td>" + out[i].nome + " " + out[i].cognome + "</td></tr>";
                var row4 = "<tr><td>" + "Associazione" + "</td><td>" + out[i].associazione + "</td></tr>";
                var row5 = "<tr><td>" + "Stima altezza (mt)" + "</td><td>" + out[i].stima_altezza + "</td></tr>";
                var row6 = "<tr><td>" + "Circonferenza a 1,5mt (cm)" + "</td><td>" + out[i].diametro + "</td></tr>";
                var row7 = "<tr><td>" + "Specie" + "</td><td>" + out[i].specie + "</td></tr>";
                var row8 = "<tr><td>" + "Ecotipo" + "</td><td>" + out[i].ecotipo + "</td></tr>";
                var row9 = "<tr><td>" + "Località dimora" + "</td><td>" + out[i].localita + "</td></tr>";
                var row10 = "<tr><td>" + "Stato fitosanitario" + "</td><td>" + out[i].stato_fito + "</td></tr>";
                var row11 = "<tr><td>" + "Note" + "</td><td>" + out[i].nota + "</td></tr>";
                var row12 = "<tr><td>" + "Coordinate" + "</td><td>" + out[i].lat + "," + out[i].lon + "</td></tr>";
                var row13 = "<tr><td>" + "Stima larghezza (mt)" + "</td><td>" + out[i].stima_larghezza + "</td></tr>";


                //var rows = row0+acc1+acc3+acc4+acc5+row4;
                var rows = row1+row2+row9+row12 +row7+row8+row5+row13+row6+ row10+row11 + row3+row4;
                tbody.innerHTML += rows;
                };
            },
            error: function (er) {
                var status = er.status;
                var text = er.statustext;
                var message = status + ":" + text; -
                    alert(message);
            }
        });
    }