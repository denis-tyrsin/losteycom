                  function findid_focus(){
					document.getElementById('selectfid').style.display = 'block';
					$("#selectfid").fadeTo( "slow", 1 );
                                      }

                  function findid_blur(){
                                      }

                  function addrID_focus(){
					mapimg=1;
					document.getElementById('add_map').style.display = 'block';
                                        map_add.setZoom(14);
                                        map_add.setCenter(new google.maps.LatLng(new_lat, new_lng));
                                     	google.maps.event.trigger(document.getElementById("add_map"),'resize');
					document.getElementById('add_map').style.display = 'none';
					document.getElementById('add_map').style.display = 'block';
                                        map_add.setCenter(new google.maps.LatLng(new_lat, new_lng));
                                     	google.maps.event.trigger(document.getElementById("add_map"),'resize');
                                      }

                  function text_add_focus(){
                                      }

                  function titleID_focus(){
                                      }

                  function placeID_focus(){
					if (document.getElementById('selectfid').style.display=='block') {$("#selectfid").fadeTo( "fast", 0, function(){ document.getElementById('selectfid').style.display = 'none'; } );}
                                      input_temp=$(this).value;
                                      $(this).val("");
                                      }

                  function findbtn_click(){
                                        if (document.getElementById('selectfid').style.display=='block') {$("#selectfid").fadeTo( "fast", 0, function(){ document.getElementById('selectfid').style.display = 'none'; } );}
                                        var findval=$('#findid').val();
                                      if (findval=='') { findval='no'; }
					findval=url+'\?find='+findval;
					location.replace(findval);
                                     }

                  function btnadd_click(){
					window.document.title='Lostey';
                                    if (document.getElementById('selectfid').style.display=='block') {$("#selectfid").fadeTo( "fast", 0, function(){ document.getElementById('selectfid').style.display = 'none'; } );}
					        if (cur_user_name=='') {
							document.getElementById('personal-tab').style.display = 'block';
                                                        document.getElementById('tab_map2').style.display = 'none';
                                                        document.getElementById('tab_list').style.display = 'none';
                                                        document.getElementById('personaladd-tab').style.display = 'none';
        					} else {
							my_obj='1';
						document.getElementById('sumID').readOnly=false;
						viewAllTags();
						document.getElementById('preview-frame').src = "social.php";
						document.getElementById('titleIDinput').style.display = 'block';
						document.getElementById('titleIDdiv').innerHTML='';
						document.getElementById('addrIDinput').style.display = 'block';
						document.getElementById('addrIDdiv').innerHTML='';
						document.getElementById('text_add').style.display = 'block';
						document.getElementById('text_adddiv').innerHTML='';
						document.getElementById('upload').style.display = 'block';
						document.getElementById('btnsaveid').style.display = 'block';
							document.getElementById('sumID').readOnly=false;
                                                        document.getElementById('tab_map2').style.display = 'none';
                                                        document.getElementById('tab_list').style.display = 'none';
                                     			document.getElementById('personal-tab').style.display = 'none';
                                                        document.getElementById('personaladd-tab').style.display = 'block';
							mapimg=0;

					mapimg=1;
					document.getElementById('daysid').innerHTML=name_get52;
					document.getElementById('add_map').style.display = 'block';
                                        map_add.setZoom(14);
                                        map_add.setCenter(new google.maps.LatLng(new_lat, new_lng));
                                     	google.maps.event.trigger(document.getElementById("add_map"),'resize');
					document.getElementById('add_map').style.display = 'none';
					document.getElementById('add_map').style.display = 'block';
                                        map_add.setCenter(new google.maps.LatLng(new_lat, new_lng));
                                     	google.maps.event.trigger(document.getElementById("add_map"),'resize');

							document.getElementById('sumID').value='100';
							if (marker) {        marker.setMap(null);  }
							kfstart=1;
    							new_lat=lat2;
    							new_lng=long2;
							kf=0;
							cur_id='';
							oper_obj='n';
							proc='0';
							cur_sum='0';
							document.getElementById('cursumid').innerHTML='$'+cur_sum+' USD';
							document.getElementById('curprocid').innerHTML=proc+'%';
							document.getElementById('progressid').style.width=proc+'%';
							document.getElementById('smallimg').innerHTML='';
							clearAllTags();
							document.getElementById('bigimg').src='images/blanc_new.png';
							document.getElementById('titleID').value='';
							document.getElementById('addrID').value='';
							$('#text_add').text('');

							//document.getElementById('add_map').style.display = 'none';
                                                        document.getElementById('tab_map2').style.display = 'none';
							mapimg=0;
                                                        $('html, body').animate({scrollTop:0}, 'fast');
						}
                                     }

                  function mapimg_click(){
					if (mapimg==0) {
					mapimg=1;
					document.getElementById('add_map').style.display = 'block';
                                        map_add.setZoom(14);
                                        map_add.setCenter(new google.maps.LatLng(new_lat, new_lng));
                                     	google.maps.event.trigger(document.getElementById("add_map"),'resize');
					document.getElementById('add_map').style.display = 'none';
					document.getElementById('add_map').style.display = 'block';
                                        //map_add.setZoom(12);
                                        map_add.setCenter(new google.maps.LatLng(new_lat, new_lng));
                                     	google.maps.event.trigger(document.getElementById("add_map"),'resize');
					} else {
					document.getElementById('add_map').style.display = 'none';
					mapimg=0;
					}
                                     }

                  function btnsave_click(){
					sent_new();
					mapimg=0;
                                     }

                  function btnmap_click(){
					window.document.title='Lostey';
					if (document.getElementById('selectfid').style.display=='block') {$("#selectfid").fadeTo( "fast", 0, function(){ document.getElementById('selectfid').style.display = 'none'; } );}
                                     cur_win=2;
                                     get_markers0();
                                     document.getElementById('personal-tab').style.display = 'none';
                                     document.getElementById('personaladd-tab').style.display = 'none';
                                     document.getElementById('tab_map2').style.display = 'block';
                                     google.maps.event.trigger(document.getElementById("tab_map2"),'resize');
                                     if (change_city==1) {
                                        map.setZoom(12);
                                        map.setCenter(new google.maps.LatLng(lat2, long2));
                                        change_city=0;
                                     }
                                     }

                  function btnlst_click(){
					window.document.title='Lostey';
					if (document.getElementById('selectfid').style.display=='block') {$("#selectfid").fadeTo( "fast", 0, function(){ document.getElementById('selectfid').style.display = 'none'; } );}
                                     cur_win=1;
				      $("#tab_list").fadeTo( "slow", 1 );
                                     animpixels=(-1)*animpixels;
                                     document.getElementById('personal-tab').style.display = 'none';
                                     document.getElementById('personaladd-tab').style.display = 'none';
                                     document.getElementById('tab_map2').style.display = 'none';
					$('#tab_list')
					.toggleClass('variable-sizes')
					.isotope('reLayout');
                                     $('html, body').animate({scrollTop:target_top}, 'fast');
                                     $('html, body').animate({scrollTop:target_top+animpixels}, 'slow');
				fl_resize=0;
                                     }

                  function btnlost_click(){
					window.document.title='Lostey';
					if (document.getElementById('selectfid').style.display=='block') {$("#selectfid").fadeTo( "fast", 0, function(){ document.getElementById('selectfid').style.display = 'none'; } );}
                                      document.getElementById('tab_list').style.display = 'block';
				      $("#tab_list").fadeTo( "slow", 1 );
                                      cur_win=1;
                                      $('html, body').animate({scrollTop:target_top}, 'fast');
                                      $('html, body').animate({scrollTop:target_top+animpixels}, 'slow');
                                      animpixels=(-1)*animpixels;
                                     document.getElementById('personal-tab').style.display = 'none';
                                     document.getElementById('personaladd-tab').style.display = 'none';
                                     document.getElementById('tab_map2').style.display = 'none';
                                     google.maps.event.trigger(document.getElementById("tab_map2"),'resize');
                                     }
