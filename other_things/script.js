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