document.querySelectorAll('.error-msg, .success-msg').forEach(el=>{
  setTimeout(()=>{ el.style.transition='opacity .3s'; el.style.opacity='0';
    setTimeout(()=> el.remove(), 300);
  }, 4000);
});
