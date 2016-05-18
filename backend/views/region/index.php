<?php
$regionValue = $regionValue ?? '';
?>
<link href="/css/city-picker.css" rel="stylesheet">
<div style="position: relative;min-width: 330px;">
    <input readonly class="form-control" type="text" value="<?php echo $regionValue;?>" data-toggle="city-picker">
</div>
<div data-toggle="city-picker-region" style="display: none;"></div>

