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
  } else {
    password.type = "password";
  }
}

function searching(event) {
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

function clearFormInputs(form) {
  form.reset();
}

function disableSubmitButton(form) {
  const submitButton = form.querySelector('button[type="submit"]');
  submitButton.disabled = true;
}

function enableSubmitButton(form) {
  const submitButton = form.querySelector('button[type="submit"]');
  submitButton.disabled = false;
}

function reply(event, comment_id) {
  // thank you mr. ChatGPT!
  event.preventDefault();
  console.log("Function reply called");

  const modalBody = document.getElementById("replyBody" + comment_id);

  // Retrieve the reply form element
  const replyForm = document.getElementById("replyForm_" + comment_id);
  if (!replyForm) {
      console.error("Form element not found");
      return false;
  }

  // Get the reply input field and its value
  const replyInput = document.getElementById("replyText_"+comment_id);
  const replyValue = replyInput.value.trim();

  // Check if the reply input is empty
  if (!replyValue) {
      console.log("Reply is empty");
      return false;
  }

  // Prepare the request data
  const requestData = {
      reply: replyValue,
      id: comment_id
  };

  // Create a new XMLHttpRequest
  const xhr = new XMLHttpRequest();
  const url = "../db_shenanigans/reply.php"; 
  xhr.open("POST", url);

  // Set the request header
  xhr.setRequestHeader("Content-Type", "application/json");


  // Handle the response
  xhr.onreadystatechange = function() {
      if (xhr.readyState === 4) {
          if (xhr.status === 200) {
              // Success case
              console.log('Reply submitted successfully:', xhr.response);
              modalBody.insertAdjacentHTML("afterbegin", xhr.response);
              clearFormInputs(replyForm);
          } else {
              // Error case
              console.error('Error submitting reply:', xhr.status, xhr.statusText, xhr.responseText);
          }

          // Re-enable the submit button
          enableSubmitButton(replyForm);
      }
  };

  // Send the request
  try {
      xhr.send(JSON.stringify(requestData));
      console.log("Request sent successfully");
  } catch (error) {
      console.error("Failed to send the request:", error);
  }

  // Return false to prevent default form submission
  return false;
}

function like(event, post_id){
  event.preventDefault();
  const likeButton = document.getElementById("likeButton_"+post_id);
  

  return false;
}

function login(event){
event.preventDefault();

const list = document.getElementById("listToUpdate").innerHTML;
const loginForm = document.getElementById("login");
const user = document.getElementById("username").value.trim();
const pass = document.getElementById("password").value.trim();

const errorDisplay = document.getElementById("error");
const nav = document.getElementById("listToUpdate");

const request = {
  username: user,
  password: pass
}
const xhr = new XMLHttpRequest();
const url = "../db_shenanigans/login.php";
xhr.open("POST", url);

xhr.setRequestHeader("Content-Type", "application/json");

xhr.onreadystatechange = function() {
  
    if (xhr.status === 200){
    console.log(xhr.response);
    if (xhr.response === "Invalid username or password"){
      errorDisplay.textContent = xhr.response;
      nav.innerHTML = list;
    } else if (xhr.response !== "Invalid username or password") {
      errorDisplay.textContent = "Logged in!";
    nav.innerHTML = xhr.response;
    }
    clearFormInputs(loginForm);
    enableSubmitButton(loginForm);
    }
   else {
    console.error('Error submitting reply:', xhr.status, xhr.statusText, xhr.responseText);
  }
  
};


try {
  xhr.send(JSON.stringify(request));
  console.log("Request sent successfully");
} catch (error) {
  console.error("Failed to send the request:", error);
}

return false;
}

let fileToUpload = "";
function post(event){
  event.preventDefault();

  const postList = document.getElementById("postList");
  const postForm = document.getElementById("postForm");
  const title = document.getElementById("title").value.trim();
  const description = document.getElementById("description").value.trim();
  const category = document.getElementById("category").value;
const image = fileToUpload;

  const request = {
    image: image,
    title: title,
    description: description,
    category: category
  }

  const xhr = new XMLHttpRequest();
  const url = "../db_shenanigans/upload.php";
  xhr.open("POST", url);

  xhr.onreadystatechange = function(){
    if (xhr.status === 200){
      console.log(xhr.response);
      console.log(image);
    } else {
      console.log(xhr.status, xhr.statusText, xhr.responseText)
    }
    clearFormInputs(postForm);
    enableSubmitButton(postForm);
  };

  try {
    xhr.send(JSON.stringify(request))
  } catch (error){
    console.log(error);
  }

  return false;
}

window.onload = function() {
  document.getElementById('image').addEventListener('change', getFileName);
}

const getFileName = (event) => {
  const file = event.target.files;
  const fileName = file[0].name;
  console.log(fileName);
  fileToUpload = fileName;
}