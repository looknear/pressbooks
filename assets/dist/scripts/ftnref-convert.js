!function(t){function n(o){if(e[o])return e[o].exports;var r=e[o]={i:o,l:!1,exports:{}};return t[o].call(r.exports,r,r.exports,n),r.l=!0,r.exports}var e={};n.m=t,n.c=e,n.d=function(t,e,o){n.o(t,e)||Object.defineProperty(t,e,{configurable:!1,enumerable:!0,get:o})},n.n=function(t){var e=t&&t.__esModule?function(){return t.default}:function(){return t};return n.d(e,"a",e),e},n.o=function(t,n){return Object.prototype.hasOwnProperty.call(t,n)},n.p="",n(n.s=7)}({7:function(t,n,e){t.exports=e("9sVg")},"9sVg":function(t,n){!function(){tinymce.create("tinymce.plugins.ftnref_convert",{init:function(t,n){t.addButton("ftnref_convert",{title:PB_FootnotesToken.ftnref_title,icon:"icon dashicons-screenoptions",onclick:function(){jQuery.ajax({type:"post",dataType:"json",url:ajaxurl,data:{action:"pb_ftnref_convert",content:t.getContent(),_ajax_nonce:PB_FootnotesToken.nonce},beforeSend:function(){t.setProgressState(1)},success:function(n,e,o){t.setProgressState(0),t.setContent(n.content,{format:"raw"})},error:function(n){t.setProgressState(0),jQuery.trim(n.responseText).length&&alert(n.responseText)}})}})},createControl:function(t,n){return null}}),tinymce.PluginManager.add("ftnref_convert",tinymce.plugins.ftnref_convert)}()}});