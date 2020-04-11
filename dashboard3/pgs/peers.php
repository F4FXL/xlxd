<?php

$Result = @fopen($CallingHome['ServerURL']."?do=GetReflectorList", "r");

$INPUT = "";

if ($Result) {

    while (!feof ($Result)) {
        $INPUT .= fgets ($Result, 1024);
    }

    $XML = new ParseXML();
    $Reflectorlist = $XML->GetElement($INPUT, "reflectorlist");
    $Reflectors    = $XML->GetAllElements($Reflectorlist, "reflector");
}

fclose($Result);
?>

<div class="row justify-content-md-center">
   <div class="col">
      <table class="table table-hover table-sm table-responsive-md">
            <thead>
                <tr>
                    <th scope="row">#</th>
                    <th scope="row">XLX Peer</th>
                    <th scope="row">Last Heard</th>
                    <th scope="row">Linked for</th>
                    <th scope="row">Protocol</th>
                    <th scope="row">Module</th>
                    <?php
                    if ($PageOptions['PeerPage']['IPModus'] != 'HideIP') {
                    echo '
                    <th scope="row">IP</th>';
                    }
                    ?>
                </tr>
            </thead>
<?php

$Reflector->LoadFlags();

for ($i=0;$i<$Reflector->PeerCount();$i++) {
         
   echo '
  <tr>
   <th scope="row">'.($i+1).'</th>';

   $Name = $Reflector->Peers[$i]->GetCallSign();
   $URL = '';

    for ($j=1;$j<count($Reflectors);$j++) {
        if ($Name === $XML->GetElement($Reflectors[$j], "name")) {
            $URL  = $XML->GetElement($Reflectors[$j], "dashboardurl");
        }
    }
    if ($Result && (trim($URL) != "")) {
        echo '<td><a href="'.$URL.'" target="_blank" class="listinglink" title="Visit the Dashboard of&nbsp;'.$Name.'" style="text-decoration:none;color:#000000;">'.$Name.'</a></td>';
    } else {
        echo '<td>'.$Name.'</td>';
    }
    echo '
   <td>'.date("d.m.Y H:i", $Reflector->Peers[$i]->GetLastHeardTime()).'</td>
   <td>'.FormatSeconds(time()-$Reflector->Peers[$i]->GetConnectTime()).' s</td>
   <td>'.$Reflector->Peers[$i]->GetProtocol().'</td>
   <td>'.$Reflector->Peers[$i]->GetLinkedModule().'</td>';
   if ($PageOptions['PeerPage']['IPModus'] != 'HideIP') {
      echo '<td>';
      $Bytes = explode(".", $Reflector->Peers[$i]->GetIP());
      if ($Bytes !== false && count($Bytes) == 4) {
         switch ($PageOptions['PeerPage']['IPModus']) {
            case 'ShowLast1ByteOfIP'      : echo $PageOptions['PeerPage']['MasqueradeCharacter'].'.'.$PageOptions['PeerPage']['MasqueradeCharacter'].'.'.$PageOptions['PeerPage']['MasqueradeCharacter'].'.'.$Bytes[3]; break;
            case 'ShowLast2ByteOfIP'      : echo $PageOptions['PeerPage']['MasqueradeCharacter'].'.'.$PageOptions['PeerPage']['MasqueradeCharacter'].'.'.$Bytes[2].'.'.$Bytes[3]; break;
            case 'ShowLast3ByteOfIP'      : echo $PageOptions['PeerPage']['MasqueradeCharacter'].'.'.$Bytes[1].'.'.$Bytes[2].'.'.$Bytes[3]; break;
            default                       : echo '<a href="http://'.$Reflector->Peers[$i]->GetIP().'" target="_blank" style="text-decoration:none;color:#000000;">'.$Reflector->Peers[$i]->GetIP().'</a>';
         }
      }
      echo '</td>';
   }
   echo '</tr>';
   if ($i == $PageOptions['PeerPage']['LimitTo']) { $i = $Reflector->PeerCount()+1; }
}

?> 
 
</table>
