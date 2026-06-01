let btn = document.querySelector('#backToTop');

window.addEventListener('scroll' , function(){
    if(this.scrollY>=300){
        btn.style.display = 'block'
    }else{
        btn.style.display = 'none'
    }
})

btn.addEventListener('click' , function(){
    window.scrollTo(0,0)
})