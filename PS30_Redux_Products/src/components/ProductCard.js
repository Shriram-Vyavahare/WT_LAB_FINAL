import React from 'react';

const ProductCard = ({ product }) => {
  return (
    <div className="product-card">
      <h3 className="product-name">{product.name}</h3>
      <div className="product-category">{product.category}</div>
      <div className="product-price">${product.price}</div>
    </div>
  );
};

export default ProductCard;