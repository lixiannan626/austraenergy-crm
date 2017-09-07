// Function to run after page loads
window.onload = function(){
    //Insert the Exporting button
	/*
	var bi = document.getElementById("before_download_td");
	
	if(bi != null)
	{
		var a = document.createElement('td');
		
		var ai = document.createElement('form');
		ai.setAttribute("id","download_form");
		ai.setAttribute("name","download_form");
		ai.setAttribute("action","custom_yi/exportCSV.php");
		ai.setAttribute("target","_blank");
		ai.setAttribute("method","get");
		
		var aii = document.createElement('a');
		aii.setAttribute("id","export_button");
		aii.setAttribute("style","line-height:26px;padding: 0 4px; border: 1px solid #62a0df; display: inline-block;background: #62a0df;color: white; font-weight: bold;");
		aii.setAttribute("href","javascript:getContacts()");
		aii.setAttribute("title","select contacts using checkboxes");
		aii.innerHTML = "Export Contacts";
		
		var aiii = document.createElement('input');
		aiii.setAttribute("type","hidden");
		aiii.setAttribute("id","ids");
		aiii.setAttribute("name","ids");
		aiii.setAttribute("value","");
		
		var biii = document.createElement('input');
		biii.setAttribute("type","hidden");
		biii.setAttribute("id", "cre");
		biii.setAttribute("name", "cre");
		biii.setAttribute("value", cre);
		
		
		ai.appendChild(aii);
		ai.appendChild(aiii);
		ai.appendChild(biii);
		
		a.appendChild(ai);
		
		bi.parentNode.appendChild(a);
	}
	*/

	//Insert the Tutorial Link
	//var nav_tab = document.getElementById("allMenu").nextElementSibling.nextElementSibling.nextElementSibling.nextElementSibling.nextElementSibling.nextElementSibling.childNodes[0];
	/* var nav_tab = document.getElementById("allMenu").nextElementSibling.nextElementSibling.nextElementSibling.nextElementSibling.nextElementSibling.nextElementSibling;
	alert(nav_tab.innerHTML);
	var tut_tr = document.createElement('tr');
	var tut_td = document.createElement('td');
	var tut_a = document.createElement('a');
	
	tut_a.setAttribute("href","test.php");
	tut_a.setAttribute("class","drop_down");
	tut_a.innerHTML = "Tutorials";
	
	tut_td.appendChild(tut_a);
	tut_tr.appendChild(tut_td);
	alert(tut_tr.innerHTML);
	nav_tab.appendChild(tut_td); */
	// var x = document.getElementById("header_nav_0109").getElementsByTagName("td");
	// x[0].parentNode.removeChild(x[0]);
	// x[0].parentNode.removeChild(x[0]);
	// x[0].parentNode.removeChild(x[0]);
	// x[0].parentNode.removeChild(x[0]);
	//alert(x.length);
	//nav_tab.parentNode.removeChild(nav_tab);
};


function getContacts()
{
	var crmids = "";
	var all_elements = document.getElementsByName("selected_id");
	
	//Search for checked checkboxes
	for(i = 0; i<all_elements.length; i++)
	{
		if(all_elements[i].checked)
			crmids = crmids+all_elements[i].parentNode.parentNode.getAttribute("id")+"|";
	}
	document.getElementById("ids").setAttribute("value",crmids);
	if(crmids == "")
		window.alert("You must select contacts first using checkboxes");
	else
		document.getElementById("download_form").submit();
}
