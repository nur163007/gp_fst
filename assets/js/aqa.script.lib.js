// jS library
// Convert numbers to words
// copyright 25th July 2006, by Stephen Chapman http://javascript.about.com
// permission to use this Javascript on your web page is granted
// provided that all of the code (including this copyright notice) is
// used exactly as shown (you can change the numbering system if you wish)

// American Numbering System
var th = ['','thousand','million', 'billion','trillion'];
// uncomment this line for English Number System
// var th = ['','thousand','million', 'milliard','billion'];
var dg = ['zero','one','two','three','four', 'five','six','seven','eight','nine']; 
var tn = ['ten','eleven','twelve','thirteen', 'fourteen','fifteen','sixteen', 'seventeen','eighteen','nineteen']; 
var tw = ['twenty','thirty','forty','fifty', 'sixty','seventy','eighty','ninety'];

function dollarToWords(s) {
    s = s.toString();
    s = s.replace(/[\, ]/g, '');
    if (s != parseFloat(s)) return 'not a number';
    var x = s.indexOf('.');
    var a = '', b = '', t = '';
    if (x > 0) {
        var n = s.split('.');
        a = toWords(n[0]);
        b = toWords(n[1]);
        //alert(b);
        if (b != "") {
            t = a + ' and ' + ' cents ' + b;
        } else {
            t = a;
        }
    } else {
        a = toWords(s);
        t = a;
    }

    return t.replace(/\s+/g, ' ');
}
function takaToWords(s) {
    s = s.toString();
    s = s.replace(/[\, ]/g, '');
    if (s != parseFloat(s)) return 'not a number';
    var x = s.indexOf('.');
    var a = '', b = '', t = '';
    if (x > 0) {
        var n = s.split('.');
        a = toWords(n[0]);
        b = toWords(n[1]);
        //alert(b);
        if (b != "") {
            t = a + ' and ' + ' paisa ' + b;
        } else {
            t = a;
        }
    } else {
        a = toWords(s);
        t = a;
    }

    return t.replace(/\s+/g, ' ');
}
function toWords(s){s = s.toString(); s = s.replace(/[\, ]/g,''); if (s != parseFloat(s)) return 'not a number'; var x = s.indexOf('.'); if (x == -1) x = s.length; if (x > 15) return 'too big'; var n = s.split(''); var str = ''; var sk = 0; for (var i=0; i < x; i++) {if ((x-i)%3==2) {if (n[i] == '1') {str += tn[Number(n[i+1])] + ' '; i++; sk=1;} else if (n[i]!=0) {str += tw[n[i]-2] + ' ';sk=1;}} else if (n[i]!=0) {str += dg[n[i]] +' '; if ((x-i)%3==0) str += 'hundred ';sk=1;} if ((x-i)%3==1) {if (sk) str += th[(x-i-1)/3] + ' ';sk=0;}} if (x != s.length) {var y = s.length; str += 'point '; for (var i=x+1; i<y; i++) str += dg[n[i]] +' ';} return str.replace(/\s+/g,' ');}

