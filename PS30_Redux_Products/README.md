# Product Filter App with Redux

A React application that allows users to filter products by category and price range using Redux for state management.

## Features

✅ **Store product data in Redux state** - All products are stored in the Redux store with initial sample data

✅ **Create actions for filtering products** - Redux actions for setting category, price range, and resetting filters

✅ **Implement reducer for filter logic** - Separate reducers for products and filters with proper state management

✅ **Display filtered products dynamically** - Real-time filtering based on selected criteria

✅ **Reset filters when required** - One-click reset functionality to clear all filters

## Additional Features

- **Professional UI Design** - Clean, modern interface with responsive design
- **Real-time Filtering** - Products update instantly as filters change
- **Product Count Display** - Shows number of filtered products
- **Empty State Handling** - Friendly message when no products match filters
- **Mobile Responsive** - Works seamlessly on all device sizes

## Tech Stack

- **React 18** - Frontend framework
- **Redux Toolkit** - State management
- **React-Redux** - React bindings for Redux
- **CSS3** - Styling with modern features

## Project Structure

```
src/
├── components/
│   ├── Header.js          # App header component
│   ├── Filters.js         # Filter controls component
│   ├── ProductList.js     # Products display component
│   └── ProductCard.js     # Individual product card
├── store/
│   ├── store.js           # Redux store configuration
│   ├── productsSlice.js   # Products state management
│   └── filtersSlice.js    # Filters state management
├── App.js                 # Main app component
├── index.js              # App entry point
└── index.css             # Global styles
```

## Redux State Structure

### Products State
```javascript
{
  allProducts: [...],      // Original product data
  filteredProducts: [...]  // Filtered results
}
```

### Filters State
```javascript
{
  category: 'all',         // Selected category
  minPrice: '',           // Minimum price filter
  maxPrice: ''            // Maximum price filter
}
```

## Available Actions

### Filter Actions
- `setCategory(category)` - Set product category filter
- `setMinPrice(price)` - Set minimum price filter
- `setMaxPrice(price)` - Set maximum price filter
- `resetFilters()` - Reset all filters to default

### Product Actions
- `setFilteredProducts(products)` - Update filtered products list

## Getting Started

### Prerequisites
- Node.js (version 14 or higher)
- npm or yarn

### Installation

1. **Install dependencies:**
   ```bash
   npm install
   ```

2. **Start the development server:**
   ```bash
   npm start
   ```

3. **Open your browser:**
   Navigate to `http://localhost:3000`

### Available Scripts

- `npm start` - Runs the app in development mode
- `npm run build` - Builds the app for production
- `npm test` - Launches the test runner
- `npm run eject` - Ejects from Create React App (one-way operation)

## How It Works

1. **Initial Load**: Products are loaded into Redux state from sample data
2. **Filter Selection**: Users can select category and/or set price range
3. **Real-time Updates**: Products are filtered automatically using Redux selectors
4. **State Management**: All filter state is managed through Redux actions and reducers
5. **Reset Functionality**: Users can clear all filters with one click

## Sample Data

The app includes 20 sample products across 3 categories:
- **Electronics** (iPhone, MacBook, Samsung Galaxy, etc.)
- **Clothing** (Nike shoes, Levi's jeans, Patagonia jacket, etc.)
- **Books** (Classic literature and popular titles)

## Customization

### Adding New Products
Edit `src/store/productsSlice.js` and add products to the `initialProducts` array:

```javascript
{ id: 21, name: 'New Product', category: 'Category', price: 99 }
```

### Adding New Categories
Categories are automatically generated from product data. Just add products with new category names.

### Styling
Modify `src/index.css` to customize the appearance. The CSS uses CSS Grid and Flexbox for responsive layouts.

## Browser Support

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)

## License

This project is open source and available under the [MIT License](LICENSE).