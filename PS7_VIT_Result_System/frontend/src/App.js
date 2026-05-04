import './App.css';
import { useState } from 'react';
import Student from './components/Student';
import Result from './components/Result';

function App() {
  const [studentName, setStudentName] = useState('');
  const [course, setCourse] = useState('');
  const [prn, setPrn] = useState('');

  const [subjects, setSubjects] = useState([
    { name: 'Web Technology', mse: '', ese: '' },
    { name: 'Compiler Design', mse: '', ese: '' },
    { name: 'Data Science', mse: '', ese: '' },
    { name: 'Artificial Intelligence', mse: '' , ese: '' }
  ]);

  const [savedResults, setSavedResults] = useState([]);

  const handleMarksChange = (index, field, value) => {
    const updatedSubjects = [...subjects];
    updatedSubjects[index][field] = value;
    setSubjects(updatedSubjects);
  };

  const fetchResults = async () => {
    try {
      const response = await fetch('http://localhost/WT_LAB_7/backend/getResults.php');
      const data = await response.json();
      setSavedResults(data);
    } catch (error) {
      console.log(error);
      alert('Error fetching records');
    }
  };

  const saveResult = async () => {
    if (
      studentName.trim() === '' ||
      course.trim() === '' ||
      prn.trim() === ''
    ) {
      alert('Please fill all student details');
      return;
    }

    for (let subject of subjects) {
      if (subject.mse === '' || subject.ese === '') {
        alert('Please fill all subject marks');
        return;
      }
    }

    const data = {
      student_name: studentName,
      course: course,
      prn: prn,
      subjects: subjects
    };

    try {
      const response = await fetch('http://localhost/WT_LAB_7/backend/saveResult.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
      });

      const result = await response.json();

      if (result.error) {
        alert(result.message + ': ' + result.error);
      } else {
        alert(result.message);

        setStudentName('');
        setCourse('');
        setPrn('');

        setSubjects([
          { name: 'Web Technology', mse: '', ese: '' },
          { name: 'Compiler Design', mse: '', ese: '' },
          { name: 'Data Science', mse: '', ese: '' },
          { name: 'Artificial Intelligence', mse: '', ese: '' }
        ]);
      }
    } catch (error) {
      console.log('Fetch Error:', error);
      alert('Error saving result');
    }
  };

  return (
    <div className="container">
      <h1>VIT Semester Result System</h1>

      <div className="form-box">
        <h2>Enter Student Details</h2>

        <input
          type="text"
          placeholder="Enter Student Name"
          value={studentName}
          onChange={(e) => setStudentName(e.target.value)}
        />

        <input
          type="text"
          placeholder="Enter Course"
          value={course}
          onChange={(e) => setCourse(e.target.value)}
        />

        <input
          type="text"
          placeholder="Enter PRN"
          value={prn}
          onChange={(e) => setPrn(e.target.value)}
        />

        <h2>Enter Subject Marks</h2>

        {subjects.map((subject, index) => (
          <div key={index} className="subject-box">
            <h3>{subject.name}</h3>

            <input
              type="number"
              placeholder="Enter MSE Marks out of 30"
              min="0"
              max="30"
              value={subject.mse}
              onChange={(e) => {
                const value = e.target.value;

                if (value === '' || (Number(value) >= 0 && Number(value) <= 30)) {
                  handleMarksChange(index, 'mse', value);
                }
              }}
            />

            <input
              type="number"
              placeholder="Enter ESE Marks out of 70"
              min="0"
              max="70"
              value={subject.ese}
              onChange={(e) => {
                const value = e.target.value;

                if (value === '' || (Number(value) >= 0 && Number(value) <= 70)) {
                  handleMarksChange(index, 'ese', value);
                }
              }}
            />
          </div>
        ))}

        <div className="button-group">
          <button onClick={saveResult}>Save Result</button>
          <button onClick={fetchResults}>Fetch Saved Records</button>
        </div>
      </div>

      <Student
        name={studentName}
        course={course}
        prn={prn}
      />

      <Result subjects={subjects} />

      {savedResults.length > 0 && (
        <div className="saved-records">
          <h2>Previously Saved Records</h2>

          <table>
            <thead>
              <tr>
                <th>Name</th>
                <th>Course</th>
                <th>PRN</th>
              </tr>
            </thead>

            <tbody>
              {savedResults.map((record) => (
                <tr key={record.id}>
                  <td>{record.student_name}</td>
                  <td>{record.course}</td>
                  <td>{record.prn}</td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      )}
    </div>
  );
}

export default App;