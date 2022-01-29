
$(document).ready( function() {
function spmenu () {
    var modalTrigger = $('.nav-icon'),
        transitionLayer = $('.cd-transition-layer'),
        transitionBackground = transitionLayer.children(),
        modalWindow = $('.full-menu');

    var frameProportion = 1.78,
        frames = 25,
        resize = false;

    setLayerDimensions();
    $(window).on('resize', function(){
        if( !resize ) {
            resize = true;
            (!window.requestAnimationFrame) ? setTimeout(setLayerDimensions, 300) : window.requestAnimationFrame(setLayerDimensions);
        }
    });

    modalTrigger.on('click', function(event){
        event.preventDefault();
        transitionLayer.addClass('visible opening');
        var delay = ( $('.no-cssanimations').length > 0 ) ? 0 : 150;
        setTimeout(function(){
            modalWindow.addClass('visible');
        }, delay);
    });

    modalWindow.on('click', '.modal-close', function(event){
        event.preventDefault();
        transitionLayer.addClass('closing');
        modalWindow.removeClass('visible');
        transitionBackground.one('webkitAnimationEnd oanimationend msAnimationEnd animationend', function(){
            transitionLayer.removeClass('closing opening visible');
            transitionBackground.off('webkitAnimationEnd oanimationend msAnimationEnd animationend');
        });
    });

    function setLayerDimensions() {
        var windowWidth = $(window).width(),
            windowHeight = $(window).height(),
            layerHeight, layerWidth;

        if( windowWidth/windowHeight > frameProportion ) {
            layerWidth = windowWidth;
            layerHeight = layerWidth/frameProportion;
        } else {
            layerHeight = windowHeight*1.2;
            layerWidth = layerHeight*frameProportion;
        }

        transitionBackground.css({
            'width': layerWidth*frames+'px',
            'height': layerHeight+'px',
        });

        resize = false;
    }

}
spmenu()
});

if($('.js_syncTarget').length){

  var myBreakPoint = 840;//ブレイクポイント
  var target = $('.js_syncTarget');//高さをそろえたい要素の親要素
  var target_c;//高さをそろえる要素
  var group_amount;//高さを揃えたいグループの総数
  var maxHeight = 0;//グループ内で最も高い要素を記憶する

  function heightSync(target){

    target.each(function(){

      group_amount = $(this).data('group_amount');

      for (var i = 0; i < group_amount; i++) {

        maxHeight = 0;

        console.log(i);

        target_c = $(this).find('.js_syncTarget_c.g' + (i + 1));
        target_c.height('auto');

        target_c.each(function(){
          if($(this).height() > maxHeight) {
            maxHeight = $(this).height();
          }
        });

        target_c.height(maxHeight);

      }

    });

  }

  heightSync(target);

  $(window).resize(function(){
    var w = $(window).width();
    if(w > myBreakPoint) {
      heightSync(target);
    } else {
      $('.js_syncTarget_c').height('auto');
    }

  });

}
