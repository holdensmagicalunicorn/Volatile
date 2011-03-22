
var ls={url:'',search_form:'searchform',results_box:'',selected_result:undefined,had_focus:false,init:function(){if($('livesearchpopup_results')==undefined)
return;ls.results_box=$('livesearchpopup_box');ls.results_box.hide();var s=$('s');s.setAttribute("autocomplete","off");s.setAttribute("placeholder","");s.onkeypress=ls.noEnter;new Form.Element.Observer(s,1.0,ls.show_results);Event.observe(s,"keypress",ls.handleKeypress,false);Event.observe(s,"blur",ls.lostFocus,false);Event.observe(s,"change",ls.lostFocus,false);Event.observe(s,"focus",ls.focus,false);Event.observe(ls.results_box,"mousemove",function(event){ls.updateSelection(undefined);})
ls.show_page(s.value,1);},noEnter:function(evt){evt=(evt)?evt:((window.event)?window.event:"")
if(evt){return!(evt.keyCode==13||evt.which==13);}},lostFocus:function(event){window.setTimeout("ls.results_box.hide();",500);},focus:function(event){ls.had_focus=true;value=Form.Element.getValue($('s'));if(value.length>0){ls.show_page(value,1);}},handleKeypress:function(event){ls.had_focus=true;var key=event.which||event.keyCode;switch(key){case 27:ls.close();return false;case Event.KEY_UP:ls.updateSelection(-1);return false;case Event.KEY_DOWN:ls.updateSelection(+1);return false;case Event.KEY_RETURN:if(ls.selected_result!=undefined){var resultlist=$('resultlist');var children=Element.childElements(resultlist);var link=children[ls.selected_result].firstChild;var url=link.href;document.location=url;}
return false;}
return true;},updateSelection:function(diff){var resultlist=$('resultlist');var children=undefined;if(resultlist!=undefined){children=Element.childElements(resultlist);}
if(resultlist==undefined||diff==undefined||resultlist.length==0){ls.selected_result=undefined;if(resultlist!=undefined){var children=Element.childElements(resultlist);for(i=0;i<children.length;i++){children[i].className="resultlistitem";}}}else{var num=0;if(ls.selected_result==undefined){if(diff>0)
num=-1+diff;else
num=children.length+diff;}else{num=ls.selected_result+diff;}
if(num>=children.length){num=children.length-1;}
if(num<0){num=0;}
ls.selected_result=num;for(i=0;i<children.length;i++){if(i==num){children[i].className="resultlistitem_selected";}else{children[i].className="resultlistitem";}}}},show_results:function(element,value){if(ls.had_focus){ls.show_page(value,1);}},show_page:function(s,page){if(s==""){ls.results_box.hide();}else{window.clearTimeout();ls.results_box.show();var pars='s='+s+'&paged='+page;new Ajax.Updater('livesearchpopup_results',ls.url,{method:'get',parameters:pars});}
ls.updateSelection(undefined);},close:function(){Field.clear('s');ls.results_box.hide();}}
Event.observe(window,'load',ls.init,false);
