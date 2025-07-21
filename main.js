document.addEventListener('DOMContentLoaded', () => { 
    const homeStart = document.querySelector('#home-start')
    const welcomeStart = document.querySelector('#welcome-start')
    const homeNavLinks = document.querySelectorAll('.home-nav');

    const hamburgerBtn = document.getElementById("hamburgerBtn");
    const hamburgerNav = document.getElementById("hamburger-nav");

    const mobileSpaceContainer = document.getElementById("mobile-space-container");
    const mainSection = document.getElementById("main-section");
    const burgerHomeTrigger = document.getElementById("hamburger-home-trigger");

    const contactSection = document.getElementById("contact-section");
    const mainCenter = document.querySelector('.main-center')
    const canvasCenter = document.querySelector('.canvas-centered')

    let sideNavToggle = false;
    // const resultHome = document.querySelector('#home-btn')

    if (homeStart) {
        homeStart.addEventListener('click', (e) => {
            e.preventDefault()
            window.location.href = 'chooseLayout.html'
            // window.location.href = 'welcome.html'
        })
    }

    if(mainCenter) {
        mainCenter.style.display = "flex";

    }

    if (canvasCenter) {
        canvasCenter.style.zIndex = "4"
        canvasCenter.style.display = "flex";
    }

    // if (welcomeStart) {
    //     welcomeStart.addEventListener('click', (e) => {
    //         e.preventDefault()
    //         window.location.href = 'canvas.html'
    //     })
    // }
    
    //sidebar nav
    if (hamburgerBtn) {
        burgerHomeTrigger.style.display = "flex";          
        hamburgerNav.style.position = "absolute";
        hamburgerNav.style.opacity = "0";
        hamburgerNav.style.display = "none";

        hamburgerBtn.addEventListener('click', () => {
            sideNavToggle = !sideNavToggle;
    
            if (sideNavToggle) {
                // alert("true");
                console.log("on");

                burgerHomeTrigger.style.display = "none";
                

                hamburgerNav.style.position = "relative";
                hamburgerNav.style.opacity = "1";
                hamburgerNav.style.display = "block";
                

                if(mainSection) {
                    mainSection.style.display = "none";
                }

                if(mainCenter) {
                    mainCenter.style.display = "none";
                }

                if(contactSection) {
                    contactSection.style.zIndex = "0";
                }
                
                
                
                // hamburgerNav.style.zIndex = "2";
                // mainSection.style.marginTop = "50px";
                
            }
            else {
                // alert("false");
                console.log("off");

                burgerHomeTrigger.style.display = "flex";
                
                hamburgerNav.style.position = "absolute";
                hamburgerNav.style.opacity = "0";
                hamburgerNav.style.display = "none";
                

                if(mainSection) {
                    mainSection.style.display = "flex";

                }

                if(mainCenter) {
                    mainCenter.style.display = "flex";

                }

                if(contactSection) {
                    contactSection.style.zIndex = "2"
                }
                
                // hamburgerNav.style.zIndex = "-1";
                // mainSection.style.marginTop = "150px";
            }
            
        })
    }
    

    if (burgerHomeTrigger) {
        burgerHomeTrigger.addEventListener('click', () => {
            sideNavToggle = !sideNavToggle;
    
            if (sideNavToggle) {
                // alert("true");
                console.log("on");
                burgerHomeTrigger.style.display = "none";
                

                hamburgerNav.style.position = "relative";
                hamburgerNav.style.opacity = "1";
                hamburgerNav.style.display = "block";
                
                if(mainSection) {
                    mainSection.style.display = "none";
                }

                if(mainCenter) {
                    mainCenter.style.display = "none";
                }

                if(contactSection) {
                    contactSection.style.zIndex = "0";
                }
                
            }
            else {
                // alert("false");
                console.log("off");

                burgerHomeTrigger.style.display = "flex";

                hamburgerNav.style.position = "absolute";
                hamburgerNav.style.opacity = "0";
                hamburgerNav.style.display = "none";
                
                if(mainSection) {
                    mainSection.style.display = "flex";

                }

                if(mainCenter) {
                    mainCenter.style.display = "flex";

                }

                if(contactSection) {
                    contactSection.style.zIndex = "2"
                }
            }
        })
    }

    homeNavLinks.forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault()
          
          const linkText = link.textContent.trim()
          
          switch(linkText) {
              case 'home':
                  window.location.href = 'index.html';
                  break;
              case 'about':
                  window.location.href = 'about.html';
                  break;
              case 'FAQ':
                  window.location.href = 'FAQ.html';    
                  break;
              case 'privacy policy':
                  window.location.href = 'privacy.html';
                  break;
              case 'contact':
                  window.location.href = 'contact.html';
                  break;
              case 'choose layout':
                  window.location.href = 'chooseLayout.html';
                  break;
              default:
                  alert('No navigation defined for this link');
            }
        });
    });

    

    // if (resultHome) {
    //     resultHome.addEventListener('click', (e) => {
    //         e.preventDefault()
    //         window.location.href = 'index.html'
    //     })
    // }
})