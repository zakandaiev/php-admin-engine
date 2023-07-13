"use strict";class CustomField{constructor(e){var t;this.node=e,this.name=null===(t=this.node.getAttribute("name"))||void 0===t?void 0:t.replace("[]",""),this.store=document.querySelector('#page-custom-fields [name="custom_fields"]'),this.name&&this.initialize()}initialize(){const e=this.getStoreValue()[this.name];this.populateField(e)}getStoreValue(){var e,t;return null!==(e=this.store)&&void 0!==e&&null!==(t=e.value)&&void 0!==t&&t.length?JSON.parse(this.store.value):{}}populateField(){let e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:"";return"file"===this.node.getAttribute("type")?this.populateFile(e):this.node.value=e,!0}populateFile(){let e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:"";if(!e||!e.length)return!1;let t=[];return("["===e[0]?JSON.parse(e):[e]).forEach((e=>{t.push({value:e,poster:BASE_URL+"/"+e})})),this.node.setAttribute("data-value",JSON.stringify(t)),!0}}document.querySelectorAll("#page-custom-fields [name]").forEach((e=>{if("custom_fields"===e.name||e.closest(".foreign-form__body"))return!1;new CustomField(e)}));