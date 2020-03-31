import prestashop from 'prestashop';
import $ from 'jquery';

prestashop.blockcart = prestashop.blockcart || {};

prestashop.blockcart.showModal = function(html) {
  function getBlockCartModal() {
    return $('#blockcart-modal');
  }

  let $blockCartModal = getBlockCartModal();
  if ($blockCartModal.length){
    $blockCartModal.remove();
  }

  $('body').append(html);

  $blockCartModal = getBlockCartModal();
  $blockCartModal.modal('show');

  $blockCartModal.on('hidden.bs.modal', function(event) {
    if (prestashop.page.page_name == 'product') {
      prestashop.emit('updateProduct', {});
    }
  });
};

