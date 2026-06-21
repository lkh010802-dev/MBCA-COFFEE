const flagButton = document.querySelector(".flag-button");
const flagList = document.querySelector(".flag-list");
const currentFlag = document.querySelector(".current-flag");

if (flagButton && flagList && currentFlag) {
  flagButton.addEventListener("click", () => {
    const isOpen = flagList.classList.toggle("is-open");
    flagButton.setAttribute("aria-expanded", String(isOpen));
  });

  flagList.addEventListener("click", (event) => {
    const button = event.target.closest("button[data-lang]");
    if (!button) return;

    currentFlag.textContent = button.dataset.flag;
    document.documentElement.lang = button.dataset.lang;
    flagList.classList.remove("is-open");
    flagButton.setAttribute("aria-expanded", "false");
  });
}