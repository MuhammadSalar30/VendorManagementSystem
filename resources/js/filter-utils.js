/**
 * Apply filters and update the URL.
 * @param {Array} selectedCategories - Array of selected category IDs.
 * @param {string} minPrice - Minimum price filter.
 * @param {string} maxPrice - Maximum price filter.
 */
export function applyFilters(selectedCategories = [], minPrice = '0', maxPrice = '5000') {
    const url = new URL(window.location.href);
    const params = new URLSearchParams(url.search);

    // Update query parameters
    if (selectedCategories.length) {
      params.set('categories', selectedCategories.join(','));
    } else {
      params.delete('categories');
    }
    params.set('min_price', String(minPrice));
    params.set('max_price', String(maxPrice));

    // Update the URL and navigate
    url.search = params.toString();
    window.location.href = url.toString();
  }

  /**
   * Update the URL with sorting parameters.
   * @param {string} sortValue - The selected sort value.
   */
  export function applySort(sortValue) {
    const url = new URL(window.location.href);
    const params = new URLSearchParams(url.search);

    params.set('sort', sortValue);
    params.set('page', '1'); // Reset pagination
    url.search = params.toString();
    window.location.href = url.toString();
  }

  /**
   * Update the URL with search query.
   * @param {string} searchQuery - The search query string.
   */
  export function applySearch(searchQuery) {
    const url = new URL(window.location.href);
    const params = new URLSearchParams(url.search);

    if (searchQuery) {
      params.set('q', searchQuery);
    } else {
      params.delete('q');
    }
    params.set('page', '1'); // Reset pagination
    url.search = params.toString();
    window.location.href = url.toString();
  }
