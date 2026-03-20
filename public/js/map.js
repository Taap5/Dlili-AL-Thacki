document.addEventListener("DOMContentLoaded", function () {

    if (!window.govData) return;

    let map = null;
    let govMarker = null;
    let userMarker = null;
    let routeLayer = null;

    const govLat = window.govData.lat;
    const govLng = window.govData.lng;
    const govName = window.govData.name;

    // مفتاح ORS
    const ORS_API_KEY = "eyJvcmciOiI1YjNjZTM1OTc4NTExMTAwMDFjZjYyNDgiLCJpZCI6Ijk2NzIxMjI1NTk4ODQ5NTI5MjA0Y2U2MTc2YzVkNzU3IiwiaCI6Im11cm11cjY0In0=";

    function initMap() {
        if (!govLat || !govLng) {
            document.getElementById("map").innerText = "لم يتم تحديد موقع هذه الجهة بعد";
            return;
        }

        map = L.map("map").setView([govLat, govLng], 14);
        window.mapInstance = map; // لإعادة التحجيم عند فتح الـ Accordion

        L.tileLayer(
            "https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png",
            { attribution: "&copy; OpenStreetMap" }
        ).addTo(map);

        govMarker = L.marker([govLat, govLng])
            .addTo(map)
            .bindPopup(govName)
            .openPopup();
    }

    function calculateRoute(userLat, userLng, govLat, govLng, profile = "driving-car") {
        const url = `https://api.openrouteservice.org/v2/directions/${profile}?api_key=${ORS_API_KEY}&start=${userLng},${userLat}&end=${govLng},${govLat}`;

        return fetch(url)
            .then(res => res.json())
            .then(data => {
                if (!data.features || data.features.length === 0) throw new Error("لم يتم العثور على المسار");
                const coords = data.features[0].geometry.coordinates.map(c => [c[1], c[0]]); // [lat, lng]
                const summary = data.features[0].properties.summary; // distance & duration
                return { coords, summary };
            });
    }

    function drawRoute(coords) {
        if (routeLayer) map.removeLayer(routeLayer);

        routeLayer = L.polyline(coords, { color: "blue", weight: 5, opacity: 0.7 }).addTo(map);

        map.fitBounds(routeLayer.getBounds());
    }

    // عند فتح بطاقة الموقع
    const locationCard = document.getElementById("locationCard");
    if (locationCard) {
        locationCard.addEventListener("shown.bs.collapse", function () {
            if (!map) initMap();
            else map.invalidateSize();
        });
    }

    let selectedProfile = "driving-car"; // الافتراضي سيارة

    // اختيار نوع التنقل
    document.querySelectorAll(".btn-group button").forEach(btn => {
        btn.addEventListener("click", function() {
            document.querySelectorAll(".btn-group button").forEach(b => b.classList.remove("active"));
            this.classList.add("active");
            selectedProfile = this.getAttribute("data-profile");
        });
    });

    // زر استخدام الموقع
    const btn = document.getElementById("useMyLocationBtn");

    if (btn) {
        btn.addEventListener("click", function () {

            if (!navigator.geolocation) {
                alert("المتصفح لا يدعم تحديد الموقع");
                return;
            }

            navigator.geolocation.getCurrentPosition(pos => {

                const userLat = pos.coords.latitude;
                const userLng = pos.coords.longitude;

                if (userMarker) map.removeLayer(userMarker);

                userMarker = L.marker([userLat, userLng])
                    .addTo(map)
                    .bindPopup("موقعك الحالي")
                    .openPopup();

                calculateRoute(userLat, userLng, govLat, govLng, selectedProfile)
                    .then(res => {
                        drawRoute(res.coords);

                        document.getElementById("routeInfo").classList.remove("d-none");
                        document.getElementById("distanceText").innerText = (res.summary.distance / 1000).toFixed(2) + " كم";
                        document.getElementById("timeText").innerText = Math.ceil(res.summary.duration / 60) + " دقيقة";
                    })
                    .catch(err => alert("تعذر حساب المسار: " + err.message));

            }, err => {
                alert("تعذر الوصول لموقعك الحالي: " + err.message);
            });
        });
    }

});
