$('ul.nav li.dropdown').hover(function() {
  $(this).find('.dropdown-menu').stop(true, true).delay(20).fadeIn(50);
}, function() {
  $(this).find('.dropdown-menu').stop(true, true).delay(20).fadeOut(50);
});