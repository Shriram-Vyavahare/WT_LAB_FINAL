function Result(props) {
  let grandTotal = 0;

  props.subjects.forEach((subject) => {
    grandTotal += Number(subject.mse || 0) + Number(subject.ese || 0);
  });

  const percentage = (grandTotal / 400) * 100;
  const sgpa = (percentage / 10).toFixed(2);

  return (
    <div className="result-box">
      <h2>Marks Details</h2>

      <table border="1" cellPadding="10">
        <thead>
          <tr>
            <th>Subject</th>
            <th>MSE Marks</th>
            <th>ESE Marks</th>
            <th>Total</th>
            <th>Status</th>
          </tr>
        </thead>

        <tbody>
          {props.subjects.map((subject, index) => {
            const total = Number(subject.mse || 0) + Number(subject.ese || 0);
            const status = total >= 40 ? "Pass" : "Fail";

            return (
              <tr key={index}>
                <td>{subject.name}</td>
                <td>{subject.mse}</td>
                <td>{subject.ese}</td>
                <td>{total}</td>
                <td>{status}</td>
              </tr>
            );
          })}
        </tbody>
      </table>

      <div className="summary-box">
        <h3>Overall Percentage: {percentage.toFixed(2)}%</h3>
        <h3>Estimated SGPA: {sgpa}</h3>
      </div>
    </div>
  );
}

export default Result;