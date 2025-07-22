document.addEventListener('DOMContentLoaded', () => { 
    const layoutBtn1 = document.querySelector('#layout1Btn')
    const layoutBtn2 = document.querySelector('#layout2Btn')
    const layoutBtn3 = document.querySelector('#layout3Btn')
    const layoutBtn4 = document.querySelector('#layout4Btn')

    if (layoutBtn1) {
        layoutBtn1.addEventListener('click', (e) => {
            e.preventDefault()
            window.location.href = 'canvas.php'
        })
    }

    if (layoutBtn2) {
        layoutBtn2.addEventListener('click', (e) => {
            e.preventDefault()
            window.location.href = 'canvas4.php'
        })
    }

    if (layoutBtn3) {
        layoutBtn3.addEventListener('click', (e) => {
            e.preventDefault()
            window.location.href = 'canvas2.php'
        })
    }

    if(layoutBtn4) {
        layoutBtn4.addEventListener('click', (e) => {
            e.preventDefault()
            window.location.href = 'canvas6.php'
        })
    }

});