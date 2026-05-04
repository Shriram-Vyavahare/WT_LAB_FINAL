import { createSlice } from '@reduxjs/toolkit';

const filtersSlice = createSlice({
  name: 'filters',
  initialState: {
    category: 'all',
    minPrice: '',
    maxPrice: '',
  },
  reducers: {
    setCategory: (state, action) => {
      state.category = action.payload;
    },
    setMinPrice: (state, action) => {
      state.minPrice = action.payload;
    },
    setMaxPrice: (state, action) => {
      state.maxPrice = action.payload;
    },
    resetFilters: (state) => {
      state.category = 'all';
      state.minPrice = '';
      state.maxPrice = '';
    },
  },
});

export const { setCategory, setMinPrice, setMaxPrice, resetFilters } = filtersSlice.actions;

// Selectors
export const selectFilters = (state) => state.filters;

export default filtersSlice.reducer;