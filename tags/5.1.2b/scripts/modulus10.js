function modulus10Check(){clean_cc_number=document.getElementById('number').value;total=0;for(i=clean_cc_number.length-1;i>=0;i=i-2){total=total+parseInt(clean_cc_number.substr(i,1));}
for(i=clean_cc_number.length-2;i>=0;i=i-2){temp=parseInt(clean_cc_number.substr(i,1))*2;if(temp>9){tens=Math.floor(temp/10);ones=temp-(tens*10);temp=tens+ones;}
total=total+temp;}
modresult=(10-total%10)%10;document.getElementById('modulus10_result').value=modresult;return false;}