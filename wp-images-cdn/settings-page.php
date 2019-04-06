<?php

$service = new PtsWpImageReplacerCDN;
$cdn = $service->getCdnHostOption();

if (isset($_POST['submit'])) {
    $cdn = $_POST['cdn'];
    $service->upadeteCdnHost($cdn);
} ?>

<h3 class="title">Cdn host - "https://cdn.site.com"</h3>
<form method="post" target="_self">
	<input style="width: 300px" name="cdn" class="form-control btn btn-danger" value="<?= $cdn ?>" />
    <input type="submit" name="submit" class="form-control btn btn-danger" value="Update" />
</form>