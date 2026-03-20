document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.getElementById("searchInput");
    const suggestionsBox = document.getElementById("suggestionsBox");
    const searchBtn = document.getElementById("searchBtn");
    const categoryFilter = document.getElementById("categoryFilter");

    let timeout = null;

    function updateSuggestions() {
        const query = searchInput.value.trim();
        const category = categoryFilter.value;

        fetch(`/search/suggestions?query=${encodeURIComponent(query)}&category_id=${category}`)
            .then(res => res.json())
            .then(data => {
                // تحديث صندوق الاقتراحات اللحظية
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

                    // حدث النقر على الاقتراح
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

                // تحديث نصائح البحث السريعة مع الأيقونات
                const tipsContainer = document.querySelector('.search-tips .d-flex');
                if (!tipsContainer) return;

                // مسح القديم
                document.querySelectorAll('.quick-suggestion').forEach(tip => tip.remove());

                // إضافة 5 اقتراحات جديدة مع الأيقونات
                data.slice(0, 5).forEach(item => {
                    const a = document.createElement('a');
                    const icon = document.createElement('i');

                    // تعيين الأيقونة بناءً على النوع
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

                    // منع السلوك الافتراضي للرابط وإضافة حدث النقر
                    a.addEventListener('click', function(e) {
                        e.preventDefault();
                        window.location.href = this.href;
                    });

                    tipsContainer.appendChild(a);
                });
            });
    }

    // بقية الكود كما هو...
    searchInput.addEventListener("input", function () {
        clearTimeout(timeout);
        timeout = setTimeout(updateSuggestions, 200);
    });

    categoryFilter.addEventListener("change", updateSuggestions);

    searchBtn.addEventListener("click", function () {
        const query = searchInput.value.trim();
        const category = categoryFilter.value;
        if (!query) return;
        window.location.href = `/search?query=${encodeURIComponent(query)}&category_id=${category}`;
    });

    searchInput.addEventListener("keypress", function (e) {
        if (e.key === "Enter") {
            e.preventDefault();
            searchBtn.click();
        }
    });

    document.addEventListener("click", function (e) {
        if (!searchInput.contains(e.target) && !suggestionsBox.contains(e.target)) {
            suggestionsBox.classList.add("d-none");
        }
    });
});

document.querySelectorAll('.gov-thumb').forEach(img => {
    img.addEventListener('click', function() {
        document.getElementById('previewImage').src = this.dataset.img;
    });
});

