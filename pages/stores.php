<?php

require_once __DIR__ . '/../config/database.php';

$result = mysqli_query(
    $db,
    "
    SELECT *
    FROM stores
    ORDER BY id DESC
    "
);

$stores = [];

while($row = mysqli_fetch_assoc($result)){
    $stores[] = $row;
}

?>
<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>STORE | MBCA COFFEE</title>
  <link rel="stylesheet" href="/coffee/assets/css/header.css">
  <link rel="stylesheet" href="/coffee/assets/css/nav.css">
  <link rel="stylesheet" href="/coffee/assets/css/store.css">
  <script src="//dapi.kakao.com/v2/maps/sdk.js?appkey=189902bbe93b113649d2eeeb9e4b90e9"></script>
</head>
<body>
  <?php include __DIR__ . '/../includes/header.php'; ?>

  <main class="store-page">
    <section class="store-hero">
      <p>MBCA STORE</p>
      <h1>매장 찾기</h1>
    </section>

<section class="store-search">

  <input
    type="search"
    id="store-search-input"
    placeholder="지역명 또는 매장명을 입력하세요"
>
<button
    type="button"
    id="store-search-btn"
>
    검색
</button>

  <button
    type="button"
    id="current-location-btn"
  >
    현재 위치
  </button>

</section>

<div id="nearest-store-box"></div>

    <section class="store-layout">
      <div class="store-map">
       <div id="map"></div>
      </div>
      <div class="store-list">
        <?php foreach ($stores as $store): ?>
          <article
          class="store-card"
          data-lat="<?= $store['lat'] ?>"
          data-lng="<?= $store['lng'] ?>"
          data-name="<?= htmlspecialchars($store['name']) ?>"
          >
            <h2><?= $store['name'] ?></h2>
            <p><?= $store['address'] ?></p>
            <span><?= $store['phone'] ?></span>
            <small><?= $store['hours'] ?></small>
            <a
    class="direction-btn"
    href="https://map.kakao.com/link/to/<?= urlencode($store['name']) ?>,<?= $store['lat'] ?>,<?= $store['lng'] ?>"
    target="_blank"
>
    길찾기
</a>
          </article>
        <?php endforeach; ?>
      </div>
    </section>
  </main>
  <script src="/coffee/assets/js/nav.js"></script>
 <script>

const container =
    document.getElementById('map');

const options = {
    center: new kakao.maps.LatLng(
        37.4847625375982,
        126.930096548294
    ),
    level: 5
};

const map =
    new kakao.maps.Map(
        container,
        options
    );

const stores = <?= json_encode($stores) ?>;
const markers = [];
let currentMarker = null;
let currentInfoWindow = null;
function getDistance(lat1, lng1, lat2, lng2){

    const R = 6371;

    const dLat =
        (lat2 - lat1) * Math.PI / 180;

    const dLng =
        (lng2 - lng1) * Math.PI / 180;

    const a =
        Math.sin(dLat / 2) *
        Math.sin(dLat / 2)

        +

        Math.cos(lat1 * Math.PI / 180)
        *
        Math.cos(lat2 * Math.PI / 180)

        *

        Math.sin(dLng / 2)
        *
        Math.sin(dLng / 2);

    const c =
        2 *
        Math.atan2(
            Math.sqrt(a),
            Math.sqrt(1 - a)
        );

    return R * c;
}

stores.forEach(function(store){

    if(!store.lat || !store.lng){
        return;
    }

    const marker =
        new kakao.maps.Marker({

            map: map,

            position:
                new kakao.maps.LatLng(
                    parseFloat(store.lat),
                    parseFloat(store.lng)
                )

        });

    const infoWindow =
        new kakao.maps.InfoWindow({

            content:
                '<div style="padding:8px;">'
                + store.name +
                '</div>'

        });
    markers.push({
    marker: marker,
    infoWindow: infoWindow,
    storeName: store.name
});

    kakao.maps.event.addListener(
        marker,
        'click',
        function(){

            infoWindow.open(
                map,
                marker
            );

        }
    );

});
document
.querySelectorAll('.store-card')
.forEach(function(card){

    card.addEventListener(
        'click',
        function(){

            const lat =
                parseFloat(
                    this.dataset.lat
                );

            const lng =
                parseFloat(
                    this.dataset.lng
                );

            const name =
                this.dataset.name;

            const moveLatLng =
                new kakao.maps.LatLng(
                    lat,
                    lng
                );

            map.panTo(moveLatLng);

            markers.forEach(function(item){

                if(
                    item.storeName === name
                ){
                    markers.forEach(function(item){
                        item.infoWindow.close();
                    });
                    item.infoWindow.open(
                        map,
                        item.marker
                    );

                }

            });

        }
    );

});
const currentLocationBtn =
    document.getElementById(
        'current-location-btn'
    );

