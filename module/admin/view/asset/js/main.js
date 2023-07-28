"use strict";const BASE_URL=window.location.protocol+"//"+window.location.host,PATH_URL=window.location.pathname,FULL_URL=window.location.href,GET_PARAM=t=>new URL(FULL_URL).searchParams.get(t);function fadeOut(t){let e=arguments.length>1&&void 0!==arguments[1]&&arguments[1],o=arguments.length>2&&void 0!==arguments[2]?arguments[2]:null;if(!t)return!1;t.style.opacity=1,function a(){(t.style.opacity-=.1)<0?(e?t.style.display="none":t.remove(),o instanceof Function?o():window[o]instanceof Function&&window[o]()):requestAnimationFrame(a)}()}function smoothScroll(t){t&&t.scrollIntoView({behavior:"smooth"})}function smoothScrollToTop(){window.scrollTo({top:0,behavior:"smooth"})}console.log("%cMade by Zakandaiev","background:#222e3c;color:#fff;padding:10px;font-weight:bold;"),document.addEventListener("click",(t=>{if("A"!==t.target.tagName)return!1;const e=t.target,o=e.getAttribute("href");if("#"===o)t.preventDefault(),smoothScrollToTop();else if("#"===o.charAt(0)||"/"===o.charAt(0)&&"#"===o.charAt(1)){if(!e.hash)return!1;const o=document.querySelector(e.hash);o&&(t.preventDefault(),smoothScroll(o))}})),window.onload=()=>{document.querySelectorAll("img").forEach((t=>{t.complete&&void 0!==t.naturalWidth&&t.naturalWidth<=0&&(t.src=BASE_URL+"/module/admin/view/asset/img/no-image.jpg")}))},document.addEventListener("DOMContentLoaded",(()=>{document.addEventListener("click",(t=>{const e=t.target.closest(".accordion"),o=t.target.closest(".accordion__body");if(!e||o)return!1;const a=e.querySelector(".accordion__header"),r=e.querySelector(".accordion__body");if(!a||!r)return!1;t.preventDefault();e.parentElement&&(e.parentElement.hasAttribute("data-collapse")||1==e.parentElement.getAttribute("data-collapse"))&&e.parentElement.querySelectorAll(".accordion").forEach((t=>{if(t===e)return!1;t.classList.remove("active");const o=t.querySelector(".accordion__body");o&&(o.style.height="0px")}));const n=r.scrollHeight;e.classList.contains("active")?(r.style.height="0px",e.classList.remove("active")):(r.style.height=`${n}px`,e.classList.add("active"))})),document.querySelectorAll(".accordion").forEach((t=>{const e=t.querySelector(".accordion__body");if(!e||!t.hasAttribute("data-active"))return!1;const o=e.scrollHeight;e.style.height=`${o}px`,e.style.transition="none",setTimeout((()=>e.style.transition=""),100),t.classList.add("active")})),document.addEventListener("click",(t=>{const e=t.target.closest(".dropdown"),o=t.target.closest(".dropdown__item:not(:disabled):not(.disabled)"),a=t.target.closest(".dropdown__header"),r=t.target.closest(".dropdown__text"),n=t.target.closest(".dropdown__separator");return e?(o&&"A"!==o.tagName&&t.preventDefault(),document.querySelectorAll(".dropdown").forEach((t=>{if(a||r||n||t.hasAttribute("data-keep-open")&&o)return!1;t===e?t.classList.toggle("active"):t.classList.remove("active")})),!!o&&void e.querySelectorAll(".dropdown__item").forEach((t=>{t===o?t.classList.toggle("active"):t.classList.remove("active")}))):(document.querySelectorAll(".dropdown.active").forEach((t=>t.classList.remove("active"))),!1)})),document.querySelectorAll("a").forEach((t=>{t.hasAttribute("href")&&t.href.startsWith("tel:")&&(t.href="tel:"+t.href.replaceAll(/[^\d+]/g,""))})),document.querySelectorAll("a").forEach((t=>{t.hasAttribute("target")&&"_blank"===t.getAttribute("target")&&t.setAttribute("rel","noopener noreferrer nofollow")})),document.addEventListener("contextmenu",(t=>{"IMG"===t.target.nodeName&&t.preventDefault()})),document.addEventListener("click",(t=>{const e=t.target.closest("[data-popover]");if(!e)return document.querySelectorAll(".popover-wrapper.active").forEach((t=>t.classList.remove("active"))),!1;t.preventDefault();const o=e.getAttribute("data-popover")||"top",a=e.getAttribute("data-title"),r=e.getAttribute("data-content");if(document.querySelectorAll(".popover-wrapper").forEach((t=>{t===e.parentElement?t.classList.toggle("active"):t.classList.remove("active")})),e.parentElement.classList.contains("popover-wrapper"))return!1;const n=document.createElement("div");n.classList.add("popover-wrapper","active"),n.style.display=getComputedStyle(e).getPropertyValue("display");const s=document.createElement("div");s.classList.add("popover",`popover_${o}`);const c=document.createElement("div");c.classList.add("popover__header"),c.textContent=a;const i=document.createElement("div");i.classList.add("popover__body"),i.textContent=r,s.appendChild(c),s.appendChild(i),e.parentNode.insertBefore(n,e),n.appendChild(e),n.appendChild(s)})),document.addEventListener("click",(t=>{const e=t.target.closest(".tab"),o=t.target.closest(".tab__nav-item");if(!e||!o)return!1;t.preventDefault();const a=o.hash,r=a.substring(1);e.hasAttribute("data-save")&&(history.pushState?window.history.pushState(null,null,a):window.location.hash=a),e.querySelectorAll(".tab__nav-item").forEach((t=>{t.hash===a?t.classList.add("active"):t.classList.remove("active")})),e.querySelectorAll(".tab__body").forEach((t=>{t.id===r?t.classList.add("active"):t.classList.remove("active")}))})),window.location.hash&&document.querySelectorAll(".tab__nav-item,.tab__body").forEach((t=>{if(!t.closest(".tab").hasAttribute("data-save"))return!1;const e=window.location.hash,o=e.substring(1);t.classList.contains("tab__nav-item")?t.hash===e?t.classList.add("active"):t.classList.remove("active"):t.classList.contains("tab__body")&&(t.id===o?t.classList.add("active"):t.classList.remove("active"))})),document.querySelectorAll("textarea").forEach((t=>{const e=t.scrollHeight;t.style.height=e+"px",t.setAttribute("data-initial-height",e)})),document.addEventListener("input",(t=>{const e=t.target;if("TEXTAREA"===e.tagName){const t=e.getAttribute("data-initial-height")||0,o=e.scrollHeight;if(parseInt(t)>o)return!1;e.style.height=0,e.style.height=o+"px"}})),document.addEventListener("mouseover",(t=>{const e=t.target.closest("[data-tooltip]");if(!e)return!1;t.preventDefault();const o=e.getAttribute("data-tooltip")||"top",a=e.getAttribute("data-title");if(e.parentElement.classList.contains("tooltip-wrapper"))return!1;const r=document.createElement("div");r.classList.add("tooltip-wrapper"),r.style.display=getComputedStyle(e).getPropertyValue("display");const n=document.createElement("span");n.classList.add("tooltip",`tooltip_${o}`);const s=document.createElement("span");s.classList.add("tooltip__content"),s.textContent=a,n.appendChild(s),e.parentNode.insertBefore(r,e),r.appendChild(e),r.appendChild(n)}))}));