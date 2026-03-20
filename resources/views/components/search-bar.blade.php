<div class="search-bar-component">
    <div class="search-bar-container">
        <div class="search-box d-flex align-items-center">
            <div class="category-select-wrapper">
                <select class="form-select category-select" id="categoryFilter">
                    <option value="">التصنيف</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
                <span class="custom-select-arrow">
                    <i class="fas fa-chevron-down"></i>
                </span>
            </div>

            <input type="text" class="form-control search-input" id="searchInput"
                placeholder="ابحث عن خدمة أو جهة..." autocomplete="off">

            <button type="button" class="btn search-btn" id="searchBtn">
                <i class="fas fa-search"></i>
            </button>
        </div>

        <div class="position-relative">
            <div id="suggestionsBox" class="suggestions-box d-none"></div>
        </div>
    </div>
</div>


