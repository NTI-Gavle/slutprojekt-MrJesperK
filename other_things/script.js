const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))

function Shenanigans() {
  var password = document.getElementById("password");
  if (password.type === "password"){
    password.type = "text";
    textContent = "Hide password";
  }
  else{
    password.type = "password";
    textContent = "Show password";
  }
}

function modal1(){
    const Modal = document.getElementById('PostModal')
    const Input = document.getElementById('modalInput1')
    
    Modal.addEventListener('shown.bs.modal', () => {
      Input.focus()
    })
}

function modal2(){
    const Modal = document.getElementById('LoginModal')
    const Input = document.getElementById('modalInput2')
    
    Modal.addEventListener('shown.bs.modal', () => {
      Input.focus()
    })
}

function loginFormStuff(){
  document.getElementById("thing").addEventListener("click", function(event){
    event.preventDefault()
  });
  
}

function modal3(){
  const Modal = document.getElementById('CommentModal')
  const Input = document.getElementById('modalInput3')
  
  Modal.addEventListener('shown.bs.modal', () => {
    Input.focus()
  })
}

function modal4(){
  const Modal = document.getElementById('deleteModal')
  const Input = document.getElementById('modalInput4')
  
  Modal.addEventListener('shown.bs.modal', () => {
    Input.focus()
  })
}

function modal5(){
  const Modal = document.getElementById('DeleteUserModal')
  const Input = document.getElementById('Input')
  
  Modal.addEventListener('shown.bs.modal', () => {
    Input.focus()
  })
}
