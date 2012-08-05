/*
ARMS/2 by Todd Boyd is licensed under a Creative Commons
Attribution-Noncommercial-No Derivative Works 3.0 United States License.

License URL: http://creativecommons.org/licenses/by-nc-nd/3.0/us/
Work URL: http://www.dennis-sellers.com/arms2/arms2.xpi
*/
var arms2version = '1.3.2';
var prefMgr = Components.classes['@mozilla.org/preferences-service;1']
	.getService(Components.interfaces.nsIPrefBranch);
var hash, udid, tmp, tmpg, tmpr;
var officerfile = "http://www.dennis-sellers.com/dhpdtracker/roster.txt";
var mostwantedfile = "http://www.dennis-sellers.com/dhpdtracker/mwroster.txt";
var mostwanted = "none";
var bluhosturl = "https://secure.bluehost.com/~dennisse/arms/";
var developerurl = "https://secure.bluehost.com/~dennisse/arms/";
var statusstring = "https://secure.bluehost.com/~dennisse/arms/status.php"; 

/* Checking the plugin is being used by a valid DHPD officer, using their DHPD character */
try
{
	hash = prefMgr.getCharPref('extensions.arms2.hashValue');
	udid = prefMgr.getCharPref('extensions.arms2.udId');
}
catch(e)
{
	hash = false;
	udid = false;
}

if(hash && udid)
	window.addEventListener('load', function() { arms2.init(); }, false);
	else
	alert('Your ARMS/2 configuration is not complete. The extension will not '
		+ 'function until both the Hash Value and Character ID have been '
		+ 'entered. Go to Tools -> Add Ons -> ARMS/2 -> Options, enter your '
		+ 'information, and restart Firefox.\n\nRemember: You must restart '
		+ 'Firefox after setting up ARMS/2 before the extension will work '
		+ 'properly!');

