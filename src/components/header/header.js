const hamburgerMenu = document.querySelector(".hamburger-menu");
const menu = document.querySelector(".header__nav");
const headerClass = document.querySelector("header");

function header() {
  hamburgerMenu?.addEventListener("click", () => {
    hamburgerMenu.classList.toggle("is-active");
    if (hamburgerMenu.classList.contains("is-active")) {
      menu.style.display = "flex";
    } else {
      menu.style.display = "none";
    }
  });


  window.addEventListener("scroll", () => {
    if (window.pageYOffset > 150) {
      headerClass.classList.add("fixed");
    } else {
      headerClass.classList.remove("fixed");
    }
  });
}


export default header;
