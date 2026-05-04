import React, { useState, useRef } from 'react';
import './App.css';

function App() {
  const [formData, setFormData] = useState({
    name: '',
    email: '',
    prn: '',
    course: '',
    feedback: ''
  });

  const [feedbackList, setFeedbackList] = useState([]);
  const [errors, setErrors] = useState({});

  const nameRef = useRef(null);

  const handleChange = (e) => {
    const { name, value } = e.target;

    setFormData({
      ...formData,
      [name]: value
    });
  };

  const validateForm = () => {
    let newErrors = {};

    if (formData.name.trim() === '') {
      newErrors.name = 'Name is required';
    }

    if (formData.email.trim() === '') {
      newErrors.email = 'Email is required';
    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(formData.email)) {
      newErrors.email = 'Enter a valid email';
    }

    if (formData.prn.trim() === '') {
      newErrors.prn = 'PRN Number is required';
    } else if (!/^\d{8}$/.test(formData.prn)) {
      newErrors.prn = 'PRN should of exactly 8 digits';
    }

    if (formData.course === '') {
      newErrors.course = 'Please select a course';
    }

    if (formData.feedback.trim() === '') {
      newErrors.feedback = 'Feedback message is required';
    }

    setErrors(newErrors);

    return Object.keys(newErrors).length === 0;
  };

  const handleSubmit = (e) => {
    e.preventDefault();

    if (validateForm()) {
      const newFeedback = {
        id: Date.now(),
        ...formData
      };

      setFeedbackList([...feedbackList, newFeedback]);

      setFormData({
        name: '',
        email: '',
        prn: '',
        course: '',
        feedback: ''
      });

      setErrors({});

      nameRef.current.focus();
    }
  };

  return (
    <div className="container">
      <h1>Student Feedback Form</h1>

      <form onSubmit={handleSubmit} className="feedback-form">
        <input
          type="text"
          name="name"
          placeholder="Enter Name"
          value={formData.name}
          onChange={handleChange}
          ref={nameRef}
        />
        {errors.name && <p className="error">{errors.name}</p>}

        <input
          type="email"
          name="email"
          placeholder="Enter Email"
          value={formData.email}
          onChange={handleChange}
        />
        {errors.email && <p className="error">{errors.email}</p>}

        <input
          type="text"
          name="prn"
          placeholder="Enter PRN Number"
          value={formData.prn}
          onChange={handleChange}
        />
        {errors.prn && <p className="error">{errors.prn}</p>}

        <select
          name="course"
          value={formData.course}
          onChange={handleChange}
        >
          <option value="">Select Course</option>
          <option value="Web Technology">Web Technology</option>
          <option value="Design and Analysis of Algorithm">
            Design and Analysis of Algorithm
          </option>
          <option value="Software Design and Modeling">
            Software Design and Modeling
          </option>
          <option value="Compiler Design">Compiler Design</option>
          <option value="Artificial Intelligence">
            Artificial Intelligence
          </option>
          <option value="Database Management System">
            Database Management System
          </option>
        </select>
        {errors.course && <p className="error">{errors.course}</p>}

        <textarea
          name="feedback"
          placeholder="Enter Feedback Message"
          value={formData.feedback}
          onChange={handleChange}
        ></textarea>
        {errors.feedback && <p className="error">{errors.feedback}</p>}

        <button type="submit">Submit Feedback</button>
      </form>

      <h2>Submitted Feedback</h2>

      {feedbackList.length === 0 ? (
        <p>No feedback submitted yet.</p>
      ) : (
        <div className="feedback-list">
          {feedbackList.map((item) => (
            <div className="feedback-card" key={item.id}>
              <h3>{item.name}</h3>
              <p><strong>Email:</strong> {item.email}</p>
              <p><strong>PRN:</strong> {item.prn}</p>
              <p><strong>Course:</strong> {item.course}</p>
              <p><strong>Feedback:</strong> {item.feedback}</p>
            </div>
          ))}
        </div>
      )}
    </div>
  );
}

export default App;