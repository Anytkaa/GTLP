// Инициализация карты Яндекс
function initMap() {
    return new ymaps.Map('map', {
        center: [20, 0],
        zoom: 3,
        controls: ['zoomControl', 'geolocationControl'],
        restrictMapArea: [
            [-85, -175],
            [85, 175]
        ]
    });
}

// Добавление маркеров
function addMarkers(map, countries) {
    const markers = [];
    const markerSpacing = 1;
    const usedCoords = new Set();

    countries.forEach(country => {
        let { lat, lng } = country.coords;

        // Разносим маркеры, если они находятся в одном месте
        while (usedCoords.has(`${lat},${lng}`)) {
            lat += (Math.random() - 0.5) * markerSpacing;
            lng += (Math.random() - 0.5) * markerSpacing;
        }
        usedCoords.add(`${lat},${lng}`);

        const marker = new ymaps.Placemark([lat, lng], {
            hintContent: country.name || 'Узнать больше о стране'
        }, {
            iconLayout: 'default#image',
            iconImageHref: country.iconUrl,
            iconImageSize: [64, 64],
            iconImageOffset: [-32, -32],
            zIndex: 1000
        });

        // Обработчик клика - переход на страницу страны
        marker.events.add('click', () => {
            window.location.href = country.link;
        });

        // Эффекты при наведении
        marker.events.add('mouseenter', function() {
            this.options.set({
                iconImageSize: [96, 96],
                iconImageOffset: [-48, -48],
                zIndex: 2000
            });
        });

        marker.events.add('mouseleave', function() {
            this.options.set({
                iconImageSize: [64, 64],
                iconImageOffset: [-32, -32],
                zIndex: 1000
            });
        });

        map.geoObjects.add(marker);
        markers.push(marker);
    });

    return markers;
}

// Инициализация после загрузки API
ymaps.ready(() => {
    const map = initMap();
    const markers = addMarkers(map, countries);
});