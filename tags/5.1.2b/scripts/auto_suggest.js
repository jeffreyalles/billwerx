function createObject(){var request_type;var browser=navigator.appName;if(browser=="Microsoft Internet Explorer"){request_type=new ActiveXObject("Microsoft.XMLHTTP");}
else{request_type=new XMLHttpRequest();}
return request_type;}
var http=createObject();function autosuggest(){suggest=document.getElementById('suggest').value;suggest_type=document.getElementById('suggest_type').value;nocache=Math.random();http.open('get','../employees/auto_suggest_'+suggest_type+'.php?query='+suggest+'&nocache = '+nocache);http.onreadystatechange=autosuggestReply;http.send(null);}
function autosuggestReply(){if(http.readyState==4){var response=http.responseText;e=document.getElementById('results');if(response!=""){e.innerHTML=response;e.style.display="block";}
else{e.style.display="none";}}}