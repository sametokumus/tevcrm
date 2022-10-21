(function($) {
  'use strict';

  /*Tinymce editor*/
  if ($(".tinyMce").length) {
    tinymce.init({
      selector: '.tinyMce',
      min_height: 200,
      theme: 'silver',
      plugins: [
        'advlist autolink lists link image charmap print preview hr anchor pagebreak',
        'searchreplace wordcount visualblocks visualchars code fullscreen powerpaste autoresize'
      ],
      toolbar1: 'bold italic | alignleft aligncenter alignright alignjustify | bullist numlist | link',
      menubar: false,
      image_advtab: true,
      templates: [{
          title: 'Test template 1',
          content: 'Test 1'
        },
        {
          title: 'Test template 2',
          content: 'Test 2'
        }
      ],
		powerpaste_allow_local_images: true,
  		powerpaste_word_import: 'clean',
  		powerpaste_html_import: 'clean',
      content_css: []
    });
  }

})(jQuery);