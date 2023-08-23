function searchUsers() {
    var searchKeyword = document.getElementById("search").value;
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            var searchResults = JSON.parse(xhr.responseText);

            var tableBody = document.querySelector("table tbody");
            tableBody.innerHTML = "";

            if (searchResults.length > 0) {
                for (var i = 0; i < searchResults.length; i++) {
                    var user = searchResults[i];
                    var row = document.createElement("tr");
                    row.innerHTML = "<td>" + user.username + "</td>" +
                        "<td>" + user.name + "</td>" +
                        "<td>" + user.nik + "</td>" +
                        "<td>" + user.company + "</td>" +
                        "<td>" + user.department + "</td>" +
                        "<td style='text-align: center;'><span class='popup-btn' onclick='openUserDetailsPopup(\"" + user.user_id + "\")'><i class='fas fa-edit'></i></span></td>";
                    tableBody.appendChild(row);
                }
            } else {
                var row = document.createElement("tr");
                row.innerHTML = "<td colspan='6'>No users found</td>";
                tableBody.appendChild(row);
            }
        }
    };
    xhr.open("GET", "search_users.php?keyword=" + searchKeyword, true);
    xhr.send();
}

function handleSearchInput() {
    var searchInput = document.getElementById('search');
    var searchLabel = document.querySelector('.search-label');

    if (searchInput.value !== '') {
        searchInput.classList.add('not-empty');
        searchLabel.style.display = 'none';
    } else {
        searchInput.classList.remove('not-empty');
        searchLabel.style.display = 'block';
    }
}

function showPopup() {
    var popup = document.getElementById("popup");
    popup.style.display = "block";
    
    var form = document.querySelector('.popup-form');
    form.addEventListener('submit', function(event) {
        event.preventDefault(); 
    });
}

function closePopup() {
    var popup = document.getElementById("popup");
    popup.style.display = "none";
}

function togglePasswordVisibility() {
    var passwordInput = document.getElementById("password");
    var showPasswordCheckbox = document.getElementById("showPassword");
    passwordInput.type = showPasswordCheckbox.checked ? "text" : "password";
}

function displayNotification(message) {
    var notification = document.getElementById("notification");
    notification.textContent = message;
    notification.style.display = "block";
    setTimeout(function() {
        notification.style.display = "none";
    }, 3000);
}

function closeUserDetailsPopup() {
    document.getElementById("userDetailsPopup").style.display = "none";
}

function togglePasswordVisibility() {
    var passwordInput = document.getElementById("password");
    var showPasswordCheckbox = document.getElementById("showPassword");
    passwordInput.type = showPasswordCheckbox.checked ? "text" : "password";
}

function changeLimit() {
    var limit = document.getElementById("limit").value;
    window.location.href = "admin.php?limit=" + limit;
}

function showSuccessPopup() {
    var notification = document.getElementById('notification');
    notification.textContent = 'Successfully! New user has been created.';
    notification.style.display = 'block';
    setTimeout(function() {
        notification.style.display = 'none';
    }, 3000);
}
