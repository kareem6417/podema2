const nameInput = document.getElementById('name');
const companyInput = document.getElementById('company');
const divisiInput = document.getElementById('divisi');

nameInput.addEventListener('change', () => {
    const selectedName = nameInput.value;
    const xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            const userData = JSON.parse(xhr.responseText);
            companyInput.value = userData.company || "";
            divisiInput.value = userData.department || "";
        }
    };
    
    xhr.open("GET", "get_userdata.php?name=" + selectedName, true);
    xhr.send();
});
