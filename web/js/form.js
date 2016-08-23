$('input').blur(function(){
   $(this).parent().removeClass('has-error')
   $(this).next().remove()
   $(this).parent().next().remove()
})