function htmlspecialchars_decode(string, quote_style) {
	//       discuss at: http://phpjs.org/functions/htmlspecialchars_decode/
	//      original by: Mirek Slugen
	//      improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
	//      bugfixed by: Mateusz "loonquawl" Zalega
	//      bugfixed by: Onno Marsman
	//      bugfixed by: Brett Zamir (http://brett-zamir.me)
	//      bugfixed by: Brett Zamir (http://brett-zamir.me)
	//         input by: ReverseSyntax
	//         input by: Slawomir Kaniecki
	//         input by: Scott Cariss
	//         input by: Francois
	//         input by: Ratheous
	//         input by: Mailfaker (http://www.weedem.fr/)
	//       revised by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
	// reimplemented by: Brett Zamir (http://brett-zamir.me)
	//        example 1: htmlspecialchars_decode("<p>this -&gt; &quot;</p>", 'ENT_NOQUOTES');
	//        returns 1: '<p>this -> &quot;</p>'
	//        example 2: htmlspecialchars_decode("&amp;quot;");
	//        returns 2: '&quot;'

	var optTemp = 0,
	i = 0,
	noquotes = false;
	if (typeof quote_style === 'undefined') {
		quote_style = 2;
	}
    if (string != null) {
        string = string.toString().replace(/&lt;/g, "<").replace(/&gt;/g, ">");
    }
	var OPTS = {
		'ENT_NOQUOTES': 0,
		'ENT_HTML_QUOTE_SINGLE': 1,
		'ENT_HTML_QUOTE_DOUBLE': 2,
		'ENT_COMPAT': 2,
		'ENT_QUOTES': 3,
		'ENT_IGNORE': 4
	};
	if (quote_style === 0) {
		noquotes = true;
	}
	if (typeof quote_style !== 'number') { // Allow for a single string or an array of string flags
		quote_style = [].concat(quote_style);
		for (i = 0; i < quote_style.length; i++) {
			// Resolve string input to bitwise e.g. 'PATHINFO_EXTENSION' becomes 4
			if (OPTS[quote_style[i]] === 0) {
				noquotes = true;
			} else if (OPTS[quote_style[i]]) {
				optTemp = optTemp | OPTS[quote_style[i]];
			}
		}
		quote_style = optTemp;
	}
	if (quote_style & OPTS.ENT_HTML_QUOTE_SINGLE) {
		string = string.replace(/&#0*39;/g, "'"); // PHP doesn't currently escape if more than one 0, but it should
		// string = string.replace(/&apos;|&#x0*27;/g, "'"); // This would also be useful here, but not a part of PHP
	}
	if (!noquotes) {
        if (string != null) {
            string = string.replace(/&quot;/g, '"');
        }
	}
    // Put this in last place to avoid escape being double-decoded
    if (string != null) {
        // Put this in last place to avoid escape being double-decoded
        string = string.replace(/&amp;/g, '&');
        //Replace multiple amp
        string = string.replace(/amp /g, '');
        //Replace multiple space with single space
        string = string.replace(/\s\s+/g, ' ');

        string = string.replace(/&amp;/g, '&');
        string = string.replace(/[␤␍␊↵⏎]+/g, '\n');
        string = string.replace(/\n/g, '<br>');
    }

	return string;
}

//function NotifyMessage(msg, color)
//{
//	 // set the message to display: none to fade it in later.
//    var message = $('<div class="alert alert-'+color+' alert-dismissable errormessage" style="display: none;">');
//    // a close button
//    var close = $('<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>');
//    message.append(close); // adding the close button to the message
//    message.append(msg); // adding the error response to the message
//    // add the message element to the body, fadein, wait 3secs, fadeout
//    message.appendTo($('body')).fadeIn(300).delay(3000).fadeOut(500);
//}

// Add images.............
$(document).on('change', '.btn-file :file', function() {
  var input = $(this),
      numFiles = input.get(0).files ? input.get(0).files.length : 1,
      label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
  input.trigger('fileselect', [numFiles, label]);
});

$(document).ready( function() {
    $('.btn-file :file').on('fileselect', function(event, numFiles, label) {
        
        var input = $(this).parents('.input-group').find(':text'),
            log = numFiles > 1 ? numFiles + ' files selected' : label;
        
        if( input.length ) {
            input.val(log);
        } else {
            if( log )
				alert(log);
        }
        
    });
});

//-----formated datetime for upload file names----------------------------

function twoDigitNumZeroLeadFormat(nam)
{
	if (nam < 10) {
        nam = "0" + nam;
    }
	return nam;
}
function Date_toMDY(d)
{
    var year, month, day;
    year = String(d.getFullYear());
    month = String(d.getMonth() + 1);
    if (month.length == 1) {
        month = "0" + month;
    }
    day = String(d.getDate());
    if (day.length == 1) {
        day = "0" + day;
    }
    return month + "/" + day + "/" + year;
}

function Date_toMDY_HMS(d)
{
    var year, month, day, hour, min, sec;
    year = String(d.getFullYear());
    month = String(d.getMonth() + 1);
    hour = String(d.getHours());
    min = String(d.getMinutes());
    sec = String(d.getSeconds());
    
    if (month.length == 1) {
        month = "0" + month;
    }
    day = String(d.getDate());
    if (day.length == 1) {
        day = "0" + day;
    }
    return month + "/" + day + "/" + year + " " + hour + ":" + min + ":" + sec;
}

function Date_toDMY(d, sep)
{
    sep = sep || "/";
    var year, month, day;
    year = String(d.getFullYear());
    month = String(d.getMonth() + 1);
    if (month.length == 1) {
        month = "0" + month;
    }
    day = String(d.getDate());
    if (day.length == 1) {
        day = "0" + day;
    }
    return day + sep + month + sep + year;
}

function Date_toDetailFormat(d)
{
    var year, month, day;
    year = String(d.getFullYear());
    month = d.getMonth();
    //alert(month);

    var mName = new Array();
    mName[0] = "January";
    mName[1] = "February";
    mName[2] = "March";
    mName[3] = "April";
    mName[4] = "May";
    mName[5] = "June";
    mName[6] = "July";
    mName[7] = "August";
    mName[8] = "September";
    mName[9] = "October";
    mName[10] = "November";
    mName[11] = "December";
    
    day = String(d.getDate());
    if (day.length == 1) {
        day = "0" + day;
    }

    return mName[month] + " " + day + ", " + year;
}

function Date_toMDY_HMS_detail(d)
{
    var year, month, day, hour, min, sec;
    year = String(d.getFullYear());
    month = String(d.getMonth() + 1);
    hour = String(d.getHours());
    min = String(d.getMinutes());
    sec = String(d.getSeconds());

    if (month.length == 1) {
        month = "0" + month;
    }
    day = String(d.getDate());
    if (day.length == 1) {
        day = "0" + day;
    }
    return Date_toDetailFormat(d) + " " + hour + ":" + min + ":" + sec;
}

function zeroPad(n) {
    if((n < 10))
        z = "00" + n;
    else if(n > 9 && n < 100)
        z = "0" + n;
    else
        z = n;
    return z;
}

$.formatedDate = function(dateObject, shortYear) {
    var d = new Date(dateObject);
	var sec = d.getSeconds();
	var mi = d.getMinutes();
	var hr = d.getHours();
    var day = d.getDate();
    var month = d.getMonth() + 1;
    var year = d.getFullYear();
	
	day = twoDigitNumZeroLeadFormat(day.toString());
	month = twoDigitNumZeroLeadFormat(month.toString());
	sec = twoDigitNumZeroLeadFormat(sec.toString());
	mi = twoDigitNumZeroLeadFormat(mi.toString());
	hr = twoDigitNumZeroLeadFormat(hr.toString());
	if(shortYear==true)
		var year = year.toString().substring(2,4);
	/*
    if (day < 10) {
        day = "0" + day;
    }
    if (month < 10) {
        month = "0" + month;
    }*/
	
    var date = year+month+day+hr+mi+sec;

    return date;
};

//var d = $.formatedDate(Date.now(), true);
//alert(d);

function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
        results = regex.exec(location.search);
    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}
