const tooltipTriggerList = document.querySelectorAll(
  '[data-bs-toggle="tooltip"]'
);
const tooltipList = [...tooltipTriggerList].map(
  (tooltipTriggerEl) => new bootstrap.Tooltip(tooltipTriggerEl)
);

function Shenanigans() {
  var password = document.getElementById("password");
  var passLabel = document.getElementById("passLabel");
  if (password.type === "password") {
    password.type = "text";
    passLabel.textContent = "Hide password: ";
  } else {
    password.type = "password";
    passLabel.textContent = "Show password: ";
  }
}

function test(event) {
  event.preventDefault();
  console.log("PENIS");
  var data = new FormData(document.getElementById("searchForm"));
  console.log("Form data:", data);

  var xhr = new XMLHttpRequest();
  xhr.onload = function () {
    console.log("xhr.onload function called");
    if (xhr.status === 200) {
      console.log(xhr.responseText);
      document.body.innerHTML = xhr.responseText;
    } else {
      console.error("Request failed. Status: " + xhr.status);
    }
  };

  var url = "index.php";
  xhr.open("POST", url);
  console.log("Sending request to:", url);
  xhr.send(data);

  return false; // Prevent default form submission
}

function thing(event) {
  event.preventDefault;

  const penis = new XMLHttpRequest();

  const url = "passreset.php";
  penis.open("POST", url);
  return false;
}

function modal1() {
  const Modal = document.getElementById("PostModal");
  const Input = document.getElementById("modalInput1");

  Modal.addEventListener("shown.bs.modal", () => {
    Input.focus();
  });
}

function modal2() {
  const Modal = document.getElementById("LoginModal");
  const Input = document.getElementById("modalInput2");

  Modal.addEventListener("shown.bs.modal", () => {
    Input.focus();
  });
}

function loginFormStuff() {
  document.getElementById("thing").addEventListener("click", function (event) {
    event.preventDefault();
  });
}

function modal3() {
  const Modal = document.getElementById("CommentModal");
  const Input = document.getElementById("modalInput3");

  Modal.addEventListener("shown.bs.modal", () => {
    Input.focus();
  });
}

function modal4() {
  const Modal = document.getElementById("deleteModal");
  const Input = document.getElementById("modalInput4");

  Modal.addEventListener("shown.bs.modal", () => {
    Input.focus();
  });
}

function modal5() {
  const Modal = document.getElementById("DeleteUserModal");
  const Input = document.getElementById("Input");

  Modal.addEventListener("shown.bs.modal", () => {
    Input.focus();
  });
}

function modal6() {
  const Modal = document.getElementById("ReplyModal");
  const Input = document.getElementById("replyModalInput");

  Modal.addEventListener("shown.bs.modal", () => {
    Input.focus();
  });
}
