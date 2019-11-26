$(function () {
   $('body').on('click','.generate-apples',function () {
      $.ajax({
         url: '/index.php?r=site/generate-apples',
         success: function(data) {
            if(data.result === 'success'){
               location.reload();
            }
         }
      });
   });

   $('body').on('click','.leaves .apple',function () {
      $.ajax({
         url: '/index.php?r=site/fall-to-ground',
         data: {id : $(this).attr('id')},
         method: 'get',
         success: function(data) {
            if(data.result === 'success'){
               location.reload();
            }
         }
      });
   });

   $('body').on('click','.eat-button',function () {
      const  data = $('#eat-form').serialize();
      $.ajax({
         url: '/index.php?r=site/eat',
         data: data,
         method: 'get',
         success: function(data) {
            if(data.result === 'success'){
               location.reload();
            }else{
               $('#eat-modal').modal('hide');
               alert(data.error);
            }
         }
      });
   });
   $('#eat-modal').on('show.bs.modal', function (event) {
      const button = $(event.relatedTarget);
      const id = button.data('id');
      const percent = button.data('percent');
      $(this).find('#eat-apple').val(id);
      $(this).find('#cur-percent').text(percent);
   });
   $('.ground .apple').tooltip(options)
});