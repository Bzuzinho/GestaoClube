(function(){
  const root = document.getElementById('bscn');
  const btn  = document.getElementById('bscnToggleSidebar');
  const KEY  = 'bscnSidebar';

  function apply(state){
    root.classList.toggle('is-collapsed', state === 'collapsed');
  }
  // estado guardado
  apply(localStorage.getItem(KEY));

  btn && btn.addEventListener('click', function(){
    const collapsed = root.classList.toggle('is-collapsed');
    localStorage.setItem(KEY, collapsed ? 'collapsed' : 'expanded');
  });
})();
