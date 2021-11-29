
function generate_endorsement_letter() {
    
    $.get('application/templates/letter_template/lc-endorsement-letter.php', function (data) {
        //alert(data);
        
        var pdf = new jsPDF('p', 'pt', 'a4')

    	// source can be HTML-formatted string, or a reference
    	// to an actual DOM element from which the text will be scraped.
    	, source = data
    
    	// we support special element handlers. Register them with jQuery-style 
    	// ID selector for either ID or node name. ("#iAmID", "div", "span" etc.)
    	// There is no support for any other type of selectors 
    	// (class, of compound) at this time.
    	, specialElementHandlers = {
    		// element with id of "bypass" - jQuery style selector
    		'#bypassme': function(element, renderer){
    			// true = "handled elsewhere, bypass text extraction"
    			return true
    		}
    	}
        //alert(source);
//        source = source.innerHTML.replace('##LCNO##',$("#lcno").val());
//        source = source.replace('##PINO##',$("#pinum").html());
//        source = source.replace('##PIDATE##',$("#pidate").html());
//        //source = source.innerHTML.replace('##CUR##',$("#lcno").val());
//        source = source.replace('##PIVALUE##',$("#pivalue").html());
//        source = source.replace('##ACCNO##',$("#bankaccount").find('option:selected').text());
        //alert(source);
        
    	margins = {
          top: 80,
          bottom: 60,
          left: 60,
          width: 500
        };
        // all coords and widths are in jsPDF instance's declared units
        // 'inches' in this case
        pdf.fromHTML(
        	source // HTML string or DOM elem ref.
        	, margins.left // x coord
        	, margins.top // y coord
        	, {
        		'width': margins.width // max width of content on PDF
        		, 'elementHandlers': specialElementHandlers
        	},
        	function (dispose) {
        	  // dispose: object with X, Y of the last line add to the PDF 
        	  //          this allow the insertion of new lines after html
              pdf.save('EndorsementLetter.pdf');
            },
        	margins
        )
        
    });    
}