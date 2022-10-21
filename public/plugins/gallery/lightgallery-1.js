 // lightGallery(document.getElementById('lightgallery'));
 // lightGallery(document.getElementsByClassName('lightgallery'));
 var elements = document.getElementsByClassName('lightgallery');
 for (let item of elements) {
     lightGallery(item, {
         share:false
     })
     console.log(item)
 }