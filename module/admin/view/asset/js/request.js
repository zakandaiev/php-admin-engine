"use strict";function sleep(t){return new Promise((e=>setTimeout(e,t)))}async function fetchWithTimeout(t){let e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:{},o=arguments.length>2&&void 0!==arguments[2]?arguments[2]:15e3;const n=new AbortController,s=setTimeout((()=>n.abort()),o),a=await fetch(t,{...e,signal:n.signal});return clearTimeout(s),a}async function request(t){let e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:{},o=arguments.length>2&&void 0!==arguments[2]?arguments[2]:15e3,n=arguments.length>3&&void 0!==arguments[3]?arguments[3]:500;const s=performance.now();e.method||(e.method="POST"),"get"!==e.method.toLowerCase()&&"object"==typeof e.body&&"application/json"===e["Content-Type"]&&(e.body=JSON.stringify(e.body));const a={code:null,status:null,message:null,data:null};try{var i;const n=await fetchWithTimeout(t,e,o),s=null!==(i=await n.json())&&void 0!==i?i:{};a.code=n.status,a.status=s.status,a.message=s.message,a.data=s.data}catch(t){a.status="error",a.message=t}const l=performance.now()-s;return l<n&&await sleep(n-l),a}