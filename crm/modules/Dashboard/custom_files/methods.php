<?php
//
function DataUploadForm($actionphp,$uploadtitle,$uploadextension)
{
	echo '
	<div>
		<form enctype="multipart/form-data" action="'.$actionphp.'" method="POST">
			<h4>'.$uploadtitle.':</h4>
			<p>
				<input type="hidden" name="MAX_FILE_SIZE" value="3000000" />
				Please choose a .'.$uploadextension.' file: <input name="uploadedfile" id="upload_file" type="file" />
				<input type="submit" name="csvupload" value="Upload File" id="upload_submit"/>
			</p>
		</form>
	</div>
	';
}
//Return arrayed uploaded file
function UploadFileToArray()
{
	$target_path = "temp/";
	$target_path = $target_path.basename($_FILES['uploadedfile']['name']); 
	if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path)) 
	{
		//echo "The file ".  basename( $_FILES['uploadedfile']['name']). 
		//" has been uploaded";
	} 
	else
	{
		echo "There was an error uploading the file, please try again!";
	}
	return ReadToArray($target_path);
}
//Give the file name, return an array;
function ReadToArray($filename)
{
	$fh = fopen($filename,'r');
	$Data = fread($fh, filesize($filename));		
	$Data = explode("\n",$Data);
	$Output = array();
	foreach($Data as $row)
	{
		if(trim($row) != "")
		{
			array_push($Output, trim(strval($row)));
		}
	}
	return $Output;
}

/**
Validate an email address.
Provide email address (raw input)
Returns true if the email address has the email 
address format and the domain exists.
*/
function check_email_address($email)
{
   $isValid = true;
   $atIndex = strrpos($email, "@");
   if (is_bool($atIndex) && !$atIndex)
   {
      $isValid = false;
   }
   else
   {
      $domain = substr($email, $atIndex+1);
      $local = substr($email, 0, $atIndex);
      $localLen = strlen($local);
      $domainLen = strlen($domain);
      if ($localLen < 1 || $localLen > 64)
      {
         // local part length exceeded
         $isValid = false;
      }
      else if ($domainLen < 1 || $domainLen > 255)
      {
         // domain part length exceeded
         $isValid = false;
      }
      else if ($local[0] == '.' || $local[$localLen-1] == '.')
      {
         // local part starts or ends with '.'
         $isValid = false;
      }
      else if (preg_match('/\\.\\./', $local))
      {
         // local part has two consecutive dots
         $isValid = false;
      }
      else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain))
      {
         // character not valid in domain part
         $isValid = false;
      }
      else if (preg_match('/\\.\\./', $domain))
      {
         // domain part has two consecutive dots
         $isValid = false;
      }
      else if
(!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/',str_replace("\\\\","",$local)))
      {
         // character not valid in local part unless 
         // local part is quoted
         if (!preg_match('/^"(\\\\"|[^"])+"$/',
             str_replace("\\\\","",$local)))
         {
            $isValid = false;
         }
      }
      if ($isValid && !(checkdnsrr($domain,"MX") || checkdnsrr($domain,"A")))
      {
         // domain not found in DNS
         $isValid = false;
      }
   }
   return $isValid;
}





function SumArray($array,$colnumber)
{
	for($i = 1; $i <= $colnumber; $i++)
	{
		$sum[$i] = 0;
	}
	foreach($array as $row)
	{
		for($i = 1; $i <= $colnumber; $i++)
		{
			$sum[$i] += $row[$i];
		}
	}
	$sumrow = array("Total");
	foreach($sum as $element)
	{
		array_push($sumrow, $element);
	}
	array_push($array,$sumrow);
	return $array;
}
function ArrayToTable($headerArray,$array)
{
	echo '<table class="data_table">';
	if($headerArray == "")
	{}
	else
	{
		echo '<tr>';
		foreach($headerArray as $element)
		{
			echo "<th>$element</th>";
		}
		echo '</tr>';
	}
	foreach($array as $row)
	{
		echo '<tr>';
		foreach($row as $element)
		{
			echo "<td>$element</td>";
		}
		echo '</tr>';
	}
	echo '</table>';
	
	
}
?>