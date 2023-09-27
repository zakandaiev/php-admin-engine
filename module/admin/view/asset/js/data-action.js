"use strict";class DataAction{constructor(t){var e,s;let a=arguments.length>1&&void 0!==arguments[1]?arguments[1]:{};this.node=t,this.options=a,this.action=this.node.getAttribute("data-action"),this.method=this.node.getAttribute("data-method")||"POST","get"===this.method.toLowerCase()&&(this.method="POST"),this.data_event=this.node.getAttribute("data-event")||"click",this.data_confirm=this.node.getAttribute("data-confirm"),this.data_fields=this.node.getAttribute("data-fields")||"",this.data_unset_null=!!this.node.hasAttribute("data-unset-null"),this.data_class=this.node.getAttribute("data-class")||"submit",this.data_class_target=this.node.getAttribute("data-class-target"),this.data_redirect=this.node.getAttribute("data-redirect"),this.data_increment=this.node.getAttribute("data-increment"),this.data_decrement=this.node.getAttribute("data-decrement"),this.data_remove=this.node.getAttribute("data-remove"),this.data_message=this.node.getAttribute("data-message"),this.toast=console.log,"undefined"!=typeof toast&&(this.toast=toast),this.confirmation=confirm,"undefined"!=typeof confirmation&&(this.confirmation=confirmation),this.fade_out=t=>!!t&&t.remove(),"undefined"!=typeof fadeOut&&(this.fade_out=fadeOut),this.api={delay_ms:this.node.getAttribute("data-delay")?parseInt(this.node.getAttribute("data-delay")):(null===(e=this.options)||void 0===e||null===(e=e.api)||void 0===e?void 0:e.delay_ms)||1e3,timeout_ms:this.node.getAttribute("data-timeout")?parseInt(this.node.getAttribute("data-timeout")):(null===(s=this.options)||void 0===s||null===(s=s.api)||void 0===s?void 0:s.timeout_ms)||15e3},this.action&&this.initialize()}initialize(){this.node.addEventListener(this.data_event,(async t=>{t.preventDefault(),t.stopImmediatePropagation();if(!await this.checkConfirmation())return!1;this.disableNodes();const e=await request(this.action,{method:this.method,body:this.getFormData()},this.api.timeout_ms,this.api.delay_ms);"success"===e.status?(this.successRedirect(e),this.successCounters(),this.successRemoveNodes(),this.toast(e.status,this.data_message||e.message)):this.toast(e.status,e.message),this.enableNodes()}))}async checkConfirmation(){if(!this.data_confirm)return!0;let t=!0;return t=this.confirmation===confirm?confirm(this.data_confirm):await this.confirmation(this.data_confirm),t}getFormData(){var t;let e=new FormData;return(this.data_fields.split("|")||[]).forEach((t=>{const[s,a]=t.split(":");if(!s)return!1;e.set(s,a||null)})),null!==(t=this.options)&&void 0!==t&&t.csrf&&e.set(this.options.csrf.key,this.options.csrf.token),this.data_unset_null&&this.unsetNullFormData(e),e}unsetNullFormData(t){for(const e of t.entries()){const s=e[0],a=e[1];a&&a.length||t.delete(s)}}disableNodes(){return this.node.setAttribute("disabled","disabled"),this.node.classList.add("submit"),this.data_class&&this.data_class_target?document.querySelectorAll(this.data_class_target).forEach((t=>{t.classList.add(this.data_class)})):this.data_class&&this.node.classList.add(this.data_class),!0}enableNodes(){return this.node.removeAttribute("disabled","disabled"),this.node.classList.remove("submit"),this.data_class&&this.data_class_target?document.querySelectorAll(this.data_class_target).forEach((t=>{t.classList.remove(this.data_class)})):this.data_class&&this.node.classList.remove(this.data_class),!0}successRedirect(t){this.data_redirect&&("this"===this.data_redirect?document.location.reload():window.location.href=decodeURI(this.data_redirect).replaceAll(/({\w+})/g,null==t?void 0:t.data))}successCounters(){this.data_increment&&document.querySelectorAll(this.data_increment).forEach((t=>{const e=parseInt(t.textContent);t.textContent=e+1})),this.data_decrement&&document.querySelectorAll(this.data_decrement).forEach((t=>{const e=parseInt(t.textContent);t.textContent=e-1}))}successRemoveNodes(){if(!this.data_remove)return!1;if("this"===this.data_remove)return this.fade_out(this.node),!0;if("trow"===this.data_remove){const t=this.node.closest("tr");return this.fade_out(t),!0}return document.querySelectorAll(this.data_remove).forEach((t=>{this.fade_out(t)})),!0}}document.addEventListener("DOMContentLoaded",(()=>{document.querySelectorAll("[data-action]").forEach((t=>{new DataAction(t,ENGINE)}))}));