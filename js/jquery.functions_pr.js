function nl2brback(s) {
	s = s.split("</br>").join("\r");
	//s = s.split("</br>").join("\u000A");
	return s;
}

function nl2br(s) {
	s = s.split("\u000A").join("</br>\u000A");
	return s;
}

function hideAllTags()
{
	document.getElementById('titleIDsum').style.display = 'none';
	for ( var j = 0; j<tags; j++) {
		document.getElementById('s'+tags2[j]).style.display = 'none';
	}
}

function viewAllTags()
{
	//document.getElementById('titleIDsum').style.display = 'block';
	for ( var j = 0; j<tags; j++) {
		document.getElementById('s'+tags2[j]).style.display = 'block';
	}
}

function curTagsView(obj_str)
{
	//alert (obj_str);
	var arrobj=obj_str.split(',');
	if (arrobj.length>=1) {
		for ( var i = 0; i<arrobj.length; i++) {
			if (arrobj[i]=='') { } else {
				//alert(arrobj[i]);
				for ( var j = 0; j<tags; j++) {
					if (tags2[j] == arrobj[i]) {
//alert (tags2[j]);
						document.getElementById('s'+tags2[j]).style.display = 'block';
						document.getElementById('t'+tags2[j]).innerHTML=tags1[j];
						if (tags2[j]==8) {
							document.getElementById('titleIDsum').style.display = 'block';
						}
					}
				}
			}
		}
	}
}

function curTags(obj_str)
{
	//alert (obj_str);
	var arrobj=obj_str.split(',');
	if (arrobj.length>=1) {
		for ( var i = 0; i<arrobj.length; i++) {
			if (arrobj[i]=='') { } else {
				//alert(arrobj[i]);
				for ( var j = 0; j<tags; j++) {
					if (tags2[j] == arrobj[i]) {
						selTag(j);
					}
				}
			}
		}
	}
}

function selTagF(obj_id)
{
	var findval=$('#findid').val();
	//var findval=document.getElementByid('findstrid').innerHTML;
	if (findval!='') {
			findval=findval+', ';
			var arrs = findval.split(', ');
			var i;
			var flag0 = 1;
			findval='';
			for ( var i = 0; i<arrs.length; i++) {
			//for (i in arrs) {
				if (arrs[i]!='') {
				if (tags1[obj_id]==arrs[i]) {
					flag0 = 0;
				} else {
					if (findval!='') { findval=findval+', '; }
					findval=findval+arrs[i];
				}
				}
			}
			if (flag0 == 1) {
				findval=findval+', '+tags1[obj_id];
			} else {
				if (findval==', ') { findval=''; }
			}
	} else {
		findval=tags1[obj_id];
	}
	document.getElementById('findid').value=findval;
	//document.getElementById('findstrid').innerHTML=findval;
}

function selTag(obj_id)
{
   if (my_obj=='1') {
	j=0;
	for ( var i = 0; i<tags; i++) {
		if (tags3[i] == tags3[obj_id]) {
			j = j+1;
		}
	}
	if (j==1) {
		if (tags4[obj_id]==0) {
			tags4[obj_id]=1;
			document.getElementById('t'+tags2[obj_id]).innerHTML='<U>'+tags1[obj_id]+'</U>';
			if (tags2[obj_id]==8) {
				document.getElementById('titleIDsum').style.display = 'block';
			}
		} else { 
			tags4[obj_id]=0; 
			clearTag(tags3[obj_id]);
		}
	} else {
		clearTag(tags3[obj_id]);
		tags4[obj_id]=1;
		document.getElementById('t'+tags2[obj_id]).innerHTML='<U>'+tags1[obj_id]+'</U>';
		if (tags2[obj_id]==8) {
			document.getElementById('titleIDsum').style.display = 'block';
		}
	}
}

function clearTag(obj_id)
{
	for ( var i = 0; i<tags; i++) {
		if (tags3[i] == obj_id) {
			tags4[i]=0;
			document.getElementById('t'+tags2[i]).innerHTML=tags1[i];
			if (tags2[i]==8) {
				document.getElementById('titleIDsum').style.display = 'none';
			}
		}
	}
   }
}

function clearAllTags()
{
	document.getElementById('titleIDsum').style.display = 'none';
	for ( var i = 0; i<tags; i++) {
		tags4[i]=0;
		document.getElementById('t'+tags2[i]).innerHTML=tags1[i];
	}
}

function stringAllTags()
{
	strtg='';
	flagtag=0;
	var flagok;
	var koltag;
	var lasttag;
	for ( var i = 0; i<tags; i++) {
		flagok=0;
		koltag=0;
		lasttag=0;
		for ( var j = 0; j<tags; j++) { if (tags3[j]==tags3[i]) { koltag=koltag+1; if (tags4[j]==1) {flagok=1;} } }
		for ( var j = i; j<tags; j++) { if (tags3[j]==tags3[i]) { if (j>i) {lasttag=lasttag+1;} } }
		if (flagok==0 && koltag>1 && lasttag==0) { flagtag=flagtag+1; }
		if (tags4[i]==1) {
			if (strtg=='') { 
				//strtg=tags2[i].toString(); 
				strtg='(idobj, '+tags2[i].toString()+')';
			} else {
				//strtg=strtg+','+tags2[i].toString();
				strtg=strtg+', (idobj, '+tags2[i].toString()+')';
			}
		}
	}
}