var form = document.getElementById("dateForm");
form.addEventListener("submit", function(event) {
    event.preventDefault();
    refreshPage();
    
});

function refreshPage() {
    const dateDisplay = document.getElementById("pageDate");
    const dateForm = document.getElementById("dateForm");
    const dateInput = dateForm.firstElementChild
    dateInput.innerHTML = dateDisplay.innerHTML;

}