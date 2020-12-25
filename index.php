<?php
if($_SERVER['HTTPS'] != "on"){ ?>
	<script>
	location.replace("https://<?php echo $_SERVER['SERVER_NAME'];?>");
	</script>
<?php } ?>
<!DOCTYPE html>
<html>
  <head>
    <title>Walk to alive</title>
    <meta charset="utf8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@500&display=swap" rel="stylesheet">
    <style>
      body {
	text-align: center;
	font-family: 'Noto Sans KR', sans-serif;
	padding: 5vw;
      }
      p {
	padding-left: 0px;
	padding-right: 0px;
      }
      img {
	border: 1px solid #444444;
	max-width: 100%;
      }
      .calign { text-align: center; }
      .lalign { text-align: left; }
      .ralign { text-align: right; }
    </style>
    <script type="text/javascript" src="https://openapi.map.naver.com/openapi/v3/maps.js?ncpClientId={YOUR-NAVER-MAP-API-KEY}&submodules=geocoder"></script>
  </head>
    <div class="row">
      <div class="col-md-1"></div>
      <div class="col-md-10">  
	<h1 class="h1"><strong><br>Walk to be alive</strong></h1>
	<div id="question" style="width: 100%;"></div>
	<div id="map" style="width: 100%; height: 75vw; max-height: 50vh; border: 1px solid #dfdfdf;"></div>
	<script>
	// Questions
	var q = document.createElement("h3");
	var qdiv = document.getElementById("question");
	q.setAttribute("class", "h3 calign");
	q.innerHTML = "<br>먼저 사용자 위치 사용에 동의해 주세요<br>";
	qdiv.appendChild(q);
	var lat, lng, nmap;
	navigator.geolocation.getCurrentPosition(function(pos) {
	  lat = parseFloat(pos.coords.latitude);
	  lng = parseFloat(pos.coords.longitude);
	  var mapOptions = {
	    	center: new naver.maps.LatLng(lat, lng),
	    	zoom: 20,
		mapTypeId: naver.maps.MapTypeId.NORMAL
	  };
	
	  var map = new naver.maps.Map('map', mapOptions);
	  var markerOptions = {
		position: mapOptions["center"],
		map: map
	  }
	  var marker = new naver.maps.Marker(markerOptions);
	  naver.maps.Event.addListener(map, "click", function(e){
		lat = e.coord.x;
		lng = e.coord.y;
		marker.setPosition(e.coord);
		map.setCenter(e.coord);
	  });

	  q.setAttribute("class", "p calign");
	  q.innerHTML="<br>어디에 계신가요?<br><br>";
	  var btn = document.getElementById("next");
	  btn.removeAttribute("style");
	  btn.addEventListener("click", function question1() {
		  btn.removeEventListener("click", question1);
		  var mapdiv = document.getElementById("map");
		  const mapdivstyle = mapdiv.getAttribute("style");
		  mapdiv.setAttribute("style", mapdivstyle+"display: none;");
		  q.innerHTML="<br>얼마나 멀리 갈까요??<br><br>";
		  var select = document.createElement("select");
		  select.setAttribute("class", "form-control");
		  select.innerHTML = "<option value=\"5\">0.5km 정도</option>";
		  select.innerHTML = select.innerHTML+"<option value=\"10\">1km 정도</option>";
		  select.innerHTML = select.innerHTML+"<option value=\"15\">1.5km 정도</option>";
		  select.innerHTML = select.innerHTML+"<option value=\"20\">2km 정도</option>";
		  select.innerHTML = select.innerHTML+"<option value=\"25\">2.5km 정도</option>";
		  select.innerHTML = select.innerHTML+"<option value=\"30\">3km 정도</option>";
		  select.innerHTML = select.innerHTML+"<option value=\"35\">3.5km 정도</option>";
		  select.innerHTML = select.innerHTML+"<option value=\"40\">4km 정도</option>";
		  select.innerHTML = select.innerHTML+"<option value=\"45\">4.5km 정도</option>";
		  select.innerHTML = select.innerHTML+"<option value=\"50\">5km 정도</option>";
		  select.innerHTML = select.innerHTML+"<option value=\"60\">6km 정도</option>";
		  select.innerHTML = select.innerHTML+"<option value=\"70\">7km 정도</option>";
		  select.innerHTML = select.innerHTML+"<option value=\"80\">8km 정도</option>";
		  select.innerHTML = select.innerHTML+"<option value=\"90\">9km 정도</option>";
		  select.innerHTML = select.innerHTML+"<option value=\"100\">10km 정도</option>";
		  qdiv.appendChild(select);
		  btn.addEventListener("click", function question2(){
			  btn.removeEventListener("click", question2);
			  const dist = 100 * select.value;
			  q.innerHTML = "<br>어느 방향으로 갈까요?<br><br>";
			  select.innerHTML = "<option value=\"90\">동</option>";
			  select.innerHTML = select.innerHTML + "<option value=\"270\">서</option>";
			  select.innerHTML = select.innerHTML + "<option value=\"180\">남</option>";
			  select.innerHTML = select.innerHTML + "<option value=\"0\">북</option>";
			  btn.addEventListener("click", function question3(){
			  	  btn.removeEventListener("click", question3);
				  const deg = select.value
				  const param = dist/1000;
				  var fromLatLng = map.getCenter();
				  var toLatLng = naver.maps.LatLng(naver.maps.UTMK.getDestinationCoord(fromLatLng, deg, dist - (312.2*Math.pow(param,0.7548))/2));
				  naver.maps.Service.reverseGeocode({
				  	coords: fromLatLng,
					orders: [naver.maps.Service.OrderType.ROAD_ADDR]
				  }, function(status, resfrom){
					  const fromAddr = resfrom.v2.address.roadAddress;
					  naver.maps.Service.reverseGeocode({
					  	coords: toLatLng,
						orders: [naver.maps.Service.OrderType.ROAD_ADDR]
					  }, function(status, resto){
						if(resto.v2.status.code !== 0){
							alert("적절한 목적지를 찾지 못했습니다");
							window.location.reload();
						} else {
							var toAddr = resto.v2.address.roadAddress;
							qdiv.removeChild(select);
							q.innerHTML = "<br>네이버 지도로 연결합니다<br><br>";
							window.location.href = encodeURI("http://map.naver.com/?appMenu=route&routeType=4&app=Y&menu=route&pathType=3&version=11&sText="+fromAddr+"&t1Text="+toAddr+"&eText="+fromAddr+"&slat="+fromLatLng['_lat']+"&slng="+fromLatLng['_lng']+"&t1lat="+toLatLng['_lat']+"&t1lng="+toLatLng['_lng']+"&elat="+fromLatLng['_lat']+"&elng="+fromLatLng['_lng']);
							btn.setAttribute("style", "display: none;");
						}
					  });
				  });
			  });
		  });
	  });
	}) 
	// Result
	</script>
	<br><button id="next" class="btn btn-success" style="display: none;">다음</button><br>
      </div>
      <div class="col-md-1"></div>
    </div>
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
  </body>
</html>
