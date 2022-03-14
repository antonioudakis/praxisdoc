<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
  <title>Google Maps Marker as a Link</title>
  <script src="http://maps.google.com/maps/api/js?sensor=false"
          type="text/javascript"></script>
</head>
<body>
  <div id="map" style="width: 500px; height: 400px;"></div>
  <?php
	if (isset($_POST["submit_address"]))
	{
		$address = $_POST["address"];
?>
<iframe width="100%" height="500" src="https://maps.google.com/maps"></iframe>
<?php
	}
?>
<form method="POST">
<p>
<input type="text" name="address" placeholder="Enter address">
</p>
<input type="submit" name="submit_address">
</form>
  <!--<script type="text/javascript">
    var map = new google.maps.Map(document.getElementById('map'), {
      zoom: 2,
      center: new google.maps.LatLng(35.55, -25.75),
      mapTypeId: google.maps.MapTypeId.ROADMAP
    });

    var marker = new google.maps.Marker({
      position: map.getCenter(),
      url: 'http://www.google.com/',
      map: map
    });

    google.maps.event.addListener(marker, 'click', function() {
      window.location.href = marker.url;
      window.open(this.url, '_blank');
    });

  </script>-->
</body>
</html>
