const menuButton = document.querySelector(".menu-button");
const globalMenu = document.querySelector(".global-menu");

if (menuButton && globalMenu) {
  menuButton.addEventListener("click", () => {
    const isOpen = globalMenu.classList.toggle("is-open");
    menuButton.setAttribute("aria-expanded", String(isOpen));
  });
}