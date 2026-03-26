<div class="search-bar-component">
    <div class="search-container">
        <div class="search-card">
            <!-- حقل البحث الرئيسي -->
            <div class="search-input-group">
                <div class="search-input-wrapper">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" class="search-field" id="searchInput"
                        placeholder="ابحث عن خدمة أو جهة..." autocomplete="off">
                    <button type="button" class="search-clear-btn" id="clearSearchBtn" style="display: none;">
                        <i class="fas fa-times-circle"></i>
                    </button>
                </div>

                <!-- فلتر التصنيف -->
                <div class="category-filter-wrapper">
                    <select class="category-filter" id="categoryFilter">
                        <option value="">جميع التصنيفات</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    <i class="fas fa-chevron-down filter-arrow"></i>
                </div>

                <button type="button" class="search-submit-btn" id="searchBtn">
                    <i class="fas fa-search"></i>
                    <span>بحث</span>
                </button>
            </div>

            <!-- صندوق الاقتراحات -->
            <div id="suggestionsBox" class="suggestions-panel d-none"></div>
        </div>
    </div>
</div>

<style>
    /* ===== شريط البحث الجديد ===== */
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
        border-radius: 20px;
        box-shadow: 0 8px 24px rgba(0,0,0,0.08);
        padding: 8px;
        transition: all 0.3s;
    }

    .search-card:focus-within {
        box-shadow: 0 12px 32px rgba(47,62,158,0.15);
    }

    /* مجموعة البحث */
    .search-input-group {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    /* حقل البحث */
    .search-input-wrapper {
        flex: 1;
        position: relative;
        min-width: 180px;
    }

    .search-icon {
        position: absolute;
        right: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #aaa;
        font-size: 16px;
        pointer-events: none;
    }

    .search-field {
        width: 100%;
        padding: 12px 42px 12px 12px;
        border: 1px solid #e0e0e0;
        border-radius: 14px;
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
        box-shadow: 0 0 0 3px rgba(47,62,158,0.1);
    }

    .search-clear-btn {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: #999;
        cursor: pointer;
        font-size: 16px;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .search-clear-btn:hover {
        color: #dc3545;
    }

    /* فلتر التصنيف */
    .category-filter-wrapper {
        position: relative;
        min-width: 130px;
    }

    .category-filter {
        width: 100%;
        padding: 12px 32px 12px 12px;
        border: 1px solid #e0e0e0;
        border-radius: 14px;
        font-size: 14px;
        font-family: "Cairo", sans-serif;
        background: #fafafa;
        appearance: none;
        cursor: pointer;
        transition: all 0.2s;
        text-align: right;
        direction: rtl;
    }

    .category-filter:focus {
        outline: none;
        border-color: #2f3e9e;
        background: #fff;
    }

    .filter-arrow {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #aaa;
        font-size: 12px;
        pointer-events: none;
    }

    /* زر البحث */
    .search-submit-btn {
        background: linear-gradient(135deg, #2f3e9e, #5a6fc9);
        border: none;
        border-radius: 14px;
        padding: 12px 20px;
        color: white;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 8px;
        white-space: nowrap;
    }

    .search-submit-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(47,62,158,0.3);
    }

    .search-submit-btn:active {
        transform: translateY(0);
    }

    /* صندوق الاقتراحات */
    .suggestions-panel {
        margin-top: 12px;
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 8px 24px rgba(0,0,0,0.12);
        max-height: 300px;
        overflow-y: auto;
        z-index: 1050;
        border: 1px solid #eee;
    }

    .suggestions-panel .suggestion-item {
        padding: 12px 16px;
        border-bottom: 1px solid #f0f0f0;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .suggestions-panel .suggestion-item:last-child {
        border-bottom: none;
    }

    .suggestions-panel .suggestion-item:hover {
        background: #f8f9ff;
    }

    .suggestion-icon {
        width: 32px;
        height: 32px;
        background: #f0f2ff;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #2f3e9e;
    }

    .suggestion-info {
        flex: 1;
    }

    .suggestion-name {
        font-weight: 500;
        color: #1a2c3e;
    }

    .suggestion-type {
        font-size: 11px;
        color: #888;
    }

    /* تحسين للهواتف */
    @media (max-width: 640px) {
        .search-container {
            padding: 0 12px;
        }

        .search-input-group {
            flex-direction: column;
            gap: 10px;
        }

        .search-input-wrapper {
            width: 100%;
        }

        .category-filter-wrapper {
            width: 100%;
        }

        .search-submit-btn {
            width: 100%;
            justify-content: center;
            padding: 10px;
        }

        .search-submit-btn span {
            display: inline;
        }

        .search-card {
            padding: 12px;
        }

        .search-field {
            padding: 12px 42px 12px 12px;
        }

        .category-filter {
            padding: 12px 32px 12px 12px;
        }
    }

    /* للأجهزة اللوحية */
    @media (min-width: 641px) and (max-width: 768px) {
        .search-input-group {
            flex-wrap: nowrap;
        }

        .category-filter-wrapper {
            min-width: 120px;
        }

        .search-submit-btn span {
            display: none;
        }

        .search-submit-btn {
            padding: 12px 16px;
        }
    }

    /* للشاشات الكبيرة */
    @media (min-width: 769px) {
        .search-submit-btn span {
            display: inline;
        }

        .category-filter-wrapper {
            min-width: 140px;
        }

        .search-field {
            padding: 14px 46px 14px 14px;
        }

        .category-filter {
            padding: 14px 36px 14px 14px;
        }

        .filter-arrow {
            right: 14px;
        }

        .search-submit-btn {
            padding: 14px 24px;
        }
    }
</style>
<script>
    // إظهار/إخفاء زر مسح النص
    const searchInput = document.getElementById('searchInput');
    const clearBtn = document.getElementById('clearSearchBtn');

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
            // إخفاء الاقتراحات
            const suggestionsBox = document.getElementById('suggestionsBox');
            if (suggestionsBox) {
                suggestionsBox.innerHTML = '';
                suggestionsBox.classList.add('d-none');
            }
        });
    }
</script>
