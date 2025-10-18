document.getElementById("forgotForm").addEventListener("submit", (e) => {
    e.preventDefault();
    
    const email = document.getElementById("email").value.trim();
  
    if (email === "") {
      alert("Please enter your email address.");
      return;
    }
  
    // Check if user exists (optional if you store user data in localStorage)
    // const users = JSON.parse(localStorage.getItem("users")) || [];
    // const userExists = users.some(user => user.email === email);
  
    // if (!userExists) {
    //   alert("No account found with this email address.");
    //   return;
    // }
  
    // Simulate sending reset link
    alert(`A password reset link has been sent to ${email}.`);
    window.location.href = "index.html"; // Go back to login
  });
  