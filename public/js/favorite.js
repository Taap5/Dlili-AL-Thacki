// ملف favorite.js - معالجة أزرار المفضلة

document.addEventListener("DOMContentLoaded", function() {
    console.log("favorite.js loaded");

    document.querySelectorAll('.favorite-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            const id = this.dataset.id;
            const type = this.dataset.type;
            const url = this.dataset.url;
            const btnElement = this;
            const icon = btnElement.querySelector('i');
            const span = btnElement.querySelector('span');

            console.log("Button clicked:", {id, type, url});

            if (!url) {
                console.error("URL is missing!");
                showToast("حدث خطأ: الرابط غير موجود", "danger");
                return;
            }

            btnElement.disabled = true;
            btnElement.style.opacity = '0.6';

            let requestBody = {};
            if (type === 'government') {
                requestBody.government_id = id;
            } else {
                requestBody.service_id = id;
            }

            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(requestBody)
            })
            .then(response => {
                console.log("Response status:", response.status);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log("Response data:", data);
                if (data.success) {
                    if (data.is_favorited) {
                        icon.className = 'fas fa-heart me-1';
                        span.textContent = 'تمت الإضافة';
                        btnElement.classList.remove('btn-outline-danger');
                        btnElement.classList.add('btn-danger');
                    } else {
                        icon.className = 'fas fa-heart-broken me-1';
                        span.textContent = 'أضف إلى المفضلة';
                        btnElement.classList.remove('btn-danger');
                        btnElement.classList.add('btn-outline-danger');
                    }
                    showToast(data.message, 'success');
                } else {
                    showToast(data.message || 'حدث خطأ، يرجى المحاولة مرة أخرى', 'danger');
                }
            })
            .catch(error => {
                console.error('Fetch Error:', error);
                showToast('حدث خطأ في الاتصال، يرجى المحاولة مرة أخرى', 'danger');
            })
            .finally(() => {
                btnElement.disabled = false;
                btnElement.style.opacity = '1';
            });
        });
    });
});

function showToast(message, type = 'success') {
    const existingToast = document.querySelector('.toast-message');
    if (existingToast) {
        existingToast.remove();
    }

    const toast = document.createElement('div');
    toast.className = `toast-message alert alert-${type} alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3`;
    toast.style.zIndex = '9999';
    toast.style.minWidth = '250px';
    toast.style.textAlign = 'center';
    toast.style.boxShadow = '0 4px 12px rgba(0,0,0,0.15)';
    toast.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    document.body.appendChild(toast);

    setTimeout(() => {
        if (toast) toast.remove();
    }, 3000);
}
