window.onload = function() {

  var initialShow = 28;
  var displayFrequency = 298;
  var showTime = 60;

  var $canvas = $('<div  />', { width: "1222px", height: "884px" });
  $canvas.css({
            position: 'absolute',
                 top: '0px',
                left: '0px',
  });
   
  $canvas.hide();
  $canvas.prependTo('body');

  var $splashScreen = $('<iframe  />', { width: "1000px", height: "700px", src: 'http://new.portal.res.clixconnect.net/games/snakesandladders' });
  $splashScreen.css({
    overflow: 'hidden',
    border: '1px solid black',
    position: 'absolute',
    left: '80px',
    top: '80px',
    borderRadius: '15px',
    boxShadow: '0px 0px 10px black',
  });

//  $splashScreen.prependTo($canvas);

  setTimeout(function() {
    runExchange();
  }, (initialShow*1000));

  function runExchange()
  {
    $splashScreen.prependTo($canvas);
    $canvas.delay(2000).fadeIn(1000).delay((showTime*1000)).fadeOut(1000, function() {
       $splashScreen.remove();
       setTimeout(function() {
         runExchange();
       }, (displayFrequency*1000));
    });
  }


};
