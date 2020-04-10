<div class="row">
   <div class="col-md-9">
      <table class="table table-striped table-hover">
      <tr class="table-center">   
            <th class="col-md-1">Module</th>
            <th class="col-md-1">Nom du Module</th>
			<th class="col-md-1">Utilisateurs</th>
			<th class="col-md-1">Relais</th>
            <!--<th class="col-md-1">Se Connecter</th>-->
        </tr>
<?php


foreach($PageOptions['ModuleNames'] as $module => $description)
{
?>
<tr class="table-center">
    <td><?php echo $module;?></td>
    <td><?php echo $description;?></td>
    <td><?php echo count($Reflector->GetCallSignsInModules($module));?></td>
    <td><?php echo count($Reflector->GetNodesInModulesById($module));?></td>
</tr>
<?php
}

?>

</table>