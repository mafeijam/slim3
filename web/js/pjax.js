setActive()

$(document).pjax('a.plink', '#pjax', {fragment: '#pjax'})

$(document).on('pjax:send', function() {
   NProgress.start()
})

$(document).on('pjax:end', function(){
   NProgress.done()
   setActive()
   var i = document.location.pathname == '/update-profile' ? 1 : 0
   $('.form-control').eq(i).focus()
   $('#sticky').stick_in_parent({
      'offset_top': 70
   })
})

function setActive() {
   $('.main-nav > li.sub-item').each(function(){
      if ($(this).children().attr('href') == document.location.pathname) {
         $(this).addClass('active').siblings().removeClass('active')
      } else {
         $(this).removeClass('active')
      }
   })
}