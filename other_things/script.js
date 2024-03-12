const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))

function modal1(){
    const Modal = document.getElementById('PostModal')
    const Input = document.getElementById('Input')
    
    Modal.addEventListener('shown.bs.modal', () => {
      Input.focus()
    })
}

function modal2(){
    const Modal = document.getElementById('LoginModal')
    const Input = document.getElementById('Input')
    
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
  const Input = document.getElementById('Input')
  
  Modal.addEventListener('shown.bs.modal', () => {
    Input.focus()
  })
}

function modal4(){
  const Modal = document.getElementById('ReplyModal')
  const Input = document.getElementById('Input')
  
  Modal.addEventListener('shown.bs.modal', () => {
    Input.focus()
  })
}