const BASE_URL = window.location.protocol + '//' + window.location.host;
const PATH_URL = window.location.pathname;
const FULL_URL = window.location.href;
const GET_PARAM = (key) => {
	return new URL(FULL_URL).searchParams.get(key);
};

console.log('%cMade by Zakandaiev', 'background:#da4648;color:#fff;padding:10px;font-weight:bold;');


// UTILS
function fadeOut(element, soft = false, callback = null) {
	if(!element) {
		return false;
	}

	element.style.opacity = 1;

	(function fade() {
		if((element.style.opacity -= 0.1) < 0) {
			if(soft) {
				element.style.display = "none";
			} else {
				element.remove();
			}

			if(callback instanceof Function) {
				callback();
			} else if(window[callback] instanceof Function) {
				window[callback]();
			}
		} else {
			requestAnimationFrame(fade);
		}
	})();

}

const sleep = (ms) => {
  return new Promise(resolve => setTimeout(resolve, ms))
}

function smoothScroll(element) {
	if(element) {
		element.scrollIntoView({
			behavior: 'smooth'
		});
	}
}

function smoothScrollToTop() {
	window.scrollTo({
		top: 0,
		behavior: 'smooth'
	});
}

document.addEventListener('click', event => {
	if(event.target.tagName !== 'A') {
		return false;
	}
	const anchor = event.target;
	const anchor_href = anchor.getAttribute('href');

	if(anchor_href === '#') {
		event.preventDefault();
		smoothScrollToTop();
	}
	else if(anchor_href.charAt(0) === '#' || (anchor_href.charAt(0) === '/' && anchor_href.charAt(1) === '#')) {
		if(!anchor.hash) {
			return false;
		}

		const target = document.querySelector(anchor.hash);
		if(target) {
			event.preventDefault();
			smoothScroll(target);
		}
	}
});

window.onload = () => {
	document.querySelectorAll('img').forEach(image => {
		if(image.complete && typeof image.naturalWidth != 'undefined' && image.naturalWidth <= 0) {
			image.src = BASE_URL + '/img/no-image.jpg';
		}
	});
};


