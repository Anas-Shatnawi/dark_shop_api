window.onload = function () {
    // let body = document.querySelector('body');
    // let toggle = document.querySelector('.toggle');
    //let sidebar = document.querySelector('.sidebar');

    document.querySelector('.toggle').addEventListener('click', () => {
        console.log("test");
        document.querySelector('.sidebar').classList.toggle('close')
    })
}
