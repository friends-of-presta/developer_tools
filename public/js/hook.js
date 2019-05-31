window.addEventListener("DOMContentLoaded", () => {
  const hooks = document.querySelectorAll('.hook_debug')

  for (let hook of hooks) {
    hook.addEventListener('click', (e) => {
      e.target.remove();
    })
  }
})
