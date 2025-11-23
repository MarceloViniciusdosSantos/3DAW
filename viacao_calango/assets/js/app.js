
document.addEventListener('DOMContentLoaded', function(){
  const open = document.getElementById('openMap');
  if(open){
    open.addEventListener('click', function(e){
      e.preventDefault();
      const bus = document.getElementById('busSelect') ? document.getElementById('busSelect').value : 1;
      const seatWindow = window.open('/seatmap.php?bus_id='+bus,'seatmap','width=700,height=500');
      
      // Focar na janela se já estiver aberta
      if(seatWindow) {
        seatWindow.focus();
      }
    });
  }
  
  // Validação de formulário de compra
  const buyForm = document.querySelector('form[method="post"]');
  if(buyForm) {
    buyForm.addEventListener('submit', function(e) {
      const seatInput = document.getElementById('seatInput');
      if(seatInput && !seatInput.value.trim()) {
        e.preventDefault();
        alert('Por favor, selecione um assento');
        seatInput.focus();
      }
    });
  }
});