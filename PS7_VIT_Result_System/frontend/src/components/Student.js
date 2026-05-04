function Student(props) {
  return (
    <div className="student-box">
      <h2>Student Information</h2>
      <p><strong>Name:</strong> {props.name}</p>
      <p><strong>Course:</strong> {props.course}</p>
      <p><strong>PRN:</strong> {props.prn}</p>
    </div>
  );
}

export default Student;