
$(document).ready(function(){
	//
	$("div.qld").hide();
	
	$("a#vicjob").click(function()
	{
		$("#vicqld a").attr("class", "");
		$(this).attr("class", "selected");
		$("div.commentdetail").remove();
		$("div.reveal-modal-bg").remove();
		
		$("div.vic").show();
		$("div.qld").hide();
	});
	
	$("a#qldjob").click(function()
	{
		$("#vicqld a").attr("class", "");
		$(this).attr("class", "selected");
		$("div.commentdetail").remove();
		$("div.reveal-modal-bg").remove();
		$("div.qld").show();
		$("div.vic").hide();
	});
	
	$("table.summary_table.vic tr.data").click(function()
	{
		if($(this).attr("class") == "selected")
		{
			$(this).attr("class", "");
			$("div.job_div.vic tr.data").show();
		}
		else
		{
			$("table.summary_table.vic tr").each(function(){
			$(this).attr("class", "");
			});
			var option = $(this).attr("data-option");
			$("div.job_div.vic tr.data").hide();
			$("div.job_div.vic tr."+option).show();
			$(this).attr("class", "selected");
		}
	});
	$("table.summary_table.qld tr.data").click(function()
	{
		if($(this).attr("class") == "selected")
		{
			$(this).attr("class", "");
			$("div.job_div.qld tr.data").show();
		}
		else
		{
			$("table.summary_table.qld tr").each(function(){
			$(this).attr("class", "");
			});
			var option = $(this).attr("data-option");
			$("div.job_div.qld tr.data").hide();
			$("div.job_div.qld tr."+option).show();
			$(this).attr("class", "selected");
		}
	});
	//
	$("a.comment_detail").click(function()
	{
		var self = $(this);
		var id = $(this).attr("data-id");
		var root = $(this).parent();
		
		if(root.find('div.commentdetail').length == 0)
		{
		$.post(
			"modules/StockManager/comment_detail.php",
			{id:id},
			function(responseText)
			{
				//self.hide();
				root.append(responseText);
				root.find('div.commentdetail').reveal();
				//self.show();
			},
			"html"
		);
		}
		else
		{
			root.find('div.commentdetail').reveal();
		}
	});
	
});