// script.js
function validateForm() {
  // Get form inputs
  const firstName = document.getElementById('firstName').value;
  const lastName = document.getElementById('lastName').value;
  const email = document.getElementById('email').value;
  const username = document.getElementById('username').value;
  const password = document.getElementById('password').value;
  const confirmPassword = document.getElementById('confirmPassword').value;
  const userType = document.getElementById('userType').value;
  
  // Basic validation for each field
  if (!firstName || !lastName || !email || !username || !password || !confirmPassword || !userType) {
    alert('Please fill in all fields.');
    return false; // Prevent form submission
  }

  // Check if password and confirm password match
  if (password !== confirmPassword) {
    alert('Passwords do not match.');
    return false; // Prevent form submission
  }

  return true; // Form is valid, allow submission
}
