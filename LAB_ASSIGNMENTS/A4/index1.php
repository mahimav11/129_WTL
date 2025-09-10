<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Student Management System</title>
<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: #f6f8fa;
        margin: 20px;
        color: #333;
    }
    h2 {
        text-align: center;
        color: #2c3e50;
    }
    form, #miniForm, #updateForm, #deleteForm {
        background: #fff;
        border: 1px solid #ddd;
        border-radius: 5px;
        padding: 15px 20px;
        max-width: 350px;
        margin: 10px auto;
        box-shadow: 0 2px 8px rgb(0 0 0 / 0.1);
    }
    label {
        display: block;
        margin-top: 10px;
        font-weight: 600;
        color: #555;
    }
    input[type=text], input[type=email], input[type=number] {
        width: 100%;
        padding: 8px 10px;
        margin-top: 5px;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 15px;
        box-sizing: border-box;
    }
    button {
        margin-top: 15px;
        padding: 10px 16px;
        background-color: #2980b9;
        color: #fff;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 16px;
        transition: background-color 0.3s ease;
    }
    button:hover {
        background-color: #3498db;
    }
    #resultDiv {
        max-width: 350px;
        margin: 15px auto;
        font-weight: 600;
        color: #27ae60;
        text-align: center;
    }
    #errorMsg {
        color: #e74c3c;
        font-weight: 600;
    }
    #studentTable {
        display: none;
        border-collapse: collapse;
        width: 90%;
        max-width: 900px;
        margin: 25px auto;
        box-shadow: 0 2px 8px rgb(0 0 0 / 0.15);
        background: #fff;
        border-radius: 5px;
        overflow: hidden;
    }
    #studentTable th, #studentTable td {
        padding: 10px 15px;
        border-bottom: 1px solid #ddd;
        text-align: left;
        font-size: 16px;
    }
    #studentTable th {
        background-color: #2980b9;
        color: white;
        font-weight: 700;
    }
    #studentTable tr:hover {
        background-color: #f1faff;
    }
    .action-buttons {
        text-align: center;
        margin: 15px auto;
        max-width: 350px;
    }
    .action-buttons button {
        margin-right: 10px;
        background-color: #34495e;
    }
    .action-buttons button:hover {
        background-color: #2c3e50;
    }
    #countDiv {
        text-align: center;
        margin-top: 15px;
        font-size: 18px;
        font-weight: bold;
        color: #2980b9;
    }
</style>
</head>
<body>
<h2>Student Management System</h2>

<!-- CREATE FORM -->
<form id="createForm">
    <h3>Create Student</h3>
    <label>Name:</label>
    <input type="text" name="name" required />
    <label>Email:</label>
    <input type="email" name="email" required />
    <label>Age:</label>
    <input type="number" name="age" required />
    <button type="button" id="createBtn">Create</button>
</form>

<div id="resultDiv"></div>

<div class="action-buttons">
    <button type="button" id="updateMainBtn">Update</button>
    <button type="button" id="deleteMainBtn">Delete</button>
    <button type="button" id="getBtn">Get All Students</button>
    <button type="button" id="countBtn">Count Students</button>
</div>

<!-- UPDATE MINI FORM -->
<div id="miniForm">
    <h3>Enter ID to Update</h3>
    <input type="text" id="checkId" placeholder="Enter ID" />
    <button type="button" id="miniSubmitBtn">Submit</button>
    <p id="errorMsg"></p>
</div>

<!-- UPDATE FORM -->
<form id="updateForm">
    <h4>Update Details</h4>
    <input type="hidden" name="id" id="upd_id" />
    <label>Name:</label>
    <input type="text" name="name" id="upd_name" />
    <label>Email:</label>
    <input type="email" name="email" id="upd_email" />
    <label>Age:</label>
    <input type="number" name="age" id="upd_age" />
    <button type="button" id="updateBtn">Update</button>
</form>

<!-- DELETE FORM -->
<form id="deleteForm">
    <h3>Delete Student</h3>
    <label>Enter ID:</label>
    <input type="number" name="id" required />
    <button type="button" id="deleteBtn">Delete</button>
</form>

<table id="studentTable">
    <thead>
        <tr><th>ID</th><th>Name</th><th>Email</th><th>Age</th></tr>
    </thead>
    <tbody id="tbody1"></tbody>