function commaSeperatedFormat(num) {
    // alert(num);
    if(num=="" || num==null){ num = 0; }

    num = num.toString().replace(/,/g, "");
    num = parseFloat(num).toFixed(2);
    return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
}

function parseToCurrency(str){
    str = str.replace(/,/g,"");
    var c = parseFloat(str);
    return c;
}

function parseCurOrBlank2Zero(str){
    if(str=="" || str==null){ str = "0"; }
    str = str.replace(/,/g,"");
    var c = parseFloat(str);
    return c;
}

function formatCommaToArray(str){
    var arr = '["'+str.replace(/,/g,'","')+'"]';
    //var arr = str.split(",");
    return arr;
}

function isset(object){
    return (typeof object !=='undefined');
}

String.prototype.mysqlToDate = String.prototype.mysqlToDate || function() {
    var t = this.split(/[- :]/);
    return new Date(t[0], t[1]-1, t[2], t[3]||0, t[4]||0, t[5]||0);
};

function HTMLDecode(txt){
    return jQuery('<div></div>').html(txt).text();
}
function HTMLEncode(txt) {
    return jQuery('<div></div>').html(txt).html();
}

function isStepOver(po, step){
    //alert('api/purchaseorder?action=4&po='+po+'&step='+step);
    var res=0;
    $.get('api/purchaseorder?action=4&po='+po+'&step='+step, function(result){
        res = $trim(result);
    });
    return res;
}

function validAttachment(fileName){
    if(!/([a-z0-9])*\.(jpg|png|xlsx|xls|doc|docx|pdf|zip)$/i.test(fileName)){
        return false;
    }
    return true;
}

function originalPO(poid){
    return poid.substring(0,poid.indexOf('P'));
}

function attachmentLink(filename, filetitle) {

    filetitle = filetitle || '';

    var displayName = filename;

    if(filetitle=='') {
        if (filename.length > 22) {
            displayName = filename.substr(0, 22) + '...';
        }
    } else{
        displayName = filetitle;
    }

    if(filename!="") {
        return `<i class="icon fa-${filename.substr(filename.lastIndexOf(".") + 1).toLowerCase()}"></i>&nbsp;&nbsp;
                <a href="download-attachment/${filename}" title="${filetitle}" target="_blank">${displayName}</a>`;
    } else{
        return 'N/A';
    }
}

function validEmail(email){
    if(!/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/i.test(email)){
        return false;
    }
    return true;
}