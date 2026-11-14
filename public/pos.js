document.addEventListener('DOMContentLoaded',()=>{
 const confirmation=document.getElementById('confirmation');
 document.querySelectorAll('form[data-confirm]').forEach(form=>form.addEventListener('submit',event=>{
  if(form.dataset.confirmed==='true'){delete form.dataset.confirmed;return;}
  event.preventDefault();
  if(!confirmation?.showModal){if(window.confirm(form.dataset.confirm)){form.dataset.confirmed='true';form.requestSubmit();}return;}
  const trigger=document.activeElement;document.getElementById('confirmation-message').textContent=form.dataset.confirm;confirmation.returnValue='';confirmation.showModal();
  confirmation.addEventListener('close',()=>{if(confirmation.returnValue==='confirm'){form.dataset.confirmed='true';form.requestSubmit();}else{trigger?.focus();}},{once:true});
 }));
 document.querySelectorAll('form[data-submit]').forEach(form=>form.addEventListener('submit',event=>{if(!event.defaultPrevented&&form.checkValidity()){const button=form.querySelector('button[type=submit]');if(button){button.disabled=true;button.setAttribute('aria-busy','true');button.dataset.originalLabel=button.innerHTML;button.textContent='Saving...';}}}));
 window.addEventListener('pageshow',()=>document.querySelectorAll('[data-original-label]').forEach(button=>{button.disabled=false;button.removeAttribute('aria-busy');button.innerHTML=button.dataset.originalLabel;delete button.dataset.originalLabel;}));
 document.querySelectorAll('[data-password-toggle]').forEach(button=>button.addEventListener('click',()=>{const field=document.getElementById(button.dataset.passwordToggle);const visible=field.type==='password';field.type=visible?'text':'password';button.setAttribute('aria-label',visible?'Hide password':'Show password');button.setAttribute('aria-pressed',String(visible));}));
 document.querySelectorAll('[data-image-preview]').forEach(input=>{let previewUrl;input.addEventListener('change',()=>{const file=input.files[0];if(!file)return;document.getElementById('upload-filename').textContent=file.name;if(previewUrl)URL.revokeObjectURL(previewUrl);if(file.type.startsWith('image/')){previewUrl=URL.createObjectURL(file);const preview=document.getElementById('upload-preview');preview.src=previewUrl;preview.hidden=false;document.getElementById('upload-placeholder').hidden=true;}});});
 document.querySelectorAll('[data-print]').forEach(button=>button.addEventListener('click',()=>window.print()));
 document.querySelectorAll('.action-menu').forEach(menu=>menu.addEventListener('toggle',()=>{if(!menu.open)return;document.querySelectorAll('.action-menu').forEach(other=>{if(other!==menu)other.open=false;});const rect=menu.querySelector('summary').getBoundingClientRect();const content=menu.querySelector('.action-menu-content');content.style.left=Math.max(10,Math.min(innerWidth-200,rect.right-190))+'px';content.style.top=(innerHeight-rect.bottom>130?rect.bottom+6:rect.top-content.offsetHeight-6)+'px';}));
 document.addEventListener('click',event=>document.querySelectorAll('.action-menu[open]').forEach(menu=>{if(!menu.contains(event.target))menu.open=false;}));
 document.addEventListener('keydown',event=>{if(event.key==='Escape')document.querySelectorAll('.action-menu[open]').forEach(menu=>menu.open=false);});
 window.addEventListener('resize',()=>document.querySelectorAll('.action-menu[open]').forEach(menu=>menu.open=false));
 const product=document.getElementById('product'),quantity=document.getElementById('quantity');
 if(product&&quantity){
 const money=value=>value.toLocaleString(undefined,{minimumFractionDigits:2,maximumFractionDigits:2});
 const update=()=>{const option=product.selectedOptions[0];const price=Number(option?.dataset.price)||0;const amount=Number(quantity.value)||0;document.getElementById('total').textContent=money(price*amount);document.getElementById('unit-price').textContent=money(price);document.getElementById('summary-quantity').textContent=String(amount);document.getElementById('availability').textContent=option?.dataset.stock?option.dataset.stock+' units available.':'Choose a product to see available stock.';document.querySelectorAll('[data-product-id]').forEach(card=>card.setAttribute('aria-pressed',String(card.dataset.productId===product.value)));};
 product.addEventListener('change',update);quantity.addEventListener('input',update);
 document.querySelectorAll('[data-product-id]').forEach(card=>card.addEventListener('click',()=>{product.value=card.dataset.productId;product.dispatchEvent(new Event('change'));}));
 document.querySelectorAll('[data-quantity-step]').forEach(button=>button.addEventListener('click',()=>{quantity.value=String(Math.max(1,Math.min(2147483647,(Number(quantity.value)||1)+Number(button.dataset.quantityStep))));quantity.dispatchEvent(new Event('input'));}));
 document.getElementById('product-search')?.addEventListener('input',event=>{const query=event.target.value.trim().toLocaleLowerCase();let matches=0;document.querySelectorAll('[data-product-id]').forEach(card=>{card.hidden=!card.dataset.search.includes(query);if(!card.hidden)matches++;});const empty=document.getElementById('no-product-matches');if(empty)empty.hidden=matches>0||document.querySelectorAll('[data-product-id]').length===0;});update();
 }
});
