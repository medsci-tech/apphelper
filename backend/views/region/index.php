<?php
$city = $regionValue ?? '';
?>
<link href="/css/city-picker.css" rel="stylesheet">

<div style="position: relative;min-width: 330px;">
    <input id="city-picker" class="form-control" type="text" value="<?php echo $city;?>" data-toggle="city-picker">
</div>



