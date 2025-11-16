JS validation 
<!DOCTYPE html>
<html>
<head>
  <title>Registration</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      padding: 30px;
      background-color: #f0f8ff;
    }
 
    h2 {
      text-align: center;
      color: #003366;
    }
 
    form {
      background-color: #ffffff;
      padding: 20px;
      border-radius: 10px;
      width: 300px;
      margin: 0 auto;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
 
    input, select, button {
      width: 100%;
      padding: 8px;
      margin-top: 10px;
      border-radius: 5px;
      border: 1px solid #ccc;
    }
 
    button {
      background-color: #003366;
      color: white;
      cursor: pointer;
    }
 
    button:hover {
      background-color: #0055aa;
    }
 
    #output {
      margin-top: 20px;
      text-align: center;
      font-size: 16px;
      color: #003366;
    }
 
    #error {
      margin-top: 10px;
      color: red;
      text-align: center;
    }
  </style>
</head>
<body>
 
  <h2>Registration Form</h2>
 
  <form onsubmit="return handleSubmit()">
    <label>Full Name:</label>
    <input type="text" id="fname" />
 
    <label>Email:</label>
    <input type="text" id="email" />

    <label>Password:</label>
    <input type="password" id="pass" />

    <label>Confirm Password:</label>
    <input type="password" id="cpass" />
 
 
    <button type="submit">Submit</button>
  </form>
 
  <div id="error"></div>
  <div id="output"></div>
 <form>
     <div class="course-section">
    <h2 style="text-align:center;">-- Course --</h2>
    <input type="text" id="courseInput" placeholder="Enter course name">
    <button type="button" onclick="addCourse()">Add Course</button>
    <ul id="courseList"></ul>
  </div>
 </form>
  <script>
    function handleSubmit() {
      // Get values from form
      var fname = document.getElementById("fname").value.trim();
      var email = document.getElementById("email").value.trim();
      var pass = document.getElementById("pass").value.trim();
      var cpass = document.getElementById("cpass").value.trim();
 
      var errorDiv = document.getElementById("error");
      var outputDiv = document.getElementById("output");
 
      // Clear previous messages
      errorDiv.innerHTML = "";
      outputDiv.innerHTML = "";
 
      // Validation
      if (fname === "" || email === "" || pass === "" || cpass === "") {
        errorDiv.innerHTML = "Please fill in all fields.";
        return false;
      }
 
      if (!email.includes("@")) {
        errorDiv.innerHTML = "Email must contain '@'";
        return false;
      }
 
      if (pass!==cpass) {
        errorDiv.innerHTML = " passwords do not match.";
        return false;
      }
 
 
      outputDiv.innerHTML = `
        <strong>Registration Complete!</strong><br><br>
        Full Name: ${fname}<br>
        Email: ${email}<br>
        <br>
      `;
 
      return false;
    }
    function addcourse() {
    var courseName = document.getElementById("courseInput").value.trim();
    if (courseName === "") {
      alert("Please enter a course name.");
      return;
    }
    var courseList = document.getElementById("courseList");
    courseList.textcontent += courseName + " ";

    var deleteBtn = document.createElement("button");
    deleteBtn.textContent = "Delete";
    deleteBtn.onclick = function() {
      courseList.remove();
    };
}
  </script>
 
</body>
</html>
 