const {chromium}=require('C:/Users/cedri/AppData/Local/npm-cache/_npx/420ff84f11983ee5/node_modules/playwright');
(async()=>{
 const b=await chromium.launch({channel:'msedge',headless:true});
 const c=await b.newContext({viewport:{width:1440,height:1000}});
 const p=await c.newPage();const errors=[];p.on('pageerror',e=>errors.push(e.message));
 const base='http://127.0.0.1:8001',email='order-'+Date.now()+'@example.com';
 await p.goto(base+'/customer/register');
 await p.locator('[name=full_name]').fill('Sofia Mercado');
 await p.locator('[name=email]').fill(email);
 await p.locator('[name=phone]').fill('09123456789');
 await p.locator('[name=password]').fill('OrderCustomer123');
 await p.locator('[name=password_confirmation]').fill('OrderCustomer123');
 await p.getByRole('button',{name:'Create account',exact:true}).click();await p.waitForURL('**/customer/account');
 await p.goto(base+'/customer/shop');await p.screenshot({path:'storage/orders-shop-desktop.png',fullPage:true});
 const card=p.locator('article').filter({hasText:'Everyday notebook'});
 await card.getByRole('button',{name:'Add to cart'}).click();await p.waitForLoadState('networkidle');
 await p.goto(base+'/customer/cart');
 await p.locator('input[type=number]').fill('2');
 await p.getByRole('button',{name:'Update',exact:true}).click();await p.waitForLoadState('networkidle');
 if(!await p.locator('.order-summary').innerText().then(x=>x.includes('251.00'))) throw Error('Wrong cart total');
 await p.screenshot({path:'storage/orders-cart-desktop.png',fullPage:true});
 await p.getByRole('button',{name:'Place order'}).click();await p.waitForURL(/customer\/orders\/\d+$/);
 const customerOrderUrl=p.url(),orderId=customerOrderUrl.split('/').pop();
 if(!await p.locator('main').innerText().then(x=>x.includes('Pending'))) throw Error('Order not pending');
 const sc=await b.newContext({viewport:{width:1440,height:1000}}),s=await sc.newPage();s.on('pageerror',e=>errors.push(e.message));
 await s.goto(base+'/login');await s.locator('[name=username]').fill('order-staff');await s.locator('[name=password]').fill('OrderReview123');await s.getByRole('button',{name:'Sign in to workspace'}).click();await s.waitForURL('**/sales/create');
 await s.goto(base+'/orders');await s.getByRole('link',{name:'View order'}).first().click();await s.waitForURL('**/orders/'+orderId);
 await s.getByRole('button',{name:'Confirm payment & complete',exact:true}).click();
 await s.locator('#confirmation').getByRole('button',{name:'Cancel',exact:true}).click();
 if(await s.getByRole('button',{name:'Confirm payment & complete',exact:true}).isDisabled()) throw Error('Cancel leaves completion disabled');
 await s.getByRole('button',{name:'Confirm payment & complete',exact:true}).click();
 await s.locator('#confirmation').getByRole('button',{name:'Confirm',exact:true}).click();
 await s.waitForLoadState('networkidle');
 if(!await s.locator('main').innerText().then(x=>x.includes('Completed'))) throw Error('Staff completion failed');
 await p.goto(customerOrderUrl);await p.getByRole('link',{name:/View receipt/}).click();await p.waitForURL(/customer\/receipts\/\d+$/);
 if(!await p.locator('main').innerText().then(x=>x.includes('251.00'))) throw Error('Receipt total wrong');
 await p.goto(base+'/customer/shop');
 await p.locator('article').filter({hasText:'Desk pen set'}).getByRole('button',{name:'Add to cart'}).click();await p.waitForLoadState('networkidle');
 await p.goto(base+'/customer/cart');await p.getByRole('button',{name:'Place order'}).click();await p.waitForURL(url=>url.pathname.startsWith('/customer/orders/'));
 const cancelUrl=p.url(),cancelId=cancelUrl.split('/').pop();
 await s.goto(base+'/orders/'+cancelId);
 await s.getByRole('button',{name:'Cancel order',exact:true}).click();
 await s.locator('#confirmation').getByRole('button',{name:'Confirm',exact:true}).click();await s.waitForLoadState('networkidle');
 await p.goto(cancelUrl);
 if(!await p.locator('main').innerText().then(x=>x.includes('Cancelled'))) throw Error('Cancelled order status not visible to customer');
 for(const url of ['/customer/shop','/customer/cart','/customer/orders','/customer/orders/'+orderId,'/customer/purchases','/customer/account']){
  await p.setViewportSize({width:390,height:844});const r=await p.goto(base+url);if(!r.ok())throw Error(url+' '+r.status());
  if(await p.evaluate(()=>document.documentElement.scrollWidth>innerWidth))throw Error('Mobile overflow '+url);
 }
 await p.goto(base+'/customer/shop');await p.screenshot({path:'storage/orders-shop-mobile.png',fullPage:true});
 for(const url of ['/orders','/orders/'+orderId,'/sales/create']){
  await s.setViewportSize({width:390,height:844});const r=await s.goto(base+url);if(!r.ok())throw Error(url+' '+r.status());
  if(await s.evaluate(()=>document.documentElement.scrollWidth>innerWidth))throw Error('Staff mobile overflow '+url);
 }
 await s.goto(base+'/orders/'+orderId);await s.screenshot({path:'storage/orders-completed-mobile.png',fullPage:true});
 if(errors.length)throw Error(errors.join('; '));
 await b.close();console.log('PASS: catalog, cart quantity/total, checkout, staff confirmation cancellation/retry, completion, customer receipt and nine mobile routes.');
})().catch(e=>{console.error(e);process.exit(1)});
