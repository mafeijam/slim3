$('input').blur(function(){
   $(this).parent().removeClass('has-error')
   $(this).next().fadeOut(300)
   $(this).parent().next().fadeOut(300)
})
