import { createSlice } from '@reduxjs/toolkit';

// Sample product data
const initialProducts = [
  { id: 1, name: 'iPhone 14 Pro', category: 'Electronics', price: 999 },
  { id: 2, name: 'MacBook Air M2', category: 'Electronics', price: 1199 },
  { id: 3, name: 'Nike Air Max', category: 'Clothing', price: 120 },
  { id: 4, name: 'Adidas Ultraboost', category: 'Clothing', price: 180 },
  { id: 5, name: 'Samsung Galaxy S23', category: 'Electronics', price: 799 },
  { id: 6, name: 'Levi\'s 501 Jeans', category: 'Clothing', price: 89 },
  { id: 7, name: 'The Great Gatsby', category: 'Books', price: 15 },
  { id: 8, name: 'To Kill a Mockingbird', category: 'Books', price: 12 },
  { id: 9, name: 'iPad Pro', category: 'Electronics', price: 1099 },
  { id: 10, name: 'AirPods Pro', category: 'Electronics', price: 249 },
  { id: 11, name: 'Calvin Klein Shirt', category: 'Clothing', price: 65 },
  { id: 12, name: 'Dune', category: 'Books', price: 18 },
  { id: 13, name: 'Sony WH-1000XM4', category: 'Electronics', price: 349 },
  { id: 14, name: 'Patagonia Jacket', category: 'Clothing', price: 299 },
  { id: 15, name: '1984 by George Orwell', category: 'Books', price: 14 },
  { id: 16, name: 'Dell XPS 13', category: 'Electronics', price: 999 },
  { id: 17, name: 'Converse Chuck Taylor', category: 'Clothing', price: 55 },
  { id: 18, name: 'Harry Potter Series', category: 'Books', price: 45 },
  { id: 19, name: 'Apple Watch Series 8', category: 'Electronics', price: 399 },
  { id: 20, name: 'North Face Backpack', category: 'Clothing', price: 89 },
];

const productsSlice = createSlice({
  name: 'products',
  initialState: {
    allProducts: initialProducts,
    filteredProducts: initialProducts,
  },
  reducers: {
    setFilteredProducts: (state, action) => {
      state.filteredProducts = action.payload;
    },
  },
});

export const { setFilteredProducts } = productsSlice.actions;

// Selectors
export const selectAllProducts = (state) => state.products.allProducts;
export const selectFilteredProducts = (state) => state.products.filteredProducts;

export default productsSlice.reducer;