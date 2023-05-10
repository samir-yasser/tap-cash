// add hovered class to selected list item
let list = document.querySelectorAll(".navigation li");

function activeLink() {
  list.forEach((item) => {
    item.classList.remove("hovered");
  });
  this.classList.add("hovered");
}

list.forEach((item) => item.addEventListener("mouseover", activeLink));

// Menu Toggle
let toggle = document.querySelector(".toggle");
let navigation = document.querySelector(".navigation");
let main = document.querySelector(".main");

toggle.onclick = function () {
  navigation.classList.toggle("active");
  main.classList.toggle("active");
};





// get user 
// WARNING: For GET requests, body is set to null by browsers.

var xhr = new XMLHttpRequest();
xhr.withCredentials = true;

xhr.addEventListener("readystatechange", function() {
  if(this.readyState === 4) {
      response = JSON.parse(this.responseText);
    if (response.success) {
        user = response.user.name;
        document.querySelector('#username').innerText = user;
    } else {
        window.location.assign('/login.html')
    }
    console.log(this.responseText);
  }
});
$token = localStorage.getItem('token');
if (!$token) window.location.assign('/login.html')
xhr.open("GET", "/api/auth/get_user.php");
xhr.setRequestHeader("Authorization", "Bearer " + $token);

xhr.send();