</table>

<div id="countDiv"></div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    // Hide forms initially
    document.getElementById("miniForm").style.display = "none";
    document.getElementById("updateForm").style.display = "none";
    document.getElementById("deleteForm").style.display = "none";
    document.getElementById("studentTable").style.display = "none";

    // ===== CREATE =====
    async function sendData() {
        let form = document.getElementById("createForm");
        let formData = new FormData(form);

        let res = await fetch("create.php", {
            method: "POST",
            body: formData,
        });

        let result = await res.text();
        document.getElementById("resultDiv").innerHTML = result;
        showTable();
    }
    document.getElementById("createBtn").addEventListener("click", sendData);

    // ===== READ =====
    async function showTable() {
        document.getElementById("studentTable").style.display = "table";
        let res = await fetch("read.php");
        let data = await res.json();
        let tbody = document.getElementById("tbody1");
        tbody.innerHTML = "";

        // Clear count display
        document.getElementById("countDiv").innerHTML = "";

        for (let i in data) {
            let row = document.createElement("tr");
            for (let j in data[i]) {
                let cell = document.createElement("td");
                cell.textContent = data[i][j];
                row.appendChild(cell);
            }
            tbody.appendChild(row);
        }
    }
    document.getElementById("getBtn").addEventListener("click", showTable);

    // ===== UPDATE =====
    function showMiniForm() {
        document.getElementById("miniForm").style.display = "block";
        document.getElementById("updateForm").style.display = "none";
        document.getElementById("errorMsg").innerText = "";
    }
    document.getElementById("updateMainBtn").addEventListener("click", showMiniForm);

    async function fetchStudentForUpdate() {
        let id = document.getElementById("checkId").value;
        let res = await fetch("edit.php?id=" + id);
        let data = await res.json();

        if (data.status === "ok") {
            document.getElementById("miniForm").style.display = "none";
            document.getElementById("updateForm").style.display = "block";

            document.getElementById("upd_id").value = data.record.id;
            document.getElementById("upd_name").value = data.record.name;
            document.getElementById("upd_email").value = data.record.email;
            document.getElementById("upd_age").value = data.record.age;
        } else {
            document.getElementById("errorMsg").innerText = "ID not found!";
        }
    }
    document.getElementById("miniSubmitBtn").addEventListener("click", fetchStudentForUpdate);

    async function updateStudent() {
        let id = document.getElementById("upd_id").value;
        let name = document.getElementById("upd_name").value;
        let email = document.getElementById("upd_email").value;
        let age = document.getElementById("upd_age").value;

        let res = await fetch("edit.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `id=${id}&name=${name}&email=${email}&age=${age}`,
        });

        let result = await res.json();
        alert(result.message);
        document.getElementById("updateForm").style.display = "none";
        showTable();
    }
    document.getElementById("updateBtn").addEventListener("click", updateStudent);

    // ===== DELETE =====
    function showDeleteForm() {
        document.getElementById("deleteForm").style.display = "block";
    }
    document.getElementById("deleteMainBtn").addEventListener("click", showDeleteForm);

    async function deleteStudent() {
        let form = document.getElementById("deleteForm");
        let formData = new FormData(form);

        let res = await fetch("delete.php", {
            method: "POST",
            body: formData,
        });

        let result = await res.json();
        alert(result.message);

        document.getElementById("deleteForm").style.display = "none";
        form.reset();
        showTable();

        
    }
    document.getElementById("deleteBtn").addEventListener("click", deleteStudent);

    // ===== COUNT STUDENTS =====
    async function countStudents() {
        let res = await fetch("count.php");
        let data = await res.json();

        if (data.status === "ok") {
            document.getElementById("countDiv").textContent = `Total Students: ${data.count}`;
        } else {
            document.getElementById("countDiv").textContent = `Count Error: ${data.message}`;
        }
        // Optionally hide table and forms when counting only
        document.getElementById("studentTable").style.display = "none";
        document.getElementById("miniForm").style.display = "none";
        document.getElementById("updateForm").style.display = "none";
        document.getElementById("deleteForm").style.display = "none";
        document.getElementById("resultDiv").innerHTML = "";
    }
    document.getElementById("countBtn").addEventListener("click", countStudents);
});


</script>
</body>
</html>
