function getXmlHttp(){
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

function sent_new() {
    // (1) создать объект для запроса к серверу
    var req = getXmlHttp();
    var sb1=$('#titleID').val();
    var tx1=nl2br($('#text_add').val());
    var sum1 = $('#sumID').val();
    var sumi = parseInt(sum1);
    var sumflag = "1";
    if (document.getElementById('titleIDsum').style.display=='block') {
	if (sumi=='NaN' || sumi<1 || sumi>99999) {
		sumi='';
		sumflag = "0";
	} else {
		if (sum1!=sumi) { sumflag = "0"; }
	}
	document.getElementById('sumID').value=sumi;
	sum1 = $('#sumID').val();
	if (sum1=='NaN') { document.getElementById('sumID').value=''; }
	if (sumflag == '0') {
		alert(name_get47+'!');
	}
    } else {
        sum1 = '0';
    }
        //alert(sumflag);

    var name_1=$('#addrID').val();
	stringAllTags();
	//alert (kf);
	if (flagtag>0) { alert('not selected any tags'); } else { if (kf==0) { alert('no photo'); } }

    //var name_0=$('#name0').val();
    //var name_00=$('#name00').val();
    //var nm1=$('#ti').val();
    
    //if (sb1=='') { document.getElementById('alert2').style.display = 'block'; } else { document.getElementById('alert2').style.display = 'none'; }
    //if (tx1=='') { document.getElementById('alert3').style.display = 'block'; } else { document.getElementById('alert3').style.display = 'none'; }
    //if (name_0+name_00+country0=='') { document.getElementById('alert1').style.display = 'block'; } else { document.getElementById('alert1').style.display = 'none'; }
    
    req.onreadystatechange = function() { 
        //alert(req.responseText);
        if (req.readyState == 4) {
            // если запрос закончил выполняться
            //statusElem.innerHTML = req.statusText // показать статус (Not Found, ОК..)
            if(req.status == 200) {
                //alert(req.responseText);
                name_1=$('#addrID').val();
                name_1=url+'/?loc='+name_1+'&lat='+new_lat+'&long='+new_lng+'&uname=no&date=no';
                location.replace(name_1);
                //location.reload()
            }
            // тут можно добавить else с обработкой ошибок запроса
        }
    }
    
    // (3) задать адрес подключения
    var str_sent='';
    var i1=0;
    if (kf>0) {
	if (kfstart<=kf) { 
        for ( var i = kfstart; i <= kf; i += 1) {
            //alert(i0,i);
            if (namef[i]!='') {
                i1=i1+1;
                str_sent=str_sent+'&namef'+i1.toString()+'='+namef[i];
            }
        }
        }
    }
    str_sent=str_sent+'&kf='+i1.toString();

    str_sent=str_sent+'&kf_sel='+kf_sel;
    str_sent=str_sent+'&kfstart='+kfstart.toString();
    //str_sent=str_sent+'&name1='+nm1;
    str_sent=str_sent+'&subj1='+sb1;
    str_sent=str_sent+'&text1='+tx1;
    str_sent=str_sent+'&type_o='+strtg;
    str_sent=str_sent+'&name_1='+name_1;

    //str_sent=str_sent+'&type_o1='+type_o1;
    //str_sent=str_sent+'&needs_o='+needs_o;
    str_sent=str_sent+'&new_lat='+new_lat;
    str_sent=str_sent+'&new_lng='+new_lng;
	if (cur_id=='') {} else {
    		str_sent=str_sent+'&cur_id='+cur_id;
	}
    str_sent=str_sent+'&cur_user_id='+cur_user_id;
    str_sent=str_sent+'&sum1='+sum1;
    //str_sent=str_sent+'&name_0='+name_0;
    //str_sent=str_sent+'&name_00='+name_00;
    
    if (name_1!="" && sb1!="" && tx1!="" && flagtag==0 && kf>0 && cur_user_id!='' && sumflag=='1') {        
        req.open('GET', 'save_new.php?act='+oper_obj+str_sent, true); 
        req.send(null);  // отослать запрос

    }
}

function sel_tr(id_tr)
  {
    kf_sel=id_tr;
	var img = document.getElementById('bigimg');  
    if (id_tr=='') {
	img.src='images/blanc_new.png';
    } else {
        //sel_tr_add(id_tr);
	if (id_tr.indexOf("images/obj/")<0) {
		img.src='uploads/'+id_tr;
	} else {
		img.src=id_tr;
	}
    }    
  }

function save_comment() {

    $("#div_add").fadeTo( "fast", 0 );
    //$("#div_add").fadeTo( "fast", 0, function(){ document.getElementById('div_add').style.display = 'none'; } );

    var req = getXmlHttp();
    var reqtext = '';
    var tx1=nl2br($('#comm_add').val());
    tx1 = tx1.replace(new RegExp("'",'g'), "`");
    
    req.onreadystatechange = function() {
        if (req.readyState == 4) {
            //statusElem.innerHTML = req.statusText
            if(req.status == 200) {
		reqtext=req.responseText;
		document.getElementById('div_add').style.display = 'block';
		if(reqtext.indexOf('&to=')==0) {
	                //alert(reqtext+'!');
			//hash = reqtext;

	theData = new Date(); 

    $('#comm_add').text('');
    html = '';
    html=html+'<div class="comment"><div class="info"><a href="?uname='+first_name+'"><U><br>'+first_name+'</U></a></div><div class="text"><p>'+tx1;
    html=html+'</p><div class="time">'+name_get52;
    html=html+'</div></div></div>';
    $('#commentid').prepend(html);

    $("#div_add").fadeTo( "slow", 1 );

			sent_comment(reqtext);
		} else {
			$("#div_add").fadeTo( "fast", 1 );
	                alert(reqtext);
		}
            }
        }
    }
    	str_sent='&objid='+cur_obj_id;
    	str_sent=str_sent+'&usrid='+cur_user_id;
    	str_sent=str_sent+'&text='+tx1;

    if (cur_obj_id!='' & cur_user_id!='' & tx1!='') {
        req.open('GET', 'save_comment.php?act=m'+str_sent, true);
        req.send(null);
    } else {
	$("#div_add").fadeTo( "fast", 1 );
    }
}


function sent_comment(nm1) {

    var req = getXmlHttp();
    var sb1=$('#titleID').val();
    var tx1=nl2br($('#comm_add').val());
    
    req.onreadystatechange = function() { 
        if (req.readyState == 4) {
            //statusElem.innerHTML = req.statusText 
            if(req.status == 200) {
                
                reqtext=req.responseText;
                //alert(reqtext);
                
		//document.getElementById('layer1').style.display = 'none';
                //alert(msg1+'! '+msg4+'.');
                //location.reload()
                //$('#outward').fadeOut(0);
		//location.reload(url);
		//window.location.reload(url);
                //location.replace(url);
            }
        }
    }

    	str_sent=nm1;
	str_sent=str_sent+'&msgsent=1';
    	str_sent=str_sent+'&subject='+sb1;
	msg1='Add new comment:';
	message = '<html> <head> <h3>'+msg1+'</h3> </head> <body> <p>';
	message = message+'</br><a href="'+url+'"><img src="'+url+'/images/bar/lost.png" alt=""></a>';
	message = message+'<a href="'+url+'/n='+cur_obj_id+'">'+tx1+'</a></p></body></html>';
    	str_sent=str_sent+'&content='+message;
//alert (str_sent);
    if (nm1!="") {
        req.open('GET', 'registration/send.php?act=m'+str_sent, true);
        req.send(null);
    }
}

function init() {
	//$("#selectfid").fadeTo( "fast", 0 );
	//document.getElementById('selectfid').style.display = 'none';

    numPages=maxnumPages;
    // Данные о местоположении, определённом по IP
    var geolocation = ymaps.geolocation,
    // координаты
    lat=geolocation.latitude;
    long=geolocation.longitude;
    coords = [geolocation.latitude, geolocation.longitude],
    //myMap = new ymaps.Map('map', {
    //                      center: coords,
    //                      zoom: 10
    //                      });
    //objSel.options[objSel.options.length] = new Option(geolocation.city, "0");
    $('#placeID').val(geolocation.city);
    
    var mapOptions = {
    zoom: 12,
    center: new google.maps.LatLng(lat, long),
    mapTypeId: google.maps.MapTypeId.ROADMAP
    };
	geocoder = new google.maps.Geocoder();
    map = new google.maps.Map(document.getElementById('tab_map2'),
                              mapOptions);
    map_add = new google.maps.Map(document.getElementById('add_map'),
                              mapOptions);

    if (filter_lat!='') { lat=filter_lat; }
    if (filter_long!='') { long=filter_long; }
    
    lat2 = lat;
    long2 = long;
    new_lat=lat2;
    new_lng=long2;
    
    var input2 = document.getElementById('addrID');
    autocomplete2 = new google.maps.places.Autocomplete(input2);

    google.maps.event.addListener(map_add, 'click', function(event) {
                                  //alert('!!!!');
			if (my_obj=='1') {
                                  if (marker) {        marker.setMap(null);  } 
                                  marker = new google.maps.Marker({
                                                                  position:event.latLng,
                                                                  clickable:false
                                                                  });
                                  marker.setMap(map_add);
                                  codeLatLng(0);
			}
                                  });
    
    google.maps.event.addListener(autocomplete2, 'place_changed', function() {
                                  var place = autocomplete2.getPlace();
                                  //alert(place.geometry.location);
                                  //sel_start0(1);
                                  if (!place.geometry) {
                                  // Inform the user that the place was not found and return.
                                  input.className = 'notfound';
                                  return;
                                  }                                  
                                  if (marker) {        marker.setMap(null);  }
                                  marker = new google.maps.Marker({
                                                                  clickable:false
                                                                  });
                                  marker.setPosition(place.geometry.location);
                                  marker.setMap(map_add);
                                  //codeLatLng(0);
                                  map_add.setZoom(14);
                                  map_add.setCenter(place.geometry.location);
                         	  new_lat=place.geometry.location.lat().toString();
                         	  new_lng=place.geometry.location.lng().toString();
                                  //if (document.getElementById('mapbtn0').style.display == 'none') {
                                  //sel_start0(0);
                                  //}
                                  });

    var input1 = document.getElementById('placeID');
    autocomplete = new google.maps.places.Autocomplete(input1);
    google.maps.event.addListener(autocomplete, 'place_changed', function() {
                                  var place = autocomplete.getPlace();
                                  if (!place.geometry) {
                                  input1.className = 'notfound';
                                  return;
                                  }                                  
                                  numPages=maxnumPages;
                                  //objSel.options[objSel.options.length] = new Option($('#placeID').val(), "0", true, true);
                                  lat2 = place.geometry.location.lat();
                                  long2 = place.geometry.location.lng();
				  new_lat=lat2;
				  new_lng=long2;
					window.document.title='Lostey';

                                  document.getElementById('personal-tab').style.display = 'none';
                                  document.getElementById('tab_list').style.display = 'block';
                                  map.setCenter(new google.maps.LatLng(lat2, long2));
				  google.maps.event.trigger(document.getElementById("tab_map2"),'resize');
                                  cur_win=1;
                                  

                                  var $container = $('#tab_list');

                                  var $firstTwoElems = $container.data('isotope')
                                  .$filteredAtoms.filter( function( i ) {
                                                         return i < nextPage*15;
                                                         });
                                  nextPage=0;
                                  target_top=0;
                                  //show_progress();
                                  var block = document.getElementById('layer1');
                                  block.style.left = parseInt(document.documentElement.clientWidth)/2 - parseInt(block.offsetWidth)/2 + "px";
                                  block.style.top = parseInt(document.documentElement.clientHeight)/2 - parseInt(block.offsetHeight)/2 + "px";
                                  block.style.display = 'block';
                                  $.ajax({
                                         type: "GET",
                                         url: "more.php?page=" + nextPage+"&lat="+lat2+"&long="+long2+filter_str+"&find="+where_find+"&mob_get="+mob_get,
                                         cache: false,
                                         success: function(html){
                                         $( html ).imagesLoaded(function(){ 
                                                                $container.isotope( 'insert', $( html ) );
                                         });                   
                                            $container.isotope( 'remove', $firstTwoElems);
                                            block.style.display = 'none';
                                            change_city=1;
                                            //hide_progress();
                                         }
                                         });
                                    nextPage++;                                  
                                  });
    if (id_get=='') {
    $.ajax({
           type: "GET",
           url: "more.php?page=0&lat="+lat2+"&long="+long2+filter_str+"&find="+where_find+"&mob_get="+mob_get,
           cache: false,
           success: function(html){
		//alert("more.php?page=0&lat="+lat2+"&long="+long2+filter_str+"&find="+where_find+"&mob_get="+mob_get);
           $( html ).imagesLoaded(function(){ 
                                  $('#tab_list').isotope( 'insert', $( html ) ); });     
           }
           });
    } else {
        selObj(id_get);
    }

    
}

function initialize() {
    var myCountry;                     	
}

function codeLatLng(n_map) {
    var latlng;
    var lng = 'en';
    //var cur_addr='';
    var getCountry;                     
    var getColog;                     
    var getPolit;                     
    var getLocal;                     
    var getArea1;                     
    var getArea2;                     
    var getArea3;

        latlng = marker.position;
        
        geocoder.geocode({'latLng': latlng}, function(results, status) {
                         if (status == google.maps.GeocoderStatus.OK) {
                         //alert(marker.position);
                         if (results[1]) {

                         new_lat=latlng.lat().toString();
                         new_lng=latlng.lng().toString();
                         country0='';

                         pnm00='addrID';

                         //alert(results[1].formatted_address);
                         document.getElementById(pnm00).value=results[1].formatted_address;

                         if (document.getElementById(pnm00).value!='') {cur_addr=document.getElementById(pnm00).value;}
                         
                         } else {
                         alert('No results found');
                         }
                         } else {
                         alert('Geocoder failed due to: ' + status);
                         }
                         });
        
}

function upd()
{
  $.ajax({
         type: "GET",
         url: "more.php?page=" + nextPage+"&lat="+lat2+"&long="+long2+filter_str+"&find="+where_find+"&mob_get="+mob_get,
         cache: false,
         success: function(html){
         $( html ).imagesLoaded(function(){ 
                                if (html=='') { 
                                    numPages=nextPage-1; 
                                }
                                $('#tab_list').isotope( 'insert', $( html ) );
                                hide_progress();
                                }); 
         }
         });
  nextPage++;
}
  
function show_progress()
{
  	var block = document.getElementById('layer1');
  	block.style.left = parseInt(document.documentElement.clientWidth)/2 - parseInt(block.offsetWidth)/2 + "px";
  	block.style.top = parseInt(document.documentElement.clientHeight)/2 - parseInt(block.offsetHeight)/2 + "px";
  	block.style.display = 'block';
}

function hide_progress()
{
  document.getElementById('layer1').style.display = 'none';
}
