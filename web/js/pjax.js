setActive()

$(document).pjax('a.plink', '#pjax', {fragment: '#pjax'})

$(document).on('pjax:send', function() {
   NProgress.start()
})

$(document).on('pjax:end', function() {
   NProgress.done()
   setActive()
   $('.form-control').eq(0).focus()
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

$('#sticky').stick_in_parent({
   'offset_top': 70
})