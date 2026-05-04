const form = document.getElementById('studentForm');
const studentTableBody = document.getElementById('studentTableBody');

// Load students when page opens
window.onload = fetchStudents;

// Add student
form.addEventListener('submit', async (e) => {
    e.preventDefault();

    const student = {
        name: document.getElementById('name').value,
        email: document.getElementById('email').value,
        course: document.getElementById('course').value
    };

    const response = await fetch('/add-student', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(student)
    });

    const message = await response.text();
    alert(message);

    form.reset();
    fetchStudents();
});

// Fetch all students
async function fetchStudents() {
    const response = await fetch('/students');
    const students = await response.json();

    studentTableBody.innerHTML = '';

    students.forEach(student => {
        const row = `
            <tr>
                <td>${student.id}</td>
                <td>${student.name}</td>
                <td>${student.email}</td>
                <td>${student.course}</td>
            </tr>
        `;

        studentTableBody.innerHTML += row;
    });
}