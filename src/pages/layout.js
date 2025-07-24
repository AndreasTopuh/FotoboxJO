document.addEventListener('DOMContentLoaded', () => { 
    const layoutBtn1 = document.querySelector('#layout1Btn')
    const layoutBtn2 = document.querySelector('#layout2Btn')
    const layoutBtn3 = document.querySelector('#layout3Btn')
    const layoutBtn4 = document.querySelector('#layout4Btn')
    const layoutBtn5 = document.querySelector('#layout5Btn')
    const layoutBtn6 = document.querySelector('#layout6Btn')

    if (layoutBtn1) {
        layoutBtn1.addEventListener('click', (e) => {
            e.preventDefault()
            window.location.href = './canvasLayout1.php' // Layout 1 - 2 photos
        })
    }

    if (layoutBtn2) {
        layoutBtn2.addEventListener('click', (e) => {
            e.preventDefault()
            window.location.href = './canvasLayout2.php' // Layout 2 - 4 photos
        })
    }

    if (layoutBtn3) {
        layoutBtn3.addEventListener('click', (e) => {
            e.preventDefault()
            window.location.href = './canvasLayout3.php' // Layout 3 - 6 photos
        })
    }

    if(layoutBtn4) {
        layoutBtn4.addEventListener('click', (e) => {
            e.preventDefault()
            window.location.href = './canvasLayout4.php' // Layout 4 - 8 photos
        })
    }

    if(layoutBtn5) {
        layoutBtn5.addEventListener('click', (e) => {
            e.preventDefault()
            window.location.href = './canvasLayout5.php' // Layout 5 - 6 photos
        })
    }

    if(layoutBtn6) {
        layoutBtn6.addEventListener('click', (e) => {
            e.preventDefault()
            window.location.href = './canvasLayout6.php' // Layout 6 - 4 photos
        })
    }

    // Original Canvas Buttons
    // const canvasBtn = document.querySelector('#canvasBtn')
    const canvas2Btn = document.querySelector('#canvas2Btn')
    const canvas4Btn = document.querySelector('#canvas4Btn')
    const canvas6Btn = document.querySelector('#canvas6Btn')

    // if (canvasBtn) {
    //     canvasBtn.addEventListener('click', (e) => {
    //         e.preventDefault()
    //         window.location.href = './canvas.php' // Original canvas - 1 photo
    //     })
    // }

    if (canvas2Btn) {
        canvas2Btn.addEventListener('click', (e) => {
            e.preventDefault()
            window.location.href = './canvas2.php' // Canvas 2 - 2 photos
        })
    }

    if (canvas4Btn) {
        canvas4Btn.addEventListener('click', (e) => {
            e.preventDefault()
            window.location.href = './canvas4.php' // Canvas 4 - 4 photos
        })
    }

    if (canvas6Btn) {
        canvas6Btn.addEventListener('click', (e) => {
            e.preventDefault()
            window.location.href = './canvas6.php' // Canvas 6 - 6 photos
        })
    }

});