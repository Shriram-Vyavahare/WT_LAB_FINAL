import React, { useEffect } from 'react';
import { useSelector, useDispatch } from 'react-redux';
import { 
  setCategory, 
  setMinPrice, 
  setMaxPrice, 
  resetFilters, 
  selectFilters 
} from '../store/filtersSlice';
import { 
  selectAllProducts, 
  setFilteredProducts 
} from '../store/productsSlice';

const Filters = () => {
  const dispatch = useDispatch();
  const filters = useSelector(selectFilters);
  const allProducts = useSelector(selectAllProducts);

  // Get unique categories from products
  const categories = ['all', ...new Set(allProducts.map(product => product.category))];

  // Filter products whenever filters change
  useEffect(() => {
    let filtered = allProducts;

    // Filter by category
    if (filters.category !== 'all') {
      filtered = filtered.filter(product => product.category === filters.category);
    }

    // Filter by price range
    if (filters.minPrice !== '') {
      filtered = filtered.filter(product => product.price >= parseFloat(filters.minPrice));
    }

    if (filters.maxPrice !== '') {
      filtered = filtered.filter(product => product.price <= parseFloat(filters.maxPrice));
    }

    dispatch(setFilteredProducts(filtered));
  }, [filters, allProducts, dispatch]);

  const handleCategoryChange = (e) => {
    dispatch(setCategory(e.target.value));
  };

  const handleMinPriceChange = (e) => {
    dispatch(setMinPrice(e.target.value));
  };

  const handleMaxPriceChange = (e) => {
    dispatch(setMaxPrice(e.target.value));
  };

  const handleResetFilters = () => {
    dispatch(resetFilters());
  };

  return (
    <div className="filters-section">
      <h2 className="filters-title">Filter Products</h2>
      <div className="filters-container">
        <div className="filter-group">
          <label htmlFor="category">Category:</label>
          <select
            id="category"
            value={filters.category}
            onChange={handleCategoryChange}
          >
            {categories.map(category => (
              <option key={category} value={category}>
                {category === 'all' ? 'All Categories' : category}
              </option>
            ))}
          </select>
        </div>

        <div className="filter-group">
          <label>Price Range:</label>
          <div className="price-range-container">
            <input
              type="number"
              placeholder="Min Price"
              value={filters.minPrice}
              onChange={handleMinPriceChange}
              min="0"
            />
            <span>to</span>
            <input
              type="number"
              placeholder="Max Price"
              value={filters.maxPrice}
              onChange={handleMaxPriceChange}
              min="0"
            />
          </div>
        </div>

        <div className="filter-group">
          <button 
            className="reset-button"
            onClick={handleResetFilters}
          >
            Reset Filters
          </button>
        </div>
      </div>
    </div>
  );
};

export default Filters;