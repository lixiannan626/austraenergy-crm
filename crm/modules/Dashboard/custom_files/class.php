<?php


class Leads2
{
	var $leads = array();			//Store all original leads
	var $leadstat = array();		//Store leads from different sources, "Source Name"=>Source Number
	
	function add($lead)
	{
		$this->leads[count($this->leads)] = $lead;
		//$lead[0]: lead id
		//$lead[1]: lead source
		//$lead[2]: lead status
		$source = $lead[1];
		if(isset($this->leadstat[$source]))
			$this->leadstat[$source]++;
		else
			$this->leadstat[$source] = 1;
	}
	function showPieChart($div, $width, $height, $name, $options)
	{
		drawPieChartA($this->leadstat, $div, $width, $height, $name, $options);
	}
	function leadperformance()
	{
		$lead_sources = array();
		for($i = 0; $i < count($this->leads); $i++)
		{
			$single_lead = $this->leads[$i];
			if(isset($lead_sources[$single_lead[1]]))
			{
				$lead_sources[$single_lead[1]]->add($single_lead);
			}
			else
			{
				$lead_sources[$single_lead[1]] = new LeadSourceStat;
				$lead_sources[$single_lead[1]]->add($single_lead);
			}
		}
		
		//Display
		$i = 0;
		foreach($lead_sources as $ls)
		{
			$i++;
			$metadata = array("Lead Status"=>"string","Lead Number"=>"number");
			drawColumnChart($metadata, OneToTwo($ls->leadstat), "test".$i, 700, 500, "$ls->lead_source leads status (total: $ls->count)", "");
		}
	}
	
}

class LeadSourceStat2
{
	var $leads = array();
	var $leadstat = array();
	var $lead_source = "";
	var $count = 0;
	
	function  add($lead)
	{
		$this->count++;
		array_push($this->leads, $lead);
		$this->lead_source = $lead[1];
		if(isset($this->leadstat[$lead[2]]))
		{
			$this->leadstat[$lead[2]]++;
		}
		else
		{
			$this->leadstat[$lead[2]] = 1;
		}
	}
}

class SalesPerformance2
{
	var $leads = array();
	var $leadstat = array();
	var $sales = "";
	var $count = 0;
	
	function add($lead)
	{
		$this->count++;
		array_push($this->leads, $lead);
		$this->sales = $lead[3];
		if(isset($this->leadstat[$lead[2]]))
		{
			$this->leadstat[$lead[2]]++;
		}
		else
		{
			$this->leadstat[$lead[2]] = 1;
		}
	}
}






class LeadAssignment2
{
	var $leads = array();			//Store all original leads
	var $leadstat = array();		//Store leads from different sources, "Source Name"=>Source Number
	
	function add($lead)
	{
		$this->leads[count($this->leads)] = $lead;
		//$lead[0]: lead id
		//$lead[1]: lead source
		//$lead[2]: lead status
		//$lead[3]: lead Assignment
		$assign = $lead[3];
		if(isset($this->leadstat[$assign]))
			$this->leadstat[$assign]++;
		else
			$this->leadstat[$assign] = 1;
	}
	function showPieChart($div, $width, $height, $name, $options)
	{
		drawPieChartA($this->leadstat, $div, $width, $height, $name, $options);
	}
	
	function SalesPerform()
	{
		$sales = array();
		for($i = 0; $i < count($this->leads); $i++)
		{
			$single_lead = $this->leads[$i];
			if(isset($sales[$single_lead[3]]))
			{
				$sales[$single_lead[3]]->add($single_lead);
			}
			else
			{
				$sales[$single_lead[3]] = new SalesPerformance;
				$sales[$single_lead[3]]->add($single_lead);
			}
		}	
		
		//Display
		$i = 0;
		foreach($sales as $ls)
		{
			$i++;
			$metadata = array("Lead Status"=>"string","Lead Number"=>"number");
			drawColumnChart($metadata, OneToTwo($ls->leadstat), "test".$i, 700, 500, "$ls->sales leads status (total: $ls->count)", "");	
		}
	}
	
}
//Definition of classes
class Leadsource2
{
	//Lead: leadid, leadsource, leadstatus
	var $leads = array();
	var $leadstat = array();
	var $source = "";
	
	function add($lead)
	{
		$this->leads[count($this->leads)] = $lead;
		//$lead[0]: lead id
		//$lead[1]: lead source
		//$lead[2]: lead status
		$status = $lead[2];
		if(isset($this->leadstat[$status]))
			$this->leadstat[$status]++;
		else
			$this->leadstat[$status] = 1;
	}
	
	function showPieChart($width, $height, $options)
	{
		//drawPieChart(OneToTwo($this->leadstat),"test3",0,0,$this->source.' Pie Chart');
		drawPieChartA($this->leadstat,"test3",$width,$height,$this->source.' Pie Chart',$options);
	}
}

//Methods




?>