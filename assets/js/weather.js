navigator.geolocation.getCurrentPosition(
    function (position) {
        const lat = position.coords.latitude;
        const lng = position.coords.longitude;

        fetch(`/coffee/api/weather.php?lat=${lat}&lng=${lng}`)
            .then(function (response) {
                return response.json();
            })
            .then(function (data) {
                const temp = Math.round(data.main.temp);
                document.getElementById('weather-temp').innerText = temp + '°C';

                const temperatureType = temp >= 20 ? 'ice' : 'hot';

                return fetch(`/coffee/api/recommend_menu.php?type=${temperatureType}`)
                    .then(function (response) {
                        return response.json();
                    })
                    .then(function (menu) {
                        document.getElementById('weather-menu').innerHTML =
                            '오늘 날씨엔<br>' + menu.name;
                        document.getElementById('weather-image').src = menu.image;
                        document.getElementById('weather-summary').innerText =
                            temperatureType === 'ice'
                                ? '시원하게 즐기기 좋은 날씨예요.'
                                : '따뜻한 음료가 어울리는 날씨예요.';

                        const description = menu.description || '';
                        document.getElementById('weather-description').innerText =
                            description.substring(0, 60) + (description.length > 60 ? '...' : '');
                    });
            })
            .catch(function () {
                document.getElementById('weather-location').innerText = '날씨 확인 실패';
            });
    },
    function () {
        document.getElementById('weather-location').innerText = '위치 확인 실패';
    }
);
