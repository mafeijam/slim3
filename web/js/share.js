$('#marked').keyup(function(){
   var m = marked($('#marked').val());
   $('#marked-preview').html(m)
   $('pre code').each(function(i, block) {
      hljs.highlightBlock(block)
   })
})

$('#show').click(function(){
   $('#preview').toggleClass('hide')
})

$('[data-toggle="tooltip"]').tooltip()

$('body').on('keydown', 'textarea', function(e) {
   $(this).removeClass('textarea-error')
   $(this).parent().next('span').fadeOut(300)

   var keyCode = e.keyCode || e.which

   if (keyCode === 9) {
      e.preventDefault()
      var start = this.selectionStart
      var end = this.selectionEnd
      var val = this.value
      var selected = val.substring(start, end)
      var re = /^/gm
      var count = selected.match(re).length

      if (selected) {
         this.value = val.substring(0, start) + selected.replace(re, '\t') + val.substring(end)
         this.selectionStart = start
         this.selectionEnd = end + count
      } else {
         this.value += '    '
      }
   }
})

$('select').change(function(){
   if ($(this).val().trim()) {
      $(this).parent().removeClass('has-error')
      $(this).next().fadeOut(300)
      $(this).parent().next().fadeOut(300)
   }
})
