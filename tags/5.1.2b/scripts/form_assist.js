function openWindow(url){window.open(url,'popup','resizable = 1, scrollbars = 1, top = 100, left = 100, width = 600, height = 550');}
function hideDiv(id){document.getElementById(id).style.visibility='hidden';}
function copyBilling(){document.getElementById('shipping_address').value=document.getElementById('billing_address').value;document.getElementById('shipping_city').value=document.getElementById('billing_city').value;document.getElementById('shipping_province').value=document.getElementById('billing_province').value;document.getElementById('shipping_postal').value=document.getElementById('billing_postal').value;document.getElementById('shipping_country').value=document.getElementById('billing_country').value;};function copyShipping(){document.getElementById('billing_address').value=document.getElementById('shipping_address').value;document.getElementById('billing_city').value=document.getElementById('shipping_city').value;document.getElementById('billing_province').value=document.getElementById('shipping_province').value;document.getElementById('billing_postal').value=document.getElementById('shipping_postal').value;document.getElementById('billing_country').value=document.getElementById('shipping_country').value;};function copyEmail(){document.getElementById('billing_email_address').value=document.getElementById('email_address').value;};function getMarkup(){var price
var cost
price=Number(document.getElementById('price').value)
cost=Number(document.getElementById('cost').value)
markup=Number(((price-cost)/cost)*100)
document.getElementById('markup').value=markup}
function getPrice(){var cost
var markup
var price
cost=Number(document.getElementById('cost').value)
markup=Number(document.getElementById('markup').value)
price=Number(cost+(cost*markup/100))
document.getElementById('price').value=price}
function cleanNumber(obj){obj.value=obj.value.replace(/[^0-9]+/g,'')};function formatNumber(obj){obj.value=obj.value.replace(/(\d{3})(\d{3})/,'('+'$1'+') '+'$2'+'-')};function copyText(content){window.clipboardData.setData('Text',content);}