document.addEventListener('DOMContentLoaded', () => {
	// PARTIALS
	document.addEventListener('click', event => {
	const accordion = event.target.closest('.accordion');
	const is_body_click = event.target.closest('.accordion__body');

	if (!accordion || is_body_click) {
		return false;
	}

	const header = accordion.querySelector('.accordion__header');
	const body = accordion.querySelector('.accordion__body');

	if (!header || !body) {
		return false;
	}

	event.preventDefault();

	const is_collapse = (accordion.parentElement && (accordion.parentElement.hasAttribute('data-collapse') || accordion.parentElement.getAttribute('data-collapse') == true));
	if (is_collapse) {
		accordion.parentElement.querySelectorAll('.accordion').forEach(a => {
			if(a === accordion) {
				return false;
			}

			a.classList.remove('active');

			const b = a.querySelector('.accordion__body');
			if(b) {
				b.style.height = '0px';
			}
		});
	}

	const body_height = body.scrollHeight;
	if (accordion.classList.contains('active')) {
		body.style.height = '0px';
		accordion.classList.remove('active');
	}
	else {
		body.style.height = `${body_height}px`;
		accordion.classList.add('active');
	}
});

document.querySelectorAll('.accordion').forEach(accordion => {
	const body = accordion.querySelector('.accordion__body');

	if (!body || !accordion.hasAttribute('data-active')) {
		return false;
	}

	const body_height = body.scrollHeight;
	body.style.height = `${body_height}px`;
	accordion.classList.add('active');
});

	// DROPDOWN
document.addEventListener('click', event => {
	const dropdown = event.target.closest('.dropdown');
	const dropdown_item = event.target.closest('.dropdown__item:not(:disabled):not(.disabled)');
	const dropdown_header = event.target.closest('.dropdown__header');
	const dropdown_text = event.target.closest('.dropdown__text');
	const dropdown_separator = event.target.closest('.dropdown__separator');

	if (!dropdown) {
		document.querySelectorAll('.dropdown.active').forEach(dd => dd.classList.remove('active'));

		return false;
	}

	document.querySelectorAll('.dropdown').forEach(dd => {
		if ( dropdown_header || dropdown_text || dropdown_separator || (dd.hasAttribute('data-keep-open') && dropdown_item) ) {
			return false;
		}

		if (dd === dropdown) {
			dd.classList.toggle('active');
		}
		else {
			dd.classList.remove('active');
		}
	});

	if (!dropdown_item) {
		return false;
	}

	dropdown.querySelectorAll('.dropdown__item').forEach(di => {
		if (di === dropdown_item) {
			di.classList.toggle('active');
		}
		else {
			di.classList.remove('active');
		}
	});
});

	document.querySelectorAll('a').forEach(anchor => {
	if(anchor.hasAttribute('href') && anchor.href.startsWith('tel:')) {
		anchor.href = 'tel:' + anchor.href.replaceAll(/[^\d+]/g, '');
	}
});

	document.querySelectorAll('a').forEach(anchor => {
	if(anchor.hasAttribute('target') && anchor.getAttribute('target') === '_blank') {
		anchor.setAttribute('rel', 'noopener noreferrer nofollow');
	}
});

	document.addEventListener('contextmenu', event => {
	if(event.target.nodeName === 'IMG') {
		event.preventDefault();
	}
});

	document.querySelectorAll('table').forEach(table => {
	if(!table.parentElement.classList.contains('table-responsive')) {
		table.outerHTML = '<div class="table-responsive">' + table.outerHTML + '</div>';
	}
});

	// POPOVER
document.addEventListener('click', event => {
	const popover = event.target.closest('[data-popover]');

	if (!popover) {
		document.querySelectorAll('.popover-wrapper.active').forEach(wr => wr.classList.remove('active'));

		return false;
	}

	const placement = popover.getAttribute('data-popover') || 'top';
	const title = popover.getAttribute('data-title');
	const content = popover.getAttribute('data-content');

	document.querySelectorAll('.popover-wrapper').forEach(wr => {
		if (wr === popover.parentElement) {
			wr.classList.toggle('active');
		}
		else {
			wr.classList.remove('active');
		}
	});

	if (popover.parentElement.classList.contains('popover-wrapper')) {
		return false;
	}

	const wrapper = document.createElement('div');
	wrapper.classList.add('popover-wrapper', 'active');
	wrapper.style.display = getComputedStyle(popover).getPropertyValue('display');

	const pop = document.createElement('div');
	pop.classList.add('popover', `popover_${placement}`);

	const pop_header = document.createElement('div');
	pop_header.classList.add('popover__header');
	pop_header.textContent = title;

	const pop_body = document.createElement('div');
	pop_body.classList.add('popover__body');
	pop_body.textContent = content;

	pop.appendChild(pop_header);
	pop.appendChild(pop_body);

	popover.parentNode.insertBefore(wrapper, popover);
  wrapper.appendChild(popover);
  wrapper.appendChild(pop);
});

	// TOOLTIP
document.addEventListener('mouseover', event => {
	const tooltip = event.target.closest('[data-tooltip]');

	if (!tooltip) {
		return false;
	}

	const placement = tooltip.getAttribute('data-tooltip') || 'top';
	const content = tooltip.getAttribute('data-title');

	if (tooltip.parentElement.classList.contains('tooltip-wrapper')) {
		return false;
	}

	const wrapper = document.createElement('div');
	wrapper.classList.add('tooltip-wrapper');
	wrapper.style.display = getComputedStyle(tooltip).getPropertyValue('display');

	const tip = document.createElement('span');
	tip.classList.add('tooltip', `tooltip_${placement}`);

	const tip_content = document.createElement('span');
	tip_content.classList.add('tooltip__content');
	tip_content.textContent = content;

  tip.appendChild(tip_content);

	tooltip.parentNode.insertBefore(wrapper, tooltip);
  wrapper.appendChild(tooltip);
  wrapper.appendChild(tip);
});

});
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJuYW1lcyI6W10sIm1hcHBpbmdzIjoiIiwic291cmNlcyI6WyJtYWluLmpzIl0sInNvdXJjZXNDb250ZW50IjpbImNvbnN0IEJBU0VfVVJMID0gd2luZG93LmxvY2F0aW9uLnByb3RvY29sICsgJy8vJyArIHdpbmRvdy5sb2NhdGlvbi5ob3N0O1xyXG5jb25zdCBQQVRIX1VSTCA9IHdpbmRvdy5sb2NhdGlvbi5wYXRobmFtZTtcclxuY29uc3QgRlVMTF9VUkwgPSB3aW5kb3cubG9jYXRpb24uaHJlZjtcclxuY29uc3QgR0VUX1BBUkFNID0gKGtleSkgPT4ge1xyXG5cdHJldHVybiBuZXcgVVJMKEZVTExfVVJMKS5zZWFyY2hQYXJhbXMuZ2V0KGtleSk7XHJcbn07XHJcblxyXG5AQGluY2x1ZGUoJ3BhcnRpYWwvd2F0ZXJtYXJrLmpzJylcclxuXHJcbi8vIFVUSUxTXHJcbkBAaW5jbHVkZSgndXRpbC9mYWRlLW91dC5qcycpXHJcbkBAaW5jbHVkZSgndXRpbC9zbGVlcC5qcycpXHJcbkBAaW5jbHVkZSgndXRpbC9zbW9vdGgtc2Nyb2xsLmpzJylcclxuQEBpbmNsdWRlKCd1dGlsL3JlcGxhY2UtYnJva2VuLWltYWdlLmpzJylcclxuXHJcbmRvY3VtZW50LmFkZEV2ZW50TGlzdGVuZXIoJ0RPTUNvbnRlbnRMb2FkZWQnLCAoKSA9PiB7XHJcblx0Ly8gUEFSVElBTFNcclxuXHRAQGluY2x1ZGUoJ3BhcnRpYWwvYWNjcm9kaW9uLmpzJylcclxuXHRAQGluY2x1ZGUoJ3BhcnRpYWwvZHJvcGRvd24uanMnKVxyXG5cdEBAaW5jbHVkZSgncGFydGlhbC9mb3JtYXQtdGVsLWxpbmsuanMnKVxyXG5cdEBAaW5jbHVkZSgncGFydGlhbC9leHRlcm5hbC1saW5rLW5vcmVmZXIuanMnKVxyXG5cdEBAaW5jbHVkZSgncGFydGlhbC9wcm90ZWN0LWltYWdlLmpzJylcclxuXHRAQGluY2x1ZGUoJ3BhcnRpYWwvcmVzcG9uc2l2ZS10YWJsZS5qcycpXHJcblx0QEBpbmNsdWRlKCdwYXJ0aWFsL3BvcG92ZXIuanMnKVxyXG5cdEBAaW5jbHVkZSgncGFydGlhbC90b29sdGlwLmpzJylcclxufSk7XHJcbiJdLCJmaWxlIjoibWFpbi5qcyJ9
