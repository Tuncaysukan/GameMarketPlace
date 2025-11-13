Alpine.data("CategoryShow", ({ category, subCategories }) => ({
    category: category,
    subCategories: subCategories,
    
    get hasSubCategories() {
        return this.subCategories.length > 0;
    },
    
    get categoryImage() {
        return this.category.category_image?.exists 
            ? this.category.category_image.path 
            : null;
    },
    
    get categoryBanner() {
        return this.category.banner?.exists 
            ? this.category.banner.path 
            : null;
    },
    
    get placeholderImage() {
        // 3:4 oranında placeholder resim URL'i - resim ikonu ile
        return 'data:image/svg+xml;base64,' + btoa(`
            <svg width="300" height="400" viewBox="0 0 300 400" fill="none" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <linearGradient id="bg" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" style="stop-color:#f8f9fa" />
                        <stop offset="50%" style="stop-color:#e9ecef" />
                        <stop offset="100%" style="stop-color:#dee2e6" />
                    </linearGradient>
                </defs>
                <rect width="300" height="400" fill="url(#bg)"/>
                <g transform="translate(150,200) scale(0.15,0.15) translate(-256,-256)">
                    <path d="M169.7,170.2c0,14-4.7,26.1-14.9,36.4c-10.3,10.3-22.4,14.9-36.4,14.9s-26.1-4.7-36.4-14.9s-14.9-22.4-14.9-36.4s4.7-26.1,14.9-36.4s22.4-14.9,36.4-14.9s26.1,4.7,36.4,14.9C165.1,144.1,169.7,156.2,169.7,170.2z M443.9,272.8v119.4H67.1v-51.3l85.8-84.9l42.9,42.9l137.1-137.1L443.9,272.8z M469.1,85.3H42c-1.9,0-4.7,0.9-5.6,2.8c-1.9,1.9-2.8,3.7-2.8,5.6v324.5c0,1.9,0.9,4.7,2.8,5.6c1.9,1.9,3.7,2.8,5.6,2.8h427.1c1.9,0,4.7-0.9,5.6-2.8c1.9-1.9,2.8-3.7,2.8-5.6V93.7c0-1.9-0.9-4.7-2.8-5.6C473.8,86.3,471.9,85.3,469.1,85.3z M512,93.7v324.5c0,12.1-3.7,21.4-12.1,29.8c-8.4,8.4-18.7,12.1-29.8,12.1H42c-12.1,0-21.4-3.7-29.8-12.1C3.7,439.7,0,429.5,0,418.3V93.7c0-12.1,3.7-21.4,12.1-29.8c8.4-8.4,18.7-12.1,29.8-12.1h427.1c12.1,0,21.4,3.7,29.8,12.1C508.3,71.3,512,81.6,512,93.7z" 
                          fill="#adb5bd" opacity="0.6"/>
                </g>
            </svg>
        `);
    },
    
    categoryUrl(subCategory) {
        return route('categories.products.index', { category: subCategory.slug });
    },
    
    subCategoryImage(subCategory) {
        return subCategory.category_image?.exists 
            ? subCategory.category_image.path 
            : null;
    },
    
    hasSubCategoryImage(subCategory) {
        return subCategory.category_image?.exists || false;
    }
}));

