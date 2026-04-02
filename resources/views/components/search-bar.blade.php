<div class="search-bar-component">
    <div class="search-container">
        <div class="search-card">
            <!-- حقل البحث الرئيسي -->
            <div class="search-input-group">
                <div class="search-input-wrapper">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" class="search-field" id="searchInput" placeholder="ابحث عن خدمة أو جهة..."
                        autocomplete="off">
                    <button type="button" class="search-clear-btn" id="clearSearchBtn" style="display: none;">
                        <i class="fas fa-times-circle"></i>
                    </button>
                </div>

                <button type="button" class="search-submit-btn" id="searchBtn">
                    <i class="fas fa-search"></i>
                    <span>بحث</span>
                </button>

                <!-- زر بحث متقدم -->
                <button type="button" class="advanced-search-btn" id="advancedSearchBtn">
                    <i class="fas fa-sliders-h"></i>
                    <span>بحث متقدم</span>
                </button>
            </div>

            <!-- صندوق الاقتراحات -->
            <div id="suggestionsBox" class="suggestions-panel d-none"></div>
        </div>
    </div>
</div>

<style>
    /* ===== شريط البحث المحسن ===== */
    .search-bar-component {
        width: 100%;
        margin: 0 auto;
    }

    .search-container {
        max-width: 800px;
        margin: 0 auto;
        padding: 0 16px;
    }

    .search-card {
        background: #fff;
        border-radius: 60px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
        padding: 6px;
        transition: all 0.3s ease;
        border: 1px solid rgba(47, 62, 158, 0.1);
    }

    .search-card:focus-within {
        box-shadow: 0 12px 32px rgba(47, 62, 158, 0.2);
        border-color: rgba(47, 62, 158, 0.3);
    }

    .search-input-group {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .search-input-wrapper {
        flex: 1;
        position: relative;
        min-width: 180px;
    }

    .search-icon {
        position: absolute;
        right: 18px;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
        font-size: 16px;
        pointer-events: none;
        transition: color 0.2s;
    }

    .search-field {
        width: 100%;
        padding: 14px 48px 14px 18px;
        border: 1px solid #e5e7eb;
        border-radius: 60px;
        font-size: 15px;
        font-family: "Cairo", sans-serif;
        transition: all 0.2s;
        background: #fafafa;
        text-align: right;
    }

    .search-field:focus {
        outline: none;
        border-color: #2f3e9e;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(47, 62, 158, 0.1);
    }

    .search-clear-btn {
        position: absolute;
        left: 18px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: #9ca3af;
        cursor: pointer;
        font-size: 16px;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: color 0.2s;
    }

    .search-clear-btn:hover {
        color: #dc3545;
    }

    /* زر البحث الرئيسي */
    .search-submit-btn {
        background: linear-gradient(135deg, #2f3e9e, #5a6fc9);
        border: none;
        border-radius: 60px;
        padding: 12px 28px;
        color: white;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 8px;
        white-space: nowrap;
        box-shadow: 0 2px 4px rgba(47, 62, 158, 0.2);
    }

    .search-submit-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(47, 62, 158, 0.3);
        background: linear-gradient(135deg, #1e2a6e, #2f3e9e);
    }

    .search-submit-btn:active {
        transform: translateY(0);
    }

    /* زر بحث متقدم */
    .advanced-search-btn {
        background: transparent;
        border: 1px solid #e5e7eb;
        border-radius: 60px;
        padding: 12px 20px;
        color: #2f3e9e;
        font-weight: 500;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 8px;
        white-space: nowrap;
        background: #fafafa;
    }

    .advanced-search-btn:hover {
        background: #f0f4ff;
        border-color: #2f3e9e;
        transform: translateY(-1px);
    }

    /* صندوق الاقتراحات */
    .suggestions-panel {
        margin-top: 12px;
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 12px 32px rgba(0, 0, 0, 0.12);
        max-height: 350px;
        overflow-y: auto;
        z-index: 1050;
        border: 1px solid #f0f0f0;
    }

    .suggestions-panel .suggestion-item {
        padding: 14px 20px;
        border-bottom: 1px solid #f0f0f0;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .suggestions-panel .suggestion-item:last-child {
        border-bottom: none;
    }

    .suggestions-panel .suggestion-item:hover {
        background: #f8f9ff;
    }

    .suggestion-icon {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        flex-shrink: 0;
    }

    .suggestion-icon.government {
        background: linear-gradient(135deg, #e3f2fd, #bbdef5);
        color: #1976d2;
    }

    .suggestion-icon.service {
        background: linear-gradient(135deg, #e8f5e9, #c8e6c9);
        color: #388e3c;
    }

    .suggestion-info {
        flex: 1;
    }

    .suggestion-name {
        font-weight: 600;
        color: #1a2c3e;
        font-size: 15px;
    }

    .suggestion-type {
        font-size: 11px;
        color: #888;
        margin-top: 2px;
    }

    .suggestion-rating {
        font-size: 11px;
        margin-top: 4px;
    }

    .suggestion-rating .text-warning {
        color: #ffc107;
    }

    /* تخصيص شريط التمرير */
    .suggestions-panel::-webkit-scrollbar {
        width: 6px;
    }

    .suggestions-panel::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .suggestions-panel::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 10px;
    }

    .suggestions-panel::-webkit-scrollbar-thumb:hover {
        background: #a1a1a1;
    }

    /* تحسين للهواتف */
    @media (max-width: 640px) {
        .search-container {
            padding: 0 12px;
        }

        .search-card {
            border-radius: 40px;
            padding: 10px;
        }

        .search-input-group {
            flex-direction: column;
            gap: 10px;
        }

        .search-input-wrapper {
            width: 100%;
        }

        .search-field {
            padding: 12px 44px 12px 16px;
        }

        .search-submit-btn {
            width: 100%;
            justify-content: center;
            padding: 10px;
        }

        .advanced-search-btn {
            width: 100%;
            justify-content: center;
            padding: 10px;
        }

        .search-submit-btn span {
            display: inline;
        }

        .advanced-search-btn span {
            display: inline;
        }

        .suggestion-icon {
            width: 36px;
            height: 36px;
            font-size: 14px;
        }

        .suggestion-name {
            font-size: 14px;
        }
    }

    @media (min-width: 641px) and (max-width: 768px) {
        .search-input-group {
            flex-wrap: nowrap;
        }

        .search-submit-btn span {
            display: none;
        }

        .advanced-search-btn span {
            display: none;
        }

        .search-submit-btn {
            padding: 12px 20px;
        }

        .advanced-search-btn {
            padding: 12px 18px;
        }
    }

    @media (min-width: 769px) {
        .search-submit-btn span {
            display: inline;
        }

        .advanced-search-btn span {
            display: inline;
        }

        .search-field {
            padding: 14px 52px 14px 20px;
        }

        .search-submit-btn {
            padding: 14px 32px;
        }

        .advanced-search-btn {
            padding: 14px 24px;
        }
    }
</style>

<script>
    // الحصول على العناصر
    const searchInput = document.getElementById('searchInput');
    const clearBtn = document.getElementById('clearSearchBtn');
    const suggestionsBox = document.getElementById('suggestionsBox');
    const searchBtn = document.getElementById('searchBtn');
    const advancedSearchBtn = document.getElementById('advancedSearchBtn');

    // إظهار/إخفاء زر مسح النص
    if (searchInput && clearBtn) {
        searchInput.addEventListener('input', function() {
            if (this.value.length > 0) {
                clearBtn.style.display = 'flex';
            } else {
                clearBtn.style.display = 'none';
            }
        });

        clearBtn.addEventListener('click', function() {
            searchInput.value = '';
            searchInput.focus();
            clearBtn.style.display = 'none';
            if (suggestionsBox) {
                suggestionsBox.innerHTML = '';
                suggestionsBox.classList.add('d-none');
            }
        });
    }

    // زر بحث متقدم - ينتقل لصفحة البحث المتقدم
    if (advancedSearchBtn) {
        advancedSearchBtn.addEventListener('click', function() {
            window.location.href = "{{ route('search.advanced') }}";
        });
    }

    // باقي الكود للاقتراحات وزر البحث موجود في app.js
</script>
