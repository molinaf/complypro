
export default {
  bootstrap: () => import('./main.server.mjs').then(m => m.default),
  inlineCriticalCss: true,
  routes: [
  {
    "renderMode": 2,
    "route": "/"
  }
],
  assets: new Map([
['index.csr.html', {size: 753, hash: '1cad5cc340d67c51fa431419c356d012e27d0c9c129ac0117b5832cb315e0c13', text: () => import('./assets-chunks/index_csr_html.mjs').then(m => m.default)}], 
['index.server.html', {size: 1002, hash: '799e82daea8272473a01838a6ce427ff4ac455cd8e66d860b9abd165951d9c70', text: () => import('./assets-chunks/index_server_html.mjs').then(m => m.default)}], 
['index.html', {size: 3742, hash: 'c2aa0707330a5f49875122cc9516856e66d2109de76e4208d4750ac7a87e40db', text: () => import('./assets-chunks/index_html.mjs').then(m => m.default)}], 
['styles-POK3XYNK.css', {size: 1906, hash: 'wsdDRmb/GS4', text: () => import('./assets-chunks/styles-POK3XYNK_css.mjs').then(m => m.default)}]
]),
  locale: undefined,
};
