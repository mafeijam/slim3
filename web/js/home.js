$('.auto-disappear').delay(3500).fadeOut(1000)

$('body').on('click', '.auto-disappear', function(){
   $('.auto-disappear').stop()
})

$('body').on('click', 'button', function(){
   $(this).data('loading-text', '<i class="fa fa-refresh fa-spin mr-7"></i>處理中...')
   $(this).button('loading')
})

$('[data-toggle="tooltip"]').tooltip()