let btns = document.querySelectorAll('.eBtn');
let btn2 = document.querySelector('#btn2');
let overlay = document.querySelector('#overlay');

btns.forEach(function(btn){
    btn.addEventListener('click' , function(e){
        overlay.style.display = 'flex'; 
    })
})


btn2.addEventListener('click' , function(){
    overlay.style.display = 'none';
})

overlay.addEventListener('click' , function(e){
    if(e.target === overlay){
        overlay.style.display = 'none';
    }
}) 

