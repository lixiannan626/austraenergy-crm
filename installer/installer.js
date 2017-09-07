$(document).ready(function() {


     $('a.view_detail').click(function(e) {
          e.preventDefault();
		  var self = $(this);
		  var root = $(this).parent();
		  var id=$(this).attr("data-id");
		  var conno = $(this).attr("data-con");
		  
		  //if
		  if(root.find('div.clientdetail').length == 0)
		  {
			  $.post(
				"clientdetail.php",
				{
					id: id,
					conno: conno
				},
				function(responseText)
				{
					self.hide();
					//If data fetched
					root.append(responseText);
					root.find('div.clientdetail').reveal();
					self.show();
				},
				"html"
			  );
		  }
		  else
		  {
			root.find('div.clientdetail').reveal();
		  }
     });
	 
	 
	 //New Date
	 
	 $("td.changable").click(function()
	 {
		var root = $(this);
		var dd = $(this).find('.origin').text();
		//Hide
		$(this).find('.origin').hide();
		//Show Input
		if(root.find('.inputdiv').length == 0)
		{
			var inputarea = "<div class='inputdiv'><input type='text' class='datepicker' value='"+dd+"' /><br><button class='yes'>Save</button><button class='no'>Cancel</button></div>";
			root.append(inputarea);
			root.find('input.datepicker').datepicker({dateFormat: "yy-mm-dd", minDate: 0});
		}
	 });
	 
	 $("button.no").live("click", function(){
		var root = $(this).parent().parent();
		root.find('div.origin').show();
		root.find('div.inputdiv').remove();
	 });
	 
	 $("button.yes").live("click", function(){
		
		//Send Data to backend
		var root = $(this).parent().parent();
		var crmid = root.find('input.crmid').val();
		var con_no = root.find('input.con_no').val();
		var newdate = root.find('input.datepicker').val();
		var installerid = root.find('input.installerid').val();
		$.post(
			"newdate.php",
			{
				crmid: crmid,
				con_no: con_no,
				newdate: newdate,
				installerid: installerid
			},
			function(Response)
			{
				//Err0r
				if(Response == "Err0r")
					alert("You didn't input a valid date, it must today or after today. Other than that, please let go for solar know the CON #, new install date.");
				else
				{
					//Update Origin
					root.find('div.inputdiv').remove();
					root.find('div.origin').text(Response).show();
				}
			},
			"html"
		
		);
	 });
});