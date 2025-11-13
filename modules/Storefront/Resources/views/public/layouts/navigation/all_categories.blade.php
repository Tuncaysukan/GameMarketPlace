<div class="all-categories-wrap">
    <div class="all-categories-content">
        <div class="all-categories-header">
            <h3>{{ trans('storefront::layouts.all_categories') }}</h3>
            <div class="all-categories-search">
                <input type="text" class="search-input" placeholder="{{ trans('storefront::layouts.search_categories') }}" id="category-search">
                <a href="{{ route('categories.index') }}" class="all-categories-btn">
                    {{ trans('storefront::layouts.all_categories') }}
                    <i class="las la-arrow-right"></i>
                </a>
            </div>
        </div>
        
        <div class="all-categories-grid" id="categories-grid">
            @foreach ($categories as $category)
                <a href="{{ $category->url() }}" 
                   class="category-item" 
                   data-category-name="{{ strtolower($category->name) }}"
                   style="
                       @if($category->background_color()) background-color: {{ $category->background_color() }}; @endif
                       @if($category->text_color()) color: {{ $category->text_color() }}; @endif
                   "
                   data-hover-bg="{{ $category->hover_background_color() ?? '' }}"
                   data-hover-text="{{ $category->hover_text_color() ?? '' }}"
                   onmouseover="this.style.setProperty('--hover-bg', '{{ $category->hover_background_color() ?? '' }}'); this.style.setProperty('--hover-text', '{{ $category->hover_text_color() ?? '' }}');"
                >
                    <div class="category-icon">
                        @if ($category->logo && $category->logo->exists)
                            <img src="{{ $category->logo->path }}" alt="{{ $category->name }}" class="category-logo">
                        @else
                            <i class="las la-folder"></i>
                        @endif
                    </div>
                    <div class="category-name">{{ $category->name }}</div>
                </a>
            @endforeach
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('category-search');
    const categoriesGrid = document.getElementById('categories-grid');
    const categoryItems = categoriesGrid.querySelectorAll('.category-item');

    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        
        categoryItems.forEach(item => {
            const categoryName = item.getAttribute('data-category-name');
            if (categoryName.includes(searchTerm)) {
                item.style.display = 'flex';
            } else {
                item.style.display = 'none';
            }
        });
    });
});
</script>
