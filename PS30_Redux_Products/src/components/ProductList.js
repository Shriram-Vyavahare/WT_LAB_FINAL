import React from 'react';
import { useSelector } from 'react-redux';
import { selectFilteredProducts } from '../store/productsSlice';
import ProductCard from './ProductCard';

const ProductList = () => {
  const filteredProducts = useSelector(selectFilteredProducts);

  return (
    <div className="products-section">
      <div className="products-header">
        <h2 className="products-title">Products</h2>
        <div className="products-count">
          {filteredProducts.length} {filteredProducts.length === 1 ? 'Product' : 'Products'}
        </div>
      </div>
      
      {filteredProducts.length > 0 ? (
        <div className="products-grid">
          {filteredProducts.map(product => (
            <ProductCard key={product.id} product={product} />
          ))}
        </div>
      ) : (
        <div className="no-products">
          <div className="no-products-icon">📦</div>
          <h3>No products found</h3>
          <p>Try adjusting your filters to see more products</p>
        </div>
      )}
    </div>
  );
};

export default ProductList;