$(function() {
  // Obsługa kliknięcia w przycisk kopiowania BLIK
  $(document).on('click', '.blik-button', function() {
    const blikCode = $('.fs-3.fw-bold').text();
    navigator.clipboard.writeText(blikCode).then(() => {
      alert('Kod BLIK skopiowany do schowka!');
    }).catch(() => {
      alert('Nie udało się skopiować kodu BLIK.');
    });
  });

  // Obsługa kliknięcia przycisku "Zobacz więcej"
  $(document).on('click', '#zobacz-wiecej', function() {
    const pozostalaHistoria = $('#historia-pozostala');
    if (pozostalaHistoria.hasClass('d-none')) {
      pozostalaHistoria.removeClass('d-none');
      $(this).text('Pokaż mniej');
    } else {
      pozostalaHistoria.addClass('d-none');
      $(this).text('Zobacz więcej');
    }
  });
});
