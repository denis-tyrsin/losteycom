function selObj(obj_id)
{
	cur_obj_id=obj_id;
	if (document.getElementById('selectfid').style.display=='block') {$("#selectfid").fadeTo( "fast", 0, function(){ document.getElementById('selectfid').style.display = 'none'; } );}
    cur_win=3;
    var block = document.getElementById('layer1');
    block.style.left = parseInt(document.documentElement.clientWidth)/2 - parseInt(block.offsetWidth)/2 + "px";
    block.style.top = parseInt(document.documentElement.clientHeight)/2 - parseInt(block.offsetHeight)/2 + "px";
    block.style.display = 'block';
    $.ajax({
           type: "GET",
           url: "getobj.php?obj="+obj_id,
           cache: false,
           success: function(html){
           qw=document.getElementById('personal-tab');
           $( html ).imagesLoaded(function(){ 
				my_obj='1';
                                  //qw.innerHTML=html;
				document.getElementById('sloganid').style.display = 'none';
				$("#tab_list").fadeTo( "fast", 0 );
                                  document.getElementById('tab_map2').style.display = 'none';
                                  document.getElementById('tab_list').style.display = 'none';
                                  //alert(html);
                                  document.getElementById('personal-tab').style.display = 'none';
				$("#personaladd-tab").fadeTo( "slow", 1 );
					var arrobj=html.split('&&&&***^^^');
					//alert(nl2brback(arrobj[4]));
					//$('#text_add').value=nl2brback(arrobj[4]);
					//document.getElementById('text_add').value=nl2brback(arrobj[4]);
					$('#text_add').text(nl2brback(arrobj[4]));
                    $('#comm_add').text('');
                    document.getElementById('slogan0id').style.display = 'none';
					//$('#text_add').innerHTML=arrobj[4];
                         	  	nu=arrobj[7];
                         	  	idu=arrobj[8];
                         	  	m=arrobj[9];

					document.getElementById('titleID').value=arrobj[0];
					document.getElementById('addrID').value=arrobj[2];
					document.getElementById('bigimg').src=arrobj[3];
					document.getElementById('smallimg').innerHTML='';
                                  	window.document.title=arrobj[0];
					new_lat=arrobj[5];
                         	  	new_lng=arrobj[6];
                         	  	new_sum=arrobj[10];
                         	  	cur_sum=arrobj[11];
                         	  	proc=arrobj[12];
                         	  	date_obj=arrobj[13];
					var days_obj='';
                         	  	days_obj=arrobj[14];
            				if (days_obj<1) { days_obj=name_get52; }
            				if (days_obj==1) { days_obj=days_obj+' '+name_get49; }
            				if (days_obj>1) { days_obj=days_obj+' '+name_get50; }
					document.getElementById('cursumid').innerHTML='$'+cur_sum+' USD';
					document.getElementById('curprocid').innerHTML=proc+'%';
					document.getElementById('progressid').style.width=proc+'%';
					document.getElementById('sumID').value=new_sum;
					document.getElementById('daysid').innerHTML=' '+date_obj+' - '+days_obj;
                         	  	fname=arrobj[15];
                         	  	img=arrobj[16];
					cur_id=obj_id;
					oper_obj='e';
					kf=0;
					kf_sel=arrobj[3];
					if (arrobj.length>17) {
						for ( var i = 17; i<arrobj.length; i++) {
							kf=kf+1;
		                 			namef[kf]=arrobj[i];
					$('#smallimg').append('<a onClick="javascript:sel_tr(\''+arrobj[i]+'\')"><img src="'+arrobj[i]+'" alt="" width="100px"></a>');
						}
					}
					kfstart=kf+1;
					clearAllTags();
					if (m=='1') {
						document.getElementById('sumID').readOnly=false;
						viewAllTags();
						curTags(arrobj[1]);
						document.getElementById('titleIDinput').style.display = 'block';
						document.getElementById('titleIDdiv').innerHTML='';
						document.getElementById('addrIDinput').style.display = 'block';
						document.getElementById('addrIDdiv').innerHTML='';
						document.getElementById('text_add').style.display = 'block';
						document.getElementById('text_adddiv').innerHTML='';
						document.getElementById('upload').style.display = 'block';
						document.getElementById('btnsaveid').style.display = 'block';
					} else {
						document.getElementById('sumID').readOnly=true;
						hideAllTags();
						curTagsView(arrobj[1]);
						document.getElementById('titleIDinput').style.display = 'none';
						document.getElementById('titleIDdiv').innerHTML=arrobj[0];
						document.getElementById('addrIDinput').style.display = 'none';
						document.getElementById('addrIDdiv').innerHTML=arrobj[2];
						document.getElementById('text_add').style.display = 'none';
//'<a href="?uname='+nu+'"><U><img src="images/users/s'+idu+'.jpg" alt=""> '+nu+'</U></a><br>';
						document.getElementById('text_adddiv').innerHTML='<a href="?uname='+fname+'"><img src="images/users/s'+img+'.jpg" alt="">&nbsp;&nbsp;<U>'+fname+'</U></a><br><br>'+arrobj[4];
						document.getElementById('upload').style.display = 'none';
						document.getElementById('btnsaveid').style.display = 'none';
					}
					if (marker) {        marker.setMap(null);  }
                                  	if (new_lat=='' || new_lng=='') { 
    							new_lat=lat2;
    							new_lng=long2;
							alert('No position');
					} else {
                                  		marker = new google.maps.Marker({
								  position: new google.maps.LatLng(arrobj[5], arrobj[6]),
                                                                  clickable:false
                                                                  });

                                  		marker.setMap(map_add);
					}
                                        map_add.setZoom(12);
                                        map_add.setCenter(new google.maps.LatLng(new_lat, new_lng));
                                     	google.maps.event.trigger(document.getElementById("add_map"),'resize');
					my_obj=m;

					document.getElementById('preview-frame').src = "social.php?n="+obj_id+"&text="+arrobj[0];

                                  block.style.display = 'none';
                                  $('html, body').animate({scrollTop:0}, 'fast');
			selObj_view(obj_id);
                });
           }
           });
}
function selObj_view(obj_id)
{
    document.getElementById('commentaddid').style.display = 'block';
    $.ajax({
           type: "GET",
           url: "obj_comm.php?obj="+obj_id,
           cache: false,
           success: function(html){
           qw=document.getElementById('commentid');
           $( html ).imagesLoaded(function(){ 
                                  qw.innerHTML=html;
                });
           }
           });
}