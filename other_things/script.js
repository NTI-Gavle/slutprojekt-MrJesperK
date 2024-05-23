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
  console.log("NOT PENIS");
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

  var url = window.location.href;
  xhr.open("POST", url);
  console.log("Sending request to:", url);
  xhr.send(data);

  return false;
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

  const modalBody = document.getElementById("replyBody" + comment_id);

  // Retrieve the reply form element
  const replyForm = document.getElementById("replyForm_" + comment_id);
  if (!replyForm) {
      return false;
  }

  // Get the reply input field and its value
  const replyInput = document.getElementById("replyText_"+comment_id);
  const replyValue = replyInput.value.trim();
  const replyThing = document.getElementById("replyThing");

  // Check if the reply input is empty
  if (!replyValue) {
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
          if (xhr.readyState==4) {
              console.log('Reply submitted successfully:', xhr.response);
              modalBody.insertAdjacentHTML("afterbegin", xhr.response);
              replyThing.innerHTML = "";
              clearFormInputs(replyForm);
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

  return false;
}

function updateLikeButtonAppearance(button, isLiked)
{
  if (isLiked){
    button.classList.add("liked");
  } else {
    button.classList.remove("liked");
  }
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
function post(event) {
  event.preventDefault();

  const postForm = document.getElementById("postForm");
  const postList = document.getElementById("postList");

  const formData = new FormData(postForm);

  const xhr = new XMLHttpRequest();
  const url = "../db_shenanigans/upload.php";

  xhr.open("POST", url);

  xhr.responseType = 'text';

  xhr.onreadystatechange = function() {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200) {
        console.log(xhr.response);
        postList.insertAdjacentHTML("afterbegin", xhr.response)
        
      } else {
        console.log(xhr.status, xhr.statusText, xhr.responseText);
      }

      clearFormInputs(postForm);
      enableSubmitButton(postForm);
    }
  };

  try {
    xhr.send(formData);
  } catch (error) {
    console.log(error);
  }

  return false;
}

window.onload = function() {
  document.getElementById('image').addEventListener('change', getFileName);
}

const getFileName = (event) => {
  const file = event.target.files[0];
  if (file) {
    const fileName = file.name;
    console.log(fileName);
  }
};

function comment(event, id){
  event.preventDefault();
  const form = document.getElementById("commentForm");
  const list = document.getElementById("commentList")
  const CText = document.getElementById("commentText").value;

const request = {
  text: CText,
  id: id
};

  const xhr = new XMLHttpRequest();
  const url = "../db_shenanigans/comment.php";

  xhr.open("POST", url);
  xhr.responseType = 'text';

  xhr.onreadystatechange = function() {
    if (xhr.readyState === XMLHttpRequest.DONE){
      if (xhr.status === 200){
        console.log(xhr.response);
        list.insertAdjacentHTML("afterbegin", xhr.response);
      } else {
        console.log(xhr.status, xhr.statusText, xhr.responseText);
      }

      clearFormInputs(form);
      enableSubmitButton(form);
    }
  };

  try {
    xhr.send(JSON.stringify(request));
  } catch(e) {
    console.log(e)
  }

  return false;
}

function saveLikedState(postId, state) {
  console.log("save");
  localStorage.setItem(postId, state+"_"+postId);
}

function loadLikedState(postId) {
  console.log(localStorage.getItem(`liked_${postId}`));
  return localStorage.getItem(`liked_${postId}`);
}


window.addEventListener('load', function() {
const currentUrl = window.location.href;
console.log("1");
const url = new URL(currentUrl);
console.log("2");
const searchParams = url.searchParams;
console.log("3");
const postId = searchParams.get('id');
console.log("4");
  const likedState = loadLikedState(postId);
  console.log("5_"+likedState);
  const iconElement = document.getElementById("likeButton"+postId);
  console.log("6");
  if (likedState) {
      // Update the DOM based on the saved state
      if (likedState === 'true') {
          iconElement.innerHTML = "<svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-heart-fill' viewBox='0 0 16 16'><path fill-rule='evenodd' d='M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314'/></svg>";
      } else {  
          iconElement.innerHTML = "<svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-heart' viewBox='0 0 16 16'><path d='m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143q.09.083.176.171a3 3 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15'/></svg>";
      }
  }
  console.log("7");
});