currentLocationBtn.addEventListener(
    'click',
    function(){

        if(
            !navigator.geolocation
        ){
            alert(
                '위치 정보를 지원하지 않는 브라우저입니다.'
            );
            return;
        }

        navigator.geolocation.getCurrentPosition(

            function(position){

                const lat =
                    position.coords.latitude;

                const lng =
                    position.coords.longitude;
                let nearestStore = null;
                let shortestDistance = Infinity;

                stores.forEach(function(store){

                    if(!store.lat || !store.lng){
                        return;
                    }

                    const distance =
                        getDistance(
                            lat,
                            lng,
                            parseFloat(store.lat),
                            parseFloat(store.lng)
                        );

                    if(distance < shortestDistance){

                        shortestDistance =
                            distance;

                        nearestStore =
                            store;
                    }

                });

                const moveLatLng =
                    new kakao.maps.LatLng(
                        lat,
                        lng
                    );

                map.panTo(
                    moveLatLng
                );

                if(currentMarker){
                    currentMarker.setMap(null);
                }

                if(currentInfoWindow){
                    currentInfoWindow.close();
                }

                currentMarker =
                    new kakao.maps.Marker({

                        map: map,

                        position: moveLatLng

                    });

                currentInfoWindow =
                    new kakao.maps.InfoWindow({

                        content:
                            '<div style="padding:8px;">현재 위치</div>'

                    });

                currentInfoWindow.open(
                    map,
                    currentMarker
                );
                if(nearestStore){

    const nearestLatLng =
        new kakao.maps.LatLng(
            parseFloat(nearestStore.lat),
            parseFloat(nearestStore.lng)
        );

            markers.forEach(function(item){

                item.infoWindow.close();

                if(
                    item.storeName ===
                    nearestStore.name
                ){

                    item.infoWindow.open(
                        map,
                        item.marker
                    );

                }

            });
let distanceText = '';

if(shortestDistance < 1){

    distanceText =
        Math.round(
            shortestDistance * 1000
        ) + 'm';

}else{

    distanceText =
        shortestDistance.toFixed(1)
        + 'km';

}
const nearestBox =
    document.getElementById(
        'nearest-store-box'
    );

nearestBox.innerHTML = `
    <div class="nearest-card">

        <h3>가장 가까운 매장</h3>

        <p>${nearestStore.name}</p>

        <strong>${distanceText}</strong>

        <div class="nearest-actions">

            <button
                type="button"
                id="nearest-view-btn"
            >
                매장 보기
            </button>

            <a
                href="https://map.kakao.com/link/to/${nearestStore.name},${nearestStore.lat},${nearestStore.lng}"
                target="_blank"
                class="nearest-direction-btn"
            >
                길찾기
            </a>

        </div>

    </div>
`;
const viewBtn =
    document.getElementById(
        'nearest-view-btn'
    );

viewBtn.addEventListener(
    'click',
    function(){

        const moveLatLng =
            new kakao.maps.LatLng(
                parseFloat(nearestStore.lat),
                parseFloat(nearestStore.lng)
            );

        map.panTo(
            moveLatLng
        );

        markers.forEach(function(item){

            item.infoWindow.close();

            if(
                item.storeName ===
                nearestStore.name
            ){

                item.infoWindow.open(
                    map,
                    item.marker
                );

            }

        });

    }
);

        }

            },

              function(error){

                  console.log(error);

                  alert(
                      '현재 위치를 가져올 수 없습니다.\n\n'
                      + error.message
                  );

              }

        );

    }
);
const searchInput =
    document.getElementById(
        'store-search-input'
    );
let firstMatch = null;
const searchBtn =
    document.getElementById(
        'store-search-btn'
    );

searchBtn.addEventListener(
    'click',
    function(){

        const keyword =
            searchInput.value
            .trim()
            .toLowerCase();
            if(keyword === ''){

                document
                    .querySelectorAll('.store-card')
                    .forEach(function(card){

                        card.style.display =
                            'block';

                    });

                return;
            }

        const cards =
            document.querySelectorAll(
                '.store-card'
            );
        firstMatch = null;
        cards.forEach(function(card){

            const text =
                card.dataset.name
                .toLowerCase();

           if(
    text.includes(keyword)
){
    if(!firstMatch){

    firstMatch = card;

}
    card.style.display =
        'block';

    const lat =
        parseFloat(
            card.dataset.lat
        );

    const lng =
        parseFloat(
            card.dataset.lng
        );

    const name =
        card.dataset.name;

    const moveLatLng =
        new kakao.maps.LatLng(
            lat,
            lng
        );

    map.panTo(
        moveLatLng
    );

    markers.forEach(function(item){

        item.infoWindow.close();

        if(
            item.storeName === name
        ){

            item.infoWindow.open(
                map,
                item.marker
            );

        }

    });

}else{

    card.style.display =
        'none';

}

        });
if(firstMatch){

    const lat =
        parseFloat(
            firstMatch.dataset.lat
        );

    const lng =
        parseFloat(
            firstMatch.dataset.lng
        );

    const moveLatLng =
        new kakao.maps.LatLng(
            lat,
            lng
        );

    map.panTo(
        moveLatLng
    );

}
    }
);


</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>