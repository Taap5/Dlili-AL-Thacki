document.addEventListener("DOMContentLoaded", function () {
    if (!window.govData) return;

    let map = null;
    let govMarker = null;
    let userMarker = null;
    let routeLayer = null;

    const govLat = window.govData.lat;
    const govLng = window.govData.lng;
    const govName = window.govData.name;
    const isLoggedIn = window.isLoggedIn === true;

    const ORS_API_KEY = window.ORS_API_KEY || "eyJvcmciOiI1YjNjZTM1OTc4NTExMTAwMDFjZjYyNDgiLCJpZCI6Ijk2NzIxMjI1NTk4ODQ5NTI5MjA0Y2U2MTc2YzVkNzU3IiwiaCI6Im11cm11cjY0In0=";

    function initMap() {
        if (!govLat || !govLng || map) return;

        map = L.map("map").setView([govLat, govLng], 14);
        window.mapInstance = map;

        // رابط HTTPS موثوق
        L.tileLayer("https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png", {
            attribution: '&copy; OpenStreetMap &copy; CARTO',
            subdomains: "abcd",
            maxZoom: 19,
        }).addTo(map);

        govMarker = L.marker([govLat, govLng]).addTo(map).bindPopup(`<b>${govName}</b>`).openPopup();
    }

    // تعديل الـ Collapse لضمان تحديث الحجم
    const locationCard = document.getElementById("locationCard");
    if (locationCard) {
        locationCard.addEventListener("shown.bs.collapse", function () {
            if (!map) initMap();
            setTimeout(() => {
                if(window.mapInstance) window.mapInstance.invalidateSize();
            }, 300); // تأخير بسيط لضمان اكتمال حركة الفتح
        });
    }

    if (!isLoggedIn) {
        initMap();
        return;
    }

    // دالة حساب المسار (كما هي)
    window.calculateRoute = function(userLat, userLng, gLat, gLng, profile = "driving-car") {
        const url = `https://api.openrouteservice.org/v2/directions/${profile}?api_key=${ORS_API_KEY}&start=${userLng},${userLat}&end=${gLng},${gLat}`;
        return fetch(url).then(res => res.json()).then(data => {
            if (!data.features || data.features.length === 0) throw new Error("لم يتم العثور على المسار");
            const coords = data.features[0].geometry.coordinates.map(c => [c[1], c[0]]);
            return { coords, summary: data.features[0].properties.summary };
        });
    };

    window.drawRoute = function(coords) {
        if (routeLayer) map.removeLayer(routeLayer);
        routeLayer = L.polyline(coords, { color: "#2f3e9e", weight: 5, opacity: 0.7 }).addTo(map);
        map.fitBounds(routeLayer.getBounds());
    };

    // زر تحديد الموقع (كما هو مع تحديث بسيط)
    const btn = document.getElementById("useMyLocationBtn");
    if (btn) {
        btn.addEventListener("click", function () {
            if (!navigator.geolocation) return alert("المتصفح لا يدعم تحديد الموقع");
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>جاري التحديد...';
            btn.disabled = true;

            navigator.geolocation.getCurrentPosition((pos) => {
                const uLat = pos.coords.latitude;
                const uLng = pos.coords.longitude;
                if (!map) initMap();
                if (userMarker) map.removeLayer(userMarker);
                userMarker = L.marker([uLat, uLng]).addTo(map).bindPopup("📍 موقعك الحالي").openPopup();

                window.calculateRoute(uLat, uLng, govLat, govLng)
                    .then(res => {
                        window.drawRoute(res.coords);
                        const rInfo = document.getElementById("routeInfo");
                        if (rInfo) {
                            rInfo.classList.remove("d-none");
                            document.getElementById("distanceText").innerText = (res.summary.distance / 1000).toFixed(2) + " كم";
                            document.getElementById("timeText").innerText = Math.ceil(res.summary.duration / 60) + " دقيقة";
                        }
                    })
                    .finally(() => {
                        btn.innerHTML = '<i class="fas fa-location-dot me-2"></i>استخدم موقعي الحالي';
                        btn.disabled = false;
                    });
            }, (err) => {
                alert("تعذر الوصول لموقعك: " + err.message);
                btn.disabled = false;
            });
        });
    }
});
