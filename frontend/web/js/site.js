document.addEventListener("DOMContentLoaded", function () {
    let user = "info";
    let domain = "caloriethingy.com";
    let emailElement = document.getElementById("email");
    emailElement.innerHTML = `<a href="mailto:${user}@${domain}">${user}@${domain}</a>`;
});
