document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.getElementById("searchInput");
    const suggestionsBox = document.getElementById("suggestionsBox");
    const searchBtn = document.getElementById("searchBtn");

    let timeout = null;

    function updateSuggestions() {
        if (!searchInput) return;

        const query = searchInput.value.trim();

        // إذا كان النص فارغاً، نخفي الاقتراحات
        if (query.length === 0) {
            if (suggestionsBox) {
                suggestionsBox.innerHTML = "";
                suggestionsBox.classList.add("d-none");
            }
            return;
        }

        // إرسال طلب الاقتراحات (بدون فلتر تصنيف)
        fetch(`/search/suggestions?query=${encodeURIComponent(query)}`)
            .then(res => res.json())
            .then(data => {
                if (!suggestionsBox) return;

                if (data.length === 0) {
                    suggestionsBox.innerHTML = '<div class="suggestion-item text-muted">لا توجد نتائج</div>';
                    suggestionsBox.classList.remove("d-none");
                } else {
                    suggestionsBox.innerHTML = data.map(item => {
                        const isGovernment = item.type === "government";
                        const icon = isGovernment ? '<i class="fas fa-building"></i>' : '<i class="fas fa-concierge-bell"></i>';
                        const ratingHtml = item.rating && item.rating > 0 ? `
                            <div class="suggestion-rating">
                                <span class="text-warning small">
                                    ${'★'.repeat(Math.floor(item.rating))}${'☆'.repeat(5 - Math.floor(item.rating))}
                                </span>
                                <span class="text-muted small ms-1">(${item.rating})</span>
                            </div>
                        ` : '';

                        return `<div class="suggestion-item" data-id="${item.id}" data-type="${item.type}">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="suggestion-icon ${isGovernment ? 'government' : 'service'}">${icon}</div>
                                        <div class="flex-grow-1">
                                            <div class="fw-bold">${escapeHtml(item.name)}</div>
                                            <div class="small text-muted">${isGovernment ? 'جهة حكومية' : 'خدمة'}</div>
                                            ${ratingHtml}
                                        </div>
                                    </div>
                                </div>`;
                    }).join("");
                    suggestionsBox.classList.remove("d-none");

                    document.querySelectorAll(".suggestion-item").forEach(el => {
                        el.addEventListener("click", function () {
                            const id = this.dataset.id;
                            const type = this.dataset.type;
                            if (type === "government") {
                                window.location.href = `/governments/${id}`;
                            } else {
                                window.location.href = `/services/${id}`;
                            }
                        });
                    });
                }

                // تحديث نصائح البحث السريعة (إذا وجدت)
                const tipsContainer = document.querySelector('.search-tips .d-flex');
                if (tipsContainer) {
                    const labelSpan = tipsContainer.querySelector('small.text-muted');
                    const oldTips = tipsContainer.querySelectorAll('.quick-suggestion');
                    oldTips.forEach(tip => tip.remove());

                    data.slice(0, 5).forEach(item => {
                        const a = document.createElement('a');
                        const icon = document.createElement('i');

                        if (item.type === 'government') {
                            a.href = `/governments/${item.id}`;
                            icon.className = "fas fa-building me-1";
                            a.title = `جهة حكومية - ${item.name}`;
                        } else {
                            a.href = `/services/${item.id}`;
                            icon.className = "fas fa-concierge-bell me-1";
                            a.title = `خدمة - ${item.name}`;
                        }

                        a.className = "badge bg-light text-dark text-decoration-none quick-suggestion me-2 mb-2";
                        a.appendChild(icon);
                        a.appendChild(document.createTextNode(item.name));

                        a.addEventListener('click', function(e) {
                            e.preventDefault();
                            window.location.href = this.href;
                        });

                        if (labelSpan && labelSpan.nextSibling) {
                            tipsContainer.insertBefore(a, labelSpan.nextSibling);
                        } else {
                            tipsContainer.appendChild(a);
                        }
                    });
                }
            })
            .catch(error => console.error('Error fetching suggestions:', error));
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

    // الاقتراحات عند الكتابة
    if (searchInput) {
        searchInput.addEventListener("input", function () {
            clearTimeout(timeout);
            timeout = setTimeout(updateSuggestions, 200);
        });
    }

    // زر البحث
    if (searchBtn) {
        searchBtn.addEventListener("click", function () {
            const query = searchInput ? searchInput.value.trim() : '';
            if (!query) return;
            window.location.href = `/search?query=${encodeURIComponent(query)}`;
        });
    }

    // الضغط على Enter
    if (searchInput) {
        searchInput.addEventListener("keypress", function (e) {
            if (e.key === "Enter") {
                e.preventDefault();
                if (searchBtn) searchBtn.click();
            }
        });
    }

    // إغلاق الاقتراحات عند النقر خارجها
    document.addEventListener("click", function (e) {
        if (searchInput && suggestionsBox && !searchInput.contains(e.target) && !suggestionsBox.contains(e.target)) {
            suggestionsBox.classList.add("d-none");
        }
    });
});

// معرض الصور
const govThumbs = document.querySelectorAll('.gov-thumb');
if (govThumbs.length > 0) {
    govThumbs.forEach(img => {
        img.addEventListener('click', function() {
            const previewImage = document.getElementById('previewImage');
            if (previewImage) {
                previewImage.src = this.dataset.img;
            }
        });
    });
}

// الشريط الجانبي
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    const toggleBtn = document.getElementById('sidebarToggle');
    const closeBtn = document.getElementById('closeSidebar');

    if (!sidebar || !toggleBtn) return;

    function openSidebar() {
        sidebar.classList.add('open');
        if (overlay) overlay.classList.add('active');
    }

    function closeSidebar() {
        sidebar.classList.remove('open');
        if (overlay) overlay.classList.remove('active');
    }

    toggleBtn.addEventListener('click', openSidebar);

    if (closeBtn) {
        closeBtn.addEventListener('click', closeSidebar);
    }

    if (overlay) {
        overlay.addEventListener('click', closeSidebar);
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeSidebar();
        }
    });
});

