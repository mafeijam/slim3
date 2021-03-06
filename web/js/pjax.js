setActive()

$(document).pjax('a.plink', '#pjax', {fragment: '#pjax'})

$(document).on('pjax:send', function() {
   NProgress.start()
})

$(document).on('pjax:end', function(){
   NProgress.done()
   setActive()

   if (document.location.pathname == '/') {
      $.getScript('//jamwong.me/callback.js')
   }

   $('[data-toggle="tooltip"]').tooltip()
})

function setActive() {
   $('.main-nav > li.sub-item').each(function(){
      if ($(this).children().attr('href') == document.location.pathname) {
         $(this).addClass('active').siblings().removeClass('active')
      }
   })
}