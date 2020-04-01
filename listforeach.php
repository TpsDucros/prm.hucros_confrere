<?php 
include "index.php"

function list_retour($lst_rtou)
{
    foreach ($lst_rtou as $key => $liv)
	{
        echo "<option value='$key'><pre>".$liv['rerece']." - ".$liv['recpod']." ".$liv['reloca']." ".$liv['recpay']." - ".$liv['redest']." ".$liv['readr1']." ".$liv['readr2']."</pre></option>";
	}
}

// function list_situation($lst_situ)
// {
//     foreach ($lst_situ[4] as $key => $lib)
// 	{
//         echo "<option value='$key'>".$key." - ".$lib."</option>";
// 	}
// }
?>