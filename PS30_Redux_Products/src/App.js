import React from 'react';
import Header from './components/Header';
import Filters from './components/Filters';
import ProductList from './components/ProductList';

function App() {
  return (
    <div className="container">
      <Header />
      <Filters />
      <ProductList />
    </div>
  );
}

export default App;