/* Actual Plugin Code */
var arms2 =
{
  /* Code for toggling on and off ARMS use in the Urban Dead window */
  toggle: function()
  {
     var prefManager = Components.classes["@mozilla.org/preferences-service;1"]
                                .getService(Components.interfaces.nsIPrefBranch);    
     var value = prefManager.getBoolPref("extensions.arms2.in_use");
     if (value) {
       prefManager.setBoolPref("extensions.arms2.in_use", false);
       alert("disabling ARMS/2");
     } else {
       prefManager.setBoolPref("extensions.arms2.in_use", true);
       alert("enabling ARMS/2");
     }
  },

  /* Code for checking whether or not ARMS is in use in the Urban Dead window */
  check_arms: function() 
   {
     var prefManager = Components.classes["@mozilla.org/preferences-service;1"]
                                .getService(Components.interfaces.nsIPrefBranch);    
     var value = prefManager.getBoolPref("extensions.arms2.in_use");
     if (udid == 1012870) {
       alert('check arms value is ' + value);
     }
     return value;
   },

  /* Code for getting ARMS URL */
  check_dev: function() 
   {
     var prefManager = Components.classes["@mozilla.org/preferences-service;1"]
                                .getService(Components.interfaces.nsIPrefBranch);    
     var value = prefManager.getBoolPref("extensions.arms2.dev");
     return value;
     }, 


  /* Code for switching between ARMS versions */
    devversion: function()
  {
    var prefManager = Components.classes["@mozilla.org/preferences-service;1"]
                               .getService(Components.interfaces.nsIPrefBranch);    
        var value = prefManager.getBoolPref("extensions.arms2.dev");
        if (value) {
         prefManager.setBoolPref("extensions.arms2.dev", false);
	 alert("switching to developer version of ARMS/2");
      } else {
	  prefManager.setBoolPref("extensions.arms2.dev", true);
	  alert("switching to real ARMS/2");
     }
  },

  init: function()
     {
       //enabled = prefMgr.getBoolPref('extensions.arms2.enabled');
       //if(! enabled) return false;
       var appcontent = document.getElementById('appcontent');
       if(appcontent)
	 appcontent.addEventListener('DOMContentLoaded', arms2.onPageLoad,
				     true);
     },

  /* Translating the information from the ARMS database into HTML/CSS code for the browser */
  getDivs: function(json)
     {
       if(!json.cades && !json.genny && !json.zeds && !json.ruin) return;
       var divCades = '';
       var divZeds = '';
       var divGenny = '';
       var divRuin = '';

       var bopacity = '0.3';
       var iopacity = '0.3';
       var gopacity = '0.3';
       var oopacity = '0.3';
       var ropacity = '0.3';
       if(json.age < 4)
	 bopacity = '1.0';
       else if(json.age < 8)
	 bopacity = '0.8';
       else if(json.age < 16)
	 bopacity = '0.6';
       else if(json.age < 24)
	 bopacity = '0.5';
       if(json.indoor_age < 4)
	 iopacity = '1.0';
       else if(json.indoor_age < 8)
	 iopacity = '0.8';
       else if(json.indoor_age < 16)
	 iopacity = '0.6';
       else if(json.indoor_age < 24)
	 iopacity = '0.5';
       if(json.genny_age < 4)
	 gopacity = '1.0';
       else if(json.genny_age < 8)
	 gopacity = '0.8';
       else if(json.genny_age < 16)
	 gopacity = '0.6';
       else if(json.genny_age < 24)
	 gopacity = '0.5';
       if(json.outdoor_age < 4)
	 oopacity = '1.0';
       else if(json.outdoor_age < 8)
	 oopacity = '0.8';
       else if(json.outdoor_age < 16)
	 oopacity = '0.6';
       else if(json.outdoor_age < 24)
	 oopacity = '0.5';
       if(json.ruin_age < 4)
	 ropacity = '1.0';
       else if(json.ruin_age < 8)
	 ropacity = '0.8';
       else if(json.ruin_age < 16)
	 ropacity = '0.6';
       else if(json.ruin_age < 24)
	 ropacity = '0.5';
       
       if(json.cades != '' && json.cades != null && json.cades != 'z')
	 {
	   divCades =
	     '<div style="font-weight:bold;display:inline;margin:1px;padding:0px 2px 0px 2px;border:1px solid white;background-color:black;color:';
	   
	   switch(json.cades)
	     {
	     case 'LoB':
	     case 'LiB':
	       divCades += 'orange';
	       break;
	     case 'QSB':
	     case 'VSB':
	       divCades += 'yellow';
	       break;
	     case 'HeB':
	     case 'HB':
	     case 'VHB':
	     case 'EHB':
	       divCades += 'green';
	       break;
	     case 'opn':
	     case 'cls':
	     default:
	       divCades += 'red';
	       break;
	     }
	   
	   divCades += ';opacity: ' + bopacity + '">' + json.cades + '</div>';
	 }

       if(json.ruin != '' && json.ruin != null && json.ruin != 'z')
	 {
	   divRuin =
	     '<div style="font-weight:bold;display:inline;margin:1px;padding:0px 2px 0px 2px;border:1px solid white;background-color:black;color:';
	   
	   divRuin += 'red';
	   
	   divRuin += ';opacity: ' + ropacity + '">' + json.ruin + '</div>';
	 }
       
       if(json.genny != '' && json.genny != null)
	 {
	   divGenny =
	   '<div style="font-weight:bold;display:inline;margin:1px;padding:0px 2px 0px 2px;border:1px solid black;background-color:white;color:';
	   
	   switch(json.genny)
	     {
	     case 'out':
	       divGenny += 'red';
	       break;
	     case 'low':
	       divGenny += 'orange';
	       break;
	     case 'lit':
	       divGenny += 'green';
	       break;
	     }
	   
	   divGenny += ';opacity: ' + gopacity + ';">' + json.genny + '</div>';
	 }
       
       if((!(json.zeds_out == null)) || (!(json.zeds_in == null)))
	 {
	   divZeds =
	   '<div style="font-weight:bold;display:inline;margin:1px;padding:0px 2px 0px 2px;border:1px solid white;background-color:green;color:black;opacity:';
	   
	   if(!(json.zeds_out == null)) {
	     divZeds += oopacity + ';">';
	     divZeds += 'O:' + json.zeds_out;
	   }
	   if(!(json.zeds_out == null) && !(json.zeds_in == null)) {
	     divZeds += '</div> <div style="font-weight:bold;display:inline;margin:1px;padding:0px 2px 0px 2px;border:1px solid white;background-color:green;color:black;opacity:';
	   }
	   if(!(json.zeds_in == null)) {
	     divZeds += iopacity + ';">';
	     divZeds += 'I:' + json.zeds_in;
	   }
	   
	   divZeds += '</div>';
	 }

       return '<br/><div class="arms2" style="color:black;'
       + 'text-align:center;" '
       + 'title="Age: ' + json.age + 'h">' + divCades + divRuin 
       + divGenny + divZeds + '</div>';
     },

    nameColouring: function(doc, namestring, color, key, startnum) {
	var namearray = namestring.split("\n");
	var names = new Array(namearray.length); 
        var present = new Array(namearray.length); 
        var present_names = 0;

	var regex_string = "(.*)\\," + key + "\\,\\d+";
	var regexp = new RegExp(regex_string, i);
	var found_names =  0;
	for (var i=startnum; i<(namearray.length - 1); i++) {
	    var namestring = namearray[i];
	    var name_test = namestring.match(regexp);
	    if (name_test) {
		var name = name_test[1];
		names[found_names] = name.replace(" ", "&nbsp;", 'g');
		found_names++;
	    }
	}  

	for (var i=0; i<found_names; i++) {
	    var name = names[i];
	    var name_as_re = new RegExp(name,'g');
	    var check_name_as_is = doc.body.innerHTML.match(name_as_re);
	    if (check_name_as_is) {
		var udtoolbarstring = "(<a href=\"profile\\.cgi\\?id=(\\d+)\" class=\"con(\\d+)\">" + name + "</a>)";
		var udt_as_re = new RegExp(udtoolbarstring, 'g');
		var check_udt = doc.body.innerHTML.match(udt_as_re);
		if (!check_udt) {
		    doc.body.innerHTML = doc.body.innerHTML.replace(name_as_re, '<font color="' + color + '">' + name + '</font>');
                    present[present_names] = name.replace("&nbsp;", " ", 'g');
                    present_names++;
		}
	    } else {
		var name_w_spaces = name.replace(" ", "&nbsp;", 'g');
		var space_name_re = new RegExp(name_w_spaces,'g');
		var check_name_w_spaces = doc.body.innerHTML.match(space_name_re);
		if (check_name_w_spaces) {
		    var udtoolbarstring = "(<a href=\"profile\\.cgi\\?id=(\\d+)\" class=\"con(\\d+)\">" + name_w_spaces + "</a>)";
		    var udt_as_re = new RegExp(udtoolbarstring, 'g');
		    var check_udt = doc.body.innerHTML.match(udt_as_re);
		    if (!check_udt) {
			doc.body.innerHTML = doc.body.innerHTML.replace(space_name_re, '<font color="' + color + '">' + name_w_spaces + '</font>');
			present[present_names] = name_w_spaces.replace(" ","-",'g');
			present_names++;
		    }
		}
	    }
	} 
        return present.slice(0,present_names);
    },

  onPageLoad: function(aEvent)
     {
       var doc = aEvent.originalTarget;
	 
       // aypok or dssrzs map
       if(/http:\/\/(map(\.)?dssrzs\.org\/.+|map\.aypok\.co\.uk\/(index\.php)?\?.+)/i.test(doc.location.href))
	 {
	   var maploc = (doc.location.href.indexOf('suburb') > -1 ? 0 : 1);
	   // make sure it's on the "map" page
	   var tablecheck;
	   if(maploc == 0)
	     tablecheck = /<table/i;
	   else
	     tablecheck = /<table class="map"/i;
	   if(! tablecheck.test(doc.body.innerHTML)) return;
	   var tds = doc.getElementsByTagName('table')
	     [doc.getElementsByTagName('table').length - 1]
	     .getElementsByTagName('td');
	   var req = new XMLHttpRequest();
	   var top, left, bottom, right;
	   var firstTd = true;
	   var cellcheck;
	   if(maploc == 0)
	     cellcheck = /(\d+), (\d+)/i;
	   else			
	     cellcheck = /<div class="p">(\d+),(\d+)/i;
	   
	   for(cnt = 0, len = tds.length; cnt < len; cnt++)
	     {
	       var td = tds[cnt];
	       var cell = cellcheck.exec(td.innerHTML);
	       
	       if(cell)
		 {
		   if(firstTd)
		     {
		       firstTd = false;
		       top = cell[2];
		       left = cell[1];
		     }
		   else
		     {
		       bottom = cell[2];
		       right = cell[1];
		     }
		 }
	     }
			
	   var params = 'a2v=' + arms2version + '&id=' + udid + '&hash=' + hash
	     + '&t=' + top + '&l=' + left + '&b=' + bottom + '&r=' + right;
	   
	     if (dev) {
	       statusstring = bluhosturl + 'status.php';
	     } else {
	       statusstring = developerurl + 'status.php';
	     } 
	   req.open('POST',
		    statusstring, true);
	   req.setRequestHeader('Content-type',
				'application/x-www-form-urlencoded');
	   req.setRequestHeader('Content-length', params.length);
	   req.setRequestHeader('Connection', 'close');
	   
	   req.onreadystatechange = function()
	     {
	       if(req.readyState != 4) return;
	       
	       if(req.responseText != '' && req.status == 200)
		 {
		   var json;
		   
		   try {
		     eval('json = ' + req.responseText);
		   } catch(e) {
		     alert('ARMS/2 Error [Status]: Eval - ' + e.message);
		     return;
		   }
		   
		   var repcheck;
		   if(maploc == 0) 
		     repcheck = /<td(.+\n.+\n<br>\n\[(\d+), (\d+)\]\n<\/td>)/g;
		   else
		     repcheck = /<td id="x\d+y\d+"(.+?(\d+),(\d+))/g;
		   doc.body.innerHTML = doc.body.innerHTML.replace(
								   repcheck,
								   '<td id="arms2_cell_$2_$3"$1');
		   for(a = 0; json.length && a < json.length; a++)
		     doc.getElementById('arms2_cell_' + json[a].x + '_'
					+ json[a].y).innerHTML = arms2.getDivs(json[a])
		       + doc.getElementById('arms2_cell_' + json[a].x
					    + '_' + json[a].y).innerHTML;
		 }
	     }
			
	   req.send(params);
	 }

       // urban dead
       else if(
	       /^http:\/\/(www\.)?urbandead\.com\/map\.cgi(?!\?logout)/i.test(doc.location.href))
	 {
	   var ca = arms2.check_arms();
	   if (ca) {
	     var user = doc.body.innerHTML.match(
						 /<a href="profile\.cgi\?id=(\d+)"><b>/i)[1];

	     if(user != udid) return;

	       // Officer Tracker Parsing
	       var officerreq = new XMLHttpRequest();
	       officerreq.open("GET", officerfile, false);
	       officerreq.send(null);
	       var officerstring = officerreq.responseText;
	       arms2.nameColouring(doc, officerstring, 191970, 'DHPD', 2);

	       // MostWanted Parsing
	       var mwreq = new XMLHttpRequest();
	       mwreq.open("GET", mostwantedfile, false);
	       mwreq.send(null);
	       var mwstring = mwreq.responseText;

	       var mostwanted_perm = arms2.nameColouring(doc, mwstring, 'ff0000', 'PERM', 10);
	       var most_wanted_murder = arms2.nameColouring(doc, mwstring, 'ff0000', 'MURDER', 10);
	       var most_wanted_prop = arms2.nameColouring(doc, mwstring, 'ffa500', 'PROP', 10);
	       var most_wanted_imp = arms2.nameColouring(doc, mwstring, 'ffff00', 'IMP', 10);
               var mostwanted_array = mostwanted_perm.concat(most_wanted_murder, most_wanted_prop, most_wanted_imp);
               mostwanted = mostwanted_array.join();

	       if (mostwanted.length == 0) {
		   mostwanted = "none";
	       } else {
		   alert(mostwanted.length);
	       }


	     var gt = doc.body.innerHTML.match(
					       /<td class="gp"(.|\n)+?<\/div>/)[0];
	     var mrec = gt.match(
				 /(?:The laboratories|The building(?! has been decorated)|A hole|The doors|Through).+?\./g
				);
	     if(mrec) mrec = mrec[0];
	     var indoors = /You are inside </i.test(gt);
	     var ruinout = /into ruin|been ruined/i.test(gt);

	     if(gt)
	       {
		 var cades = '';
		 var ruins = '';
		 var cx = 0;
		 var cy = 0;
		 var genny = gt.match(/A portable.*?(?=<)/);
		 // grab zeds on current cell
		 var zeds =
		   /<td[^>]+><input[^>]+>.*?<span class="fz">(\d+)\D/i.exec(
									    doc.getElementsByTagName('table')[1].innerHTML);
		 if(zeds)
		   zeds = zeds[1];
		 else
		   zeds = '';
		 
		 // grab coordinates
		 var cmatch =
		   /<input name="v" value="(\d+)-(\d+)".+?<td.+?><input class="m\w+"/i
		   .exec(doc.body.innerHTML);
		 var offset = 1;
		 
		 if(!cmatch)
		   {
		     cmatch =
		       /<td.+?><input class="m\w+"(?:.|\n)+?<input name="v" value="(\d+)-(\d+)"/i
		       .exec(doc.body.innerHTML);
		     offset = -1;
		   }
		 
		 cx = parseInt(cmatch[1]) + offset;
		 cy = parseInt(cmatch[2]);
		 
		 // grab zeds in surrounding cells
		 var szeds = '';
		 tmp =
		   doc.getElementsByTagName('table')[1].innerHTML.match(
									/<input[^>]+?value="(\d+)-(\d+)"[^>]+?>((.|\n)(?!<\/td))+?<span class="fz">(\d+)\D/ig
									);
		 
		 if(tmp)
		   {
		 if (cx < 1) {
		   cxmin = 0;
		     } else {
		   cxmin = cx - 1;
		     }

		 if (cy < 1) {
		   cymin = 0;
		     } else {
		   cymin = cy - 1;
		     }

		 if (cx > 98) {
		   cxmax = 100;
		 } else {
		   cxmax = cx + 2;
		 }
		 if (cy > 98) {
		   cyman = 100;
		 } else {
		   cymax = cy + 2;
		 }
		     var tmpZeds = Array();
		     for(a = cxmin; a < cxmax; a++)
		       for(b = cymin; b < cymax; b++)
			 {
			   if(a != cx || b != cy)
			     tmpZeds[a + ',' + b] = 0;
			 }
		     
		     for(a = 0; a < tmp.length; a++)
		       {
			 var coords = /value="(\d+)-(\d+)"/i.exec(tmp[a]);
			 var nzeds = /"fz">(\d+)/i.exec(tmp[a]);
			 //szeds += coords[1] + ',' + coords[2] + '-' + nzeds[1] + '/';
			 tmpZeds[coords[1] + ',' + coords[2]] = nzeds[1];
		       }
		     
		     for(key in tmpZeds)
		       szeds += key + '-' + tmpZeds[key] + '/';
		   }
		 
		 // determine genny status in surrounding squares
		 var sgennys = '';
		 tmpg =
		   doc.getElementsByTagName('table')[1].innerHTML.match(
									/<input[^>]+?value="(\d+)-(\d+)"[^>]+?>((.|\n)(?!<\/td))+?class=("m..?")/ig
		 									);

		 if (cx < 1) {
		   cxmin = 0;
		     } else {
		   cxmin = cx - 1;
		     }

		 if (cy < 1) {
		   cymin = 0;
		     } else {
		   cymin = cy - 1;
		     }

		 if (cx > 98) {
		   cxmax = 100;
		 } else {
		   cxmax = cx + 2;
		 }
		 if (cy > 98) {
		   cyman = 100;
		 } else {
		   cymax = cy + 2;
		 }
		 var tmpGennys = Array();
		 for(a = cxmin; a < cxmax; a++)
		   for(b = cymin; b < cymax; b++)
		     {
		       if(a != cx || b != cy)
			 tmpGennys[a + ',' + b] = 0;
		     }
	     	 
		 for(a = 0; a < tmpg.length; a++)
		   {
		     var coords = /value="(\d+)-(\d+)"/i.exec(tmpg[a]);
		     var gennylet = /class="mr?l"/i.exec(tmpg[a]);
		     if (gennylet) {
		       tmpGennys[coords[1] + ',' + coords[2]] = 'lit';
		     } else {
		       tmpGennys[coords[1] + ',' + coords[2]] = '';
		     }
		   }
		 
		 for(key in tmpGennys) {
		   sgennys += key + '-' + tmpGennys[key] + '/';
		 }
	       
		 // Genny status of this square
		 if(genny)
		   if(/low on fuel/.test(genny))
		     genny = 'low';
		   else if(/out of fuel/.test(genny))
		     genny = 'out';
		   else
		     genny = 'lit';
		 else
		   genny = '';

		 
		 // ruin status in surrounding squares
		 var sruins = '';
		 tmpr =
		   doc.getElementsByTagName('table')[1].innerHTML.match(
									/<input[^>]+?value="(\d+)-(\d+)"[^>]+?>((.|\n)(?!<\/td))+?class=("m..?")/ig
		 									);

		 if (cx < 1) {
		   cxmin = 0;
		     } else {
		   cxmin = cx - 1;
		     }

		 if (cy < 1) {
		   cymin = 0;
		     } else {
		   cymin = cy - 1;
		     }

		 if (cx > 98) {
		   cxmax = 100;
		 } else {
		   cxmax = cx + 2;
		 }
		 if (cy > 98) {
		   cyman = 100;
		 } else {
		   cymax = cy + 2;
		 }
		 var tmpRuins = Array();
		 for(a = cxmin; a < cxmax; a++)
		   for(b = cymax; b < cymax; b++)
		     {
		       if(a != cx || b != cy)
			 tmpRuins[a + ',' + b] = 0;
		     }
	     	 
		 for(a = 0; a < tmpr.length; a++)
		   {
		     var coords = /value="(\d+)-(\d+)"/i.exec(tmpr[a]);
		     var ruinlet = /class="mrl?"/i.exec(tmpr[a]);
		     if (ruinlet) {
		       tmpRuins[coords[1] + ',' + coords[2]] = 'R:??';
		     } else {
		       tmpRuins[coords[1] + ',' + coords[2]] = '';
		     }
		   }
		 
		 for(key in tmpRuins) {
		   sruins += key + '-' + tmpRuins[key] + '/';
		 }

		 // Ruin status in this square
		 if(mrec)
		   {
		     // grab AP required to repair
		     ruinAP =
		       /<input[^>]+?value="Repair building damage"[^>]+?>(?:\s*\((\d+)AP\))?/i
		       .exec(doc.body.innerHTML);
		     
		     if(ruinAP && typeof ruinAP[1] !== 'undefined') 
		       {
			 ruins = 'R:';
			 ruins += ruinAP[1];
		       } 
		     else if(ruinAP)
		       {
			 ruins = 'R:';
			 if(/lights out/.test(mrec))
			   ruins += '??'
			   else
			     ruins += '1';
		       }
		     else if(ruinout) {
		       ruins = 'R:??';
		     }
		     
		     if(/closed|secured/.test(mrec))
		       cades = 'cls';
		     else if(/extremely heavily barricaded/i.test(mrec))
		       cades = 'EHB';
		     else if(/very heavily barricaded/i.test(mrec))
		       cades = 'VHB';
		     else if(/heavily barricaded/i.test(mrec))
		       cades = 'HeB';
		     else if(/very strongly barricaded/i.test(mrec))
		       cades = 'VSB';
		     else if(/quite strongly barricaded/i.test(mrec))
		       cades = 'QSB';
		     else if(/lightly barricaded/i.test(mrec))
		       cades = 'LiB';
		     else if(/loosely barricaded/i.test(mrec))
		       cades = 'LoB';
		     else
		       cades = 'opn';

		     //		     if (ruins != 'R:') {
		     // cades = ruins;
		     // }
		   }

		 //Collate data on surrounding squares
		 
		 // submit findings
/*
				doc.body.innerHTML += '<br /><p id="arms2_sub" class="sb" '
					+ 'style="padding:4px;font-size:8pt;display:inline;">'
					+ 'ARMS/2: Scanned</p>';
*/

                // get external data on blocks 
                //
                // @return array of block objects
                // @param trows node collection object representing the tbody children = the table rows
                // @param position position object of the top left block, only for binoc table
		 // NB:  Currently neither returned nore used.
		 function getBlocksExternal() {
                    var trows = arguments[0],
                        blocks = {},
                        needs_position = true,
                        position;
                    if( arguments.length === 2 ) {
                        position = [ Number(arguments[1][0]), Number(arguments[1][1]) ];
                        needs_position = false;
                    }
                    for( var i = 0; i < trows.length; i++ ) {
                        for( var j = 0; j < trows[i].children.length; j++ ) {
                            var my_cell = trows[i].children[j],
                                my_status = "" , my_zedcount = "";
                            // break on the suburb header
                            if( my_cell.className === "sb" || ! my_cell.firstElementChild )
                                break;
                            if( /\d+(\s+|&nbsp;)zombies?</.test( my_cell.innerHTML ) )
                                my_zedcount = Number(
                                        my_cell.innerHTML
                                            .match( /\d+(\s+|&nbsp;)zombies?</ )[0].replace( /\D+/, "" )
                                        );
                            while( my_cell.firstElementChild.tagName != "INPUT" )
                                my_cell = my_cell.firstElementChild;
                            for( var k = 0; k < my_cell.children.length; k++ ) {
                                var my_elem = my_cell.children[k];
                                if( needs_position )
                                    if( my_elem.tagName === "INPUT" && my_elem.value.match( /^\d+-\d+$/ ) ) {
                                        position = my_elem.value.split( /-/ );
                                        needs_position = false;
                                    }
                                switch( my_elem.className ) {
                                    case "mr":
                                        my_status = "ruined";
                                        break;
                                    case "md":
                                        my_status = "intact";
                                        break;
                                    case "ml":
                                        my_status = "lit";
                                        break;
                                    default:
                                        break;
                                }
                            }
                            blocks[position.join("-")] = {
                                position: [ Number(position[0]), Number(position[1]) ],
                                status: my_status,
                                zeds: my_zedcount
                            };
                            position[0]++;
                        }
                        if( ! needs_position ) {
                            position[0] -= 3;
                            position[1]++;
                        }
                    }
                    return blocks;
                }
		

                // @returns array of block objects for binoc table
                // @param binoc_table node object representiong the binoc table
                // @param blocks blocks table so far
                function getBinocTable( binoc_table ) {
                    var binoc_direction = binoc_table
                        .previousSibling.textContent
                        .replace( /You look out over the blocks to the ((south|north)*-?(west|east)*) of the building\./,
                                '$1' ),
                        topleft_diff_for = {
                            // the distance from our top left to the main top left
                            // depends on where we're looking
                            "north-west": [-2,-2], "north": [0,-2], "north-east": [2,-2],
                            "west": [-2,0],                 "east": [2,0],
                            "south-west": [-2,2], "south": [0,2], "south-east": [2,2]
                        },
                        position = [0,0],
                        topleft_block,
                        binoc_blocks;

                    // stupid hacky way to get the top element from blocks
                    for( topleft_block in blocks ) {
                        topleft_block = blocks[topleft_block];
                        break;
                    }
                    for( var i in position ) {
                        position[i] = topleft_block.position[i] + topleft_diff_for[binoc_direction][i];
                        position[i] = ( position[i] >= 0 ) ? position[i] : 0;
                        position[i] = ( position[i] <= 99 ) ? position[i] : 99;
                    }

                    binoc_table = binoc_table.firstElementChild.children;

                    binoc_blocks = getBlocksExternal( binoc_table, position );
                    for( var i in binoc_blocks )
                        blocks[i] = binoc_blocks[i];
                    return blocks;
                }

                 // look for game data from tables:
                var tables = document.getElementsByTagName( "table" ),
                    blocks = [];
                for( var i = 0; i < tables.length; i++ ) {
                    // get main navigation table
                    if( tables[i].className === "c" )
                        blocks = getBlocksExternal( tables[i].firstElementChild.children );
                    // get binocular table, if it exists
                    if( tables[i].className === "c bn" )
                        blocks = getBinocTable( tables[i], blocks );
                }

		 var subreq = new XMLHttpRequest();
		 var params = 'a2v=' + arms2version + '&id=' + udid + '&hash='
		   + hash + '&cades=' + escape(cades) + '&genny=' + genny 
		   + '&ruins=' + ruins
		   + '&cx=' + cx + '&cy=' + cy + '&zeds=' + zeds + '&szeds='
		   + szeds + '&mw=' + mostwanted + '&sgennys=' + sgennys + '&sruins=' + sruins;
		 mostwanted = "none";
		 if(indoors) params += '&indoors=1';
		 var submitstring;
		 if (dev) {
		   submitstring = bluhosturl + 'submit.php';
		 } else {
		   submitstring = developerurl + 'submit.php';
		 } 

		 subreq.open('POST',
			     submitstring, true);
		 subreq.setRequestHeader('Content-type',
					 'application/x-www-form-urlencoded');
		 subreq.setRequestHeader('Content-length', params.length);
		 subreq.setRequestHeader('Connection', 'close');
		 
		 subreq.onreadystatechange = function()
		   {
		     if(subreq.readyState != 4) return;
		     
		     if(subreq.status == 200 && subreq.responseText == '')
		       {
			 /*
			   doc.getElementById('arms2_sub').innerHTML
			   = 'ARMS/2: Sent';
			 */
		       }
		     else if(subreq.status != 200)
		       {
			 //						alert('ARMS/2 Error [Send]: HTTP Status - '
			 //							+ subreq.status);
			 return;
		       }
		     else if(subreq.responseText != '')
		       {
			 alert('ARMS/2 Error [Send]: ' + subreq.responseText);
			 return;
		       }
		   }
		 
		 subreq.send(params);
	       }
	     else
	       alert('ARMS/2 Error: Could not pull gt');
	     
	     // display statuses on other buildings
	     var tds = doc.getElementsByTagName('table')[0]
	       .getElementsByTagName('td');
	     var top, left, bottom, right;
	     var firstTd = true;
	     
	     // determine t,l,b,r coordinates
	     for(cnt = 0; cnt < tds.length; cnt++)
	       {
		 var td = tds[cnt];
		 var cell = /<input name="v" value="(\d+)-(\d+)"/i
		   .exec(td.innerHTML);
		 
		 if(cell)
		   {
		     if(firstTd)
		       {
			 firstTd = false;
			 top = cell[2];
			 left = cell[1];
		       }
		     else
		       {
			 bottom = cell[2];
			 right = cell[1];
		       }
		   }
	       }
	     
	     
	     var statreq = new XMLHttpRequest();
	     var params = 'a2v=' + arms2version + '&id=' + udid + '&hash=' + hash
	       + '&t=' + top + '&l=' + left + '&b=' + bottom + '&r=' + right;
	     if(! indoors) params += '&outdoors=1';
	     var dev = arms2.check_dev();
	     if (dev) {
	       statusstring = bluhosturl + 'status.php';
	     } else {
	       statusstring = developerurl + 'status.php';
	     } 
	     statreq.open('POST',
			  statusstring, true);
	     statreq.setRequestHeader('Content-type',
				      'application/x-www-form-urlencoded');
	     statreq.setRequestHeader('Content-length', params.length);
	     statreq.setRequestHeader('Connection', 'close');
	     
	     statreq.onreadystatechange = function()
	       {
		 if(statreq.readyState != 4) return;
		 
		 if(statreq.status == 200 && statreq.responseText != '')
		   {
		     var json;
		     
		     try {
		       eval('json = ' + statreq.responseText);
		     } catch(e) {
		       alert('ARMS/2 Error [Status]: Eval - ' + e.message
			     + '\n\n' + statreq.responseText);
		       return;
		     }
		     
		     
		     doc.body.innerHTML = 
		       doc.body.innerHTML.replace(
						  /(<td class="b(?:.|\n)+?<input name="v" value="(\d+)-(\d+)"(?:.|\n)+?>)/ig,
		      '$1<div id="arms2_cell_$2_$3"></div>');

		     for(a = 0; json.length && a < json.length; a++)
		       {
			 try
			   {
			     td = doc.getElementById('arms2_cell_' + json[a].x
						     + '_' + json[a].y)
			       td.style.fontSize = '8pt';
			     td.innerHTML = arms2.getDivs(json[a]);
			   }
			 catch(e) { }
		       }
		   }
		 else if(statreq.status != 200)
		   {
		     //					alert('ARMS/2 Error [Status]: HTTP Status - '
		     //						+ statreq.status);
		     return;
		   }
		 else if(statreq.responseText != '')
		   {
		     alert('ARMS/2 Error [Status]: ' + statreq.responseText);
		     return;
		   }
	       }
	     
	     statreq.send(params);
	   }
	 }
       return;
     }
}
