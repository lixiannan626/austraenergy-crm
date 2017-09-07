// Based On Andy Langton's show/hide
//updated 19/06/2011

// this tells jquery to run the function below once the DOM is ready
$(document).ready(function() {

// choose text for the show/hide link - can contain HTML (e.g. an image)
var showText='Show Table';
var hideText='Hide Table';

// initialise the visibility check
var is_visible = false;

// hide all of the elements with a class of 'toggle'
$('.toggle').parent().prepend("<a class='toggleLink'>Show Table</a>");
$('.toggle').hide();

// capture clicks on the toggle links
$('a.toggleLink').click(function() {

// switch visibility
if($(this).html() == showText)
	is_visible = true;
else
	is_visible = false;

// change the link depending on whether the element is shown or hidden
$(this).html( (!is_visible) ? showText : hideText);

// toggle the display - uncomment the next line for a basic "accordion" style
$(this).parent().find('.toggle').toggle('fast');

// return false so any link destination is not followed
return false;

});





//FOR DOCUMENT
$('.percentage_desc').hide();

$('a.percentage_desc_link').click(function() {

$('.doc_desc').hide();

$('.percentage_desc').show();

return false;

});

$('a.doc_desc_link').click(function() {

$('.percentage_desc').hide();

$('.doc_desc').show();

return false;

});


});