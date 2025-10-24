(function(){
  const shell = document.getElementById('app-shell');
  const btn   = document.getElementById('sidebar-toggle');
  const key   = 'bscn.sidebar';

  const state = (localStorage.getItem(key) ?? 'open');
  if(state === 'closed') shell.classList.add('is-collapsed');
  btn?.setAttribute('aria-label', shell.classList.contains('is-collapsed') ? 'Expandir menu' : 'Recolher menu');

  btn?.addEventListener('click', () => {
    const collapsed = shell.classList.toggle('is-collapsed');
    localStorage.setItem(key, collapsed ? 'closed' : 'open');
    btn.setAttribute('aria-pressed', collapsed ? 'true' : 'false');
    btn.setAttribute('aria-label', collapsed ? 'Expandir menu' : 'Recolher menu');
  });
})();


// Modais dinâmicos (AJAX)
window.BSCN = {
openModalFrom(url){
fetch(url,{headers:{'X-Requested-With':'XMLHttpRequest'}})
.then(r=>r.text())
.then(html=>{
const wrap = document.createElement('div');
wrap.innerHTML = html; document.body.appendChild(wrap);
const close = () => wrap.remove();
wrap.querySelectorAll('[data-close]')?.forEach(x=>x.addEventListener('click',close));
});
}
}


document.addEventListener('click', (e)=>{
const t = e.target.closest('[data-modal]');
if(!t) return;
e.preventDefault();
BSCN.openModalFrom(t.getAttribute('data-modal'));
});