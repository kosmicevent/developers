/* Success function for ajax call
 * @param: data returned from search.php
 */
function processData(data){ 
	$('#result').html(data); 
} 

/* Ajax form submit
 */
function submitSearch(){ 
	if( $('#searchtxt').val() != ""){ 
		var formdata = "search=" + $('#searchtxt').val(); 
		$.ajax({ 
        	url: "search.php", 
            type: "POST",    
            data: formdata, 
            cache: false, 
            success: function (data) {              
            	processData(data); 
            }       
	    }); 
	} 
} 
/* Prevents the submit action
 * Calls the ajax search submit function
 */
$("#searchbtn").click(function(event) {
	event.preventDefault(); 
	submitSearch();
});

/* Prevents the submit action
 * Calls the ajax search submit function
 */
$('#searchtxt').keypress(function(event) { 
	if(event.keyCode == 13) { 
		event.preventDefault(); 
		submitSearch(); 
	} 
}); 