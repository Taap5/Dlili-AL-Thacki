// ملف emergency.js - إدارة زر الطوارئ وأقرب المستشفيات

// فتح المودال والبحث عن أقرب المستشفيات
function openEmergencyModal() {
    const modal = document.getElementById('emergencyModal');
    if (!modal) return;

    // تشغيل صوت تنبيه
    playEmergencySound();

    modal.classList.add('show');
    document.getElementById('emergencyUserLocation').innerHTML = '<i class="fas fa-location-dot"></i><span>جاري تحديد موقعك...</span>';
    document.getElementById('emergencyResults').innerHTML = `
        <div class="emergency-loading">
            <i class="fas fa-spinner fa-spin"></i>
            <p>جاري البحث عن أقرب المستشفيات...</p>
        </div>
    `;

    // طلب موقع المستخدم
    if (!navigator.geolocation) {
        document.getElementById('emergencyUserLocation').innerHTML = '<i class="fas fa-exclamation-triangle"></i><span>المتصفح لا يدعم تحديد الموقع</span>';
        document.getElementById('emergencyResults').innerHTML = `
            <div class="emergency-loading">
                <i class="fas fa-exclamation-triangle"></i>
                <p>عذراً، متصفحك لا يدعم تحديد الموقع</p>
                <small>يرجى استخدام متصفح حديث</small>
            </div>
        `;
        return;
    }

    navigator.geolocation.getCurrentPosition(
        function(position) {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;

            console.log('موقعك:', lat, lng);

            // تحديث عرض الموقع
            document.getElementById('emergencyUserLocation').innerHTML = '<i class="fas fa-location-dot"></i><span>جاري البحث عن أقرب المستشفيات...</span>';

            // إرسال طلب للخادم
            fetch(`/emergency/nearest?lat=${lat}&lng=${lng}`)
                .then(response => response.json())
                .then(data => {
                    console.log('البيانات المستلمة:', data);

                    if (data.success && data.hospitals && data.hospitals.length > 0) {
                        displayHospitals(data);
                    } else {
                        document.getElementById('emergencyResults').innerHTML = `
                            <div class="emergency-loading">
                                <i class="fas fa-hospital"></i>
                                <p>لا توجد مستشفيات قريبة</p>
                                <small>لم يتم العثور على مستشفيات قريبة من موقعك</small>
                            </div>
                        `;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('emergencyResults').innerHTML = `
                        <div class="emergency-loading">
                            <i class="fas fa-exclamation-triangle"></i>
                            <p>حدث خطأ في البحث</p>
                            <small>يرجى المحاولة مرة أخرى</small>
                        </div>
                    `;
                });
        },
        function(error) {
            let errorMessage = "تعذر تحديد موقعك";
            if (error.code === 1) errorMessage = "الرجاء السماح بالوصول إلى موقعك";
            if (error.code === 2) errorMessage = "تعذر تحديد موقعك";
            if (error.code === 3) errorMessage = "انتهت مهلة تحديد الموقع";

            document.getElementById('emergencyUserLocation').innerHTML = `<i class="fas fa-exclamation-triangle"></i><span>${errorMessage}</span>`;
            document.getElementById('emergencyResults').innerHTML = `
                <div class="emergency-loading">
                    <i class="fas fa-exclamation-triangle"></i>
                    <p>${errorMessage}</p>
                    <small>يمكنك تحديث الموقع والمحاولة مرة أخرى</small>
                </div>
            `;
        }
    );
}

// عرض المستشفيات في المودال
function displayHospitals(data) {
    const container = document.getElementById('emergencyResults');

    // تحديث عرض الموقع
    document.getElementById('emergencyUserLocation').innerHTML = `<i class="fas fa-location-dot"></i><span>تم تحديد موقعك بنجاح</span>`;

    let html = '';
    data.hospitals.forEach((hospital, index) => {
        const rank = index + 1;
        const distance = hospital.distance ? hospital.distance.toFixed(1) : '?';
        const duration = hospital.duration ? Math.round(hospital.duration) : '?';

        html += `
            <div class="emergency-hospital-card">
                <div class="hospital-name">
                    <span class="hospital-rank">${rank}</span>
                    <span>${escapeHtml(hospital.name)}</span>
                    <span class="hospital-distance">${distance} كم</span>
                </div>
                <div class="hospital-details">
                    ${hospital.address ? `<span><i class="fas fa-map-marker-alt"></i> ${escapeHtml(hospital.address.substring(0, 50))}${hospital.address.length > 50 ? '...' : ''}</span>` : ''}
                    <span><i class="fas fa-clock"></i> ${hospital.work_hours || 'غير محدد'}</span>
                    ${duration ? `<span><i class="fas fa-hourglass-half"></i> ${duration} دقيقة</span>` : ''}
                </div>
                ${hospital.contact_number ? `
                    <div class="hospital-details">
                        <span><i class="fas fa-phone-alt"></i> ${hospital.contact_number}</span>
                    </div>
                ` : ''}
                <div class="hospital-actions">
                    <button class="btn-outline-danger" onclick="goToHospital(${hospital.id}, ${hospital.lat}, ${hospital.lng})">
                        <i class="fas fa-route"></i> عرض الاتجاهات
                    </button>
                    <a href="/governments/${hospital.id}" class="btn-outline-primary">
                        <i class="fas fa-info-circle"></i> تفاصيل الجهة
                    </a>
                </div>
            </div>
        `;
    });

    container.innerHTML = html;
}

// الانتقال إلى المستشفى مع فتح الخريطة
// الانتقال إلى المستشفى مع فتح الخريطة ورسم المسار
// الانتقال إلى المستشفى مع فتح الخريطة ورسم المسار
function goToHospital(hospitalId, hospitalLat, hospitalLng) {
    // الحصول على موقع المستخدم الحالي
    if (!navigator.geolocation) {
        window.location.href = `/governments/${hospitalId}?emergency=true`;
        return;
    }

    // إظهار رسالة تحميل
    const resultsContainer = document.getElementById('emergencyResults');
    if (resultsContainer) {
        resultsContainer.innerHTML = `
            <div class="emergency-loading">
                <i class="fas fa-spinner fa-spin"></i>
                <p>جاري تحضير الاتجاهات...</p>
            </div>
        `;
    }

    // الحصول على موقع المستخدم الحالي
    navigator.geolocation.getCurrentPosition(
        function(position) {
            const userLat = position.coords.latitude;
            const userLng = position.coords.longitude;

            // تخزين بيانات المسار في sessionStorage
            const routeData = {
                hospital: {
                    id: hospitalId,
                    lat: hospitalLat,
                    lng: hospitalLng
                },
                user: {
                    lat: userLat,
                    lng: userLng
                }
            };

            sessionStorage.setItem('emergency_route', JSON.stringify(routeData));

            // الانتقال إلى صفحة المستشفى مع معامل emergency
            window.location.href = `/governments/${hospitalId}?emergency=true`;
        },
        function(error) {
            // إذا فشل تحديد الموقع، ننتقل بدون بيانات المسار
            console.log('Failed to get location:', error);
            window.location.href = `/governments/${hospitalId}?emergency=true`;
        }
    );
}
// إغلاق المودال
function closeEmergencyModal() {
    const modal = document.getElementById('emergencyModal');
    if (modal) {
        modal.classList.remove('show');
    }
}

// تحديث الموقع وإعادة البحث
function refreshEmergencyLocation() {
    openEmergencyModal();
}

// تشغيل صوت تنبيه باستخدام Web Audio API
function playEmergencySound() {
    try {
        const audioContext = new (window.AudioContext || window.webkitAudioContext)();
        const oscillator = audioContext.createOscillator();
        const gainNode = audioContext.createGain();

        oscillator.connect(gainNode);
        gainNode.connect(audioContext.destination);

        oscillator.frequency.value = 880;
        gainNode.gain.value = 0.3;

        oscillator.start();
        gainNode.gain.exponentialRampToValueAtTime(0.00001, audioContext.currentTime + 0.5);
        oscillator.stop(audioContext.currentTime + 0.5);
    } catch(e) {
        console.log('Audio not supported');
    }
}

function escapeHtml(str) {
    if (!str) return '';
    return String(str).replace(/[&<>]/g, function(m) {
        if (m === '&') return '&amp;';
        if (m === '<') return '&lt;';
        if (m === '>') return '&gt;';
        return m;
    });
}

// ربط زر الطوارئ عند تحميل الصفحة
document.addEventListener('DOMContentLoaded', function() {
    const emergencyBtn = document.getElementById('emergencyBtn');
    if (emergencyBtn) {
        emergencyBtn.addEventListener('click', openEmergencyModal);
    }

    // إغلاق المودال عند الضغط على ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeEmergencyModal();
        }
    });

    // إغلاق المودال عند الضغط خارج المحتوى
    window.onclick = function(event) {
        const modal = document.getElementById('emergencyModal');
        if (event.target === modal) {
            closeEmergencyModal();
        }
    };
});
