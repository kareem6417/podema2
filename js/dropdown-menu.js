document.addEventListener("DOMContentLoaded", function() {
  const dropdownItems = document.querySelectorAll(".dropdown");
  
  dropdownItems.forEach(function(item) {
    const dropbtn = item.querySelector(".dropbtn");
    const dropdownContent = item.querySelector(".dropdown-content");
    
    dropbtn.addEventListener("click", function(e) {
      e.preventDefault();
      
      dropdownContent.classList.toggle("show");
    });
  });
});

window.addEventListener("click", function(e) {
  const dropdowns = document.querySelectorAll(".dropdown");
  
  dropdowns.forEach(function(item) {
    const dropbtn = item.querySelector(".dropbtn");
    const dropdownContent = item.querySelector(".dropdown-content");
    
    if (!dropbtn.contains(e.target)) {
      dropdownContent.classList.remove("show");
    }
  });
});
