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

    // مفتاح ORS
    //  const ORS_API_KEY =  "eyJvcmciOiI1YjNjZTM1OTc4NTExMTAwMDFjZjYyNDgiLCJpZCI6Ijk2NzIxMjI1NTk4ODQ5NTI5MjA0Y2U2MTc2YzVkNzU3IiwiaCI6Im11cm11cjY0In0=";
    const ORS_API_KEY =
        window.ORS_API_KEY ||
        "eyJvcmciOiI1YjNjZTM1OTc4NTExMTAwMDFjZjYyNDgiLCJpZCI6Ijk2NzIxMjI1NTk4ODQ5NTI5MjA0Y2U2MTc2YzVkNzU3IiwiaCI6Im11cm11cjY0In0=";
    function initMap() {
        if (!govLat || !govLng) {
            const mapContainer = document.getElementById("map");
            if (mapContainer) {
                mapContainer.innerHTML =
                    '<div class="p-5 text-center text-muted"><i class="fas fa-map-marker-alt me-2"></i>لم يتم تحديد موقع هذه الجهة بعد</div>';
            }
            return;
        }

        map = L.map("map").setView([govLat, govLng], 14);
        window.mapInstance = map;

        L.tileLayer(
            "https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png",
            {
                attribution:
                    '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> &copy; <a href="https://carto.com/attributions">CARTO</a>',
                subdomains: "abcd",
                maxZoom: 19,
            },
        ).addTo(map);

        govMarker = L.marker([govLat, govLng])
            .addTo(map)
            .bindPopup(`<b>${govName}</b>`)
            .openPopup();
    }

    function calculateRoute(
        userLat,
        userLng,
        govLat,
        govLng,
        profile = "driving-car",
    ) {
        const url = `https://api.openrouteservice.org/v2/directions/${profile}?api_key=${ORS_API_KEY}&start=${userLng},${userLat}&end=${govLng},${govLat}`;

        console.log("جاري حساب المسار:", profile);

        return fetch(url)
            .then((res) => {
                if (!res.ok) throw new Error(`HTTP ${res.status}`);
                return res.json();
            })
            .then((data) => {
                if (!data.features || data.features.length === 0)
                    throw new Error("لم يتم العثور على المسار");
                const coords = data.features[0].geometry.coordinates.map(
                    (c) => [c[1], c[0]],
                );
                const summary = data.features[0].properties.summary;
                console.log("المسار محسوب:", profile, summary.distance, "متر");
                return { coords, summary };
            });
    }

    function drawRoute(coords) {
        if (routeLayer) map.removeLayer(routeLayer);
        routeLayer = L.polyline(coords, {
            color: "#2f3e9e",
            weight: 5,
            opacity: 0.7,
        }).addTo(map);
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

    // إذا لم يكن المستخدم مسجلاً، نعرض الخريطة فقط ونخرج
    if (!isLoggedIn) {
        initMap();
        return;
    }

    // بقية الكود للمستخدمين المسجلين فقط
    let selectedProfile = "driving-car";

    // اختيار نوع التنقل
    const directionBtns = document.querySelectorAll(".direction-btn");
    directionBtns.forEach((btn) => {
        btn.addEventListener("click", function () {
            directionBtns.forEach((b) =>
                b.classList.remove("direction-active"),
            );
            this.classList.add("direction-active");
            selectedProfile =
                this.getAttribute("data-profile") || "driving-car";
            console.log("تم تغيير نوع التنقل إلى:", selectedProfile);
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

            btn.innerHTML =
                '<i class="fas fa-spinner fa-spin me-2"></i>جاري تحديد موقعك...';
            btn.disabled = true;

            navigator.geolocation.getCurrentPosition(
                (pos) => {
                    const userLat = pos.coords.latitude;
                    const userLng = pos.coords.longitude;

                    if (!map) initMap();

                    if (userMarker) map.removeLayer(userMarker);

                    userMarker = L.marker([userLat, userLng])
                        .addTo(map)
                        .bindPopup("📍 موقعك الحالي")
                        .openPopup();

                    calculateRoute(
                        userLat,
                        userLng,
                        govLat,
                        govLng,
                        selectedProfile,
                    )
                        .then((res) => {
                            drawRoute(res.coords);

                            const routeInfo =
                                document.getElementById("routeInfo");
                            if (routeInfo) {
                                routeInfo.classList.remove("d-none");
                                document.getElementById(
                                    "distanceText",
                                ).innerText =
                                    (res.summary.distance / 1000).toFixed(2) +
                                    " كم";
                                document.getElementById("timeText").innerText =
                                    Math.ceil(res.summary.duration / 60) +
                                    " دقيقة";
                            }

                            btn.innerHTML =
                                '<i class="fas fa-location-dot me-2"></i>استخدم موقعي الحالي';
                            btn.disabled = false;
                        })
                        .catch((err) => {
                            console.error("Route error:", err);
                            alert("تعذر حساب المسار: " + err.message);
                            btn.innerHTML =
                                '<i class="fas fa-location-dot me-2"></i>استخدم موقعي الحالي';
                            btn.disabled = false;
                        });
                },
                (err) => {
                    alert("تعذر الوصول لموقعك الحالي: " + err.message);
                    btn.innerHTML =
                        '<i class="fas fa-location-dot me-2"></i>استخدم موقعي الحالي';
                    btn.disabled = false;
                },
            );
        });
    }

    // تهيئة الخريطة فوراً إذا كانت البطاقة مفتوحة
    setTimeout(() => {
        if (
            document.getElementById("locationCard")?.classList.contains("show")
        ) {
            initMap();
        }
    }, 100);
    // دالة عامة لحساب ورسم المسار (يمكن استدعاؤها من أي مكان)
    window.calculateRouteAndDraw = function (
        startLat,
        startLng,
        endLat,
        endLng,
        profile = "driving-car",
    ) {
        if (!window.mapInstance) return;

        // استخدام دالة calculateRoute من map.js
        if (typeof calculateRoute === "undefined") {
            // تعريف دالة calculateRoute إذا لم تكن موجودة
            const ORS_API_KEY =
                "5b3ce3597851110001cf6248e9b2c8f95d32449f86b7b3b1f3e2b3e1";

            window.calculateRoute = function (
                userLat,
                userLng,
                govLat,
                govLng,
                profile = "driving-car",
            ) {
                const url = `https://api.openrouteservice.org/v2/directions/${profile}?api_key=${ORS_API_KEY}&start=${userLng},${userLat}&end=${govLng},${govLat}`;
                return fetch(url)
                    .then((res) => res.json())
                    .then((data) => {
                        if (!data.features || data.features.length === 0)
                            throw new Error("لم يتم العثور على المسار");
                        const coords =
                            data.features[0].geometry.coordinates.map((c) => [
                                c[1],
                                c[0],
                            ]);
                        const summary = data.features[0].properties.summary;
                        return { coords, summary };
                    });
            };

            window.drawRoute = function (coords) {
                if (window.routeLayer)
                    window.mapInstance.removeLayer(window.routeLayer);
                window.routeLayer = L.polyline(coords, {
                    color: "#2f3e9e",
                    weight: 5,
                    opacity: 0.8,
                    lineJoin: "round",
                }).addTo(window.mapInstance);
                window.mapInstance.fitBounds(window.routeLayer.getBounds());
            };
        }

        // حساب المسار
        return window
            .calculateRoute(startLat, startLng, endLat, endLng, profile)
            .then((res) => {
                window.drawRoute(res.coords);

                // عرض معلومات المسافة والزمن
                const routeInfo = document.getElementById("routeInfo");
                if (routeInfo) {
                    routeInfo.classList.remove("d-none");
                    document.getElementById("distanceText").innerText =
                        (res.summary.distance / 1000).toFixed(2) + " كم";
                    document.getElementById("timeText").innerText =
                        Math.ceil(res.summary.duration / 60) + " دقيقة";
                }

                return res;
            })
            .catch((error) => {
                console.error("Route error:", error);
                // إذا فشل API، ارسم خطاً مستقيماً (بديل)
                drawStraightLineFallback(startLat, startLng, endLat, endLng);
            });
    };

    // دالة احتياطية لرسم خط مستقيم
    function drawStraightLineFallback(startLat, startLng, endLat, endLng) {
        const coords = [
            [startLat, startLng],
            [endLat, endLng],
        ];
        if (window.routeLayer)
            window.mapInstance.removeLayer(window.routeLayer);
        window.routeLayer = L.polyline(coords, {
            color: "#dc3545",
            weight: 4,
            opacity: 0.8,
            dashArray: "8, 8",
        }).addTo(window.mapInstance);
        window.mapInstance.fitBounds(window.routeLayer.getBounds());

        // حساب المسافة التقريبية
        const distance = calculateDistance(startLat, startLng, endLat, endLng);
        const duration = Math.round(distance * 3);

        const routeInfo = document.getElementById("routeInfo");
        if (routeInfo) {
            routeInfo.classList.remove("d-none");
            document.getElementById("distanceText").innerText =
                distance.toFixed(2) + " كم";
            document.getElementById("timeText").innerText = duration + " دقيقة";
        }
    }

    function calculateDistance(lat1, lng1, lat2, lng2) {
        const R = 6371;
        const dLat = ((lat2 - lat1) * Math.PI) / 180;
        const dLng = ((lng2 - lng1) * Math.PI) / 180;
        const a =
            Math.sin(dLat / 2) * Math.sin(dLat / 2) +
            Math.cos((lat1 * Math.PI) / 180) *
                Math.cos((lat2 * Math.PI) / 180) *
                Math.sin(dLng / 2) *
                Math.sin(dLng / 2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        return R * c;
    }
});
