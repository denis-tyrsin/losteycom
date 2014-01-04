function getXmlHttp0(){
    var xmlhttp;
    try {
        xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
    } catch (e) {
        try {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        } catch (E) {
            xmlhttp = false;
        }
    }
    if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
        xmlhttp = new XMLHttpRequest();
    }
    return xmlhttp;
}

function attachSecretMessage(marker, number) {
    google.maps.event.addListener(marker, 'click', function() {
                                  //params=document.getElementById('id_param1').value;
                                  //params=params+'&det='+markers_obj[number];
                                  //document.location.href = params;
                                  //document.getElementById('tab_map').style.display = 'none';
                                  	selObj(markers_obj[number]);
                                  });
}

function show_markers10(latlng_text) {
    var response = latlng_text.split("|");
    var lat;
    var lng;
    var center_mark=0;
    var j=1;
    for (var i = 1; i < response.length; i++) {
        id=response[i];
        lat=response[i+1];
        lng=response[i+2];
        id_obj=response[i+3];
        i=i+3;
        if (lat!=0 && lng!=0) {
                var marker11 = new google.maps.Marker({
                                                      position: new google.maps.LatLng(lat, lng),
                                                      clickable: true,
                                                      zIndex: j,
                                                      title: id
                                                      });
                marker11.setMap(map);
                attachSecretMessage(marker11, j);
            //alert (lat);
                markers_obj[j]=id_obj;
                j=j+1;
        }
    }

}

function show_markers0(latlng_text) {
    //alert(latlng_text);
    var response = latlng_text.split("|");
    var lat;
    var lng;
    var center_mark=0;
    var j=1;
    
    for (var i = 1; i < response.length; i++) {
        id=response[i];
        lat=response[i+1];
        lng=response[i+2];
        id_obj=response[i+3];
        
        //lat=place_result.lat();
        //lng=place_result.lng();
        
        i=i+3;
        //place_result
        if (lat!=0 && lng!=0) {
            //id=0;
            if (id==0) {
                var marker11 = new google.maps.Marker({
                                                      position: new google.maps.LatLng(lat, lng),
                                                      clickable: true,
                                                      zIndex: j,
                                                      title: id
                                                      });
                marker11.setMap(map);                
                
                attachSecretMessage(marker11, j);
                markers_obj[j]=id_obj;
                
                
                j=j+1;
            } else {
                var shadow = new google.maps.MarkerImage('images/obj/s'+id+'.jpg',
                                                         new google.maps.Size(40, 40),
                                                         new google.maps.Point(0,0),
                                                         new google.maps.Point(-10, 50));
                var image = new google.maps.MarkerImage('images/baloon22.png',      
                                                        new google.maps.Size(60, 60),      
                                                        new google.maps.Point(0,0),      
                                                        new google.maps.Point(0, 60)); 
                var marker11 = new google.maps.Marker({
                                                      position: new google.maps.LatLng(lat, lng),
                                                      icon: image,
                                                      //icon: image,
                                                      clickable: true,
                                                      zIndex: j+1
                                                      //title: id_obj.toString()
                                                      });
                //marker11.setTitle(id_obj);
                markers_obj[j]=id_obj;
                markers_obj[j+1]=id_obj;
                //alert(markers_obj[j+1]);
                
                
                
                //map1.addOverlay(marker11);
                marker11.setMap(map);
                
                attachSecretMessage(marker11, j+1);
                
                
                
                
                
                
                var marker11 = new google.maps.Marker({
                                                      position: new google.maps.LatLng(lat, lng),
                                                      icon: shadow,
                                                      clickable: true,
                                                      zIndex: j
                                                      });
                marker11.setMap(map);
                
                attachSecretMessage(marker11, j);
                
                j=j+2;                
                //                j=j+1;                
            }
         
            
            
        } else {
            center_mark=center_mark+1;
        }
        
    }
    
    //alert(j);
    if (center_mark>0) {
        
        
        
        
        lat=place_result.lat();//.toString();
        lng=place_result.lng();//.toString();
        var image1 = new google.maps.MarkerImage('images/baloon223.png',      
                                                 new google.maps.Size(60, 60),      
                                                 new google.maps.Point(0,0),      
                                                 new google.maps.Point(0, 60)); 
        marker11 = new google.maps.Marker({
                                          position: new google.maps.LatLng(lat, lng),
                                          //shadow: image1,
                                          icon: image1,
                                          clickable: true,
                                          zIndex: j,
                                          title: id
                                          });
        marker11.setMap(map);  
        
    }
    
    //alert(response.length);
    
    
}


function get_markers0() {
    var req = getXmlHttp0();
    //var params=document.getElementById('id_param1').value;
    //params=params.replace('?', '');
    req.onreadystatechange = function() { 
        if (req.readyState == 4) {
            if(req.status == 200) {
//                alert(req.responseText);
                show_markers0(req.responseText);
            }
        }
    }
        req.open('GET', 'get_markers.php?page=' + nextPage+'&lat='+lat2+'&long='+long2+filter_str+"&find="+where_find, true); 
        req.send(null);  // отослать запрос
}

function upm(el){
	$(el).fadeTo( "fast", 0.3);
}
function downm(el){
	$(el).fadeTo( "fast", 1);
}
