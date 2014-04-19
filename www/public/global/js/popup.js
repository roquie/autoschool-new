(function(e,c,a,f){var d="popupWin";function b(h,g){this.init(h,g)}b.prototype={constructor:b,init:function(k,h){this.$element=e(k);var g,j;this.options=e.extend({},e.fn[d].defaults,h);g=this.options.trigger;if(this.options.width!=""){e(this.options.container).css({"min-width":this.options.width})}switch(g){case"click":this.$element.on("click",e.proxy(this.toggle,this));break;case"hover":this.$element.on("mouseenter",e.proxy(this.enter,this));e(this.options.container).parent().on("mouseleave",e.proxy(this.leave,this));break}e(a).on("click",e.proxy(this.documentClick,this))},documentClick:function(j){var i=this.$element,h=e(j.target),g=i.closest("li");if(h.closest(i.parent()[0]).length==0){if(!e(this.options.container).hasClass("hide")){this.hide();g.hasClass("active")?g.removeClass("active"):g.addClass("active")}}},toggle:function(i){i.preventDefault();var h=e(i.currentTarget),g=h.closest("li");g.hasClass("active")?g.removeClass("active"):g.addClass("active");e(this.options.container).hasClass("hide")?this.show():this.hide()},enter:function(i){var h=e(i.currentTarget),g=h.closest("li");if(e(this.options.container).hasClass("hide")){g.addClass("active");this.show()}},leave:function(j){var i=this.$element,h=e(j.target),g=i.closest("li");if(!e(this.options.container).hasClass("hide")){this.hide();g.removeClass("active")}},show:function(){var h=e(this.options.container),i,j=this.$element.height(),g="";i=this.options.location;switch(i){case"bottom":j=(j+this.options.edgeOffset+10)+"px";g="_bottom";break;case"top":j="-"+(j+this.options.edgeOffset+10)+"px";g="_top";break;case"left":j=(j+this.options.edgeOffset+10)+"px";g="_left";break;case"right":j=(j+this.options.edgeOffset+10)+"px";g="_right";break}this.top=j;h.addClass("popup"+g);h.css({top:j});h.removeClass("hide").css({opacity:0}).animate({top:"-="+10,opacity:1},this.options.delay).show()},hide:function(){var g=e(this.options.container);g.animate({top:this.top,opacity:0},this.options.delay,function(){g.addClass("hide").hide()})}};e.fn[d]=function(g){return this.each(function(){var j=e(this),i=j.data("plugin_"+d),h=typeof g=="object"&&g;if(!i){j.data("plugin_"+d,(i=new b(this,h)))}if(typeof g=="string"){i[g]()}})};e.fn.popupWin.defaults={container:"#popup",location:"bottom",edgeOffset:3,delay:300,trigger:"click",width:""}})(jQuery,window,document);