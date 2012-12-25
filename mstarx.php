<html>
<head>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>

<script type="text/javascript">
var $_GET = <?php echo json_encode($_GET); ?>;
var $ERRORS = "";
$(document).ready(function() {
  // Create dummy empty object to avoid errors
  if (Object.keys($_GET).length == 0) {
    $_GET.symbol = [];
    $_GET.value = [];
  }
  if ($_GET.symbol.length != $_GET.value.length) {
    $ERRORS = "All symbols must have a value";
  }

  // Create a minimum of 20 entry boxes and fill in previous values
  var $num_syms = Math.max(20,Math.min($_GET.symbol.length,$_GET.value.length));
  for (var i=0; i < $num_syms; i++) {
    if (i < $_GET.symbol.length && i < $_GET.value.length) {
      var sym = $_GET.symbol[i];
      var val = $_GET.value[i];
      add_holding(sym,val);
    } else {
      add_holding('','');
    }
  }

  $('p[id="error_txt"]').append($ERRORS);
  update_url();

  $('input[id="geturl"]').click(function() {
    update_url();
    return false;
  });

  $('input[id="xray"]').click(function() {
    var xmlstr = '';
    xmlstr += '<Portfolio>';
    $('div[id="symval_entry"]').each(function(index) {
      var sym = $(this).find('input[name="symbol"]').val();
      var val = parseFloat($(this).find('input[name="value"]').val(),10);
      if (sym != "" && val != "") {
        xmlstr += '<Security>';
        xmlstr += '<Ticker>'+sym+'</Ticker>';
        xmlstr += '<MarketValue>'+val+'</MarketValue>';
        xmlstr += '</Security>';
      }
    });
    xmlstr += '</Portfolio>';
    $('input[name="xmlfile"]').val(xmlstr);
  });
});
function update_url() {
  $('form[name="forurl"]').empty();
  $('div[id="symval_entry"]').each(function(index) {
    var sym = $(this).find('input[name="symbol"]').val();
    var val = parseFloat($(this).find('input[name="value"]').val(),10);
    if (sym != "" && val != "") {
      $(this).find('input[name="value"]').val(val);
      $('form[name="forurl"]').append(
              '<input type="hidden" name="symbol[]" value="'+sym+'"/>'+
              '<input type="hidden" name="value[]" value="'+val+'"/>'
      );
    }
  });
  var url = (window.location.toString().split('?')[0] + '?' +
          $('form[name="forurl"]').serialize());
  $('p[id="url"]').html(url);
}
function add_holding(sym,val) {
  $('input[id="geturl"]').before('<div id="symval_entry"><input type="text" name="symbol" value="'+sym+'"><input type="text" name="value" value="'+val+'"></div>');
}
</script>

<title>Morningstar X-Ray Converter</title>
</head>

<body>
  <p id="error_txt"></p>
  <p>
    This page works-around the lack of a portfolio feature in the free
    version of the amazing
    <a href="http://portfolio.morningstar.com/Rtport/Free/InstantXRayDEntry.aspx">Morningstar X-Ray</a>
    product.
  </p>
  <p>
    You can enter all your holdings here, then click "Get URL"
    for a link you can save. When you use the link to come back to this
    page, all the data will be re-entered automatically.
  </p>
  <p>
    When you want to see the X-Ray analysis, click X-Ray and you will be
    taken to Morningstar's website.
  </p>
  <p>
    For an example, <a href="mstarx.php?symbol%5B%5D=VBMFX&value%5B%5D=20000&symbol%5B%5D=VTSMX&value%5B%5D=48000&symbol%5B%5D=VGTSX&value%5B%5D=24000&symbol%5B%5D=VGSIX&value%5B%5D=8000">click here</a>
    for a Core Four portfolio.
  </p>
  <form method="get" name="entry">
    <input id="geturl" type="submit" value="Get URL"/>
  </form>
  <p id="url"></p>
  <form name="forurl"></form>
  <form name="mstar" action="http://portfolio.morningstar.com/Rtport/Free/InstantXRayDEntry.aspx" method="post">
    <input type="hidden" name="xmlfile" />
    <input type="hidden" name="finalSubmit" value="T"/>
    <input type="hidden" name="InstantMode" value="D"/>
    <input id="xray" type="submit" value="X-Ray"/>
  </form>
</body>
</html>

