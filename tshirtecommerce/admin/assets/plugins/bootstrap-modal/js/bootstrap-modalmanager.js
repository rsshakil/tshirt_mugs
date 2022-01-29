!function(e){"use strict";function t(e){return function(t){if(this===t.target)return e.apply(this,arguments)}}var n=function(e,t){this.init(e,t)};n.prototype={constructor:n,init:function(t,n){if(this.$element=e(t),this.options=e.extend({},e.fn.modalmanager.defaults,this.$element.data(),"object"==typeof n&&n),this.stack=[],this.backdropCount=0,this.options.resize){var o,a=this;e(window).on("resize.modal",function(){o&&clearTimeout(o),o=setTimeout(function(){for(var e=0;e<a.stack.length;e++)a.stack[e].isShown&&a.stack[e].layout()},10)})}},createModal:function(t,n){e(t).modal(e.extend({manager:this},n))},appendModal:function(n){this.stack.push(n);var o=this;n.$element.on("show.modalmanager",t(function(t){var a=function(){n.isShown=!0;var t=e.support.transition&&n.$element.hasClass("fade");o.$element.toggleClass("modal-open",o.hasOpenModal()).toggleClass("page-overflow",e(window).height()<o.$element.height()),n.$parent=n.$element.parent(),n.$container=o.createContainer(n),n.$element.appendTo(n.$container),o.backdrop(n,function(){n.$element.show(),t&&n.$element[0].offsetWidth,n.layout(),n.$element.addClass("in").attr("aria-hidden",!1);var a=function(){o.setFocus(),n.$element.trigger("shown")};t?n.$element.one(e.support.transition.end,a):a()})};n.options.replace?o.replace(a):a()})),n.$element.on("hidden.modalmanager",t(function(t){o.backdrop(n),n.$backdrop?(e.support.transition&&n.$element.hasClass("fade")&&n.$element[0].offsetWidth,e.support.transition&&n.$element.hasClass("fade")?n.$backdrop.one(e.support.transition.end,function(){o.destroyModal(n)}):o.destroyModal(n)):o.destroyModal(n)})),n.$element.on("destroy.modalmanager",t(function(e){o.removeModal(n)}))},destroyModal:function(e){e.destroy();var t=this.hasOpenModal();this.$element.toggleClass("modal-open",t),t||this.$element.removeClass("page-overflow"),this.removeContainer(e),this.setFocus()},getOpenModals:function(){for(var e=[],t=0;t<this.stack.length;t++)this.stack[t].isShown&&e.push(this.stack[t]);return e},hasOpenModal:function(){return this.getOpenModals().length>0},setFocus:function(){for(var e,t=0;t<this.stack.length;t++)this.stack[t].isShown&&(e=this.stack[t]);e&&e.focus()},removeModal:function(e){e.$element.off(".modalmanager"),e.$backdrop&&this.removeBackdrop(e),this.stack.splice(this.getIndexOfModal(e),1)},getModalAt:function(e){return this.stack[e]},getIndexOfModal:function(e){for(var t=0;t<this.stack.length;t++)if(e===this.stack[t])return t},replace:function(n){for(var o,a=0;a<this.stack.length;a++)this.stack[a].isShown&&(o=this.stack[a]);o?(this.$backdropHandle=o.$backdrop,o.$backdrop=null,n&&o.$element.one("hidden",t(e.proxy(n,this))),o.hide()):n&&n()},removeBackdrop:function(e){e.$backdrop.remove(),e.$backdrop=null},createBackdrop:function(t,n){var o;return this.$backdropHandle?((o=this.$backdropHandle).off(".modalmanager"),this.$backdropHandle=null,this.isLoading&&this.removeSpinner()):o=e(n).addClass(t).appendTo(this.$element),o},removeContainer:function(e){e.$container.remove(),e.$container=null},createContainer:function(n){var a;return a=e('<div class="modal-scrollable">').css("z-index",o("modal",this.getOpenModals().length)).appendTo(this.$element),n&&"static"!=n.options.backdrop?a.on("click.modal",t(function(e){n.hide()})):n&&a.on("click.modal",t(function(e){n.attention()})),a},backdrop:function(t,n){var a=t.$element.hasClass("fade")?"fade":"",i=t.options.backdrop&&this.backdropCount<this.options.backdropLimit;if(t.isShown&&i){var s=e.support.transition&&a&&!this.$backdropHandle;t.$backdrop=this.createBackdrop(a,t.options.backdropTemplate),t.$backdrop.css("z-index",o("backdrop",this.getOpenModals().length)),s&&t.$backdrop[0].offsetWidth,t.$backdrop.addClass("in"),this.backdropCount+=1,s?t.$backdrop.one(e.support.transition.end,n):n()}else if(!t.isShown&&t.$backdrop){t.$backdrop.removeClass("in"),this.backdropCount-=1;var r=this;e.support.transition&&t.$element.hasClass("fade")?t.$backdrop.one(e.support.transition.end,function(){r.removeBackdrop(t)}):r.removeBackdrop(t)}else n&&n()},removeSpinner:function(){this.$spinner&&this.$spinner.remove(),this.$spinner=null,this.isLoading=!1},removeLoading:function(){this.$backdropHandle&&this.$backdropHandle.remove(),this.$backdropHandle=null,this.removeSpinner()},loading:function(t){if(t=t||function(){},this.$element.toggleClass("modal-open",!this.isLoading||this.hasOpenModal()).toggleClass("page-overflow",e(window).height()<this.$element.height()),this.isLoading)if(this.isLoading&&this.$backdropHandle){this.$backdropHandle.removeClass("in");var n=this;e.support.transition?this.$backdropHandle.one(e.support.transition.end,function(){n.removeLoading()}):n.removeLoading()}else t&&t(this.isLoading);else{this.$backdropHandle=this.createBackdrop("fade",this.options.backdropTemplate),this.$backdropHandle[0].offsetWidth;var a=this.getOpenModals();this.$backdropHandle.css("z-index",o("backdrop",a.length+1)).addClass("in");var i=e(this.options.spinner).css("z-index",o("modal",a.length+1)).appendTo(this.$element).addClass("in");this.$spinner=e(this.createContainer()).append(i).on("click.modalmanager",e.proxy(this.loading,this)),this.isLoading=!0,e.support.transition?this.$backdropHandle.one(e.support.transition.end,t):t()}}};var o=function(){var t,n={};return function(o,a){if(void 0===t){var i=e('<div class="modal hide" />').appendTo("body"),s=e('<div class="modal-backdrop hide" />').appendTo("body");n.modal=+i.css("z-index"),n.backdrop=+s.css("z-index"),t=n.modal-n.backdrop,i.remove(),s.remove(),s=i=null}return n[o]+t*a}}();e.fn.modalmanager=function(t,o){return this.each(function(){var a=e(this),i=a.data("modalmanager");i||a.data("modalmanager",i=new n(this,t)),"string"==typeof t&&i[t].apply(i,[].concat(o))})},e.fn.modalmanager.defaults={backdropLimit:999,resize:!0,spinner:'<div class="loading-spinner fade" style="width: 200px; margin-left: -100px;"><div class="progress progress-striped active"><div class="bar" style="width: 100%;"></div></div></div>',backdropTemplate:'<div class="modal-backdrop" />'},e.fn.modalmanager.Constructor=n}(jQuery);