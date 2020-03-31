$(window).ready(function () {
  $('.blockreassurance_product img.svg, .blockreassurance img.svg').each(function () {
    var imgObject = $(this);
    var imgID = imgObject.attr('id');
    var imgClass = imgObject.attr('class');
    var imgURL = imgObject.attr('src');

    $.ajax({
      url: imgURL,
      type: 'GET',
      success: function(data){
        if ($.isXMLDoc(data)) {
          // Get the SVG tag, ignore the rest
          var $svg = $(data).find('svg');
          // Add replaced image's ID to the new SVG
          $svg = typeof imgID !== 'undefined' ? $svg.attr('id', imgID) : $svg;
          // Add replaced image's classes to the new SVG
          $svg = typeof imgClass !== 'undefined' ? $svg.attr('class', imgClass + ' replaced-svg') : $svg.attr('class', ' replaced-svg');
          $svg.removeClass('invisible');
          // Add URL in data
          $svg = $svg.attr('data-img-url', imgURL);
          // Remove any invalid XML tags as per http://validator.w3.org
          $svg = $svg.removeAttr('xmlns:a');
          // Set color defined in backoffice
          $svg.find('path[fill]').attr('fill', psr_icon_color);
          $svg.find('path:not([fill])').css('fill', psr_icon_color);
          // Replace image with new SVG
          imgObject.replaceWith($svg);
        }
        imgObject.removeClass('invisible');
      }
    });
  });
});
