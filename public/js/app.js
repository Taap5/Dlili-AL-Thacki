document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.getElementById("searchInput");
    const suggestionsBox = document.getElementById("suggestionsBox");
    const searchBtn = document.getElementById("searchBtn");
    const categoryFilter = document.getElementById("categoryFilter");

    let timeout = null;

    function updateSuggestions() {
        if (!searchInput || !categoryFilter) return;

        const query = searchInput.value.trim();
        const category = categoryFilter.value;

        fetch(`/search/suggestions?query=${encodeURIComponent(query)}&category_id=${category}`)
            .then(res => res.json())
            .then(data => {
                if (!suggestionsBox) return;

                if (!query) {
                    suggestionsBox.innerHTML = "";
                    suggestionsBox.classList.add("d-none");
                } else {
                    suggestionsBox.innerHTML = data.map(item => {
                        const icon = item.type === "government" ? "🏛️" : "📝";
                        return `<div class="list-group-item suggestion-item" data-id="${item.id}" data-type="${item.type}">
                                    ${icon} ${item.name} — ${item.type === "government" ? "جهة" : "خدمة"}
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

                const tipsContainer = document.querySelector('.search-tips .d-flex');
                if (!tipsContainer) return;

                document.querySelectorAll('.quick-suggestion').forEach(tip => tip.remove());

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

                    tipsContainer.appendChild(a);
                });
            });
    }

    if (searchInput) {
        searchInput.addEventListener("input", function () {
            clearTimeout(timeout);
            timeout = setTimeout(updateSuggestions, 200);
        });
    }

    if (categoryFilter) {
        categoryFilter.addEventListener("change", updateSuggestions);
    }

    if (searchBtn) {
        searchBtn.addEventListener("click", function () {
            const query = searchInput ? searchInput.value.trim() : '';
            const category = categoryFilter ? categoryFilter.value : '';
            if (!query) return;
            window.location.href = `/search?query=${encodeURIComponent(query)}&category_id=${category}`;
        });
    }

    if (searchInput) {
        searchInput.addEventListener("keypress", function (e) {
            if (e.key === "Enter") {
                e.preventDefault();
                if (searchBtn) searchBtn.click();
            }
        });
    }

    document.addEventListener("click", function (e) {
        if (searchInput && suggestionsBox && !searchInput.contains(e.target) && !suggestionsBox.contains(e.target)) {
            suggestionsBox.classList.add("d-none");
        }
    });
});

// معرض الصور - فقط إذا كانت العناصر موجودة
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

// الشريط الجانبي - فقط إذا كانت العناصر موجودة
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
